@extends('layouts.app')
@section('title', 'Data Pelanggan')
@section('page-title', 'Data Pelanggan')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">👥 Daftar Pelanggan</div>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">
            Tambah Pelanggan
        </a>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('customers.index') }}" style="margin-bottom:20px;">
        <div class="search-wrap">
            <div class="search-input-wrap">
                <input type="text" name="search" class="form-control"
                    placeholder="Cari nama / nomor HP..." value="{{ $search ?? '' }}">
            </div>
            <button type="submit" class="btn btn-primary">Cari</button>
            @if($search)
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Pelanggan</th>
                    <th>No Telepon</th>
                    <th>Alamat</th>
                    <th>Terdaftar</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $i => $c)
                    <tr>
                        <td style="color:#64748b;">{{ $customers->firstItem() + $i }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#06b6d4);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;flex-shrink:0;">
                                    {{ strtoupper(substr($c->customer_name, 0, 1)) }}
                                </div>
                                <span style="font-weight:600;">{{ $c->customer_name }}</span>
                            </div>
                        </td>
                        <td>{{ $c->phone }}</td>
                        <td style="max-width:200px;color:#94a3b8;font-size:13px;">{{ Str::limit($c->address, 50) }}</td>
                        <td style="color:#64748b;font-size:13px;">{{ $c->created_at->format('d M Y') }}</td>
                        <td>
                            <div style="display:flex;gap:6px;justify-content:center;">
                                <a href="{{ route('customers.edit', $c) }}" class="btn btn-info btn-sm">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('customers.destroy', $c) }}"
                                    onsubmit="return confirm('Hapus pelanggan {{ $c->customer_name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:40px;color:#64748b;">
                            <div style="font-size:36px;margin-bottom:10px;">👤</div>
                            Belum ada data pelanggan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pagination">
        {{ $customers->links('pagination::simple-default') }}
    </div>
    <div style="text-align:center;font-size:12px;color:#64748b;margin-top:8px;">
        Menampilkan {{ $customers->firstItem() ?? 0 }}–{{ $customers->lastItem() ?? 0 }} dari {{ $customers->total() }} pelanggan
    </div>
</div>
@endsection
