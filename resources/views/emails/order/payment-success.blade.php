@extends('emails.layout')

@section('content')
    {{-- Success Icon --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 80px; height: 80px; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-radius: 50%; line-height: 80px; font-size: 40px;">
            ✅
        </div>
    </div>

    <h1 class="email-title" style="text-align: center; font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 16px 0;">
        Pembayaran Berhasil!
    </h1>
    
    <p class="email-text" style="text-align: center; font-size: 16px; color: #6b7280; margin: 0 0 32px 0;">
        Selamat <strong style="color: #111827;">{{ $order->user->name }}</strong>! Kamu sekarang bisa mengakses kelas.
    </p>

    {{-- Success Box --}}
    @include('emails.components.info-box', [
        'type' => 'success',
        'title' => 'Transaksi Selesai',
        'content' => 'Pembayaran untuk pesanan ' . $order->order_number . ' telah berhasil dikonfirmasi.'
    ])

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 32px 0;"></div>

    {{-- Course Card --}}
    <div style="background-color: #f9fafb; border-radius: 12px; padding: 24px; margin: 24px 0;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td>
                    <p style="font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px 0;">
                        Kelas yang Dibeli
                    </p>
                    <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin: 0 0 8px 0;">
                        {{ $order->course->title }}
                    </h3>
                    <p style="font-size: 14px; color: #6b7280; margin: 0 0 16px 0;">
                        oleh {{ $order->course->instructor ?? 'Digitalabs' }}
                    </p>
                    
                    {{-- Course Stats --}}
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td style="padding-right: 16px;">
                                <span style="font-size: 13px; color: #6b7280;">
                                    📚 {{ $order->course->lessons_count ?? 0 }} Materi
                                </span>
                            </td>
                            <td style="padding-right: 16px;">
                                <span style="font-size: 13px; color: #6b7280;">
                                    ⏱️ {{ $order->course->duration ?? 'Lifetime' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    {{-- Payment Summary --}}
    <h3 style="font-size: 16px; font-weight: 600; color: #111827; margin: 24px 0 16px 0;">
        Ringkasan Pembayaran
    </h3>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
        <tr>
            <td style="padding: 8px 0; font-size: 14px; color: #6b7280;">Harga Kelas</td>
            <td style="padding: 8px 0; font-size: 14px; color: #374151; text-align: right;">
                Rp {{ number_format($order->original_price, 0, ',', '.') }}
            </td>
        </tr>
        @if($order->discount_amount > 0)
        <tr>
            <td style="padding: 8px 0; font-size: 14px; color: #059669;">Diskon</td>
            <td style="padding: 8px 0; font-size: 14px; color: #059669; text-align: right;">
                - Rp {{ number_format($order->discount_amount, 0, ',', '.') }}
            </td>
        </tr>
        @endif
        <tr>
            <td style="padding: 12px 0; font-size: 16px; font-weight: 700; color: #111827; border-top: 2px solid #e5e7eb;">
                Total Dibayar
            </td>
            <td style="padding: 12px 0; font-size: 16px; font-weight: 700; color: #f97316; text-align: right; border-top: 2px solid #e5e7eb;">
                Rp {{ number_format($order->total_price, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    {{-- CTA Button --}}
    <div style="text-align: center; margin: 32px 0;">
        @include('emails.components.button-primary', [
            'url' => config('app.url') . '/dashboard/learn/' . $order->course->slug,
            'text' => '🚀 Mulai Belajar Sekarang'
        ])
    </div>

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 32px 0;"></div>

    {{-- Tips --}}
    <h3 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 16px 0;">
        💡 Tips untuk Memaksimalkan Belajarmu
    </h3>
    
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="padding: 8px 0; font-size: 14px; color: #374151; line-height: 1.6;">
                ✓ Tonton video dengan urutan yang benar
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 0; font-size: 14px; color: #374151; line-height: 1.6;">
                ✓ Praktekkan langsung setiap materi yang dipelajari
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 0; font-size: 14px; color: #374151; line-height: 1.6;">
                ✓ Gabung grup diskusi untuk bertanya dan sharing
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 0; font-size: 14px; color: #374151; line-height: 1.6;">
                ✓ Selesaikan kelas untuk mendapatkan sertifikat
            </td>
        </tr>
    </table>

    <p class="email-text" style="font-size: 15px; color: #374151; margin: 32px 0 0 0; line-height: 1.7;">
        Selamat belajar! 🎓<br>
        <strong style="color: #f97316;">Tim Digitalabs</strong>
    </p>
@endsection
