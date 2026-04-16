@extends('layouts.app')
@section('title', 'Tambah Layanan')
@section('page-title', 'Tambah Layanan')

@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header">
        <div class="card-title">Form Tambah Layanan</div>
        <a href="{{ route('services.index') }}" class="btn btn-secondary btn-sm">
            Kembali
        </a>
    </div>
    <form method="POST" action="{{ route('services.store') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Nama Layanan <span style="color:#ef4444;">*</span></label>
            <input type="text" name="service_name" class="form-control {{ $errors->has('service_name') ? 'is-invalid' : '' }}"
                value="{{ old('service_name') }}" placeholder="Contoh: Cuci + Setrika" autofocus>
            @error('service_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Harga (Rp) <span style="color:#ef4444;">*</span></label>
            <input type="number" name="price" class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}"
                value="{{ old('price') }}" placeholder="Contoh: 7000" min="0">
            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                placeholder="Deskripsi layanan (opsional)">{{ old('description') }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div style="display:flex;gap:10px;margin-top:24px;">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('services.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
