@extends('layouts.app')
@section('title', 'Detail Order')
@section('page-title', 'Detail Order')

@section('content')
<div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start;">
    <div>
        {{-- Order Info --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Informasi Order</div>
                <div style="display:flex;gap:8px;">
                    @if($order->order_status == 0)
                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-info btn-sm">
                        Edit
                    </a>
                    @endif
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">
                        Kembali
                    </a>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Kode Order</div>
                <div class="detail-value" style="font-weight:700;font-family:monospace;color:#a5b4fc;">{{ $order->order_code }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Pelanggan</div>
                <div class="detail-value" style="font-weight:600;">
                    {{ $order->customer->customer_name ?? $order->guest_name ?? '-' }}
                    @if(!$order->id_customer && $order->guest_name)
                        <span class="badge" style="background:#6366f1;font-size:10px;padding:2px 6px;">Guest</span>
                    @endif
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">No Telepon</div>
                <div class="detail-value">{{ $order->customer->phone ?? $order->guest_phone ?? '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Tanggal Order</div>
                <div class="detail-value">{{ $order->order_date->format('d M Y') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Estimasi Selesai <span style="color:#ef4444;">*</span></div>
                <div class="detail-value">{{ $order->order_end_date ? $order->order_end_date->format('d M Y') : '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    <span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
                </div>
            </div>

            {{-- Update Status --}}
            @if($order->order_status == 0)
            <hr class="divider">
            <div style="font-weight:600;font-size:14px;margin-bottom:12px;">Ubah Status Order</div>
            <form method="POST" action="{{ route('orders.updateStatus', $order) }}" style="display:flex;gap:8px;flex-wrap:wrap;">
                @csrf @method('PATCH')
                @foreach([0 => ['Baru','warning'], 1 => ['Sudah Diambil','success']] as $val => $info)
                    <button type="submit" name="order_status" value="{{ $val }}"
                        class="btn btn-{{ $info[1] }} btn-sm {{ $order->order_status == $val ? '' : '' }}"
                        style="{{ $order->order_status == $val ? 'opacity:.5;pointer-events:none;' : '' }}">
                        {{ $info[0] }}
                    </button>
                @endforeach
            </form>
            @endif
        </div>

        {{-- Detail Items --}}
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;">Detail Layanan</div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Layanan</th>
                            <th>Harga Satuan</th>
                            <th>Qty (Gram)</th>
                            <th>Subtotal</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->details as $i => $d)
                        <tr>
                            <td style="color:#64748b;">{{ $i + 1 }}</td>
                            <td style="font-weight:600;">{{ $d->service->service_name ?? '-' }}</td>
                            <td style="color:#94a3b8;">Rp {{ number_format($d->service->price ?? 0, 0, ',', '.') }}/kg</td>
                            <td>{{ $d->qty }} g</td>
                            <td style="font-weight:700;color:#6ee7b7;">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                            <td style="color:#94a3b8;font-size:13px;">{{ $d->notes ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Payment Summary --}}
    <div style="position:sticky;top:80px;">
        <div class="card" style="background:linear-gradient(145deg,rgba(79,70,229,.15),rgba(6,182,212,.1));border-color:rgba(79,70,229,.3);">
            <div class="card-title" style="margin-bottom:20px;">Ringkasan Pembayaran</div>

            @foreach($order->details as $d)
            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:8px;">
                <span style="color:#94a3b8;">{{ $d->service->service_name ?? '-' }} x {{ $d->qty }}g</span>
                <span>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
            </div>
            @endforeach

            <hr class="divider">

            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span style="color:#94a3b8;">Subtotal</span>
                <span style="font-weight:600;">Rp {{ number_format($order->subtotal ?? 0, 0, ',', '.') }}</span>
            </div>
            {{-- 
            <div style="display:flex;justify-content:space-between;margin-bottom:12px;">
                <span style="color:#94a3b8;">Pajak (10%)</span>
                <span style="font-weight:600;">Rp {{ number_format($order->tax ?? 0, 0, ',', '.') }}</span>
            </div>
            --}}

            {{-- 
            @if($order->discount_member > 0)
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span style="color:#10b981;">Diskon Member</span>
                <span style="font-weight:600;color:#10b981;">- Rp {{ number_format($order->discount_member, 0, ',', '.') }}</span>
            </div>
            @endif

            @if($order->discount_voucher > 0)
            <div style="display:flex;justify-content:space-between;margin-bottom:12px;">
                <span style="color:#10b981;">Diskon Voucher</span>
                <span style="font-weight:600;color:#10b981;">- Rp {{ number_format($order->discount_voucher, 0, ',', '.') }}</span>
            </div>
            @endif
            --}}

            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span style="font-weight:600;">Total</span>
                <span style="font-size:22px;font-weight:800;color:#6ee7b7;font-family:monospace;">
                    Rp {{ number_format($order->total, 0, ',', '.') }}
                </span>
            </div>

            @if($order->order_pay)
            <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:6px;">
                <span style="color:#94a3b8;">Bayar</span>
                <span style="color:#60a5fa;">Rp {{ number_format($order->order_pay, 0, ',', '.') }}</span>
            </div>
            @endif

            @if($order->order_change)
            <div style="display:flex;justify-content:space-between;font-size:14px;">
                <span style="color:#94a3b8;">Kembalian</span>
                <span style="color:#fbbf24;">Rp {{ number_format($order->order_change, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
