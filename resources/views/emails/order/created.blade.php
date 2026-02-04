@extends('emails.layout')

@section('content')
    {{-- Order Icon --}}
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="display: inline-block; width: 80px; height: 80px; background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border-radius: 50%; line-height: 80px; font-size: 40px;">
            🛒
        </div>
    </div>

    <h1 class="email-title" style="text-align: center; font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 16px 0;">
        Pesanan Berhasil Dibuat!
    </h1>
    
    <p class="email-text" style="text-align: center; font-size: 16px; color: #6b7280; margin: 0 0 32px 0;">
        Halo <strong style="color: #111827;">{{ $order->user->name }}</strong>, pesananmu telah berhasil dibuat.
    </p>

    {{-- Order Info Box --}}
    @include('emails.components.info-box', [
        'type' => 'default',
        'title' => 'Nomor Pesanan',
        'content' => $order->order_number
    ])

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 32px 0;"></div>

    <h2 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0 0 16px 0;">
        Detail Pesanan
    </h2>

    {{-- Order Table --}}
    @include('emails.components.order-table', [
        'items' => [
            ['name' => $order->items->first()->course->title, 'quantity' => 1, 'price' => $order->original_price]
        ],
        'subtotal' => $order->original_price,
        'discount' => $order->discount_amount ?? 0,
        'total' => $order->total_price
    ])

    @if($order->coupon)
    <p style="font-size: 14px; color: #059669; margin: 0 0 16px 0;">
        ✅ Kode kupon <strong>{{ $order->coupon->code }}</strong> berhasil digunakan
    </p>
    @endif

    {{-- Payment Info --}}
    <div style="background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 20px 24px; margin: 24px 0;">
        <p style="font-size: 14px; font-weight: 600; color: #92400e; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px;">
            ⏳ Menunggu Pembayaran
        </p>
        <p style="font-size: 15px; color: #374151; margin: 0 0 16px 0; line-height: 1.6;">
            Silakan selesaikan pembayaranmu dalam waktu <strong>24 jam</strong> untuk mengakses kelas.
        </p>
        
        @if($order->payment_url ?? $order->duitku_payment_url ?? null)
        <a href="{{ $order->payment_url ?? $order->duitku_payment_url }}" style="display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px;">
            Bayar Sekarang
        </a>
        @endif
    </div>

    {{-- Payment Methods --}}
    <h3 style="font-size: 16px; font-weight: 600; color: #111827; margin: 24px 0 12px 0;">
        Metode Pembayaran
    </h3>
    <p style="font-size: 14px; color: #6b7280; margin: 0 0 16px 0; line-height: 1.7;">
        Kamu bisa membayar melalui Virtual Account, E-Wallet (OVO, GoPay, DANA), atau transfer bank.
    </p>

    <div class="divider" style="height: 1px; background-color: #e5e7eb; margin: 32px 0;"></div>

    {{-- CTA Button --}}
    <div style="text-align: center; margin: 32px 0;">
        @include('emails.components.button-primary', [
            'url' => config('app.url') . '/dashboard/orders',
            'text' => 'Lihat Detail Pesanan'
        ])
    </div>

    @include('emails.components.info-box', [
        'type' => 'default',
        'title' => 'Butuh Bantuan?',
        'content' => 'Hubungi kami jika ada kendala pembayaran di support@digitalabs.id atau WhatsApp +62 896-7088-3312'
    ])

    <p class="email-text" style="font-size: 15px; color: #374151; margin: 24px 0 0 0; line-height: 1.7;">
        Salam hangat,<br>
        <strong style="color: #f97316;">Tim Digitalabs</strong>
    </p>
@endsection
