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
            'id_customer'  => 'required|exists:customers,id',
            'order_date'   => 'required|date',
            'order_end_date' => 'nullable|date|after_or_equal:order_date',
            'services'     => 'required|array|min:1',
            'services.*.id_service' => 'required|exists:type_of_service,id',
            'services.*.qty'        => 'required|integer|min:1',
            'services.*.notes'      => 'nullable|string',
        ]);

        $orderCode = 'ORD-' . strtoupper(Str::random(8));
        $total = 0;

        $order = TransOrder::create([
            'id_customer'    => $request->id_customer,
            'order_code'     => $orderCode,
            'order_date'     => $request->order_date,
            'order_end_date' => $request->order_end_date,
            'order_status'   => 0,
            'subtotal'       => 0,
            'tax'            => 0,
            'total'          => 0,
        ]);

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

        $orderPay    = $request->order_pay ?? 0;
        $orderChange = max(0, $orderPay - $total);

        $order->update([
            'subtotal'     => $subtotalOrder,
            'tax'          => $tax,
            'total'        => $total,
            'order_pay'    => $orderPay ?: null,
            'order_change' => $orderChange ?: null,
        ]);

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
        $customers = Customer::orderBy('customer_name')->get();
        $services  = TypeOfService::orderBy('service_name')->get();
        $order->load('details');
        return view('orders.edit', compact('order', 'customers', 'services'));
    }

    public function update(Request $request, TransOrder $order)
    {
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

        $orderPay    = $request->order_pay ?? 0;
        $orderChange = max(0, $orderPay - $total);

        $order->update([
            'id_customer'    => $request->id_customer,
            'order_date'     => $request->order_date,
            'order_end_date' => $request->order_end_date,
            'order_status'   => $request->order_status,
            'subtotal'       => $subtotalOrder,
            'tax'            => $tax,
            'order_pay'      => $orderPay ?: null,
            'order_change'   => $orderChange ?: null,
            'total'          => $total,
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order berhasil diperbarui.');
    }

    public function destroy(TransOrder $order)
    {
        $order->delete();
        return redirect()->route('orders.index')
            ->with('success', 'Order berhasil dihapus.');
    }

    public function updateStatus(Request $request, TransOrder $order)
    {
        $request->validate(['order_status' => 'required|integer|between:0,1']);
        $order->update(['order_status' => $request->order_status]);

        return back()->with('success', 'Status order berhasil diperbarui.');
    }
}
