@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- Stat Cards --}}
<div class="grid grid-3" style="margin-bottom:24px;">
    <div class="card" style="margin-bottom:0; background: linear-gradient(135deg,rgba(79,70,229,.25),rgba(79,70,229,.1)); border-color:rgba(79,70,229,.3);">
        <div style="display:flex;align-items:center;gap:16px;">
            <div>
                <div style="font-size:28px;font-weight:800;color:#a5b4fc;">{{ $totalCustomers }}</div>
                <div style="font-size:12px;color:#94a3b8;margin-top:2px;">Total Pelanggan</div>
            </div>
        </div>
    </div>
    <div class="card" style="margin-bottom:0; background: linear-gradient(135deg,rgba(6,182,212,.25),rgba(6,182,212,.1)); border-color:rgba(6,182,212,.3);">
        <div style="display:flex;align-items:center;gap:16px;">
            <div>
                <div style="font-size:28px;font-weight:800;color:#67e8f9;">{{ $totalOrders }}</div>
                <div style="font-size:12px;color:#94a3b8;margin-top:2px;">Total Order</div>
            </div>
        </div>
    </div>
    <div class="card" style="margin-bottom:0; background: linear-gradient(135deg,rgba(16,185,129,.25),rgba(16,185,129,.1)); border-color:rgba(16,185,129,.3);">
        <div style="display:flex;align-items:center;gap:16px;">
            <div>
                <div style="font-size:22px;font-weight:800;color:#6ee7b7;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div style="font-size:12px;color:#94a3b8;margin-top:2px;">Total Pendapatan</div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-2">
    {{-- Order Status --}}
    <div class="card" style="margin-bottom:0;">
        <div class="card-header">
            <div class="card-title">📊 Status Order</div>
        </div>
        <div style="display:grid;gap:12px;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.2);border-radius:10px;">
                <div style="display:flex;align-items:center;gap:10px;font-size:14px;">
                    <span style="color:#fbbf24;">⏳</span> Baru (Pending)
                </div>
                <span class="badge badge-warning">{{ $pendingOrders }}</span>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.2);border-radius:10px;">
                <div style="display:flex;align-items:center;gap:10px;font-size:14px;">
                    <span style="color:#34d399;">✅</span> Sudah Diambil (Selesai)
                </div>
                <span class="badge badge-success">{{ $doneOrders }}</span>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="card" style="margin-bottom:0;">
        <div class="card-header">
            <div class="card-title">🕐 Order Terbaru</div>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        @if($recentOrders->isEmpty())
            <div style="text-align:center;padding:30px;color:#64748b;font-size:14px;">Belum ada order</div>
        @else
            <div style="display:grid;gap:10px;">
                @foreach($recentOrders as $order)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;background:rgba(255,255,255,.03);border-radius:8px;">
                    <div>
                        <div style="font-size:13px;font-weight:600;color:#e2e8f0;">{{ $order->order_code }}</div>
                        <div style="font-size:11px;color:#94a3b8;margin-top:2px;">{{ $order->customer->customer_name ?? '-' }}</div>
                    </div>
                    <div style="text-align:right;">
                        <span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
                        <div style="font-size:11px;color:#94a3b8;margin-top:3px;">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Quick Actions --}}
<div class="card" style="margin-top:24px;">
    <div class="card-title" style="margin-bottom:16px;">⚡ Aksi Cepat</div>
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
@endsection
