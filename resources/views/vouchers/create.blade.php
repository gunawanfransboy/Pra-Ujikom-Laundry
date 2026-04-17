@extends('layouts.app')
@section('title', 'Tambah Voucher')
@section('page-title', 'Tambah Voucher Baru')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <div class="card-title">Form Tambah Voucher</div>
        <a href="{{ route('vouchers.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <form action="{{ route('vouchers.store') }}" method="POST">
        @csrf
        <div class="form-group" style="margin-bottom: 15px;">
            <label class="form-label">Kode Voucher <span style="color:#ef4444;">*</span></label>
            <input type="text" name="voucher_code" class="form-control @error('voucher_code') is-invalid @enderror"
                   value="{{ old('voucher_code') }}" style="text-transform: uppercase;" required>
            @error('voucher_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label">Persentase Potongan (%) <span style="color:#ef4444;">*</span></label>
            <input type="number" name="discount_percent" class="form-control @error('discount_percent') is-invalid @enderror"
                   value="{{ old('discount_percent', 10) }}" min="1" max="100" required>
            @error('discount_percent')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label">Berlaku Sampai (Opsional)</label>
            <input type="date" name="valid_until" class="form-control @error('valid_until') is-invalid @enderror"
                   value="{{ old('valid_until') }}">
            <small style="color:#94a3b8; font-size:12px;">Kosongkan jika voucher tidak pernah kadaluarsa.</small>
            @error('valid_until')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan Voucher</button>
    </form>
</div>
@endsection
