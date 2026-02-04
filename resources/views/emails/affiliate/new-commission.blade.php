@extends('emails.layout')

@section('content')
    {{-- Commission Icon --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 80px; height: 80px; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-radius: 50%; line-height: 80px; font-size: 40px;">
            💰
        </div>
    </div>

    <h1 class="email-title" style="text-align: center; font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 16px 0;">
        Komisi Baru Masuk!
    </h1>
    
    <p class="email-text" style="text-align: center; font-size: 16px; color: #6b7280; margin: 0 0 32px 0;">
        Selamat <strong style="color: #111827;">{{ $commission->affiliate->user->name }}</strong>! Ada referral yang baru saja membeli kelas.
    </p>

    {{-- Commission Amount Box --}}
    <div style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); border-radius: 16px; padding: 32px; margin: 0 0 32px 0; text-align: center;">
        <p style="font-size: 14px; color: rgba(255,255,255,0.8); margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 1px;">
            Komisi Diterima
        </p>
        <p style="font-size: 36px; font-weight: 700; color: #ffffff; margin: 0;">
            Rp {{ number_format($commission->amount, 0, ',', '.') }}
        </p>
    </div>

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 32px 0;"></div>

    {{-- Transaction Details --}}
    <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0 0 20px 0;">
        📋 Detail Transaksi
    </h2>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f9fafb; border-radius: 12px; overflow: hidden; margin: 0 0 24px 0;">
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Kelas Dibeli</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $commission->order->items->first()->course->title }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Nilai Transaksi</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    Rp {{ number_format($commission->order->total_price, 0, ',', '.') }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Rate Komisi</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $commission->affiliate->commission_rate }}%
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px;">
                <span style="font-size: 14px; color: #6b7280;">Tanggal</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $commission->created_at->format('d M Y, H:i') }} WIB
                </p>
            </td>
        </tr>
    </table>

    {{-- Stats Summary --}}
    @include('emails.components.stats-grid', [
        'stats' => [
            ['value' => $totalCommissions ?? 0, 'label' => 'Total Referral'],
            ['value' => 'Rp ' . number_format($pendingBalance ?? 0, 0, ',', '.'), 'label' => 'Pending'],
            ['value' => 'Rp ' . number_format($totalEarnings ?? 0, 0, ',', '.'), 'label' => 'Total Earned'],
        ]
    ])

    {{-- CTA Button --}}
    <div style="text-align: center; margin: 32px 0;">
        @include('emails.components.button-primary', [
            'url' => config('app.url') . '/dashboard/affiliate',
            'text' => 'Lihat Dashboard'
        ])
    </div>

    @include('emails.components.info-box', [
        'type' => 'default',
        'title' => 'Info Pencairan',
        'content' => 'Komisi akan masuk ke pending balance dan bisa dicairkan setelah melewati periode holding (14 hari dari transaksi).'
    ])

    <p class="email-text" style="font-size: 15px; color: #374151; margin: 24px 0 0 0; line-height: 1.7;">
        Terus semangat! 🚀<br>
        <strong style="color: #f97316;">Tim Digitalabs</strong>
    </p>
@endsection
