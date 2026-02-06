@extends('emails.layout')

@section('content')
    {{-- Success Icon --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 80px; height: 80px; background: linear-gradient(135deg, #d1fae5 0%, #6ee7b7 100%); border-radius: 50%; line-height: 80px; font-size: 40px;">
            ✅
        </div>
    </div>

    <h1 class="email-title" style="text-align: center; font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 16px 0;">
        Pembayaran Berhasil
    </h1>
    
    <p class="email-text" style="text-align: center; font-size: 16px; color: #6b7280; margin: 0 0 32px 0;">
        Pesanan dari <strong style="color: #111827;">{{ $order->user->name ?? 'Unknown' }}</strong> telah berhasil dibayar.
    </p>

    {{-- Info Box --}}
    @include('emails.components.info-box', [
        'type' => 'success',
        'title' => 'Payment Confirmed',
        'content' => 'Pembayaran telah dikonfirmasi dan akses sudah diberikan ke customer.'
    ])

    {{-- Order Amount Box --}}
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 16px; padding: 32px; margin: 24px 0; text-align: center;">
        <p style="font-size: 14px; color: rgba(255,255,255,0.8); margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 1px;">
            Total Pembayaran
        </p>
        <p style="font-size: 36px; font-weight: 700; color: #ffffff; margin: 0;">
            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
        </p>
    </div>

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 32px 0;"></div>

    {{-- Order Details --}}
    <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0 0 20px 0;">
        📋 Detail Pesanan
    </h2>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f9fafb; border-radius: 12px; overflow: hidden; margin: 0 0 24px 0;">
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Invoice Number</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $order->invoice_number ?? 'N/A' }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Customer</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $order->user->name ?? 'N/A' }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Email</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $order->user->email ?? 'N/A' }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Metode Pembayaran</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ ucfirst($order->payment_method ?? 'N/A') }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px;">
                <span style="font-size: 14px; color: #6b7280;">Waktu Pembayaran</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $order->paid_at ? $order->paid_at->format('d M Y, H:i') : now()->format('d M Y, H:i') }}
                </p>
            </td>
        </tr>
    </table>

    {{-- Items List --}}
    @if($order->items->count() > 0)
        <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0 0 20px 0;">
            📦 Item Dibeli
        </h2>

        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f9fafb; border-radius: 12px; overflow: hidden; margin: 0 0 24px 0;">
            @foreach($order->items as $item)
                <tr>
                    <td style="padding: 16px 20px; {{ !$loop->last ? 'border-bottom: 1px solid #e5e7eb;' : '' }}">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 15px; color: #111827; font-weight: 500;">
                                {{ $item->product->name ?? $item->product_name ?? 'Unknown Product' }}
                            </span>
                            <span style="font-size: 15px; color: #059669; font-weight: 600;">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </span>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 32px 0;"></div>

    {{-- CTA Button --}}
    <div style="text-align: center; margin: 32px 0;">
        <a href="{{ url('/admin/orders/' . $order->id) }}" 
           style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 12px; font-weight: 600; font-size: 16px;">
            Lihat Detail Pesanan
        </a>
    </div>
@endsection
