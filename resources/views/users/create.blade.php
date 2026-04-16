@extends('layouts.app')
@section('title', 'Tambah User')
@section('page-title', 'Tambah User')

@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header">
        <div class="card-title">Form Tambah Pengguna Baru</div>
        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Level / Hak Akses <span style="color:#ef4444;">*</span></label>
            <select name="id_level" class="form-control {{ $errors->has('id_level') ? 'is-invalid' : '' }}">
                <option value="">-- Pilih Level --</option>
                @foreach($levels as $l)
                    <option value="{{ $l->id }}" {{ old('id_level') == $l->id ? 'selected' : '' }}>
                        {{ $l->level_name }}
                    </option>
                @endforeach
            </select>
            @error('id_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Nama Lengkap <span style="color:#ef4444;">*</span></label>
            <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                value="{{ old('name') }}" placeholder="Masukkan nama user">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Email <span style="color:#ef4444;">*</span></label>
            <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                value="{{ old('email') }}" placeholder="operator@laundry.com">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Password <span style="color:#ef4444;">*</span></label>
            <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                placeholder="Minimal 6 karakter">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex;gap:10px;margin-top:24px;">
            <button type="submit" class="btn btn-primary">
                Simpan User
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
