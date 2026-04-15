@extends('layouts.app')
@section('title', 'Order Laundry')
@section('page-title', 'Order Laundry')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">📋 Daftar Order Laundry</div>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">
            Buat Order
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('orders.index') }}" style="margin-bottom:20px;">
        <div class="search-wrap" style="flex-wrap:wrap;">
            <div class="search-input-wrap">
                <input type="text" name="search" class="form-control"
                    placeholder="Cari kode / nama pelanggan..." value="{{ $search ?? '' }}">
            </div>
            <select name="status" class="form-control" style="width:160px;">
                <option value="">Semua Status</option>
                <option value="0" {{ $status === '0' ? 'selected' : '' }}>Baru</option>
                <option value="1" {{ $status === '1' ? 'selected' : '' }}>Sudah Diambil</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            @if($search || $status !== null && $status !== '')
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Order</th>
                    <th>Pelanggan</th>
                    <th>Tgl Order</th>
                    <th>Tgl Selesai</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $i => $o)
                    <tr>
                        <td style="color:#64748b;">{{ $orders->firstItem() + $i }}</td>
                        <td>
                            <span style="font-weight:700;color:#a5b4fc;font-family:monospace;font-size:13px;">{{ $o->order_code }}</span>
                        </td>
                        <td style="font-weight:500;">{{ $o->customer->customer_name ?? '-' }}</td>
                        <td style="color:#94a3b8;font-size:13px;">{{ $o->order_date->format('d M Y') }}</td>
                        <td style="color:#94a3b8;font-size:13px;">
                            {{ $o->order_end_date ? $o->order_end_date->format('d M Y') : '-' }}
                        </td>
                        <td style="font-weight:700;color:#6ee7b7;">
                            Rp {{ number_format($o->total, 0, ',', '.') }}
                        </td>
                        <td><span class="badge badge-{{ $o->status_color }}">{{ $o->status_label }}</span></td>
                        <td>
                            <div style="display:flex;gap:5px;justify-content:center;">
                                <a href="{{ route('orders.show', $o) }}" class="btn btn-success btn-sm" title="Detail">
                                    </a>
                                <a href="{{ route('orders.edit', $o) }}" class="btn btn-info btn-sm" title="Edit">
                                    </a>
                                <form method="POST" action="{{ route('orders.destroy', $o) }}"
                                    onsubmit="return confirm('Hapus order {{ $o->order_code }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:40px;color:#64748b;">
                            <div style="font-size:36px;margin-bottom:10px;">📋</div>
                            Belum ada data order
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $orders->links('pagination::simple-default') }}</div>
    <div style="text-align:center;font-size:12px;color:#64748b;margin-top:8px;">
        Menampilkan {{ $orders->firstItem() ?? 0 }}–{{ $orders->lastItem() ?? 0 }} dari {{ $orders->total() }} order
    </div>
</div>
@endsection
