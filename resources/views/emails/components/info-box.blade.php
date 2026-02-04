{{-- 
    Info Box Component
    Usage: @include('emails.components.info-box', [
        'type' => 'default|success|warning',
        'title' => 'Title Text',
        'content' => 'Content text here'
    ])
--}}

@php
    $boxClass = 'info-box';
    $bgColor = '#fff7ed';
    $borderColor = '#fed7aa';
    $titleColor = '#9a3412';
    
    if (isset($type)) {
        switch($type) {
            case 'success':
                $boxClass .= ' info-box-success';
                $bgColor = '#ecfdf5';
                $borderColor = '#a7f3d0';
                $titleColor = '#065f46';
                break;
            case 'warning':
                $boxClass .= ' info-box-warning';
                $bgColor = '#fffbeb';
                $borderColor = '#fde68a';
                $titleColor = '#92400e';
                break;
        }
    }
@endphp

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0;">
    <tr>
        <td style="background-color: {{ $bgColor }}; border: 1px solid {{ $borderColor }}; border-radius: 12px; padding: 20px 24px;">
            @if(isset($title))
                <p style="font-size: 14px; font-weight: 600; color: {{ $titleColor }}; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px;">
                    {{ $title }}
                </p>
            @endif
            <p style="font-size: 15px; color: #374151; margin: 0; line-height: 1.6;">
                {{ $content ?? $slot ?? '' }}
            </p>
        </td>
    </tr>
</table>
