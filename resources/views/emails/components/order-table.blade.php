{{-- 
    Order Table Component
    Usage: @include('emails.components.order-table', [
        'items' => [
            ['name' => 'Product Name', 'quantity' => 1, 'price' => 100000],
        ],
        'subtotal' => 100000,
        'discount' => 0,
        'total' => 100000
    ])
--}}

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 20px 0; border-collapse: collapse;">
    {{-- Header --}}
    <tr>
        <th style="background-color: #f9fafb; padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb;">
            Produk
        </th>
        <th style="background-color: #f9fafb; padding: 12px 16px; text-align: center; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb;">
            Qty
        </th>
        <th style="background-color: #f9fafb; padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb;">
            Harga
        </th>
    </tr>
    
    {{-- Items --}}
    @foreach($items as $item)
    <tr>
        <td style="padding: 16px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #374151;">
            {{ $item['name'] }}
        </td>
        <td style="padding: 16px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #374151; text-align: center;">
            {{ $item['quantity'] ?? 1 }}
        </td>
        <td style="padding: 16px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #374151; text-align: right;">
            Rp {{ number_format($item['price'], 0, ',', '.') }}
        </td>
    </tr>
    @endforeach
    
    {{-- Subtotal --}}
    @if(isset($subtotal) && isset($discount) && $discount > 0)
    <tr>
        <td colspan="2" style="padding: 12px 16px; font-size: 14px; color: #6b7280; text-align: right;">
            Subtotal
        </td>
        <td style="padding: 12px 16px; font-size: 14px; color: #374151; text-align: right;">
            Rp {{ number_format($subtotal, 0, ',', '.') }}
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 12px 16px; font-size: 14px; color: #059669; text-align: right;">
            Diskon
        </td>
        <td style="padding: 12px 16px; font-size: 14px; color: #059669; text-align: right;">
            - Rp {{ number_format($discount, 0, ',', '.') }}
        </td>
    </tr>
    @endif
    
    {{-- Total --}}
    <tr>
        <td colspan="2" style="padding: 16px; font-weight: 700; font-size: 16px; color: #111827; border-top: 2px solid #e5e7eb; text-align: right;">
            Total
        </td>
        <td style="padding: 16px; font-weight: 700; font-size: 16px; color: #f97316; border-top: 2px solid #e5e7eb; text-align: right;">
            Rp {{ number_format($total, 0, ',', '.') }}
        </td>
    </tr>
</table>
