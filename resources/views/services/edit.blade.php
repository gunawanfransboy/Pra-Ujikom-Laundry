@extends('layouts.app')
@section('title', 'Edit Layanan')
@section('page-title', 'Edit Layanan')

@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header">
        <div class="card-title">Edit Layanan</div>
        <a href="{{ route('services.index') }}" class="btn btn-secondary btn-sm">
            Kembali
        </a>
    </div>
    <form method="POST" action="{{ route('services.update', $service) }}">
        @csrf @method('PUT')
        <div class="form-group">
            <label class="form-label">Nama Layanan <span style="color:#ef4444;">*</span></label>
            <input type="text" name="service_name" class="form-control {{ $errors->has('service_name') ? 'is-invalid' : '' }}"
                value="{{ old('service_name', $service->service_name) }}">
            @error('service_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Harga (Rp) <span style="color:#ef4444;">*</span></label>
            <input type="number" name="price" class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}"
                value="{{ old('price', $service->price) }}" min="0">
            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control">{{ old('description', $service->description) }}</textarea>
        </div>
        <div style="display:flex;gap:10px;margin-top:24px;">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('services.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
