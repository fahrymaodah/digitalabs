@extends('emails.layout')

@section('content')
    {{-- Failed Icon & Title --}}
    <div style="text-align: center; margin-bottom: 20px;">
        <div style="display: inline-block; width: 60px; height: 60px; background: linear-gradient(135deg, #fee2e2 0%, #fca5a5 100%); border-radius: 50%; line-height: 60px; font-size: 30px;">
            ❌
        </div>
        <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 12px 0 8px 0;">
            Pembayaran {{ $order->status === 'expired' ? 'Kadaluarsa' : 'Gagal' }}
        </h1>
        <p style="font-size: 15px; color: #6b7280; margin: 0;">
            {{ $order->user->name ?? 'Unknown' }} • {{ $order->order_number }}
        </p>
    </div>

    {{-- Order Amount Box --}}
    <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 12px; padding: 20px; margin: 20px 0; text-align: center;">
        <p style="font-size: 13px; color: rgba(255,255,255,0.8); margin: 0 0 6px 0; text-transform: uppercase; letter-spacing: 0.5px;">
            Total Pesanan
        </p>
        <p style="font-size: 32px; font-weight: 700; color: #ffffff; margin: 0;">
            Rp {{ number_format($order->total, 0, ',', '.') }}
        </p>
        <p style="font-size: 12px; color: rgba(255,255,255,0.7); margin: 8px 0 0 0; text-transform: uppercase;">
            {{ strtoupper($order->status) }}
        </p>
    </div>

    {{-- Order Details --}}
    <h2 style="font-size: 16px; font-weight: 600; color: #111827; margin: 20px 0 12px 0;">
        📋 Detail Pesanan
    </h2>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f9fafb; border-radius: 8px; overflow: hidden; margin: 0 0 16px 0;">
        <tr>
            <td style="padding: 10px 16px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 13px; color: #6b7280;">Email</span>
                <p style="font-size: 14px; color: #111827; font-weight: 600; margin: 4px 0 0 0;">
                    {{ $order->user->email ?? 'N/A' }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px 16px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 13px; color: #6b7280;">Status</span>
                <p style="font-size: 14px; color: #dc2626; font-weight: 600; margin: 4px 0 0 0;">
                    {{ ucfirst($order->status) }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px 16px;">
                <span style="font-size: 13px; color: #6b7280;">Waktu {{ $order->status === 'expired' ? 'Kadaluarsa' : 'Gagal' }}</span>
                <p style="font-size: 14px; color: #111827; font-weight: 600; margin: 4px 0 0 0;">
                    {{ now()->format('d M Y, H:i') }}
                </p>
            </td>
        </tr>
    </table>

    {{-- Items List --}}
    @if($order->items->count() > 0)
        <h2 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px 0;">
            📦 Item yang Tidak Jadi Dibeli
        </h2>

        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f9fafb; border-radius: 8px; overflow: hidden; margin: 0 0 16px 0;">
            @foreach($order->items as $item)
                <tr>
                    <td style="padding: 10px 16px; {{ !$loop->last ? 'border-bottom: 1px solid #e5e7eb;' : '' }}">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td style="width: 65%; vertical-align: middle;">
                                    <span style="font-size: 14px; color: #111827; font-weight: 500; display: block;">
                                        {{ $item->course->name ?? 'Unknown Course' }}
                                    </span>
                                </td>
                                <td style="width: 35%; text-align: right; vertical-align: middle;">
                                    <span style="font-size: 14px; color: #6b7280; font-weight: 600; text-decoration: line-through; display: block;">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

    @php
        $affiliateCommission = $order->commission->first()?->commission_amount
            ?? ($order->affiliate ? $order->total * ($order->affiliate->commission_rate / 100) : 0);
    @endphp

    @if($order->affiliate)
    {{-- Affiliate Info --}}
    <div style="background-color: #fef3c7; border-radius: 8px; padding: 12px 16px; margin: 0 0 16px 0;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td style="width: 50%; vertical-align: middle;">
                    <span style="font-size: 12px; color: #92400e; display: block;">🤝 Affiliate</span>
                    <p style="font-size: 14px; color: #78350f; font-weight: 600; margin: 2px 0 0 0;">
                        {{ $order->affiliate->user->name ?? 'N/A' }}
                    </p>
                </td>
                <td style="width: 50%; text-align: right; vertical-align: middle;">
                    <span style="font-size: 12px; color: #92400e; display: block;">Kode Referral</span>
                    <p style="font-size: 14px; color: #78350f; font-weight: 600; margin: 2px 0 0 0;">
                        {{ $order->affiliate->referral_code ?? 'N/A' }}
                    </p>
                </td>
            </tr>
        </table>
    </div>
    @endif

    {{-- Price Summary --}}
    <h2 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px 0;">
        💰 Rincian Harga (Tidak Terbayar)
    </h2>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #fef2f2; border-radius: 8px; overflow: hidden; margin: 0 0 16px 0;">
        <tr>
            <td style="padding: 10px 16px; border-bottom: 1px solid #fecaca;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="width: 60%;">
                            <span style="font-size: 14px; color: #6b7280;">Subtotal</span>
                        </td>
                        <td style="width: 40%; text-align: right;">
                            <span style="font-size: 14px; color: #111827; font-weight: 500;">
                                Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        @if($order->discount > 0 && $order->coupon)
        <tr>
            <td style="padding: 10px 16px; border-bottom: 1px solid #fecaca;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="width: 60%;">
                            <span style="font-size: 14px; color: #6b7280;">Diskon ({{ $order->coupon->code }})</span>
                        </td>
                        <td style="width: 40%; text-align: right;">
                            <span style="font-size: 14px; color: #dc2626; font-weight: 500;">
                                -Rp {{ number_format($order->discount, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @endif
        
        @if($order->affiliate && $affiliateCommission > 0)
        <tr>
            <td style="padding: 10px 16px; border-bottom: 1px solid #fecaca;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="width: 60%;">
                            <span style="font-size: 14px; color: #6b7280;">Komisi Affiliate</span>
                        </td>
                        <td style="width: 40%; text-align: right;">
                            <span style="font-size: 14px; color: #f59e0b; font-weight: 500;">
                                Rp {{ number_format($affiliateCommission, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @endif
        
        <tr>
            <td style="padding: 12px 16px; background-color: #fee2e2;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="width: 60%;">
                            <span style="font-size: 15px; color: #dc2626; font-weight: 600;">Total (Tidak Terbayar)</span>
                        </td>
                        <td style="width: 40%; text-align: right;">
                            <span style="font-size: 15px; color: #dc2626; font-weight: 700;">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 32px 0;"></div>

    {{-- CTA Button --}}
    <div style="text-align: center; margin: 32px 0;">
        <a href="{{ url('/admin/orders/' . $order->id) }}" 
           style="display: inline-block; background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 12px; font-weight: 600; font-size: 16px;">
            Lihat Detail Pesanan
        </a>
    </div>
@endsection
