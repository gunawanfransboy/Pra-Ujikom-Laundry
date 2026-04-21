@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- Quick Actions --}}
<div class="card" style="margin-top:24px;">
    <div class="card-title" style="margin-bottom:16px;">Aksi Cepat</div>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
        <a href="{{ route('orders.create') }}" class="btn btn-primary">
            Buat Order Baru
        </a>
        <a href="{{ route('customers.create') }}" class="btn btn-success">
            Tambah Pelanggan
        </a>
        <a href="{{ route('services.create') }}" class="btn btn-info">
            Tambah Layanan
        </a>
    </div>
</div>
{{-- Stat Cards --}}
<div class="grid grid-3" style="margin-bottom:24px;">
    <div class="card" style="margin-bottom:0; background: #e0e7ff; border-color: #c7d2fe;">
        <div style="display:flex;align-items:center;gap:16px;">
            <div>
                <div style="font-size:28px;font-weight:800;color: #3730a3;">{{ $totalCustomers }}</div>
                <div style="font-size:12px;color: #4338ca;margin-top:2px;">Total Pelanggan</div>
            </div>
        </div>
    </div>
    <div class="card" style="margin-bottom:0; background: #dbeafe; border-color: #bfdbfe;">
        <div style="display:flex;align-items:center;gap:16px;">
            <div>
                <div style="font-size:28px;font-weight:800;color: #1e40af;">{{ $totalOrders }}</div>
                <div style="font-size:12px;color: #1d4ed8;margin-top:2px;">Total Order</div>
            </div>
        </div>
    </div>
    <div class="card" style="margin-bottom:0; background: #dcfce7; border-color: #bbf7d0;">
        <div style="display:flex;align-items:center;gap:16px;">
            <div>
                <div style="font-size:22px;font-weight:800;color: #166534;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div style="font-size:12px;color: #15803d;margin-top:2px;">Total Pendapatan</div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-2">
    {{-- Order Status --}}
    <div class="card" style="margin-bottom:0;">
        <div class="card-header">
            <div class="card-title">Status Order</div>
        </div>
        <div style="display:grid;gap:12px;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background: #fffbeb;border:1px solid #fef3c7;border-radius:10px;">
                <div style="display:flex;align-items:center;gap:10px;font-size:14px;color: #92400e;">
                    Baru (Pending)
                </div>
                <span class="badge badge-warning">{{ $pendingOrders }}</span>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background: #ecfdf5;border:1px solid #dcfce7;border-radius:10px;">
                <div style="display:flex;align-items:center;gap:10px;font-size:14px;color: #065f46;">
                    Sudah Diambil (Selesai)
                </div>
                <span class="badge badge-success">{{ $doneOrders }}</span>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="card" style="margin-bottom:0;">
        <div class="card-header">
            <div class="card-title">Order Terbaru</div>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        @if($recentOrders->isEmpty())
            <div style="text-align:center;padding:30px;color:#64748b;font-size:14px;">Belum ada order</div>
        @else
            <div style="display:grid;gap:10px;">
                @foreach($recentOrders as $order)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;background: #f8fafc;border-radius:8px;">
                    <div>
                        <div style="font-size:13px;font-weight:600;color: #111827;">{{ $order->order_code }}</div>
                        <div style="font-size:11px;color: #64748b;margin-top:2px;">{{ $order->customer->customer_name ?? '-' }}</div>
                    </div>
                    <div style="text-align:right;">
                        <span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
                        <div style="font-size:11px;color: #64748b;margin-top:3px;">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>


@endsection
