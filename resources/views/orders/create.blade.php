@extends('layouts.app')
@section('title', 'Buat Order')
@section('page-title', 'Buat Order Laundry')

@push('styles')
<style>
    .detail-line {
        background: rgba(255,255,255,.04);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 10px;
        position: relative;
    }
    .detail-line .grid { align-items: center; }
    .remove-row {
        background: rgba(239,68,68,.15); color:#f87171;
        border: 1px solid rgba(239,68,68,.2);
        border-radius: 8px; cursor:pointer;
        width: 34px; height: 34px;
        display: flex; align-items: center; justify-content: center;
        transition: all .2s; font-size: 14px;
    }
    .remove-row:hover { background: rgba(239,68,68,.3); }
    #total-display {
        font-size: 22px; font-weight: 800;
        color: #6ee7b7; font-family: monospace;
    }
</style>
@endpush

@section('content')
<div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">
    <div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Form Buat Order</div>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">
                    Kembali
                </a>
            </div>

            <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
                @csrf

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">Tipe Pelanggan</label>
                    <div style="display: flex; gap: 16px;">
                        <label><input type="radio" name="order_type" value="member" checked onchange="toggleCustomerType()"> Member Terdaftar</label>
                        <label><input type="radio" name="order_type" value="guest" onchange="toggleCustomerType()"> Pelanggan Baru (Sekali Order)</label>
                    </div>
                </div>

                <div class="grid grid-2">
                    <div id="member-field" class="form-group">
                        <label class="form-label">Pelanggan <span style="color:#ef4444;">*</span></label>
                        <select name="id_customer" id="id_customer" class="form-control {{ $errors->has('id_customer') ? 'is-invalid' : '' }}">
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ old('id_customer') == $c->id ? 'selected' : '' }}>
                                    {{ $c->customer_name }} - {{ $c->phone }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_customer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div id="guest-field" style="display: none; grid-column: 1 / -1;">
                        <div class="grid grid-2">
                            <div class="form-group">
                                <label class="form-label">Nama Pelanggan <span style="color:#ef4444;">*</span></label>
                                <input type="text" name="guest_name" class="form-control" value="{{ old('guest_name') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">No. Telepon <span style="color:#ef4444;">*</span></label>
                                <input type="text" name="guest_phone" class="form-control" value="{{ old('guest_phone') }}"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="guest_address" class="form-control" value="{{ old('guest_address') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Order <span style="color:#ef4444;">*</span></label>
                        <input type="date" name="order_date" class="form-control {{ $errors->has('order_date') ? 'is-invalid' : '' }}"
                            min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            value="{{ old('order_date', date('Y-m-d')) }}">
                        @error('order_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Estimasi Selesai <span style="color:#ef4444;">*</span></label>
                        <input type="date" name="order_end_date" class="form-control" required
                            min="{{ date('Y-m-d') }}"
                            value="{{ old('order_end_date') }}">
                    </div>
                </div>

                <hr class="divider">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                    <div style="font-weight:700;font-size:15px;">Detail Layanan</div>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="addRow()">
                        Tambah Layanan
                    </button>
                </div>

                <div id="detail-rows"></div>

                @error('services')<div class="alert alert-danger">{{ $message }}</div>@enderror

                <div style="margin-top:20px;">
                    <button type="submit" class="btn btn-primary">
                        Simpan Order
                    </button>
                </div>
            </div>
        </div>

    {{-- Summary panel --}}
    <div style="position:sticky;top:80px;">
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;">Ringkasan Order</div>
            <div id="summary-list" style="display:grid;gap:8px;margin-bottom:16px;"></div>
            <hr class="divider">
            <hr class="divider">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <span style="font-weight:700;font-size:14px;">Total Akhir</span>
                <div id="total-display">Rp 0</div>
            </div>
            <div class="form-group" style="margin-bottom:12px;">
                <label style="font-size:13px;color:#cbd5e1;">Uang Bayar (Rp)</label>
                <input type="number" name="order_pay" id="order_pay" class="form-control"
                    placeholder="0" min="0" oninput="calcTotal()" style="text-align:right;">
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <span style="color:#94a3b8;font-size:14px;">Kembalian</span>
                <div id="change-display" style="font-size:18px;font-weight:700;color:#fcd34d;">Rp 0</div>
            </div>
        </div>
    </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const services = @json($services);
let rowIndex = 0;
function toggleCustomerType() {
    const isGuest = document.querySelector('input[name="order_type"]:checked').value === 'guest';
    const memberField = document.getElementById('member-field');
    const guestField = document.getElementById('guest-field');
    const idCustomer = document.getElementById('id_customer');

    if (isGuest) {
        memberField.style.display = 'none';
        guestField.style.display = 'block';
        idCustomer.value = '';
    } else {
        memberField.style.display = 'block';
        guestField.style.display = 'none';
    }
    calcTotal();
}

function addRow(svcId = '', qty = 1, notes = '') {
    const container = document.getElementById('detail-rows');
    const div = document.createElement('div');
    div.className = 'detail-line';
    div.dataset.index = rowIndex;

    let options = '<option value="">-- Pilih Layanan --</option>';
    services.forEach(s => {
        options += `<option value="${s.id}" data-price="${s.price}" ${s.id == svcId ? 'selected' : ''}>
            ${s.service_name} – Rp ${Number(s.price).toLocaleString('id-ID')}
        </option>`;
    });

    div.innerHTML = `
        <div style="display:grid;grid-template-columns:2fr 100px auto 34px;gap:12px;align-items:end;">
            <div>
                <label class="form-label" style="font-size:12px;">Layanan</label>
                <select name="services[${rowIndex}][id_service]" class="form-control svc-select" onchange="calcTotal()" required>
                    ${options}
                </select>
            </div>
            <div>
                <label class="form-label" style="font-size:12px;">Qty (Gram)</label>
                <input type="number" name="services[${rowIndex}][qty]" class="form-control svc-qty"
                    value="${qty}" min="1" oninput="calcTotal()" required>
            </div>
            <div>
                <label class="form-label" style="font-size:12px;">Catatan</label>
                <input type="text" name="services[${rowIndex}][notes]" class="form-control"
                    value="${notes}" placeholder="Opsional">
            </div>
            <div style="margin-top:22px;">
                <button type="button" class="remove-row" onclick="removeRow(this)">X</button>
            </div>
        </div>
        <div style="margin-top:8px;font-size:12px;color:#94a3b8;" class="svc-subtotal"></div>
    `;

    container.appendChild(div);
    rowIndex++;
    calcTotal();
}

function removeRow(btn) {
    btn.closest('.detail-line').remove();
    calcTotal();
}

function calcTotal() {
    const rows = document.querySelectorAll('.detail-line');
    let grand = 0;
    let summaryHtml = '';

    rows.forEach(row => {
        const sel = row.querySelector('.svc-select');
        const qty = parseInt(row.querySelector('.svc-qty').value) || 0;
        const opt = sel.options[sel.selectedIndex];
        const price = parseInt(opt?.dataset?.price || 0);
        const subtotal = Math.round(price * (qty / 1000));
        grand += subtotal;

        if (sel.value && qty > 0) {
            row.querySelector('.svc-subtotal').textContent = `Subtotal: Rp ${subtotal.toLocaleString('id-ID')}`;
            summaryHtml += `<div style="display:flex;justify-content:space-between;font-size:13px;">
                <span style="color:#94a3b8;">${opt.text.split('–')[0].trim()} x ${qty}g</span>
                <span style="color:#e2e8f0;">Rp ${subtotal.toLocaleString('id-ID')}</span>
            </div>`;
        } else {
            row.querySelector('.svc-subtotal').textContent = '';
        }
    });

    const finalTotal = grand;
    document.getElementById('total-display').textContent = 'Rp ' + finalTotal.toLocaleString('id-ID');

    const pay = parseInt(document.getElementById('order_pay').value) || 0;
    const change = Math.max(0, pay - finalTotal);
    document.getElementById('change-display').textContent = 'Rp ' + change.toLocaleString('id-ID');

    document.getElementById('summary-list').innerHTML = summaryHtml ||
        '<div style="color:#64748b;font-size:13px;text-align:center;">Belum ada layanan dipilih</div>';
}

// Add first row on load
toggleCustomerType(); // Initialize view
addRow();
</script>
@endpush
