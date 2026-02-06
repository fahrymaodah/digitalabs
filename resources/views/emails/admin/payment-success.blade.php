@extends('emails.layout')

@section('content')
    {{-- Success Icon & Title --}}
    <div style="text-align: center; margin-bottom: 20px;">
        <div style="display: inline-block; width: 60px; height: 60px; background: linear-gradient(135deg, #d1fae5 0%, #6ee7b7 100%); border-radius: 50%; line-height: 60px; font-size: 30px;">
            ✅
        </div>
        <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 12px 0 8px 0;">
            Pembayaran Berhasil
        </h1>
        <p style="font-size: 15px; color: #6b7280; margin: 0;">
            {{ $order->user->name ?? 'Unknown' }} • {{ $order->order_number }}
        </p>
    </div>

    {{-- Order Amount Box --}}
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 12px; padding: 20px; margin: 20px 0; text-align: center;">
        <p style="font-size: 13px; color: rgba(255,255,255,0.8); margin: 0 0 6px 0; text-transform: uppercase; letter-spacing: 0.5px;">
            Total Pembayaran
        </p>
        <p style="font-size: 32px; font-weight: 700; color: #ffffff; margin: 0;">
            Rp {{ number_format($order->total, 0, ',', '.') }}
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
                <span style="font-size: 13px; color: #6b7280;">Metode Pembayaran</span>
                <p style="font-size: 14px; color: #111827; font-weight: 600; margin: 4px 0 0 0;">
                    {{ ucfirst($order->payment_method ?? 'N/A') }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px 16px;">
                <span style="font-size: 13px; color: #6b7280;">Waktu Pembayaran</span>
                <p style="font-size: 14px; color: #111827; font-weight: 600; margin: 4px 0 0 0;">
                    {{ $order->paid_at ? $order->paid_at->format('d M Y, H:i') : now()->format('d M Y, H:i') }}
                </p>
            </td>
        </tr>
    </table>

    {{-- Items List --}}
    @if($order->items->count() > 0)
        <h2 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px 0;">
            📦 Item Dibeli
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
                                    <span style="font-size: 14px; color: #059669; font-weight: 600; display: block;">
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
        💰 Rincian Harga
    </h2>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f9fafb; border-radius: 8px; overflow: hidden; margin: 0 0 16px 0;">
        <tr>
            <td style="padding: 10px 16px; border-bottom: 1px solid #e5e7eb;">
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
            <td style="padding: 10px 16px; border-bottom: 1px solid #e5e7eb;">
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
        
        @if($order->affiliate)
        <tr>
            <td style="padding: 10px 16px; border-bottom: 1px solid #e5e7eb;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="width: 60%;">
                            <span style="font-size: 14px; color: #6b7280;">Komisi Affiliate</span>
                        </td>
                        <td style="width: 40%; text-align: right;">
                            <span style="font-size: 14px; color: #f59e0b; font-weight: 500;">
                                Rp {{ number_format($order->affiliate_commission ?? 0, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @endif
        
        <tr>
            <td style="padding: 12px 16px; background-color: #ecfdf5;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="width: 60%;">
                            <span style="font-size: 15px; color: #059669; font-weight: 600;">Total Pembayaran</span>
                        </td>
                        <td style="width: 40%; text-align: right;">
                            <span style="font-size: 15px; color: #059669; font-weight: 700;">
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
           style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 12px; font-weight: 600; font-size: 16px;">
            Lihat Detail Pesanan
        </a>
    </div>
@endsection
