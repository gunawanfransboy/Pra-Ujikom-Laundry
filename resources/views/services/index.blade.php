@extends('layouts.app')
@section('title', 'Jenis Layanan')
@section('page-title', 'Jenis Layanan')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Jenis Layanan</div>
        <a href="{{ route('services.create') }}" class="btn btn-primary">
            Tambah Layanan
        </a>
    </div>

    <form method="GET" action="{{ route('services.index') }}" style="margin-bottom:20px;">
        <div class="search-wrap">
            <div class="search-input-wrap">
                <input type="text" name="search" class="form-control"
                    placeholder="Cari nama layanan..." value="{{ $search ?? '' }}">
            </div>
            <button type="submit" class="btn btn-primary">Cari</button>
            @if($search)
                <a href="{{ route('services.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Layanan</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th>Dibuat</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $i => $s)
                    <tr>
                        <td style="color:#64748b;">{{ $services->firstItem() + $i }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span style="font-weight:600;">{{ $s->service_name }}</span>
                            </div>
                        </td>
                        <td>
                            <span style="font-weight:700;color:#6ee7b7;">Rp {{ number_format($s->price, 0, ',', '.') }}</span>
                        </td>
                        <td style="color:#94a3b8;font-size:13px;max-width:200px;">{{ Str::limit($s->description, 60) ?? '-' }}</td>
                        <td style="color:#64748b;font-size:13px;">{{ $s->created_at->format('d M Y') }}</td>
                        <td>
                            <div style="display:flex;gap:6px;justify-content:center;">
                                <a href="{{ route('services.edit', $s) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('services.destroy', $s) }}"
                                    onsubmit="return confirm('Hapus layanan {{ $s->service_name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash-fill me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:40px;color:#64748b;">
                            <div style="font-size:36px;margin-bottom:10px;"></div>
                            Belum ada jenis layanan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $services->links('pagination::simple-default') }}</div>
    <div style="text-align:center;font-size:12px;color:#64748b;margin-top:8px;">
        Menampilkan {{ $services->firstItem() ?? 0 }}–{{ $services->lastItem() ?? 0 }} dari {{ $services->total() }} layanan
    </div>
</div>
@endsection
