@extends('layouts.app')
@section('title', 'Data Voucher')
@section('page-title', 'Kelola Voucher')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>


@section('content')
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div class="card-title">Daftar Voucher</div>
        <a href="{{ route('vouchers.create') }}" class="btn btn-primary btn-sm">Tambah Voucher</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Voucher</th>
                    <th>Potongan (%)</th>
                    <th>Berlaku Sampai</th>
                    <th>Status</th>
                    <th>Dibuat Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers as $index => $voucher)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="font-weight:bold; font-family:monospace;">{{ $voucher->voucher_code }}</td>
                    <td>{{ $voucher->discount_percent }}%</td>
                    <td>
                        @if($voucher->valid_until)
                            <span style="font-size:12px;">{{ $voucher->valid_until->format('d M Y') }}</span>
                        @else
                            <span style="color:#94a3b8; font-size:12px;">Tanpa Batas</span>
                        @endif
                    </td>
                    <td>
                        @if($voucher->valid_until && $voucher->valid_until < now()->startOfDay())
                            <span class="badge" style="background:#ef4444;">Expired</span>
                        @else
                            <span class="badge" style="background:#10b981;">Aktif</span>
                        @endif
                    </td>
                    <td>{{ $voucher->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <a href="{{ route('vouchers.edit', $voucher) }}" class="btn btn-info btn-sm"><i class="bi bi-pencil-fill"></i> Edit</a>
                        <form action="{{ route('vouchers.destroy', $voucher) }}" method="POST" style="display:inline-block;"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus voucher ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada voucher.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
