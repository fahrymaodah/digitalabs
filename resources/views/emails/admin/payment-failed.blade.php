@extends('emails.layout')

@section('content')
    {{-- Failed Icon --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 80px; height: 80px; background: linear-gradient(135deg, #fee2e2 0%, #fca5a5 100%); border-radius: 50%; line-height: 80px; font-size: 40px;">
            ❌
        </div>
    </div>

    <h1 class="email-title" style="text-align: center; font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 16px 0;">
        Pembayaran {{ $order->status === 'expired' ? 'Kadaluarsa' : 'Gagal' }}
    </h1>
    
    <p class="email-text" style="text-align: center; font-size: 16px; color: #6b7280; margin: 0 0 32px 0;">
        Pesanan dari <strong style="color: #111827;">{{ $order->user->name ?? 'Unknown' }}</strong> tidak berhasil dibayar.
    </p>

    {{-- Warning Box --}}
    @include('emails.components.info-box', [
        'type' => 'error',
        'title' => 'Payment ' . ($order->status === 'expired' ? 'Expired' : 'Failed'),
        'content' => $order->status === 'expired' 
            ? 'Pembayaran tidak dilakukan dalam batas waktu yang ditentukan.'
            : 'Pembayaran ditolak atau dibatalkan oleh customer.'
    ])

    {{-- Order Amount Box --}}
    <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 16px; padding: 32px; margin: 24px 0; text-align: center;">
        <p style="font-size: 14px; color: rgba(255,255,255,0.8); margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 1px;">
            Total Pesanan
        </p>
        <p style="font-size: 36px; font-weight: 700; color: #ffffff; margin: 0;">
            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
        </p>
        <p style="font-size: 14px; color: rgba(255,255,255,0.7); margin: 12px 0 0 0; text-transform: uppercase;">
            {{ strtoupper($order->status) }}
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
                <span style="font-size: 14px; color: #6b7280;">Status</span>
                <p style="font-size: 15px; color: #dc2626; font-weight: 600; margin: 8px 0 0 0;">
                    {{ ucfirst($order->status) }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px;">
                <span style="font-size: 14px; color: #6b7280;">Waktu Kadaluarsa/Gagal</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ now()->format('d M Y, H:i') }}
                </p>
            </td>
        </tr>
    </table>

    {{-- Items List --}}
    @if($order->items->count() > 0)
        <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0 0 20px 0;">
            📦 Item yang Tidak Jadi Dibeli
        </h2>

        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f9fafb; border-radius: 12px; overflow: hidden; margin: 0 0 24px 0;">
            @foreach($order->items as $item)
                <tr>
                    <td style="padding: 16px 20px; {{ !$loop->last ? 'border-bottom: 1px solid #e5e7eb;' : '' }}">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 15px; color: #111827; font-weight: 500;">
                                {{ $item->product->name ?? $item->product_name ?? 'Unknown Product' }}
                            </span>
                            <span style="font-size: 15px; color: #6b7280; font-weight: 600; text-decoration: line-through;">
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
           style="display: inline-block; background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 12px; font-weight: 600; font-size: 16px;">
            Lihat Detail Pesanan
        </a>
    </div>
@endsection
