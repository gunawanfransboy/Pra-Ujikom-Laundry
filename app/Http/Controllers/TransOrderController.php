<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TransOrder;
use App\Models\TransOrderDetail;
use App\Models\TypeOfService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransOrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $orders = TransOrder::with('customer')
            ->when($search, fn($q) => $q->where('order_code', 'like', "%$search%")
                ->orWhereHas('customer', fn($q2) => $q2->where('customer_name', 'like', "%$search%")))
            ->when($status !== null && $status !== '', fn($q) => $q->where('order_status', $status))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('orders.index', compact('orders', 'search', 'status'));
    }

    public function create()
    {
        $customers = Customer::orderBy('customer_name')->get();
        $services  = TypeOfService::orderBy('service_name')->get();
        return view('orders.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_type'   => 'required|in:member,guest',
            'id_customer'  => 'required_if:order_type,member|nullable|exists:customers,id',
            'guest_name'   => 'required_if:order_type,guest|nullable|string|max:100',
            'guest_phone'  => 'required_if:order_type,guest|nullable|string|max:20',
            'guest_address'=> 'nullable|string',
            'voucher_code' => 'nullable|string|exists:vouchers,voucher_code',
            'order_date'   => 'required|date',
            'order_end_date' => 'nullable|date|after_or_equal:order_date',
            'services'     => 'required|array|min:1',
            'services.*.id_service' => 'required|exists:type_of_service,id',
            'services.*.qty'        => 'required|integer|min:1',
            'services.*.notes'      => 'nullable|string',
        ]);

        $orderCode = 'ORD-' . strtoupper(Str::random(8));
        $subtotalOrder = 0;

        foreach ($request->services as $svc) {
            $service  = TypeOfService::find($svc['id_service']);
            $subtotalOrder += (int) round($service->price * ($svc['qty'] / 1000));
        }

        $tax = (int) round($subtotalOrder * 0.10);
        $baseTotal = $subtotalOrder + $tax;

        $discountMember = 0;
        if ($request->order_type === 'member' && $request->id_customer) {
            $isFirst = TransOrder::where('id_customer', $request->id_customer)->count() === 0;
            if ($isFirst) {
                $discountMember = (int) round($baseTotal * 0.05);
            }
        }

        $discountVoucher = 0;
        $idVoucher = null;
        if ($request->voucher_code) {
            $voucher = \App\Models\Voucher::where('voucher_code', $request->voucher_code)->first();
            if ($voucher) {
                $discountVoucher = (int) round($baseTotal * ($voucher->discount_percent / 100));
                $idVoucher = $voucher->id;
                // do not mark globally as used
            }
        }

        $totalFinal = $baseTotal - $discountMember - $discountVoucher;
        $orderPay    = $request->order_pay ?? 0;
        $orderChange = max(0, $orderPay - $totalFinal);

        $order = TransOrder::create([
            'id_customer'    => $request->order_type === 'member' ? $request->id_customer : null,
            'guest_name'     => $request->order_type === 'guest' ? $request->guest_name : null,
            'guest_phone'    => $request->order_type === 'guest' ? $request->guest_phone : null,
            'guest_address'  => $request->order_type === 'guest' ? $request->guest_address : null,
            'order_code'     => $orderCode,
            'order_date'     => $request->order_date,
            'order_end_date' => $request->order_end_date,
            'order_status'   => 0,
            'subtotal'       => $subtotalOrder,
            'tax'            => $tax,
            'discount_member'=> $discountMember,
            'discount_voucher'=> $discountVoucher,
            'id_voucher'     => $idVoucher,
            'total'          => $totalFinal,
            'order_pay'      => $orderPay ?: null,
            'order_change'   => $orderChange ?: null,
        ]);

        foreach ($request->services as $svc) {
            $service  = TypeOfService::find($svc['id_service']);
            $svcSubtotal = (int) round($service->price * ($svc['qty'] / 1000));
            TransOrderDetail::create([
                'id_order'   => $order->id,
                'id_service' => $svc['id_service'],
                'qty'        => $svc['qty'],
                'subtotal'   => $svcSubtotal,
                'notes'      => $svc['notes'] ?? null,
            ]);
        }

        return redirect()->route('orders.index')
            ->with('success', "Order {$orderCode} berhasil dibuat.");
    }

    public function show(TransOrder $order)
    {
        $order->load(['customer', 'details.service']);
        return view('orders.show', compact('order'));
    }

    public function edit(TransOrder $order)
    {
        if ($order->order_status == 1) {
            return redirect()->route('orders.show', $order)->with('error', 'Order yang sudah diambil tidak dapat diedit kembali.');
        }

        $customers = Customer::orderBy('customer_name')->get();
        $services  = TypeOfService::orderBy('service_name')->get();
        $order->load('details');
        return view('orders.edit', compact('order', 'customers', 'services'));
    }

    public function update(Request $request, TransOrder $order)
    {
        if ($order->order_status == 1) {
            return redirect()->route('orders.show', $order)->with('error', 'Order yang sudah diambil tidak dapat diedit kembali.');
        }

        $request->validate([
            'id_customer'   => 'required|exists:customers,id',
            'order_date'    => 'required|date',
            'order_end_date' => 'nullable|date',
            'order_status'  => 'required|integer|between:0,1',
            'order_pay'     => 'nullable|integer|min:0',
            'services'      => 'required|array|min:1',
            'services.*.id_service' => 'required|exists:type_of_service,id',
            'services.*.qty'        => 'required|integer|min:1',
            'services.*.notes'      => 'nullable|string',
        ]);

        $total = 0;
        $order->details()->delete();

        foreach ($request->services as $svc) {
            $service  = TypeOfService::find($svc['id_service']);
            $subtotal = (int) round($service->price * ($svc['qty'] / 1000));
            $total   += $subtotal;

            TransOrderDetail::create([
                'id_order'   => $order->id,
                'id_service' => $svc['id_service'],
                'qty'        => $svc['qty'],
                'subtotal'   => $subtotal,
                'notes'      => $svc['notes'] ?? null,
            ]);
        }

        $subtotalOrder = $total;
        $tax = (int) round($subtotalOrder * 0.10);
        $total += $tax;

        $baseTotal = $subtotalOrder + $tax;
        
        $discountMember = $order->discount_member > 0 ? (int) round($baseTotal * 0.05) : 0;
        
        $discountVoucher = 0;
        if ($order->id_voucher) {
            $voucher = \App\Models\Voucher::find($order->id_voucher);
            if ($voucher) {
                $discountVoucher = (int) round($baseTotal * ($voucher->discount_percent / 100));
            }
        }

        $totalFinal = $baseTotal - $discountMember - $discountVoucher;
        $orderPay    = $request->order_pay ?? 0;
        $orderChange = max(0, $orderPay - $totalFinal);

        $order->update([
            'id_customer'    => $request->id_customer,
            'order_date'     => $request->order_date,
            'order_end_date' => $request->order_end_date,
            'order_status'   => $request->order_status,
            'subtotal'       => $subtotalOrder,
            'tax'            => $tax,
            'discount_member'=> $discountMember,
            'discount_voucher'=> $discountVoucher,
            'order_pay'      => $orderPay ?: null,
            'order_change'   => $orderChange ?: null,
            'total'          => $totalFinal,
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order berhasil diperbarui.');
    }

    public function destroy(TransOrder $order)
    {
        if ($order->order_status == 1) {
            return redirect()->route('orders.index')->with('error', 'Order yang sudah diambil tidak dapat dihapus.');
        }

        $order->delete();
        return redirect()->route('orders.index')
            ->with('success', 'Order berhasil dihapus.');
    }

    public function updateStatus(Request $request, TransOrder $order)
    {
        if ($order->order_status == 1) {
            return back()->with('error', 'Status order yang sudah diambil tidak dapat diubah kembali.');
        }

        $request->validate(['order_status' => 'required|integer|between:0,1']);
        $order->update(['order_status' => $request->order_status]);

        return back()->with('success', 'Status order berhasil diperbarui.');
    }

    public function checkMemberFirstOrder(Customer $customer)
    {
        $isFirst = TransOrder::where('id_customer', $customer->id)->count() === 0;
        return response()->json([
            'is_first' => $isFirst,
            'discount_percent' => $isFirst ? 5 : 0
        ]);
    }
}
