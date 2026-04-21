@extends('layouts.app')
@section('title', 'Tambah Pelanggan')
@section('page-title', 'Tambah Pelanggan')

@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header">
        <div class="card-title">Tambah Pelanggan</div>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('customers.store') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Nama Pelanggan <span style="color:#ef4444;">*</span></label>
            <input type="text" name="customer_name" class="form-control {{ $errors->has('customer_name') ? 'is-invalid' : '' }}"
                value="{{ old('customer_name') }}" placeholder="Masukkan nama lengkap" autofocus>
            @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">No Telepon <span style="color:#ef4444;">*</span></label>
            <input type="text" name="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                value="{{ old('phone') }}" placeholder="Contoh: 08123456789" maxlength="13" 
                oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Alamat <span style="color:#ef4444;">*</span></label>
            <textarea name="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex;gap:10px;margin-top:24px;">
            <button type="submit" class="btn btn-primary">
                Simpan Pelanggan
            </button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
