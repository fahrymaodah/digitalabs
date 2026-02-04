@extends('emails.layout')

@section('content')
    {{-- Payout Icon --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 80px; height: 80px; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-radius: 50%; line-height: 80px; font-size: 40px;">
            🏦
        </div>
    </div>

    <h1 class="email-title" style="text-align: center; font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 16px 0;">
        Pencairan Berhasil!
    </h1>
    
    <p class="email-text" style="text-align: center; font-size: 16px; color: #6b7280; margin: 0 0 32px 0;">
        Halo <strong style="color: #111827;">{{ $payout->affiliate->user->name }}</strong>, pencairan komisimu telah diproses.
    </p>

    {{-- Success Box --}}
    @include('emails.components.info-box', [
        'type' => 'success',
        'title' => 'Payout Completed',
        'content' => 'Dana telah ditransfer ke rekening yang terdaftar.'
    ])

    {{-- Payout Amount Box --}}
    <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); border-radius: 16px; padding: 32px; margin: 24px 0; text-align: center;">
        <p style="font-size: 14px; color: rgba(255,255,255,0.8); margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 1px;">
            Jumlah Dicairkan
        </p>
        <p style="font-size: 36px; font-weight: 700; color: #ffffff; margin: 0;">
            Rp {{ number_format($payout->amount, 0, ',', '.') }}
        </p>
    </div>

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 32px 0;"></div>

    {{-- Payout Details --}}
    <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0 0 20px 0;">
        📋 Detail Pencairan
    </h2>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f9fafb; border-radius: 12px; overflow: hidden; margin: 0 0 24px 0;">
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">ID Pencairan</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    #{{ $payout->id }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Bank Tujuan</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $payout->affiliate->bank_name ?? 'Bank' }} - {{ $payout->affiliate->bank_account_number ?? '****' }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Atas Nama</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $payout->affiliate->bank_account_name ?? $payout->affiliate->user->name }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px;">
                <span style="font-size: 14px; color: #6b7280;">Tanggal Proses</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $payout->processed_at ? $payout->processed_at->format('d M Y, H:i') : now()->format('d M Y, H:i') }} WIB
                </p>
            </td>
        </tr>
    </table>

    {{-- Note --}}
    <div style="background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 16px 20px; margin: 0 0 24px 0;">
        <p style="font-size: 14px; color: #92400e; margin: 0; line-height: 1.6;">
            ⏱️ <strong>Note:</strong> Dana biasanya masuk dalam 1-3 hari kerja tergantung bank tujuan.
        </p>
    </div>

    {{-- CTA Button --}}
    <div style="text-align: center; margin: 32px 0;">
        @include('emails.components.button-primary', [
            'url' => config('app.url') . '/dashboard/affiliate',
            'text' => 'Lihat Riwayat Pencairan'
        ])
    </div>

    @include('emails.components.info-box', [
        'type' => 'default',
        'title' => 'Terus Promosikan!',
        'content' => 'Semakin banyak referral yang membeli, semakin banyak komisi yang kamu dapatkan. Bagikan link referralmu sekarang!'
    ])

    <p class="email-text" style="font-size: 15px; color: #374151; margin: 24px 0 0 0; line-height: 1.7;">
        Terima kasih telah menjadi partner kami! 🙏<br>
        <strong style="color: #f97316;">Tim Digitalabs</strong>
    </p>
@endsection
