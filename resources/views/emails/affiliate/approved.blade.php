@extends('emails.layout')

@section('content')
    {{-- Success Icon --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 80px; height: 80px; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-radius: 50%; line-height: 80px; font-size: 40px;">
            🎊
        </div>
    </div>

    <h1 class="email-title" style="text-align: center; font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 16px 0;">
        Pendaftaran Affiliate Disetujui!
    </h1>
    
    <p class="email-text" style="text-align: center; font-size: 16px; color: #6b7280; margin: 0 0 32px 0;">
        Selamat <strong style="color: #111827;">{{ $affiliate->user->name }}</strong>! Kamu sekarang resmi menjadi Affiliate DigitaLabs.
    </p>

    {{-- Success Box --}}
    @include('emails.components.info-box', [
        'type' => 'success',
        'title' => 'Status: Approved',
        'content' => 'Akun affiliatemu sudah aktif dan siap digunakan untuk mulai menghasilkan komisi.'
    ])

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 32px 0;"></div>

    {{-- Affiliate Info --}}
    <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0 0 20px 0;">
        🔗 Link Referral Kamu
    </h2>

    <div style="background-color: #f9fafb; border: 2px dashed #d1d5db; border-radius: 12px; padding: 20px 24px; margin: 0 0 24px 0;">
        <p style="font-size: 14px; color: #6b7280; margin: 0 0 8px 0;">Kode Referral:</p>
        <p style="font-size: 20px; font-weight: 700; color: #f97316; margin: 0 0 16px 0; letter-spacing: 2px;">
            {{ $affiliate->referral_code }}
        </p>
        <p style="font-size: 13px; color: #6b7280; margin: 0; word-break: break-all;">
            Link: {{ config('app.url') }}?ref={{ $affiliate->referral_code }}
        </p>
    </div>

    {{-- Commission Info --}}
    @include('emails.components.stats-grid', [
        'stats' => [
            ['value' => $affiliate->commission_rate . '%', 'label' => 'Komisi'],
            ['value' => '30 Hari', 'label' => 'Cookie'],
            ['value' => 'Rp 0', 'label' => 'Pending'],
        ]
    ])

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 32px 0;"></div>

    {{-- How It Works --}}
    <h3 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 16px 0;">
        📋 Cara Kerja Affiliate Program
    </h3>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
        <tr>
            <td style="padding: 12px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="width: 36px; vertical-align: top;">
                            <div style="width: 28px; height: 28px; background-color: #f97316; color: #fff; border-radius: 50%; text-align: center; line-height: 28px; font-size: 14px; font-weight: 600;">
                                1
                            </div>
                        </td>
                        <td style="vertical-align: top;">
                            <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 0 0 4px 0;">Bagikan Link</p>
                            <p style="font-size: 14px; color: #6b7280; margin: 0;">Share link referralmu ke teman, follower, atau audiens</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 12px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="width: 36px; vertical-align: top;">
                            <div style="width: 28px; height: 28px; background-color: #f97316; color: #fff; border-radius: 50%; text-align: center; line-height: 28px; font-size: 14px; font-weight: 600;">
                                2
                            </div>
                        </td>
                        <td style="vertical-align: top;">
                            <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 0 0 4px 0;">Mereka Beli Kelas</p>
                            <p style="font-size: 14px; color: #6b7280; margin: 0;">Setiap orang yang membeli melalui link-mu akan di-track</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 12px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="width: 36px; vertical-align: top;">
                            <div style="width: 28px; height: 28px; background-color: #f97316; color: #fff; border-radius: 50%; text-align: center; line-height: 28px; font-size: 14px; font-weight: 600;">
                                3
                            </div>
                        </td>
                        <td style="vertical-align: top;">
                            <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 0 0 4px 0;">Dapat Komisi</p>
                            <p style="font-size: 14px; color: #6b7280; margin: 0;">Kamu mendapat {{ $affiliate->commission_rate }}% dari setiap transaksi berhasil</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- CTA Button --}}
    <div style="text-align: center; margin: 32px 0;">
        @include('emails.components.button-primary', [
            'url' => config('app.url') . '/dashboard/affiliate',
            'text' => 'Buka Dashboard Affiliate'
        ])
    </div>

    @include('emails.components.info-box', [
        'type' => 'default',
        'title' => 'Tips Sukses',
        'content' => 'Affiliate dengan konversi tertinggi biasanya membuat review jujur tentang kelas dan membagikannya ke audiens yang tepat.'
    ])

    <p class="email-text" style="font-size: 15px; color: #374151; margin: 24px 0 0 0; line-height: 1.7;">
        Sukses selalu! 💰<br>
        <strong style="color: #f97316;">Tim Digitalabs</strong>
    </p>
@endsection
