{{-- 
    Stats Grid Component
    Usage: @include('emails.components.stats-grid', [
        'stats' => [
            ['value' => '10', 'label' => 'Total'],
            ['value' => 'Rp 1jt', 'label' => 'Earnings'],
        ]
    ])
--}}

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 20px 0;">
    <tr>
        @foreach($stats as $stat)
        <td style="text-align: center; padding: 20px 10px; background-color: #f9fafb; border-radius: 10px; width: {{ 100 / count($stats) }}%;">
            <p style="font-size: 24px; font-weight: 700; color: #f97316; margin: 0;">
                {{ $stat['value'] }}
            </p>
            <p style="font-size: 12px; color: #6b7280; margin: 4px 0 0 0; text-transform: uppercase; letter-spacing: 0.5px;">
                {{ $stat['label'] }}
            </p>
        </td>
        @if(!$loop->last)
        <td style="width: 8px;"></td>
        @endif
        @endforeach
    </tr>
</table>
