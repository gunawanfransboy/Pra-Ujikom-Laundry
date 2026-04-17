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
                        <select name="id_customer" id="id_customer" class="form-control {{ $errors->has('id_customer') ? 'is-invalid' : '' }}" onchange="checkMemberStatus()">
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ old('id_customer') == $c->id ? 'selected' : '' }}>
                                    {{ $c->customer_name }} ({{ $c->phone }})
                                </option>
                            @endforeach
                        </select>
                        <div id="member-status-info" style="font-size:12px; color:#10b981; margin-top:4px; display:none;">
                            Pelanggan ini mendapatkan potongan Member Baru 5%
                        </div>
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
                                <input type="text" name="guest_phone" class="form-control" value="{{ old('guest_phone') }}">
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
                            value="{{ old('order_date', date('Y-m-d')) }}">
                        @error('order_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Estimasi Selesai</label>
                        <input type="date" name="order_end_date" class="form-control"
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
            <!-- form tag removed from here -->
        </div>
    </div>

    {{-- Summary panel --}}
    <div style="position:sticky;top:80px;">
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;">Ringkasan Order</div>
            <div id="summary-list" style="display:grid;gap:8px;margin-bottom:16px;"></div>
            <hr class="divider">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                <span style="color:#94a3b8;font-size:14px;">Subtotal</span>
                <div id="subtotal-display" style="font-weight:600;">Rp 0</div>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                <span style="color:#94a3b8;font-size:14px;">Pajak (10%)</span>
                <div id="tax-display" style="font-weight:600;">Rp 0</div>
            </div>
            <div id="member-discount-row" style="display:none;align-items:center;justify-content:space-between;margin-bottom:8px;">
                <span style="color:#10b981;font-size:14px;">Diskon Member (5%)</span>
                <div id="member-discount-display" style="font-weight:600;color:#10b981;">- Rp 0</div>
            </div>
            
            <div style="margin-bottom:12px; margin-top:12px; border:1px dashed #334155; padding:10px; border-radius:8px;">
                <label style="font-size:12px; color:#cbd5e1;">Punya Kode Voucher?</label>
                <div style="display:flex; gap:8px;">
                    <input type="text" id="voucher_input" class="form-control" placeholder="Masukkan Voucher" style="height:32px; font-size:13px; text-transform:uppercase;">
                    <input type="hidden" name="voucher_code" id="valid_voucher_code">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="checkVoucher()" style="padding:0 12px; height:32px;">Cek</button>
                </div>
                <div id="voucher-status-msg" style="font-size:11px; margin-top:6px;"></div>
            </div>

            <div id="voucher-discount-row" style="display:none;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <span style="color:#10b981;font-size:14px;">Diskon Voucher (<span id="vd-perc">0</span>%)</span>
                <div id="voucher-discount-display" style="font-weight:600;color:#10b981;">- Rp 0</div>
            </div>

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
let isMemberFirstOrder = false;
let memberDiscountPerc = 5;
let voucherDiscountPerc = 0;

function toggleCustomerType() {
    const isGuest = document.querySelector('input[name="order_type"]:checked').value === 'guest';
    const memberField = document.getElementById('member-field');
    const guestField = document.getElementById('guest-field');
    const idCustomer = document.getElementById('id_customer');

    if (isGuest) {
        memberField.style.display = 'none';
        guestField.style.display = 'block';
        idCustomer.value = '';
        isMemberFirstOrder = false;
        document.getElementById('member-status-info').style.display = 'none';
    } else {
        memberField.style.display = 'block';
        guestField.style.display = 'none';
        checkMemberStatus();
    }
    calcTotal();
}

async function checkMemberStatus() {
    const custId = document.getElementById('id_customer').value;
    const isGuest = document.querySelector('input[name="order_type"]:checked').value === 'guest';
    
    if (!custId || isGuest) {
        isMemberFirstOrder = false;
        document.getElementById('member-status-info').style.display = 'none';
        calcTotal();
        return;
    }

    try {
        const res = await fetch(`/api/check-member-first-order/${custId}`);
        const data = await res.json();
        isMemberFirstOrder = data.is_first;
        memberDiscountPerc = data.discount_percent;

        const info = document.getElementById('member-status-info');
        if (isMemberFirstOrder) {
            info.style.display = 'block';
        } else {
            info.style.display = 'none';
        }
    } catch(e) { console.log(e); }
    
    calcTotal();
}

async function checkVoucher() {
    const code = document.getElementById('voucher_input').value.toUpperCase();
    const msg = document.getElementById('voucher-status-msg');
    const inputCode = document.getElementById('valid_voucher_code');
    
    if (!code) { msg.textContent = 'Masukkan kode dulu.'; msg.style.color = '#ef4444'; return; }
    
    msg.textContent = 'Mengecek...'; msg.style.color = '#94a3b8';
    
    try {
        const formData = new FormData();
        formData.append('code', code);
        formData.append('_token', '{{ csrf_token() }}');

        const isGuest = document.querySelector('input[name="order_type"]:checked').value === 'guest';
        if (isGuest) {
            formData.append('guest_phone', document.querySelector('input[name="guest_phone"]').value);
        } else {
            formData.append('id_customer', document.getElementById('id_customer').value);
        }
        
        const res = await fetch('{{ route("vouchers.check") }}', { method: 'POST', body: formData });
        const data = await res.json();
        
        if (data.valid) {
            msg.textContent = `Voucher Valid! Potongan ${data.discount_percent}%.`;
            msg.style.color = '#10b981';
            voucherDiscountPerc = data.discount_percent;
            inputCode.value = code;
        } else {
            msg.textContent = data.message;
            msg.style.color = '#ef4444';
            voucherDiscountPerc = 0;
            inputCode.value = '';
        }
    } catch(e) {
        msg.textContent = 'Gagal mengecek voucher.';
        msg.style.color = '#ef4444';
        voucherDiscountPerc = 0;
        inputCode.value = '';
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

    document.getElementById('subtotal-display').textContent = 'Rp ' + grand.toLocaleString('id-ID');
    const tax = Math.round(grand * 0.10);
    document.getElementById('tax-display').textContent = 'Rp ' + tax.toLocaleString('id-ID');

    const baseTotal = grand + tax;
    
    let discMemberAmt = 0;
    const memberDiscRow = document.getElementById('member-discount-row');
    if (isMemberFirstOrder) {
        discMemberAmt = Math.round(baseTotal * (memberDiscountPerc / 100));
        memberDiscRow.style.display = 'flex';
        document.getElementById('member-discount-display').textContent = '- Rp ' + discMemberAmt.toLocaleString('id-ID');
    } else {
        memberDiscRow.style.display = 'none';
    }

    let discVoucherAmt = 0;
    const voucherDiscRow = document.getElementById('voucher-discount-row');
    if (voucherDiscountPerc > 0) {
        discVoucherAmt = Math.round(baseTotal * (voucherDiscountPerc / 100));
        voucherDiscRow.style.display = 'flex';
        document.getElementById('vd-perc').textContent = voucherDiscountPerc;
        document.getElementById('voucher-discount-display').textContent = '- Rp ' + discVoucherAmt.toLocaleString('id-ID');
    } else {
        voucherDiscRow.style.display = 'none';
    }

    const finalTotal = baseTotal - discMemberAmt - discVoucherAmt;
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
