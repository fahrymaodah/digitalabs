<x-layouts.public title="Program Affiliate - DigitaLabs">
    {{-- Hero Section --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-orange-500 via-orange-600 to-orange-700 py-16 lg:py-24">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-orange-300/20 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center px-4 py-2 bg-white/20 text-white rounded-full text-sm font-medium mb-6">
                💰 Program Affiliate DigitaLabs
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                Dapatkan Penghasilan Tambahan <br class="hidden lg:block">
                dengan Menjadi <span class="text-yellow-300">Affiliate</span>
            </h1>
            <p class="text-lg text-white/90 mb-8 max-w-2xl mx-auto">
                Promosikan kelas DigitaLabs dan dapatkan komisi hingga 30% dari setiap penjualan. 
                Gratis bergabung, mudah dijalankan, dan pembayaran tepat waktu.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ route('filament.user.pages.affiliate') }}" 
                       class="w-full sm:w-auto px-8 py-4 bg-white text-orange-600 font-semibold rounded-xl hover:bg-orange-50 transition shadow-lg">
                        Dashboard Affiliate
                    </a>
                @else
                    <a href="{{ route('filament.user.auth.register') }}" 
                       class="w-full sm:w-auto px-8 py-4 bg-white text-orange-600 font-semibold rounded-xl hover:bg-orange-50 transition shadow-lg">
                        Daftar Sekarang - Gratis
                    </a>
                    <a href="{{ route('login') }}" 
                       class="w-full sm:w-auto px-8 py-4 bg-transparent text-white font-semibold rounded-xl border-2 border-white/50 hover:bg-white/10 transition">
                        Sudah Punya Akun? Login
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Stats Section --}}
    <section class="py-12 bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-bold text-orange-500">30%</p>
                    <p class="text-gray-600">Komisi per Penjualan</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-bold text-orange-500">30</p>
                    <p class="text-gray-600">Hari Cookie Tracking</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-bold text-orange-500">Rp 0</p>
                    <p class="text-gray-600">Biaya Pendaftaran</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-bold text-orange-500">100%</p>
                    <p class="text-gray-600">Real-time Tracking</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works Section --}}
    <section class="py-16 lg:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    🚀 Cara Kerja Program Affiliate
                </h2>
                <p class="text-gray-600">
                    Mulai hasilkan uang dalam 3 langkah mudah
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                {{-- Step 1 --}}
                <div class="relative bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center mb-6">
                        <span class="text-3xl font-bold text-orange-500">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Daftar & Apply</h3>
                    <p class="text-gray-600">
                        Daftar akun gratis di DigitaLabs, kemudian apply menjadi affiliate melalui dashboard. Proses approval dalam 1x24 jam.
                    </p>
                </div>

                {{-- Step 2 --}}
                <div class="relative bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center mb-6">
                        <span class="text-3xl font-bold text-orange-500">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Bagikan Link</h3>
                    <p class="text-gray-600">
                        Dapatkan link referral unik Anda dan bagikan ke teman, followers, atau audiens Anda melalui media sosial, blog, atau channel lainnya.
                    </p>
                </div>

                {{-- Step 3 --}}
                <div class="relative bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center mb-6">
                        <span class="text-3xl font-bold text-orange-500">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Dapatkan Komisi</h3>
                    <p class="text-gray-600">
                        Setiap pembelian kelas melalui link Anda akan tercatat otomatis. Komisi dibayarkan setiap bulan langsung ke rekening Anda.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Benefits Section --}}
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        🎁 Keuntungan Menjadi Affiliate
                    </h2>
                    <ul class="space-y-5">
                        <li class="flex items-start">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-4 mt-0.5 flex-shrink-0">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Komisi Besar Hingga 30%</h4>
                                <p class="text-gray-600">Dapatkan komisi hingga 30% dari setiap penjualan kelas melalui link referral Anda.</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-4 mt-0.5 flex-shrink-0">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Cookie Tracking 30 Hari</h4>
                                <p class="text-gray-600">Cookie disimpan selama 30 hari, jadi meskipun user tidak langsung beli, Anda tetap dapat komisi.</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-4 mt-0.5 flex-shrink-0">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Dashboard Real-time</h4>
                                <p class="text-gray-600">Pantau performa affiliate Anda secara real-time: klik, konversi, dan pendapatan.</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-4 mt-0.5 flex-shrink-0">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Pembayaran Tepat Waktu</h4>
                                <p class="text-gray-600">Komisi dibayarkan setiap bulan langsung ke rekening bank yang Anda daftarkan.</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-4 mt-0.5 flex-shrink-0">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Gratis Bergabung</h4>
                                <p class="text-gray-600">Tidak ada biaya pendaftaran atau biaya bulanan. 100% gratis untuk bergabung.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="hidden lg:block">
                    <img src="{{ asset('images/svg/digital-nomad.svg') }}" alt="Affiliate Benefits" class="w-full max-w-md mx-auto">
                </div>
            </div>
        </div>
    </section>

    {{-- Calculator Section --}}
    <section class="py-16 lg:py-24 bg-orange-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    💵 Hitung Potensi Penghasilan Anda
                </h2>
                <p class="text-gray-600">
                    Lihat berapa banyak yang bisa Anda hasilkan sebagai affiliate
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8" x-data="{ sales: 5, price: 489650, commission: 30 }">
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Penjualan per Bulan
                        </label>
                        <input type="range" x-model="sales" min="1" max="50" 
                               class="w-full h-2 bg-orange-200 rounded-lg appearance-none cursor-pointer accent-orange-500">
                        <p class="mt-2 text-center text-2xl font-bold text-orange-500" x-text="sales + ' penjualan'"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Rata-rata Harga Kelas
                        </label>
                        <p class="text-center text-2xl font-bold text-gray-700">Rp {{ number_format(489650, 0, ',', '.') }}</p>
                        <p class="text-center text-sm text-gray-500">(Harga kelas setelah diskon)</p>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-200 text-center">
                    <p class="text-gray-600 mb-2">Potensi Penghasilan per Bulan</p>
                    <p class="text-4xl md:text-5xl font-bold text-green-500" 
                       x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(sales * price * (commission / 100))">
                    </p>
                    <p class="text-sm text-gray-500 mt-2">
                        Dengan komisi <span class="font-semibold" x-text="commission + '%'"></span> per penjualan
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ Section --}}
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    ❓ FAQ Program Affiliate
                </h2>
                <p class="text-gray-600">
                    Pertanyaan yang sering diajukan tentang program affiliate
                </p>
            </div>

            <div class="space-y-4" x-data="{ openFaq: null }">
                {{-- FAQ 1 --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 1 ? null : 1" 
                            class="w-full flex items-center justify-between p-5 text-left bg-white hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-900">Siapa yang bisa menjadi affiliate?</span>
                        <svg class="w-5 h-5 text-gray-500 transition" :class="{ 'rotate-180': openFaq === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 1" x-collapse class="px-5 pb-5 text-gray-600">
                        Siapa saja bisa mendaftar menjadi affiliate DigitaLabs, baik content creator, blogger, educator, atau siapapun yang ingin mendapatkan penghasilan tambahan. Tidak perlu pengalaman sebelumnya.
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 2 ? null : 2" 
                            class="w-full flex items-center justify-between p-5 text-left bg-white hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-900">Berapa lama proses approval?</span>
                        <svg class="w-5 h-5 text-gray-500 transition" :class="{ 'rotate-180': openFaq === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 2" x-collapse class="px-5 pb-5 text-gray-600">
                        Proses approval biasanya dalam 1x24 jam kerja. Kami akan mereview aplikasi Anda dan mengirim notifikasi melalui email.
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 3 ? null : 3" 
                            class="w-full flex items-center justify-between p-5 text-left bg-white hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-900">Bagaimana cara kerja cookie tracking?</span>
                        <svg class="w-5 h-5 text-gray-500 transition" :class="{ 'rotate-180': openFaq === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 3" x-collapse class="px-5 pb-5 text-gray-600">
                        Saat seseorang mengklik link referral Anda, cookie akan disimpan di browser mereka selama 30 hari. Jika mereka membeli kelas dalam periode tersebut, Anda akan mendapatkan komisi meskipun mereka tidak langsung membeli saat pertama kali mengklik.
                    </div>
                </div>

                {{-- FAQ 4 --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 4 ? null : 4" 
                            class="w-full flex items-center justify-between p-5 text-left bg-white hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-900">Kapan komisi dibayarkan?</span>
                        <svg class="w-5 h-5 text-gray-500 transition" :class="{ 'rotate-180': openFaq === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 4" x-collapse class="px-5 pb-5 text-gray-600">
                        Komisi dibayarkan setiap bulan (tanggal 1-5) untuk transaksi bulan sebelumnya. Minimum payout adalah Rp 100.000. Pembayaran langsung ke rekening bank yang Anda daftarkan.
                    </div>
                </div>

                {{-- FAQ 5 --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 5 ? null : 5" 
                            class="w-full flex items-center justify-between p-5 text-left bg-white hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-900">Apakah ada biaya untuk bergabung?</span>
                        <svg class="w-5 h-5 text-gray-500 transition" :class="{ 'rotate-180': openFaq === 5 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 5" x-collapse class="px-5 pb-5 text-gray-600">
                        Tidak ada biaya sama sekali. Program affiliate DigitaLabs 100% gratis untuk bergabung dan tidak ada biaya bulanan atau biaya tersembunyi lainnya.
                    </div>
                </div>

                {{-- FAQ 6 --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 6 ? null : 6" 
                            class="w-full flex items-center justify-between p-5 text-left bg-white hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-900">Bagaimana cara mempromosikan link affiliate?</span>
                        <svg class="w-5 h-5 text-gray-500 transition" :class="{ 'rotate-180': openFaq === 6 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 6" x-collapse class="px-5 pb-5 text-gray-600">
                        Anda bisa membagikan link di media sosial (Instagram, TikTok, YouTube, Twitter), blog, email newsletter, atau channel lainnya. Pastikan untuk menyampaikan value dari kelas yang Anda promosikan.
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-16 lg:py-24 bg-gradient-to-br from-gray-900 to-gray-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Siap Mulai Menghasilkan?
            </h2>
            <p class="text-gray-400 text-lg mb-8 max-w-2xl mx-auto">
                Bergabung dengan program affiliate DigitaLabs sekarang dan mulai dapatkan penghasilan tambahan dari rumah.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ route('filament.user.pages.affiliate') }}" 
                       class="w-full sm:w-auto px-8 py-4 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition shadow-lg shadow-orange-500/30">
                        Masuk ke Dashboard Affiliate
                    </a>
                @else
                    <a href="{{ route('filament.user.auth.register') }}" 
                       class="w-full sm:w-auto px-8 py-4 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition shadow-lg shadow-orange-500/30">
                        Daftar Sekarang - Gratis
                    </a>
                @endauth
                <a href="https://wa.me/6289670883312?text={{ urlencode('Halo, saya ingin bertanya tentang program affiliate DigitaLabs') }}" 
                   target="_blank"
                   class="w-full sm:w-auto px-8 py-4 bg-white/10 text-white font-semibold rounded-xl border border-white/20 hover:bg-white/20 transition">
                    Tanya via WhatsApp
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
