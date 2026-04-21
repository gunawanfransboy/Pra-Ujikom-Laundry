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

    <div class="table-wrap">
        <div class="print-header" style="display:none; text-align:center; margin-bottom:20px; border-bottom:2px solid #000; padding-bottom:10px;">
            <h2 style="margin:0; text-transform:uppercase;">Gunawan's Laundry</h2>
            <p style="margin:5px 0 0; font-size:14px; font-weight:600;">Laporan Penjualan Laundry</p>
            <p style="margin:2px 0 0; font-size:12px;">Periode: {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}</p>
        </div>
        
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th>Pelanggan</th>
                    <th>Jenis Layanan</th>
                    <th style="text-align:center; width:80px;">Berat (g)</th>
                    <th style="text-align:right;">Harga Satuan</th>
                    <th style="text-align:right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @forelse($orders as $o)
                    @foreach($o->details as $d)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td style="word-break:break-all;">{{ $o->customer->customer_name ?? $o->guest_name ?? '-' }}</td>
                            <td>{{ $d->service->service_name ?? '-' }}</td>
                            <td style="text-align:center;">{{ number_format($d->qty, 0, ',', '.') }}</td>
                            <td style="text-align:right;">Rp {{ number_format($d->service->price ?? 0, 0, ',', '.') }}/kg</td>
                            <td style="text-align:right; font-weight:700;">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:40px;color:#64748b;">
                            Tidak ada data transaksi selesai pada periode ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($orders->count() > 0)
            <tfoot>
                <tr style="border-top:2px solid #000;">
                    <th colspan="5" style="text-align:right; padding:12px;">Total Pendapatan:</th>
                    <th style="text-align:right; padding:12px; font-size:16px;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

<style>
@media print {
    @page {
        size: portrait;
        margin: 1cm;
    }
    body {
        background: #fff !important;
        color: #000 !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    .sidebar, .topbar, .search-wrap, .grid, .btn, .card-header .btn {
        display: none !important;
    }
    .main-wrap {
        margin: 0 !important;
        padding: 0 !important;
    }
    .page-content {
        padding: 0 !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    .card-header {
        display: none !important;
    }
    .print-header {
        display: block !important;
    }
    .table-wrap {
        overflow: visible !important;
    }
    table {
        width: 100% !important;
        border: 1px solid #000 !important;
        table-layout: auto !important;
    }
    th, td {
        border: 1px solid #000 !important;
        padding: 8px 6px !important;
        font-size: 11px !important;
        color: #000 !important;
    }
    th {
        background: #eee !important;
        -webkit-print-color-adjust: exact;
    }
}
</style>
@endsection
