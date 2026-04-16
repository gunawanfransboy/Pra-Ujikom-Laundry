@extends('layouts.app')
@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Laporan Penjualan (Selesai/Sudah Diambil)</div>
    </div>

    <form method="GET" action="{{ route('report.index') }}" style="margin-bottom:20px;">
        <div class="search-wrap" style="background:rgba(255,255,255,.03);padding:14px;border-radius:12px;border:1px solid var(--border);">
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <div class="form-group" style="margin-bottom:0;">
                    <label style="font-size:12px;color:var(--text-muted);display:block;margin-bottom:4px;">Rekap Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" style="width:160px;padding:8px 12px;">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label style="font-size:12px;color:var(--text-muted);display:block;margin-bottom:4px;">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" style="width:160px;padding:8px 12px;">
                </div>
                <div style="margin-top:20px;">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <button type="button" class="btn btn-secondary" onclick="window.print()">Cetak PDF</button>
                </div>
            </div>
        </div>
    </form>

    <div class="grid grid-3" style="margin-bottom:24px;">
        <div class="card" style="margin-bottom:0; background:linear-gradient(135deg,rgba(16,185,129,.15),rgba(16,185,129,.05)); border:1px solid rgba(16,185,129,.2);">
            <div style="font-size:14px;color:var(--text-muted);">Total Pendapatan (Periode Ini)</div>
            <div style="font-size:28px;font-weight:800;color:#6ee7b7;margin-top:4px;">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
        </div>
        <div class="card" style="margin-bottom:0; background:linear-gradient(135deg,rgba(6,182,212,.15),rgba(6,182,212,.05)); border:1px solid rgba(6,182,212,.2);">
            <div style="font-size:14px;color:var(--text-muted);">Transaksi Selesai</div>
            <div style="font-size:28px;font-weight:800;color:#67e8f9;margin-top:4px;">
                {{ $orders->count() }} Order
            </div>
        </div>
    </div>

    <div class="table-wrap print-area">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Order</th>
                    <th>Pelanggan</th>
                    <th>Tgl Transaksi</th>
                    <th>Tgl Diambil</th>
                    <th style="text-align:right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $i => $o)
                    <tr>
                        <td style="color:#64748b;">{{ $i + 1 }}</td>
                        <td><span style="font-weight:700;color:#a5b4fc;font-family:monospace;">{{ $o->order_code }}</span></td>
                        <td>{{ $o->customer->customer_name ?? '-' }}</td>
                        <td style="color:#94a3b8;font-size:13px;">{{ $o->order_date->format('d M Y') }}</td>
                        <td style="color:#94a3b8;font-size:13px;">{{ $o->updated_at->format('d M Y') }}</td>
                        <td style="text-align:right;font-weight:600;color:#6ee7b7;">Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:40px;color:#64748b;">
                            <div style="font-size:28px;margin-bottom:10px;"></div>
                            Tidak ada data transaksi selesai pada periode ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    .print-area, .print-area * { visibility: visible; }
    .print-area { position: absolute; left: 0; top: 0; width: 100%; }
    .card { border: none; box-shadow: none; }
    .search-wrap, .btn { display: none; }
}
</style>
@endsection
