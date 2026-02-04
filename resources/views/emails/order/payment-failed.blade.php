@extends('emails.layout')

@section('content')
    {{-- Failed Icon --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 80px; height: 80px; background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-radius: 50%; line-height: 80px; font-size: 40px;">
            ❌
        </div>
    </div>

    <h1 class="email-title" style="text-align: center; font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 16px 0;">
        Pembayaran Gagal
    </h1>
    
    <p class="email-text" style="text-align: center; font-size: 16px; color: #6b7280; margin: 0 0 32px 0;">
        Halo <strong style="color: #111827;">{{ $order->user->name }}</strong>, sayangnya pembayaranmu tidak berhasil diproses.
    </p>

    {{-- Error Box --}}
    <div style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 20px 24px; margin: 24px 0;">
        <p style="font-size: 14px; font-weight: 600; color: #991b1b; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px;">
            Pembayaran Gagal
        </p>
        <p style="font-size: 15px; color: #374151; margin: 0; line-height: 1.6;">
            Pesanan <strong>{{ $order->order_number }}</strong> tidak dapat diproses. 
            @if(isset($reason))
            Alasan: {{ $reason }}
            @else
            Silakan coba lagi dengan metode pembayaran lain.
            @endif
        </p>
    </div>

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 32px 0;"></div>

    <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0 0 16px 0;">
        Detail Pesanan
    </h2>

    {{-- Order Summary --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f9fafb; border-radius: 12px; padding: 20px; margin: 0 0 24px 0;">
        <tr>
            <td style="padding: 20px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding: 8px 0;">
                            <span style="font-size: 14px; color: #6b7280;">Kelas:</span>
                            <span style="font-size: 14px; color: #111827; font-weight: 600; float: right;">{{ $order->course->title }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0;">
                            <span style="font-size: 14px; color: #6b7280;">Total:</span>
                            <span style="font-size: 14px; color: #111827; font-weight: 600; float: right;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <h3 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 16px 0;">
        🔄 Apa yang bisa kamu lakukan?
    </h3>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="padding: 8px 0; font-size: 14px; color: #374151; line-height: 1.6;">
                1. Pastikan saldo atau limit kartu mencukupi
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 0; font-size: 14px; color: #374151; line-height: 1.6;">
                2. Coba gunakan metode pembayaran lain
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 0; font-size: 14px; color: #374151; line-height: 1.6;">
                3. Hubungi bank penerbit jika ada masalah
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 0; font-size: 14px; color: #374151; line-height: 1.6;">
                4. Buat pesanan baru dan coba pembayaran lagi
            </td>
        </tr>
    </table>

    {{-- CTA Buttons --}}
    <div style="text-align: center; margin: 32px 0;">
        @include('emails.components.button-primary', [
            'url' => config('app.url') . '/courses/' . $order->course->slug,
            'text' => 'Coba Pesan Lagi'
        ])
    </div>

    @include('emails.components.info-box', [
        'type' => 'default',
        'title' => 'Butuh Bantuan?',
        'content' => 'Jika masalah terus berlanjut, hubungi kami di support@digitalabs.id atau WhatsApp +62 896-7088-3312'
    ])

    <p class="email-text" style="font-size: 15px; color: #374151; margin: 24px 0 0 0; line-height: 1.7;">
        Salam hangat,<br>
        <strong style="color: #f97316;">Tim Digitalabs</strong>
    </p>
@endsection
