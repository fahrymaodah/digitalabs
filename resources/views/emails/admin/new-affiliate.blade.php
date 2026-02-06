@extends('emails.layout')

@section('content')
    {{-- Affiliate Icon --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 80px; height: 80px; background: linear-gradient(135deg, #ddd6fe 0%, #a78bfa 100%); border-radius: 50%; line-height: 80px; font-size: 40px;">
            🤝
        </div>
    </div>

    <h1 class="email-title" style="text-align: center; font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 16px 0;">
        Pendaftaran Affiliate Baru
    </h1>
    
    <p class="email-text" style="text-align: center; font-size: 16px; color: #6b7280; margin: 0 0 32px 0;">
        <strong style="color: #111827;">{{ $affiliate->user->name ?? 'Unknown' }}</strong> telah mendaftar sebagai affiliate.
    </p>

    {{-- Info Box --}}
    @include('emails.components.info-box', [
        'type' => 'info',
        'title' => 'Review Required',
        'content' => 'Pendaftaran affiliate ini perlu ditinjau dan disetujui oleh admin.'
    ])

    {{-- Referral Code Box --}}
    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 16px; padding: 32px; margin: 24px 0; text-align: center;">
        <p style="font-size: 14px; color: rgba(255,255,255,0.8); margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 1px;">
            Kode Referral
        </p>
        <p style="font-size: 32px; font-weight: 700; color: #ffffff; margin: 0; letter-spacing: 3px;">
            {{ $affiliate->referral_code ?? 'N/A' }}
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
                <span style="font-size: 14px; color: #6b7280;">Nama</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $affiliate->user->name ?? 'N/A' }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Email</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $affiliate->user->email ?? 'N/A' }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Status</span>
                <p style="font-size: 15px; font-weight: 600; margin: 8px 0 0 0;
                    color: {{ $affiliate->status === 'pending' ? '#f59e0b' : ($affiliate->status === 'approved' ? '#10b981' : '#ef4444') }};">
                    {{ ucfirst($affiliate->status) }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px;">
                <span style="font-size: 14px; color: #6b7280;">Tanggal Daftar</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $affiliate->created_at->format('d M Y, H:i') }}
                </p>
            </td>
        </tr>
    </table>

    {{-- Bank Information --}}
    <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0 0 20px 0;">
        🏦 Informasi Bank
    </h2>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f9fafb; border-radius: 12px; overflow: hidden; margin: 0 0 24px 0;">
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Nama Bank</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $affiliate->bank_name ?? 'Belum diisi' }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb;">
                <span style="font-size: 14px; color: #6b7280;">Nomor Rekening</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $affiliate->bank_account_number ?? 'Belum diisi' }}
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 20px;">
                <span style="font-size: 14px; color: #6b7280;">Atas Nama</span>
                <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 8px 0 0 0;">
                    {{ $affiliate->bank_account_name ?? 'Belum diisi' }}
                </p>
            </td>
        </tr>
    </table>

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 32px 0;"></div>

    {{-- CTA Button --}}
    <div style="text-align: center; margin: 32px 0;">
        <a href="{{ url('/admin/affiliates/' . $affiliate->id . '/edit') }}" 
           style="display: inline-block; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 12px; font-weight: 600; font-size: 16px;">
            Review Affiliate
        </a>
    </div>
@endsection
