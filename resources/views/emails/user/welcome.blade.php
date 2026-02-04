@extends('emails.layout')

@section('content')
    {{-- Welcome Icon --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 80px; height: 80px; background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border-radius: 50%; line-height: 80px; font-size: 40px;">
            🎉
        </div>
    </div>

    <h1 class="email-title" style="text-align: center; font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 16px 0;">
        Selamat Datang di Digitalabs!
    </h1>
    
    <p class="email-text" style="text-align: center; font-size: 16px; color: #6b7280; margin: 0 0 32px 0;">
        Halo <strong style="color: #111827;">{{ $user->name }}</strong>, terima kasih telah bergabung dengan Digitalabs!
    </p>

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 32px 0;"></div>

    <p class="email-text" style="font-size: 15px; color: #374151; margin: 0 0 16px 0; line-height: 1.7;">
        Kamu sekarang adalah bagian dari komunitas Digitalabs, tempat dimana kamu bisa belajar dan meningkatkan skill digitalmu bersama praktisi berpengalaman.
    </p>

    <p class="email-text" style="font-size: 15px; color: #374151; margin: 0 0 24px 0; line-height: 1.7;">
        Berikut adalah beberapa hal yang bisa kamu lakukan:
    </p>

    {{-- Feature List --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 32px 0;">
        <tr>
            <td style="padding: 12px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="width: 40px; vertical-align: top;">
                            <div style="width: 32px; height: 32px; background-color: #fff7ed; border-radius: 8px; text-align: center; line-height: 32px; font-size: 16px;">
                                📚
                            </div>
                        </td>
                        <td style="vertical-align: top;">
                            <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 0 0 4px 0;">Akses Kelas Premium</p>
                            <p style="font-size: 14px; color: #6b7280; margin: 0;">Pelajari materi dari mentor berpengalaman</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 12px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="width: 40px; vertical-align: top;">
                            <div style="width: 32px; height: 32px; background-color: #fff7ed; border-radius: 8px; text-align: center; line-height: 32px; font-size: 16px;">
                                💬
                            </div>
                        </td>
                        <td style="vertical-align: top;">
                            <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 0 0 4px 0;">Komunitas Eksklusif</p>
                            <p style="font-size: 14px; color: #6b7280; margin: 0;">Bergabung dengan grup diskusi dan networking</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 12px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="width: 40px; vertical-align: top;">
                            <div style="width: 32px; height: 32px; background-color: #fff7ed; border-radius: 8px; text-align: center; line-height: 32px; font-size: 16px;">
                                🏆
                            </div>
                        </td>
                        <td style="vertical-align: top;">
                            <p style="font-size: 15px; color: #111827; font-weight: 600; margin: 0 0 4px 0;">Sertifikat Resmi</p>
                            <p style="font-size: 14px; color: #6b7280; margin: 0;">Dapatkan sertifikat setelah menyelesaikan kelas</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- CTA Button --}}
    <div style="text-align: center; margin: 32px 0;">
        @include('emails.components.button-primary', [
            'url' => config('app.url') . '/dashboard',
            'text' => 'Mulai Belajar Sekarang'
        ])
    </div>

    @include('emails.components.info-box', [
        'type' => 'default',
        'title' => 'Butuh Bantuan?',
        'content' => 'Jika ada pertanyaan, silakan hubungi kami di support@digitalabs.id atau WhatsApp +62 896-7088-3312'
    ])

    <p class="email-text" style="font-size: 15px; color: #374151; margin: 24px 0 0 0; line-height: 1.7;">
        Salam hangat,<br>
        <strong style="color: #f97316;">Tim Digitalabs</strong>
    </p>
@endsection
