@extends('layouts.app')
@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Pengguna Sistem</div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            Tambah User Baru
        </a>
    </div>

    <form method="GET" action="{{ route('users.index') }}" style="margin-bottom:20px;">
        <div class="search-wrap">
            <div class="search-input-wrap">
                <input type="text" name="search" class="form-control"
                    placeholder="Cari nama / email..." value="{{ $search ?? '' }}">
            </div>
            <button type="submit" class="btn btn-primary">Cari</button>
            @if($search)
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Email</th>
                    <th>Level</th>
                    <th>Ditambahkan</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $i => $u)
                    <tr>
                        <td style="color:#64748b;">{{ $users->firstItem() + $i }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#8b5cf6,#06b6d4);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <span style="font-weight:600;">{{ $u->name }}</span>
                            </div>
                        </td>
                        <td>{{ $u->email }}</td>
                        <td>
                            @php
                                $badgeColor = 'secondary';
                                if($u->id_level == 1) $badgeColor = 'danger'; // Admin
                                elseif($u->id_level == 2) $badgeColor = 'info'; // Operator
                                elseif($u->id_level == 3) $badgeColor = 'success'; // Pimpinan
                            @endphp
                            <span class="badge badge-{{ $badgeColor }}">{{ $u->level->level_name ?? '-' }}</span>
                        </td>
                        <td style="color:#94a3b8;font-size:13px;">{{ $u->created_at->format('d M Y') }}</td>
                        <td>
                            <div style="display:flex;gap:6px;justify-content:center;">
                                <a href="{{ route('users.edit', $u) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </a>
                                @if(auth()->id() !== $u->id)
                                <form method="POST" action="{{ route('users.destroy', $u) }}"
                                    onsubmit="return confirm('Hapus user {{ $u->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash-fill"></i> Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:40px;color:#64748b;">
                            <div style="font-size:36px;margin-bottom:10px;"></div>
                            Belum ada data user
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $users->links('pagination::simple-default') }}</div>
</div>
@endsection
