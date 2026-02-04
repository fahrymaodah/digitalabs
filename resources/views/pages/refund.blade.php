<x-layouts.public title="Kebijakan Refund - DigitaLabs">
    {{-- Hero Section --}}
    <section class="bg-gradient-to-br from-gray-800 to-gray-900 py-12 lg:py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Kebijakan Refund
            </h1>
            <p class="text-gray-400">
                Terakhir diperbarui: {{ now()->format('d F Y') }}
            </p>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12 lg:py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg prose-gray max-w-none">
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-6 mb-8 not-prose">
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Ringkasan Kebijakan Refund</h3>
                            <p class="text-gray-600">
                                DigitaLabs memberikan jaminan <strong>7 hari garansi uang kembali</strong> untuk pembelian kelas, dengan syarat dan ketentuan tertentu. Baca kebijakan lengkap di bawah.
                            </p>
                        </div>
                    </div>
                </div>

                <h2>1. Garansi 7 Hari Uang Kembali</h2>
                <p>
                    Kami memahami bahwa membeli kelas online adalah keputusan penting. Untuk memberikan rasa aman, DigitaLabs menyediakan <strong>garansi 7 hari uang kembali</strong> untuk semua pembelian kelas dengan ketentuan berikut:
                </p>

                <h3>1.1 Syarat Kelayakan Refund</h3>
                <ul>
                    <li>Permintaan refund diajukan dalam waktu <strong>7 hari</strong> sejak tanggal pembelian.</li>
                    <li>Anda belum menyelesaikan lebih dari <strong>30%</strong> dari total materi kelas.</li>
                    <li>Anda belum mengunduh file latihan atau resource yang disediakan.</li>
                    <li>Ini adalah pembelian pertama Anda untuk kelas tersebut (bukan pembelian ulang).</li>
                </ul>

                <h3>1.2 Kondisi yang TIDAK Memenuhi Syarat Refund</h3>
                <ul>
                    <li>Permintaan diajukan setelah 7 hari dari tanggal pembelian.</li>
                    <li>Anda sudah menyelesaikan lebih dari 30% materi kelas.</li>
                    <li>Anda sudah mengunduh file latihan atau resource.</li>
                    <li>Akun Anda terbukti melakukan pelanggaran (sharing akun, mengunduh video, dll).</li>
                    <li>Pembelian menggunakan kupon diskon di atas 50%.</li>
                    <li>Kelas dalam kategori "Bundling" atau "Paket Hemat".</li>
                    <li>Alasan refund adalah "berubah pikiran" setelah mengakses sebagian besar materi.</li>
                </ul>

                <h2>2. Proses Pengajuan Refund</h2>
                <h3>2.1 Cara Mengajukan Refund</h3>
                <ol>
                    <li>Kirim email ke <a href="mailto:support@digitalabs.id">support@digitalabs.id</a> dengan subjek "Pengajuan Refund - [Nama Kelas]"</li>
                    <li>Sertakan informasi berikut:
                        <ul>
                            <li>Email yang terdaftar di DigitaLabs</li>
                            <li>Nama kelas yang ingin di-refund</li>
                            <li>Tanggal pembelian</li>
                            <li>Alasan pengajuan refund</li>
                            <li>Nomor rekening untuk pengembalian dana</li>
                        </ul>
                    </li>
                    <li>Tim kami akan memverifikasi dan merespon dalam <strong>1-3 hari kerja</strong>.</li>
                </ol>

                <h3>2.2 Waktu Pemrosesan</h3>
                <ul>
                    <li><strong>Verifikasi:</strong> 1-3 hari kerja setelah pengajuan diterima.</li>
                    <li><strong>Persetujuan:</strong> Anda akan menerima email konfirmasi persetujuan/penolakan.</li>
                    <li><strong>Transfer Dana:</strong> 3-7 hari kerja setelah persetujuan ke rekening bank yang Anda berikan.</li>
                </ul>

                <h2>3. Jumlah Pengembalian Dana</h2>
                <div class="overflow-x-auto not-prose my-6">
                    <table class="min-w-full border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 border-b">Kondisi</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 border-b">Refund</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-700">Refund dalam 7 hari, progress < 30%</td>
                                <td class="px-4 py-3 text-sm text-green-600 font-semibold">100% dari harga yang dibayar</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-700">Masalah teknis dari pihak DigitaLabs</td>
                                <td class="px-4 py-3 text-sm text-green-600 font-semibold">100% atau akses ke kelas alternatif</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-700">Kelas tidak sesuai deskripsi (diverifikasi)</td>
                                <td class="px-4 py-3 text-sm text-green-600 font-semibold">100% dari harga yang dibayar</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p><strong>Catatan:</strong> Biaya administrasi payment gateway mungkin tidak dapat dikembalikan tergantung pada kebijakan penyedia payment gateway.</p>

                <h2>4. Kasus Khusus</h2>
                <h3>4.1 Masalah Teknis</h3>
                <p>Jika Anda mengalami masalah teknis yang menghalangi akses ke kelas (bukan dari sisi perangkat Anda):</p>
                <ul>
                    <li>Hubungi support kami terlebih dahulu untuk bantuan teknis.</li>
                    <li>Jika masalah tidak dapat diselesaikan dalam 7 hari, Anda berhak atas refund penuh.</li>
                </ul>

                <h3>4.2 Pembelian Ganda (Double Purchase)</h3>
                <p>Jika Anda tidak sengaja membeli kelas yang sama dua kali:</p>
                <ul>
                    <li>Hubungi kami dalam 24 jam setelah pembelian.</li>
                    <li>Sertakan bukti kedua transaksi.</li>
                    <li>Refund penuh akan diproses untuk pembelian duplikat.</li>
                </ul>

                <h3>4.3 Pembatalan Kelas oleh DigitaLabs</h3>
                <p>Dalam kasus yang sangat jarang terjadi di mana kami harus membatalkan kelas:</p>
                <ul>
                    <li>Anda akan mendapat refund penuh secara otomatis.</li>
                    <li>Atau opsi untuk transfer ke kelas lain dengan nilai yang sama.</li>
                </ul>

                <h2>5. Yang Tidak Dapat Di-Refund</h2>
                <ul>
                    <li>Komisi affiliate yang sudah dibayarkan.</li>
                    <li>Akun yang sudah dihapus atau dinonaktifkan karena pelanggaran.</li>
                    <li>Pembelian yang dilakukan oleh pihak ketiga atas nama Anda (gift purchase).</li>
                </ul>

                <h2>6. Kebijakan Anti-Abuse</h2>
                <p>Untuk mencegah penyalahgunaan kebijakan refund:</p>
                <ul>
                    <li>Kami melacak riwayat refund setiap pengguna.</li>
                    <li>Pengajuan refund yang berulang-ulang atau mencurigakan dapat ditolak.</li>
                    <li>Akun dengan pola abuse dapat di-blacklist dari pembelian selanjutnya.</li>
                </ul>

                <h2>7. Pertanyaan Sebelum Membeli</h2>
                <p>Untuk menghindari perlunya refund, kami sarankan:</p>
                <ul>
                    <li>Baca deskripsi kelas dengan teliti sebelum membeli.</li>
                    <li>Tonton preview video jika tersedia.</li>
                    <li>Periksa level kelas (pemula, menengah, lanjutan) sesuai kemampuan Anda.</li>
                    <li>Hubungi kami jika ada pertanyaan sebelum membeli.</li>
                </ul>

                <h2>8. Hubungi Kami</h2>
                <p>Untuk pertanyaan tentang kebijakan refund atau mengajukan permintaan refund:</p>
                <ul>
                    <li><strong>Email:</strong> <a href="mailto:support@digitalabs.id">support@digitalabs.id</a></li>
                    <li><strong>WhatsApp:</strong> <a href="https://wa.me/6289670883312">+62 896 7088 3312</a></li>
                    <li><strong>Jam Operasional:</strong> Senin - Jumat, 09:00 - 17:00 WITA</li>
                </ul>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-xl font-bold text-gray-900 mb-2">Masih Ragu?</h2>
            <p class="text-gray-600 mb-4">Hubungi kami dulu sebelum membeli untuk memastikan kelas yang tepat untuk Anda</p>
            <a href="https://wa.me/6289670883312?text={{ urlencode('Halo, saya ingin bertanya tentang kelas di DigitaLabs sebelum membeli') }}" 
               target="_blank"
               class="inline-flex items-center px-6 py-3 bg-green-500 text-white font-medium rounded-xl hover:bg-green-600 transition">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Chat via WhatsApp
            </a>
        </div>
    </section>
</x-layouts.public>
