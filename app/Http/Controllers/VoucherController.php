<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::orderByDesc('created_at')->get();
        return view('vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string|max:50|unique:vouchers,voucher_code',
            'discount_percent' => 'required|integer|min:1|max:100',
            'valid_until' => 'nullable|date',
        ]);

        Voucher::create([
            'voucher_code' => strtoupper($request->voucher_code),
            'discount_percent' => $request->discount_percent,
            'valid_until' => $request->valid_until,
            'is_used' => false,
        ]);

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil ditambahkan.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dihapus.');
    }

    public function edit(Voucher $voucher)
    {
        return view('vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'voucher_code' => 'required|string|max:50|unique:vouchers,voucher_code,' . $voucher->id,
            'discount_percent' => 'required|integer|min:1|max:100',
            'valid_until' => 'nullable|date',
        ]);

        $voucher->update([
            'voucher_code' => strtoupper($request->voucher_code),
            'discount_percent' => $request->discount_percent,
            'valid_until' => $request->valid_until,
        ]);

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    // API to check voucher
    public function checkVoucher(Request $request)
    {
        $code = $request->input('code');
        $customerId = $request->input('id_customer');
        $guestPhone = $request->input('guest_phone');

        $voucher = Voucher::where('voucher_code', $code)->first();

        if (!$voucher) {
            return response()->json(['valid' => false, 'message' => 'Voucher tidak ditemukan.']);
        }

        if ($voucher->valid_until && $voucher->valid_until < now()->startOfDay()) {
            return response()->json(['valid' => false, 'message' => 'Voucher sudah kadaluarsa (Expired).']);
        }

        // Check if this specific customer/guest already used this voucher
        $alreadyUsed = \App\Models\TransOrder::where('id_voucher', $voucher->id)
            ->where(function($q) use ($customerId, $guestPhone) {
                if ($customerId) {
                    $q->where('id_customer', $customerId);
                } elseif ($guestPhone) {
                    $q->where('guest_phone', $guestPhone);
                } else {
                    // if neither is provided, we can't verify, maybe block it
                    $q->whereRaw('1=0');
                }
            })->exists();

        if ($alreadyUsed) {
            return response()->json(['valid' => false, 'message' => 'Anda sudah pernah menggunakan voucher ini.']);
        }

        return response()->json([
            'valid' => true,
            'discount_percent' => $voucher->discount_percent,
        ]);
    }
}
