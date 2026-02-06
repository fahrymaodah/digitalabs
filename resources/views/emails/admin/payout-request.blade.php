@extends('emails.layout')

@section('content')
    {{-- Payout Icon --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 80px; height: 80px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 50%; line-height: 80px; font-size: 40px;">
            💰
        </div>
    </div>

    <h1 class="email-title" style="text-align: center; font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 16px 0;">
        Permintaan Pencairan Baru
    </h1>
    
    <p class="email-text" style="text-align: center; font-size: 16px; color: #6b7280; margin: 0 0 32px 0;">
        Affiliate <strong style="color: #111827;">{{ $payout->affiliate->user->name ?? 'Unknown' }}</strong> mengajukan permintaan pencairan komisi.
    </p>

    {{-- Warning Box --}}
    @include('emails.components.info-box', [
        'type' => 'warning',
        'title' => 'Action Required',
        'content' => 'Permintaan ini perlu ditinjau dan diproses oleh admin.'
    ])

    {{-- Payout Amount Box --}}
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 16px; padding: 32px; margin: 24px 0; text-align: center;">
        <p style="font-size: 14px; color: rgba(255,255,255,0.8); margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 1px;">
            Jumlah Permintaan
        </p>
        <p style="font-size: 36px; font-weight: 700; color: #ffffff; margin: 0;">
            Rp {{ number_format($payout->amount, 0, ',', '.') }}
        </p>
    </div>

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 32px 0;"></div>

    {{-- Affiliate Details --}}
    <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0 0 20px 0;">
        👤 Informasi Affiliate
    </h2>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f9fafb; border-radius: 12px; overflow: hidden; margin: 0 0 24px 0;">
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Nama Affiliate</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $payout->affiliate->user->name ?? 'Unknown' }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Email</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $payout->affiliate->user->email ?? 'N/A' }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px;">
                <span style="font-size: 14px; color: #6b7280;">Kode Referral</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $payout->affiliate->referral_code ?? 'N/A' }}
                </p>
            </td>
        </tr>
    </table>

    {{-- Bank Details --}}
    <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0 0 20px 0;">
        🏦 Rekening Tujuan
    </h2>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f9fafb; border-radius: 12px; overflow: hidden; margin: 0 0 24px 0;">
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Nama Bank</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $payout->bank_name ?? $payout->affiliate->bank_name ?? 'N/A' }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Nomor Rekening</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0; font-family: monospace;">
                    {{ $payout->bank_account_number ?? $payout->affiliate->bank_account_number ?? 'N/A' }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px;">
                <span style="font-size: 14px; color: #6b7280;">Atas Nama</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $payout->bank_account_name ?? $payout->affiliate->bank_account_name ?? 'N/A' }}
                </p>
            </td>
        </tr>
    </table>

    {{-- Request Details --}}
    <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0 0 20px 0;">
        📋 Detail Permintaan
    </h2>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f9fafb; border-radius: 12px; overflow: hidden; margin: 0 0 24px 0;">
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">ID Payout</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    #{{ $payout->id }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Tanggal Request</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $payout->created_at->format('d M Y, H:i') }} WIB
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px;">
                <span style="font-size: 14px; color: #6b7280;">Status</span>
                <p style="font-size: 15px; color: #f59e0b; font-weight: 600; margin: 8px 0 0 0;">
                    {{ ucfirst($payout->status) }}
                </p>
            </td>
        </tr>
    </table>

    {{-- CTA Button --}}
    <div style="text-align: center; margin: 32px 0;">
        @include('emails.components.button-primary', [
            'url' => config('app.url') . '/admin/affiliate-payouts/' . $payout->id . '/edit',
            'text' => 'Proses Permintaan'
        ])
    </div>

    @include('emails.components.info-box', [
        'type' => 'default',
        'title' => 'Tips Proses Pencairan',
        'content' => 'Pastikan untuk memverifikasi nomor rekening dan nama penerima sebelum melakukan transfer. Setelah transfer berhasil, update status payout menjadi "completed".'
    ])

    <p class="email-text" style="font-size: 15px; color: #374151; margin: 24px 0 0 0; line-height: 1.7;">
        Email ini dikirim secara otomatis oleh sistem.<br>
        <strong style="color: #111827;">Digitalabs Admin System</strong>
    </p>
@endsection
