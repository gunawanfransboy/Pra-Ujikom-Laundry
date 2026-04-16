@extends('layouts.app')
@section('title', 'Edit Order')
@section('page-title', 'Edit Order')

@push('styles')
<style>
    .detail-line {
        background: rgba(255,255,255,.04);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 10px;
    }
    .remove-row {
        background: rgba(239,68,68,.15); color:#f87171;
        border: 1px solid rgba(239,68,68,.2);
        border-radius: 8px; cursor:pointer;
        width: 34px; height: 34px;
        display: flex; align-items: center; justify-content: center;
        transition: all .2s; font-size: 14px;
    }
    .remove-row:hover { background: rgba(239,68,68,.3); }
    #total-display { font-size: 22px; font-weight: 800; color: #6ee7b7; font-family: monospace; }
</style>
@endpush

@section('content')
<div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">
    <div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Edit Order {{ $order->order_code }}</div>
                <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary btn-sm">
                    Kembali
                </a>
            </div>

            <form method="POST" action="{{ route('orders.update', $order) }}">
                @csrf @method('PUT')

                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Pelanggan <span style="color:#ef4444;">*</span></label>
                        <select name="id_customer" class="form-control">
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ old('id_customer', $order->id_customer) == $c->id ? 'selected' : '' }}>
                                    {{ $c->customer_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="order_status" class="form-control">
                            <option value="0" {{ old('order_status', $order->order_status) == 0 ? 'selected' : '' }}>Baru</option>
                            <option value="1" {{ old('order_status', $order->order_status) == 1 ? 'selected' : '' }}>Sudah Diambil</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Order</label>
                        <input type="date" name="order_date" class="form-control"
                            value="{{ old('order_date', $order->order_date->format('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Estimasi Selesai</label>
                        <input type="date" name="order_end_date" class="form-control"
                            value="{{ old('order_end_date', $order->order_end_date ? $order->order_end_date->format('Y-m-d') : '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Uang Bayar (Rp)</label>
                        <input type="number" name="order_pay" class="form-control"
                            value="{{ old('order_pay', $order->order_pay) }}" min="0" oninput="calcTotal()">
                    </div>
                </div>

                <hr class="divider">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                    <div style="font-weight:700;font-size:15px;">Detail Layanan</div>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="addRow()">
                        Tambah Baris
                    </button>
                </div>

                <div id="detail-rows"></div>

                <div style="margin-top:20px;">
                    <button type="submit" class="btn btn-primary">
                        Update Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div style="position:sticky;top:80px;">
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;">Ringkasan</div>
            <div id="summary-list" style="display:grid;gap:8px;margin-bottom:16px;"></div>
            <hr class="divider">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span style="color:#94a3b8;">Total</span>
                <div id="total-display">Rp 0</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const services = @json($services);
let rowIndex = 0;

function addRow(svcId = '', qty = 1, notes = '') {
    const container = document.getElementById('detail-rows');
    const div = document.createElement('div');
    div.className = 'detail-line';

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
                <select name="services[${rowIndex}][id_service]" class="form-control svc-select" onchange="calcTotal()">${options}</select>
            </div>
            <div>
                <label class="form-label" style="font-size:12px;">Qty (Gram)</label>
                <input type="number" name="services[${rowIndex}][qty]" class="form-control svc-qty"
                    value="${qty}" min="1" oninput="calcTotal()">
            </div>
            <div>
                <label class="form-label" style="font-size:12px;">Catatan</label>
                <input type="text" name="services[${rowIndex}][notes]" class="form-control" value="${notes}" placeholder="Opsional">
            </div>
            <div style="margin-top:22px;">
                <button type="button" class="remove-row" onclick="this.closest('.detail-line').remove();calcTotal();">
                    </button>
            </div>
        </div>
        <div style="margin-top:8px;font-size:12px;color:#94a3b8;" class="svc-subtotal"></div>
    `;
    container.appendChild(div);
    rowIndex++;
    calcTotal();
}

function calcTotal() {
    const rows = document.querySelectorAll('.detail-line');
    let grand = 0;
    let html = '';
    rows.forEach(row => {
        const sel = row.querySelector('.svc-select');
        const qty = parseInt(row.querySelector('.svc-qty').value) || 0;
        const opt = sel.options[sel.selectedIndex];
        const price = parseInt(opt?.dataset?.price || 0);
        const sub = Math.round(price * (qty / 1000));
        grand += sub;
        if (sel.value && qty > 0) {
            row.querySelector('.svc-subtotal').textContent = `Subtotal: Rp ${sub.toLocaleString('id-ID')}`;
            html += `<div style="display:flex;justify-content:space-between;font-size:13px;">
                <span style="color:#94a3b8;">${opt.text.split('–')[0].trim()} x ${qty}g</span>
                <span>Rp ${sub.toLocaleString('id-ID')}</span></div>`;
        } else {
            row.querySelector('.svc-subtotal').textContent = '';
        }
    });
    document.getElementById('total-display').textContent = 'Rp ' + grand.toLocaleString('id-ID');
    document.getElementById('summary-list').innerHTML = html ||
        '<div style="color:#64748b;font-size:13px;text-align:center;">Belum ada layanan</div>';
}

// Load existing details
@foreach($order->details as $d)
addRow({{ $d->id_service }}, {{ $d->qty }}, '{{ $d->notes ?? '' }}');
@endforeach

if (document.querySelectorAll('.detail-line').length === 0) addRow();
</script>
@endpush
