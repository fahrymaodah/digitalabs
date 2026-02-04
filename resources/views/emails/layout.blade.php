<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $subject ?? 'Digitalabs' }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        /* Reset styles */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        
        /* Base styles */
        body {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #f3f4f6;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #374151;
        }
        
        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        
        /* Header */
        .email-header {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            padding: 32px 40px;
            text-align: center;
        }
        
        .email-logo {
            font-size: 28px;
            font-weight: bold;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: -0.5px;
        }
        
        .email-logo span {
            color: #fef3c7;
        }
        
        /* Content */
        .email-body {
            padding: 40px;
        }
        
        .email-title {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 16px 0;
            line-height: 1.3;
        }
        
        .email-subtitle {
            font-size: 16px;
            color: #6b7280;
            margin: 0 0 24px 0;
        }
        
        .email-text {
            font-size: 15px;
            color: #374151;
            margin: 0 0 16px 0;
            line-height: 1.7;
        }
        
        /* Buttons */
        .btn-primary {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            margin: 8px 0;
        }
        
        .btn-secondary {
            display: inline-block;
            padding: 12px 28px;
            background-color: #f3f4f6;
            color: #374151 !important;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            border: 1px solid #e5e7eb;
        }
        
        /* Info Box */
        .info-box {
            background-color: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 12px;
            padding: 20px 24px;
            margin: 24px 0;
        }
        
        .info-box-success {
            background-color: #ecfdf5;
            border-color: #a7f3d0;
        }
        
        .info-box-warning {
            background-color: #fffbeb;
            border-color: #fde68a;
        }
        
        .info-box-title {
            font-size: 14px;
            font-weight: 600;
            color: #9a3412;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-box-success .info-box-title {
            color: #065f46;
        }
        
        .info-box-content {
            font-size: 15px;
            color: #374151;
            margin: 0;
        }
        
        /* Order Details Table */
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .order-table th {
            background-color: #f9fafb;
            padding: 12px 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .order-table td {
            padding: 16px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
            color: #374151;
        }
        
        .order-table .total-row td {
            font-weight: 700;
            font-size: 16px;
            color: #111827;
            border-top: 2px solid #e5e7eb;
            border-bottom: none;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        
        .stat-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 20px 10px;
            background-color: #f9fafb;
            border-radius: 10px;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #f97316;
            margin: 0;
        }
        
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            margin: 4px 0 0 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Divider */
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 32px 0;
        }
        
        /* Footer */
        .email-footer {
            background-color: #1f2937;
            padding: 32px 40px;
            text-align: center;
        }
        
        .footer-logo {
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
            text-decoration: none;
            margin-bottom: 16px;
            display: inline-block;
        }
        
        .footer-logo span {
            color: #f97316;
        }
        
        .footer-links {
            margin: 16px 0;
        }
        
        .footer-links a {
            color: #9ca3af;
            text-decoration: none;
            font-size: 13px;
            margin: 0 12px;
        }
        
        .footer-links a:hover {
            color: #ffffff;
        }
        
        .footer-social {
            margin: 20px 0;
        }
        
        .footer-social a {
            display: inline-block;
            width: 36px;
            height: 36px;
            background-color: #374151;
            border-radius: 50%;
            margin: 0 4px;
            line-height: 36px;
            text-align: center;
        }
        
        .footer-text {
            font-size: 12px;
            color: #6b7280;
            margin: 16px 0 0 0;
        }
        
        .footer-contact {
            font-size: 13px;
            color: #9ca3af;
            margin: 8px 0;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 24px 20px !important;
            }
            .email-header {
                padding: 24px 20px !important;
            }
            .email-footer {
                padding: 24px 20px !important;
            }
            .email-title {
                font-size: 20px !important;
            }
            .btn-primary {
                display: block !important;
                width: 100% !important;
            }
            .stat-item {
                display: block !important;
                width: 100% !important;
                margin-bottom: 8px;
            }
        }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f3f4f6;">
        <tr>
            <td style="padding: 24px 16px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container" style="margin: 0 auto; max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    
                    {{-- Header --}}
                    <tr>
                        <td class="email-header">
                            <a href="{{ config('app.url') }}" style="display: inline-block;">
                                <img src="{{ config('app.url') }}/images/digitalabs-banner-white.png" alt="Digitalabs" style="height: 48px; width: auto;" />
                            </a>
                        </td>
                    </tr>
                    
                    {{-- Body Content --}}
                    <tr>
                        <td class="email-body">
                            @yield('content')
                        </td>
                    </tr>
                    
                    {{-- Footer --}}
                    <tr>
                        <td class="email-footer">
                            <a href="{{ config('app.url') }}" style="display: inline-block; margin-bottom: 16px;">
                                <img src="{{ config('app.url') }}/images/digitalabs-banner-light.png" alt="Digitalabs" style="height: 40px; width: auto;" />
                            </a>
                            
                            <div class="footer-links">
                                <a href="{{ config('app.url') }}">Home</a>
                                <a href="{{ config('app.url') }}/courses">Kelas</a>
                                <a href="{{ config('app.url') }}/blog">Blog</a>
                                <a href="{{ config('app.url') }}/contact">Kontak</a>
                            </div>
                            
                            <p class="footer-contact">
                                📧 support@digitalabs.id &nbsp;•&nbsp; 📱 +62 896-7088-3312
                            </p>
                            
                            <div class="divider" style="background-color: #374151; margin: 20px 0;"></div>
                            
                            <p class="footer-text">
                                © {{ date('Y') }} Digitalabs. All rights reserved.<br>
                                Jl. Darul Hikmah No.10, Bajur, Kec. Labuapi, Kab. Lombok Barat,<br>
                                Prov. Nusa Tenggara Barat 83361
                            </p>
                            
                            <p class="footer-text" style="margin-top: 12px;">
                                <a href="{{ config('app.url') }}/privacy" style="color: #6b7280; text-decoration: underline;">Privacy Policy</a>
                                &nbsp;•&nbsp;
                                <a href="{{ config('app.url') }}/terms" style="color: #6b7280; text-decoration: underline;">Terms of Service</a>
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
