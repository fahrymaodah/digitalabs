# Panduan Setup Credential untuk Client
**Email Client:** digitalabs@gmail.com

## Daftar Yang Dibutuhkan

Berikut adalah credential yang perlu disiapkan oleh client dengan akun email **digitalabs@gmail.com**:

1. ✅ Gmail SMTP (App Password)
2. ✅ Google OAuth 2.0 Credentials
3. ✅ Google Analytics 4 Setup & Service Account

---

## 1. Gmail SMTP Configuration

### Yang Dibutuhkan:
- `MAIL_USERNAME` = digitalabs@gmail.com
- `MAIL_PASSWORD` = App Password (16 karakter tanpa spasi)

### Langkah-Langkah Mendapatkan App Password:

#### Step 1: Aktifkan 2-Step Verification
1. Buka [https://myaccount.google.com/security](https://myaccount.google.com/security)
2. Login dengan **digitalabs@gmail.com**
3. Cari section **"How you sign in to Google"**
4. Klik **"2-Step Verification"**
5. Ikuti instruksi untuk mengaktifkan (jika belum aktif)
6. ⚠️ **PENTING:** 2-Step Verification HARUS aktif sebelum bisa buat App Password

#### Step 2: Generate App Password
1. Setelah 2-Step Verification aktif, kembali ke [https://myaccount.google.com/security](https://myaccount.google.com/security)
2. Cari section **"2-Step Verification"**
3. Scroll ke bawah, cari **"App passwords"** atau **"App-specific passwords"**
4. Klik **"App passwords"**
5. Mungkin diminta login ulang untuk verifikasi
6. Pada dropdown **"Select app"**, pilih **"Mail"**
7. Pada dropdown **"Select device"**, pilih **"Other (Custom name)"**
8. Ketik nama: **"Digitalabs Application"**
9. Klik **"Generate"**
10. Google akan menampilkan 16 karakter password (format: xxxx xxxx xxxx xxxx)
11. ⚠️ **COPY SEGERA!** Password ini hanya ditampilkan sekali
12. Hapus semua spasi, contoh: `abcd efgh ijkl mnop` → `abcdefghijklmnop`

#### Step 3: Test SMTP (Opsional)
Bisa test kirim email test dari aplikasi setelah credential dimasukkan.

### Credential yang Perlu Diberikan:
```env
MAIL_USERNAME=digitalabs@gmail.com
MAIL_PASSWORD=abcdefghijklmnop
```

---

## 2. Google OAuth 2.0 Credentials

### Yang Dibutuhkan:
- `GOOGLE_CLIENT_ID` (format: xxxxx-xxxxx.apps.googleusercontent.com)
- `GOOGLE_CLIENT_SECRET` (format: GOCSPX-xxxxx)
- `GOOGLE_REDIRECT_URI` (akan dikasih tahu nanti sesuai domain)

### Langkah-Langkah Mendapatkan OAuth Credentials:

#### Step 1: Buka Google Cloud Console
1. Buka [https://console.cloud.google.com](https://console.cloud.google.com)
2. Login dengan **digitalabs@gmail.com**
3. Jika pertama kali, akan ada prompt untuk accept Terms of Service

#### Step 2: Buat Project Baru
1. Klik dropdown project di pojok kiri atas (sebelah logo Google Cloud)
2. Klik **"NEW PROJECT"**
3. Isi detail project:
   - **Project name:** Digitalabs Application
   - **Organization:** (biarkan No organization)
   - **Location:** (biarkan default)
4. Klik **"CREATE"**
5. Tunggu beberapa detik sampai project selesai dibuat
6. Pastikan project **"Digitalabs Application"** sudah terpilih (lihat di dropdown pojok kiri atas)

#### Step 3: Enable Google+ API (untuk OAuth)
1. Di sidebar kiri, klik **"APIs & Services"** → **"Library"**
2. Atau buka langsung: [https://console.cloud.google.com/apis/library](https://console.cloud.google.com/apis/library)
3. Cari **"Google+ API"** atau **"People API"**
4. Klik pada API tersebut
5. Klik **"ENABLE"**
6. Tunggu sampai selesai

#### Step 4: Configure OAuth Consent Screen
1. Di sidebar kiri, klik **"APIs & Services"** → **"OAuth consent screen"**
2. Atau buka: [https://console.cloud.google.com/apis/credentials/consent](https://console.cloud.google.com/apis/credentials/consent)
3. Pilih **"External"** (untuk user umum bisa login)
4. Klik **"CREATE"**
5. Isi form OAuth consent screen:
   - **App name:** Digitalabs
   - **User support email:** digitalabs@gmail.com
   - **App logo:** (opsional, bisa upload logo nanti)
   - **Application home page:** https://digitalabs.id (atau domain yang dipakai)
   - **Authorized domains:** digitalabs.id (tanpa https://)
   - **Developer contact information:** digitalabs@gmail.com
6. Klik **"SAVE AND CONTINUE"**
7. Di halaman **"Scopes"**, klik **"ADD OR REMOVE SCOPES"**
8. Cari dan centang:
   - `userinfo.email`
   - `userinfo.profile`
   - `openid`
9. Klik **"UPDATE"** → **"SAVE AND CONTINUE"**
10. Di halaman **"Test users"**, bisa skip (klik **"SAVE AND CONTINUE"**)
11. Review summary, lalu klik **"BACK TO DASHBOARD"**

#### Step 5: Buat OAuth 2.0 Credentials
1. Di sidebar kiri, klik **"APIs & Services"** → **"Credentials"**
2. Atau buka: [https://console.cloud.google.com/apis/credentials](https://console.cloud.google.com/apis/credentials)
3. Klik **"+ CREATE CREDENTIALS"** di bagian atas
4. Pilih **"OAuth client ID"**
5. Jika diminta "Configure consent screen", berarti Step 4 belum selesai
6. Isi form Create OAuth client ID:
   - **Application type:** Web application
   - **Name:** Digitalabs Web Client
   - **Authorized JavaScript origins:**
     - http://localhost (untuk development)
     - https://digitalabs.id (untuk production)
   - **Authorized redirect URIs:**
     - http://localhost/auth/google/callback (untuk development)
     - https://digitalabs.id/auth/google/callback (untuk production)
7. Klik **"CREATE"**
8. Pop-up akan muncul menampilkan:
   - **Client ID** (format: 123456789-xxxxx.apps.googleusercontent.com)
   - **Client Secret** (format: GOCSPX-xxxxx)
9. ⚠️ **COPY KEDUA CREDENTIAL INI!**
10. Klik **"DOWNLOAD JSON"** untuk backup (opsional)
11. Klik **"OK"**

### Credential yang Perlu Diberikan:
```env
GOOGLE_CLIENT_ID=123456789-xxxxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-xxxxx
GOOGLE_REDIRECT_URI=https://digitalabs.id/auth/google/callback
```

⚠️ **CATATAN:** `GOOGLE_REDIRECT_URI` harus sesuai dengan domain production yang akan digunakan!

---

## 3. Google Analytics 4 Setup

### Yang Dibutuhkan:
- `ANALYTICS_PROPERTY_ID` (angka 9 digit, contoh: 523322030)
- `ANALYTICS_MEASUREMENT_ID` (format: G-XXXXXXXXXX)
- `ANALYTICS_SERVICE_ACCOUNT_CREDENTIALS_JSON` (file JSON)

### Langkah-Langkah Setup Google Analytics 4:

#### Step 1: Buat GA4 Property
1. Buka [https://analytics.google.com](https://analytics.google.com)
2. Login dengan **digitalabs@gmail.com**
3. Jika belum ada account, klik **"Start measuring"**
4. Jika sudah ada account, klik **"Admin"** (icon gear di pojok kiri bawah)

#### Step 2: Create Account (jika belum ada)
1. Klik **"Create Account"**
2. Isi detail:
   - **Account name:** Digitalabs
   - Centang semua checkbox untuk data sharing settings (recommended)
3. Klik **"Next"**

#### Step 3: Create Property
1. Isi detail property:
   - **Property name:** Digitalabs Website
   - **Reporting time zone:** (GMT+07:00) Jakarta
   - **Currency:** Indonesian Rupiah (IDR)
2. Klik **"Next"**
3. Isi business details:
   - **Industry category:** Education / Technology (pilih yang sesuai)
   - **Business size:** Small (1-10 employees) atau sesuaikan
4. Klik **"Next"**
5. Pilih business objectives (bisa pilih beberapa):
   - "Get baseline reports"
   - "Measure customer engagement"
   - (pilih yang relevan)
6. Klik **"Create"**
7. Accept Terms of Service
8. Klik **"I Accept"**

#### Step 4: Setup Data Stream
1. Setelah property dibuat, akan muncul "Set up your first data stream"
2. Pilih platform **"Web"**
3. Isi detail:
   - **Website URL:** https://digitalabs.id
   - **Stream name:** Digitalabs Website
   - Enhanced measurement biarkan ON (recommended)
4. Klik **"Create stream"**
5. Setelah stream dibuat, akan muncul detail:
   - **MEASUREMENT ID** (format: G-XXXXXXXXXX) ⚠️ **COPY INI!**
   - Stream details lainnya

#### Step 5: Dapatkan Property ID
1. Masih di halaman yang sama, lihat di bagian atas URL browser
2. URL akan seperti: `https://analytics.google.com/analytics/web/#/p123456789/...`
3. Angka setelah `/p` adalah **PROPERTY ID** ⚠️ **COPY INI!**
4. Contoh: jika URL-nya `.../p523322030/...` maka Property ID = `523322030`

#### Step 6: Buat Service Account (untuk API Access)
1. Buka Google Cloud Console: [https://console.cloud.google.com](https://console.cloud.google.com)
2. Login dengan **digitalabs@gmail.com**
3. Pastikan project **"Digitalabs Application"** terpilih (atau buat baru jika perlu)
4. Di sidebar kiri, klik **"IAM & Admin"** → **"Service Accounts"**
5. Atau buka: [https://console.cloud.google.com/iam-admin/serviceaccounts](https://console.cloud.google.com/iam-admin/serviceaccounts)

#### Step 7: Create Service Account
1. Klik **"+ CREATE SERVICE ACCOUNT"** di bagian atas
2. Isi detail:
   - **Service account name:** Digitalabs Analytics
   - **Service account ID:** digitalabs-analytics (otomatis terisi)
   - **Description:** Service account for Google Analytics API access
3. Klik **"CREATE AND CONTINUE"**
4. Di **"Grant this service account access to project"**:
   - Skip atau biarkan kosong
   - Klik **"CONTINUE"**
5. Di **"Grant users access to this service account"**:
   - Skip atau biarkan kosong
   - Klik **"DONE"**

#### Step 8: Generate Service Account Key (JSON)
1. Di halaman Service Accounts, akan muncul service account yang baru dibuat
2. Klik pada email service account (format: digitalabs-analytics@project-id.iam.gserviceaccount.com)
3. Klik tab **"KEYS"**
4. Klik **"ADD KEY"** → **"Create new key"**
5. Pilih **"JSON"**
6. Klik **"CREATE"**
7. File JSON akan otomatis terdownload ke komputer
8. ⚠️ **SIMPAN FILE INI DENGAN AMAN!** Ini adalah credential penting
9. Rename file jadi **"service-account-credentials.json"** (opsional, untuk konsistensi)

#### Step 9: Enable Google Analytics Data API
1. Masih di Google Cloud Console
2. Di sidebar kiri, klik **"APIs & Services"** → **"Library"**
3. Cari **"Google Analytics Data API"**
4. Klik pada API tersebut
5. Klik **"ENABLE"**
6. Tunggu sampai selesai

#### Step 10: Berikan Akses Service Account ke GA4 Property
1. Kembali ke Google Analytics: [https://analytics.google.com](https://analytics.google.com)
2. Klik **"Admin"** (icon gear di pojok kiri bawah)
3. Di kolom **"Property"**, klik **"Property Access Management"**
4. Klik **"+"** (Add users) di pojok kanan atas
5. Isi detail:
   - **Email address:** Paste email service account dari Step 8 (format: digitalabs-analytics@project-id.iam.gserviceaccount.com)
   - **Role:** pilih **"Viewer"** (cukup untuk read data)
   - Centang **"Notify this user by email"** (opsional)
6. Klik **"Add"**
7. Service account sekarang punya akses untuk baca data GA4

#### Step 11: Install Tracking Code (GA4 Tag) di Website
1. Masih di Google Analytics, klik **"Admin"** → **"Data Streams"**
2. Klik pada stream yang sudah dibuat (Digitalabs Website)
3. Scroll ke bawah, klik **"View tag instructions"**
4. Copy kode tracking yang ditampilkan:

```html
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXXXXX');
</script>
```

5. ⚠️ Kode ini perlu dipasang di website (kasih ke developer)
6. Biasanya dipasang di file layout utama (contoh: `resources/views/components/layout.blade.php` atau `app.blade.php`)
7. Letakkan di dalam tag `<head>` sebelum closing `</head>`

### Credential yang Perlu Diberikan:
```env
ANALYTICS_PROPERTY_ID=523322030
ANALYTICS_MEASUREMENT_ID=G-XXXXXXXXXX
ANALYTICS_SERVICE_ACCOUNT_CREDENTIALS_JSON=storage/app/analytics/service-account-credentials.json
```

**File yang perlu dikirim:**
- 📄 `service-account-credentials.json` (hasil download dari Step 8)

---

## Checklist untuk Client

Setelah selesai semua, client perlu mengirimkan:

### 1. Gmail SMTP
- [ ] `MAIL_USERNAME` = digitalabs@gmail.com
- [ ] `MAIL_PASSWORD` = (16 karakter App Password tanpa spasi)

### 2. Google OAuth
- [ ] `GOOGLE_CLIENT_ID` = (xxxxx-xxxxx.apps.googleusercontent.com)
- [ ] `GOOGLE_CLIENT_SECRET` = (GOCSPX-xxxxx)
- [ ] Konfirmasi domain production untuk redirect URI

### 3. Google Analytics 4
- [ ] `ANALYTICS_PROPERTY_ID` = (angka 9 digit)
- [ ] `ANALYTICS_MEASUREMENT_ID` = (G-XXXXXXXXXX)
- [ ] File `service-account-credentials.json`
- [ ] Tracking code sudah dipasang di website (opsional, bisa developer yang pasang)

---

## Format Pengiriman Credential

Minta client mengirimkan dalam format ini untuk memudahkan:

```
=== GMAIL SMTP ===
MAIL_USERNAME=digitalabs@gmail.com
MAIL_PASSWORD=abcdefghijklmnop

=== GOOGLE OAUTH ===
GOOGLE_CLIENT_ID=123456789-xxxxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-xxxxx
GOOGLE_REDIRECT_URI=https://digitalabs.id/auth/google/callback

=== GOOGLE ANALYTICS 4 ===
ANALYTICS_PROPERTY_ID=523322030
ANALYTICS_MEASUREMENT_ID=G-XXXXXXXXXX

Attachment:
- service-account-credentials.json
```

---

## Troubleshooting

### MAIL: "Invalid credentials" atau "Username and Password not accepted"
- ❌ Pastikan 2-Step Verification sudah aktif
- ❌ Gunakan App Password, BUKAN password Gmail biasa
- ❌ Hapus semua spasi di App Password
- ❌ Generate ulang App Password jika perlu

### OAuth: "Redirect URI mismatch"
- ❌ Pastikan redirect URI di Google Cloud Console sama persis dengan yang di aplikasi
- ❌ Perhatikan http vs https
- ❌ Perhatikan trailing slash (dengan `/` atau tanpa `/`)

### Analytics: "Service account doesn't have access"
- ❌ Pastikan service account sudah ditambahkan di Property Access Management (Step 10)
- ❌ Role minimal "Viewer"
- ❌ Google Analytics Data API harus sudah enabled (Step 9)

### Analytics: "No data available"
- ❌ Tracking code belum dipasang di website
- ❌ Tracking code salah Measurement ID
- ❌ Website belum ada traffic (tunggu 24-48 jam setelah pasang tracking code)
- ❌ Ad blocker bisa block tracking code (test dengan browser incognito)

---

## Keamanan & Best Practices

⚠️ **PENTING - Keamanan Credential:**

1. **Jangan share credential via email biasa**
   - Gunakan password manager (1Password, LastPass, Bitwarden)
   - Atau gunakan encrypted service (Keybase, Signal)

2. **File JSON Service Account**
   - Jangan commit ke Git
   - Jangan upload ke public storage
   - Simpan di lokasi aman

3. **App Password Gmail**
   - Jangan gunakan password Gmail utama
   - App Password hanya untuk aplikasi ini
   - Bisa revoke/hapus kapan saja jika perlu

4. **OAuth Client Secret**
   - Jangan expose di frontend/JavaScript
   - Hanya untuk backend/server-side

5. **Backup Credential**
   - Simpan semua credential di password manager
   - Document siapa yang punya akses

---

## Support

Jika ada kesulitan atau pertanyaan saat setup, hubungi developer dengan informasi:
- Screenshot error message
- Step mana yang stuck
- Email yang digunakan (digitalabs@gmail.com)

Dokumentasi ini berlaku per **Februari 2026**. Interface Google mungkin berubah, tapi konsep dasarnya sama.
