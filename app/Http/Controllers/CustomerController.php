<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search    = $request->get('search');
        $customers = Customer::when($search, fn($q) => $q->where('customer_name', 'like', "%$search%")
            ->orWhere('phone', 'like', "%$search%"))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', compact('customers', 'search'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:50',
            'phone'         => 'required|regex:/^[0-9]+$/|max:13',
            'address'       => 'required|string',
        ], [
            'phone.regex' => 'Nomor telepon harus berupa angka.',
        ]);

        Customer::create($request->only('customer_name', 'phone', 'address'));

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'customer_name' => 'required|string|max:50',
            'phone'         => 'required|regex:/^[0-9]+$/|max:13',
            'address'       => 'required|string',
        ], [
            'phone.regex' => 'Nomor telepon harus berupa angka.',
        ]);

        $customer->update($request->only('customer_name', 'phone', 'address'));

        return redirect()->route('customers.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}
