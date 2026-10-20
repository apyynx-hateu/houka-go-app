# Product Requirements Document (PRD)
## Sistem Manajemen Operasional Houka GO — Laravel 11

- **Versi dokumen:** 1.0
- **Status:** Draft untuk validasi stakeholder
- **Basis analisis:** `db_houkago.sql` yang diberikan
- **Target teknologi:** Laravel 11 + MySQL 8
- **Bahasa aplikasi:** Bahasa Indonesia

## 1. Tujuan dokumen

Dokumen ini mendefinisikan kebutuhan produk, ruang lingkup, aktor, alur bisnis, kebutuhan fungsional dan nonfungsional, model data, kriteria penerimaan, serta rencana pengujian untuk membangun aplikasi operasional Houka GO menggunakan Laravel 11.

Dokumen membedakan:
- **Fakta sumber:** entitas/kolom/status yang ada pada dump SQL.
- **Usulan:** kebutuhan yang disarankan agar aplikasi aman dan dapat dipakai.
- **Keputusan terbuka:** hal yang harus dikonfirmasi stakeholder sebelum implementasi.

## 2. Latar belakang dan masalah

Database yang diberikan menunjukkan kebutuhan pengelolaan data tutor dan sekolah, jadwal mengajar, absensi, honor, pembayaran honor, penilaian kinerja, serta permintaan laporan dari pimpinan. Aplikasi dibutuhkan untuk memusatkan data tersebut, mengurangi pencatatan manual, menjaga konsistensi proses, dan menyediakan rekap berdasarkan periode.

## 3. Sasaran produk

1. Memusatkan data tutor dan sekolah.
2. Memudahkan pengelolaan jadwal dan pencatatan absensi.
3. Menyediakan perhitungan honor yang transparan dan dapat ditelusuri.
4. Mencatat pembayaran honor dan riwayat statusnya.
5. Mendokumentasikan penilaian tutor.
6. Mengelola permintaan laporan sampai selesai.
7. Menyediakan laporan yang dapat difilter dan diekspor.
8. Membatasi akses berdasarkan peran dan melindungi data pribadi.

### 3.1 Indikator keberhasilan (target awal, perlu disepakati)

- 100% operasi tulis pada modul inti memvalidasi input di server.
- Tidak ada akun yang menyimpan password plaintext.
- Rekap honor sama dengan perhitungan yang disahkan pada seluruh test case.
- Laporan dapat difilter berdasarkan rentang tanggal/periode.
- Seluruh akses ke modul sensitif dilindungi otorisasi.
- Tidak ada duplikasi absensi untuk kombinasi jadwal dan tanggal yang sama, kecuali aturan bisnis mengizinkan.

## 4. Ruang lingkup

### 4.1 Termasuk dalam MVP

- Login/logout akun pimpinan.
- Dashboard ringkasan.
- CRUD tutor.
- CRUD sekolah.
- CRUD jadwal.
- CRUD/pencatatan absensi.
- Pembuatan dan pengelolaan honor.
- Pencatatan pembayaran honor.
- Penilaian tutor.
- Permintaan laporan dan perubahan status.
- Rekap laporan tutor, absensi, jadwal, honor, dan informasi operasional.
- Pencarian, filter, pagination, validasi, otorisasi, dan audit dasar.

### 4.2 Tidak termasuk dalam MVP kecuali disetujui

- Portal siswa/orang tua.
- Data siswa dan pendaftaran siswa.
- Pengelolaan kurikulum atau materi ajar.
- Integrasi bank/payment gateway otomatis.
- Payroll otomatis ke bank.
- Aplikasi mobile native.
- Notifikasi WhatsApp/email otomatis.
- Akuntansi umum dan pajak.
- Multi-cabang dengan isolasi tenant.

## 5. Stakeholder dan aktor

Akun sumber berada pada tabel `petinggi` dengan kolom `jabatan` dan `status_aktif`. Tiga contoh jabatan pada data adalah CO Founder, Operasional, dan Human Resource. Izin detail belum tersedia dalam database.

| Aktor | Kebutuhan | Akses yang diusulkan |
|---|---|---|
| Administrator/CO Founder | Memantau seluruh operasional dan mengelola akses | Seluruh modul |
| Operasional | Mengelola sekolah, jadwal, absensi dan laporan terkait | Sekolah, jadwal, absensi, laporan operasional |
| Human Resource | Mengelola tutor dan evaluasi kinerja | Tutor, penilaian, laporan SDM |
| Keuangan (usulan) | Mengelola honor dan pembayaran | Honor, pembayaran, laporan keuangan |
| Sistem | Menjaga konsistensi data dan status | Validasi, perhitungan, pencatatan aktivitas |

MVP dapat dimulai dengan tiga jabatan yang ada, tetapi matriks izin harus disetujui stakeholder. Jangan menggunakan pemeriksaan nama jabatan yang tersebar di controller; gunakan policy/permission terpusat.

## 6. Kebutuhan fungsional

### FR-01 — Autentikasi dan sesi

**Deskripsi:** Pengguna pimpinan dapat login dan logout secara aman.

**Aturan:**
- Username harus unik.
- Hanya akun dengan `status_aktif = Aktif` yang dapat login.
- Password harus disimpan sebagai hash.
- Regenerasi session dilakukan setelah login.
- Login diberi rate limit.
- Pesan gagal login tidak membocorkan apakah username terdaftar.

**Kriteria penerimaan:**
- Kredensial valid dan akun aktif mengarah ke dashboard.
- Password salah atau akun tidak aktif ditolak.
- Logout mengakhiri sesi.
- Route privat tidak bisa diakses tanpa autentikasi.

### FR-02 — Dashboard

Menampilkan ringkasan yang sesuai izin pengguna, minimal:
- jumlah tutor aktif dan tidak aktif;
- jumlah sekolah;
- jadwal dalam rentang waktu yang dipilih;
- ringkasan absensi;
- total honor menurut status;
- ringkasan pembayaran menurut status;
- permintaan laporan yang menunggu/diproses.

Angka dashboard harus berasal dari query database, memakai filter periode yang jelas, dan tidak menghitung transaksi yang sama lebih dari sekali.

### FR-03 — Manajemen tutor

Field sumber: nama, jenis kelamin, tanggal lahir, alamat, nomor telepon, email, pendidikan terakhir, status kerja, tanggal bergabung.

**Fungsi:** daftar, detail, tambah, ubah, pencarian, filter status, dan nonaktifkan tutor.

**Aturan:**
- Nama wajib.
- Email jika diisi harus valid.
- Tanggal lahir tidak boleh di masa depan.
- Tanggal bergabung harus berupa tanggal valid.
- Nomor telepon disimpan sebagai string agar nol awal tidak hilang.
- Tutor yang memiliki transaksi tidak dihapus permanen; gunakan status tidak aktif atau soft delete setelah keputusan stakeholder.

**Kriteria penerimaan:** data yang valid dapat disimpan; data tidak valid menampilkan pesan yang jelas; relasi historis tetap dapat ditampilkan.

### FR-04 — Manajemen sekolah

Field sumber: nama sekolah, alamat, ruangan, kapasitas.

**Fungsi:** daftar, detail, tambah, ubah, pencarian, dan filter.

**Aturan:**
- Nama sekolah wajib.
- Kapasitas jika diisi harus bilangan bulat positif.
- Penghapusan sekolah yang telah memiliki jadwal harus dicegah atau menggunakan status nonaktif (status belum ada pada skema sumber; penambahan kolom adalah usulan).

### FR-05 — Manajemen jadwal

Field sumber: tutor, sekolah, hari, jam mulai, jam selesai, tanggal opsional.

**Fungsi:** CRUD jadwal, filter tutor/sekolah/hari/periode, tampilan kalender atau tabel.

**Aturan:**
- Tutor dan sekolah harus ada.
- Jam selesai harus lebih akhir dari jam mulai.
- Hari hanya Senin–Sabtu menurut enum sumber.
- Sistem perlu memeriksa konflik waktu tutor.
- Sistem perlu memeriksa konflik ruangan/sekolah jika aturan pemakaian lokasi mengharuskannya.
- Model jadwal berulang mingguan versus jadwal satu kali harus diputuskan.

**Kriteria penerimaan:** jadwal dengan waktu tidak valid ditolak; konflik yang didefinisikan stakeholder ditandai dan tidak disimpan jika konflik bersifat hard-block.

### FR-06 — Absensi tutor

Field sumber: tutor, jadwal opsional, tanggal, status (`Hadir`, `Tidak Hadir`), keterangan.

**Fungsi:** input, ubah, detail, filter tanggal/tutor/status, rekap periode.

**Aturan:**
- Tanggal wajib.
- Status wajib sesuai enum.
- Jika jadwal diisi, tutor pada absensi harus konsisten dengan tutor jadwal.
- Cegah duplikasi untuk tutor/jadwal/tanggal sesuai aturan yang disepakati.
- Keterangan opsional.
- Perubahan absensi yang sudah dipakai dalam perhitungan honor perlu dicatat dan dapat memicu peninjauan ulang honor.

**Kriteria penerimaan:** rekap menghitung status hadir/tidak hadir secara tepat; data tidak konsisten ditolak.

### FR-07 — Honor tutor

Field sumber: tutor, periode bulan, tarif per jam, jumlah siswa, total jam mengajar, penyesuaian honor, total honor, status (`Belum Dibayar`, `Diproses`, `Dibayar`).

**Fungsi:** buat draft honor, lihat rincian kalkulasi, ubah penyesuaian dengan alasan, ajukan/konfirmasi honor, filter periode/tutor/status.

**Aturan yang harus disetujui:**
- Formula awal yang mungkin: `total_honor = tarif_per_jam × total_jam_mengajar + penyesuaian_honor`. Ini hanya usulan dan belum dapat dipastikan dari dump.
- Makna `jumlah_siswa` dan apakah memengaruhi tarif perlu dikonfirmasi.
- Sumber total jam harus ditetapkan: jadwal, absensi hadir, atau input manual yang disetujui.
- Periode harus dinormalisasi. Kolom `periode_bulan` bertipe DATE, tetapi data contoh memakai tanggal tertentu di bulan itu.
- Setelah pembayaran berhasil, perubahan honor sebaiknya memerlukan pembatalan/penyesuaian terotorisasi, bukan mengubah riwayat secara diam-diam.
- Status honor harus disinkronkan dengan jumlah pembayaran berhasil dan aturan pembayaran parsial.

**Kriteria penerimaan:**
- Sistem menampilkan rincian tarif, jam, penyesuaian, dan total.
- Hasil kalkulasi lulus test case yang disetujui.
- Total tidak dapat dimanipulasi melalui request browser tanpa validasi server.
- Perubahan tarif/perhitungan tercatat.

### FR-08 — Pembayaran honor

Field sumber: honor, tanggal pembayaran, jumlah, nomor rekening, status (`Pending`, `Berhasil`, `Gagal`), keterangan.

**Fungsi:** mencatat pembayaran manual, melihat riwayat, mengubah status sesuai kewenangan, dan memfilter pembayaran.

**Aturan:**
- Jumlah pembayaran harus positif.
- Pembayaran terkait satu honor.
- Status awal `Pending`.
- `tanggal_pembayaran` diisi sesuai definisi bisnis (tanggal instruksi atau tanggal berhasil harus dibedakan).
- Nomor rekening adalah data sensitif; batasi akses dan tampilkan secara tersamarkan.
- Pembayaran parsial atau cicilan harus diputuskan.
- Perubahan menjadi `Berhasil` wajib menyimpan aktor dan waktu perubahan (audit usulan).
- Jangan mengklaim integrasi bank; skema saat ini hanya mendukung pencatatan pembayaran.

**Kriteria penerimaan:** pembayaran dapat dicatat; status valid; jumlah pembayaran berhasil dapat direkap; status honor mengikuti aturan yang disepakati.

### FR-09 — Penilaian tutor

Field sumber: tutor, penilai/petinggi, tanggal penilaian, periode, kualitas mengajar, catatan kinerja.

**Fungsi:** buat, lihat, ubah sesuai izin, filter tutor/periode/penilai.

**Aturan:**
- Tutor dan penilai harus valid.
- Tanggal penilaian wajib.
- Periode dan catatan dapat kosong sesuai skema.
- Penilaian hanya dapat dilihat/diubah oleh peran yang berwenang.
- Perubahan historis sebaiknya tercatat.

**Kriteria penerimaan:** penilaian terhubung dengan tutor dan akun penilai yang benar.

### FR-10 — Permintaan laporan

Field sumber: peminta/petinggi, waktu permintaan, jenis (`Rekap Tutor`, `Rekap Absensi`, `Rekap Jadwal`, `Rekap Honor`, `Informasi Operasional`), periode awal/akhir, keterangan, status (`Menunggu`, `Diproses`, `Selesai`).

**Fungsi:** membuat permintaan, melihat daftar/detail, memfilter, dan mengubah status.

**Aturan:**
- Peminta diambil dari pengguna yang sedang login, bukan ID yang dipercaya dari form.
- Jika kedua tanggal ada, periode awal tidak boleh setelah periode akhir.
- Transisi status: Menunggu → Diproses → Selesai; aturan kembali ke status sebelumnya perlu ditentukan.
- Status `Selesai` sebaiknya mencatat siapa yang menyelesaikan dan kapan (kolom audit usulan).

### FR-11 — Laporan dan ekspor

Laporan yang diperlukan sesuai enum sumber:
- Rekap Tutor.
- Rekap Absensi.
- Rekap Jadwal.
- Rekap Honor.
- Informasi Operasional.

**Fungsi minimum:** filter periode, filter status/tutor bila relevan, tampilan ringkasan dan detail, pagination untuk data besar.

**Usulan:** ekspor CSV/XLSX dan PDF. Format ekspor dan kolom perlu disetujui. Laporan honor hanya tersedia untuk role yang diizinkan.

**Kriteria penerimaan:** total pada laporan konsisten dengan data detail dan filter yang dipilih; data di luar izin tidak ditampilkan.

### FR-12 — Pencarian, validasi, dan audit

- Semua list utama memiliki pagination dan pencarian/filter yang relevan.
- Validasi dilakukan di backend melalui Form Request.
- Operasi hapus dan perubahan status sensitif meminta konfirmasi UI.
- Audit log untuk perubahan honor, pembayaran, absensi, dan hak akses direkomendasikan.
- Kesalahan ditampilkan dengan pesan yang ramah, tanpa stack trace pada production.

## 7. Kebutuhan nonfungsional

### NFR-01 Keamanan
- Hash password dengan mekanisme Laravel.
- CSRF protection, validasi input, output escaping, rate limiting login.
- Otorisasi pada server untuk setiap route/action sensitif.
- HTTPS pada production.
- Rahasiakan `.env`, kredensial, dan data pribadi.
- Backup terenkripsi bila fasilitas deployment mendukung.

### NFR-02 Integritas data
- Gunakan foreign key dan transaksi database untuk proses multi-langkah.
- Uang memakai `DECIMAL`, bukan floating point.
- Gunakan constraint/index untuk pencarian dan pencegahan duplikasi yang telah disepakati.
- Jangan menghapus data historis yang masih dirujuk transaksi.

### NFR-03 Kinerja
- Target awal: halaman list umum merespons dalam ≤ 2 detik pada beban normal dan data uji yang disepakati; target ini perlu diuji pada lingkungan target.
- Gunakan pagination, eager loading Eloquent, index, dan agregasi yang efisien.
- Ekspor besar dapat dijalankan secara queued jika diperlukan.

### NFR-04 Kemudahan penggunaan
- Antarmuka responsif untuk desktop/tablet/mobile.
- Bahasa Indonesia dan format tanggal/mata uang konsisten.
- Status menggunakan label teks, bukan warna saja.
- Form memiliki label, pesan validasi, dan konfirmasi tindakan penting.

### NFR-05 Pemeliharaan
- MVC dengan service untuk logika bisnis kompleks.
- Migration dan seeder dikelola melalui version control.
- Automated tests dijalankan pada CI atau sebelum rilis.
- Konfigurasi lingkungan terpisah antara local, staging, dan production.

### NFR-06 Backup dan pemulihan
- Frekuensi backup, retensi, RPO, dan RTO harus disepakati.
- Lakukan uji restore berkala, bukan hanya membuat backup.

## 8. Model data dan relasi

### 8.1 Tabel sumber

| Tabel | Primary key | Tujuan |
|---|---|---|
| `petinggi` | `id_petinggi` | Akun pimpinan |
| `tutor` | `id_tutor` | Profil tutor |
| `sekolah` | `id_sekolah` | Lokasi/sekolah |
| `jadwal` | `id_jadwal` | Jadwal tutor dan sekolah |
| `absensi` | `id_absensi` | Kehadiran tutor |
| `honor` | `id_honor` | Rekap honor tutor per periode |
| `pembayaran_honor` | `id_pembayaran` | Catatan pembayaran honor |
| `penilaian` | `id_penilaian` | Evaluasi kinerja tutor |
| `permintaan_laporan` | `id_permintaan` | Permintaan laporan oleh pimpinan |

### 8.2 Relasi

- Tutor memiliki banyak jadwal, absensi, honor, dan penilaian.
- Sekolah memiliki banyak jadwal.
- Jadwal dapat direferensikan oleh banyak absensi (per tanggal).
- Honor memiliki banyak catatan pembayaran jika pembayaran parsial diizinkan; jika tidak, aturan unik/constraint perlu diterapkan.
- Petinggi dapat membuat banyak penilaian dan permintaan laporan.

### 8.3 Rekomendasi penyesuaian skema

Semua perubahan berikut merupakan rekomendasi, bukan fakta dari SQL:
1. Tambahkan timestamps pada tabel yang dikelola aplikasi.
2. Pastikan `petinggi.password` berisi hash; reset kredensial contoh.
3. Tambahkan `remember_token` jika mekanisme autentikasi membutuhkannya.
4. Pertimbangkan kolom status aktif untuk sekolah.
5. Tambahkan `created_by`, `updated_by`, dan audit log untuk data sensitif.
6. Tentukan indeks unik absensi berdasarkan aturan bisnis.
7. Pertimbangkan menyimpan periode sebagai tanggal awal bulan atau kolom tahun/bulan.
8. Tentukan apakah jadwal adalah recurring atau one-off; bila recurring, skema saat ini mungkin perlu pemisahan template jadwal dan sesi aktual.
9. Pertimbangkan tabel role/permission jika izin tidak cukup direpresentasikan oleh jabatan.
10. Pertimbangkan tabel siswa/kelas hanya jika memang masuk scope produk.

## 9. Aturan bisnis kritis

### BR-01 — Perhitungan honor
Formula final belum bisa ditentukan dari SQL saja. Data contoh tidak selalu cocok dengan formula sederhana tarif × jam + penyesuaian. Stakeholder harus memberikan formula resmi dan contoh hasil yang benar sebelum modul honor dikembangkan.

### BR-02 — Sumber jam mengajar
Tentukan apakah jam dihitung dari durasi jadwal, absensi `Hadir`, input manual, atau kombinasi. Definisikan penanganan pembatalan, ketidakhadiran, keterlambatan, dan sesi yang melewati tengah malam (jika mungkin).

### BR-03 — Status pembayaran
Bedakan status dokumen honor (`Belum Dibayar`, `Diproses`, `Dibayar`) dengan status transaksi (`Pending`, `Berhasil`, `Gagal`). Tentukan aturan untuk pembayaran parsial, gagal, retry, dan pembatalan.

### BR-04 — Bentrok jadwal
Tentukan apakah tutor tidak boleh mengajar dua jadwal yang waktunya bertumpuk dan apakah satu ruangan/sekolah dapat dipakai lebih dari satu jadwal pada waktu yang sama.

### BR-05 — Penghapusan
Untuk data yang sudah dipakai transaksi, gunakan nonaktif/soft delete atau mekanisme koreksi yang menjaga jejak historis.

## 10. Alur pengguna

### 10.1 Login
1. Pengguna membuka halaman login.
2. Mengisi username dan password.
3. Sistem memverifikasi hash password dan status aktif.
4. Sistem membuat sesi dan mengarahkan pengguna ke dashboard sesuai izin.

### 10.2 Jadwal dan absensi
1. Pengguna operasional membuat jadwal.
2. Sistem memvalidasi tutor, sekolah, jam, dan konflik.
3. Setelah sesi berlangsung, pengguna mencatat absensi.
4. Sistem memvalidasi kecocokan tutor dan jadwal.
5. Data tampil dalam rekap absensi.

### 10.3 Honor sampai pembayaran
1. Pengguna berwenang memilih periode dan tutor.
2. Sistem mengambil jam berdasarkan sumber yang disepakati.
3. Sistem menampilkan kalkulasi honor.
4. Pengguna meninjau dan menyimpan honor.
5. Petugas mencatat pembayaran.
6. Status transaksi diperbarui sesuai hasil.
7. Sistem menghitung status honor berdasarkan aturan pelunasan.

### 10.4 Permintaan laporan
1. Pimpinan mengajukan jenis dan periode laporan.
2. Status menjadi Menunggu.
3. Petugas memproses permintaan.
4. Laporan dibuat/ditautkan sesuai rancangan.
5. Status menjadi Selesai.

## 11. Rancangan route awal Laravel

Contoh route konseptual; nama dan metode final mengikuti implementasi:

```text
GET    /login
POST   /login
POST   /logout
GET    /dashboard

Resource /tutor
Resource /sekolah
Resource /jadwal
Resource /absensi
Resource /honor
Resource /pembayaran-honor
Resource /penilaian
Resource /permintaan-laporan

GET    /laporan/tutor
GET    /laporan/absensi
GET    /laporan/jadwal
GET    /laporan/honor
GET    /laporan/operasional
```

Semua route bisnis harus berada di bawah middleware autentikasi dan otorisasi. Route resource bukan berarti semua aktor boleh menjalankan seluruh aksi.

## 12. Rancangan struktur kode

```text
app/
  Http/
    Controllers/
      Auth/
      DashboardController.php
      TutorController.php
      SekolahController.php
      JadwalController.php
      AbsensiController.php
      HonorController.php
      PembayaranHonorController.php
      PenilaianController.php
      PermintaanLaporanController.php
      LaporanController.php
    Requests/
    Middleware/
  Models/
    Petinggi.php
    Tutor.php
    Sekolah.php
    Jadwal.php
    Absensi.php
    Honor.php
    PembayaranHonor.php
    Penilaian.php
    PermintaanLaporan.php
  Policies/
  Services/
    HonorCalculationService.php
    ReportService.php
database/
  migrations/
  seeders/
resources/views/
  layouts/
  auth/
  dashboard/
  tutor/
  sekolah/
  jadwal/
  absensi/
  honor/
  pembayaran-honor/
  penilaian/
  permintaan-laporan/
  laporan/
routes/web.php
tests/
  Feature/
  Unit/
```

## 13. Teknologi dan batasan implementasi

- Laravel 11.
- PHP versi yang kompatibel dengan Laravel 11 (minimum PHP 8.2).
- MySQL 8.
- Blade dengan Bootstrap 5 atau Tailwind CSS (pilih satu).
- Eloquent ORM dan Laravel Form Request.
- PHPUnit atau Pest.
- Ekspor CSV/XLSX/PDF merupakan opsi yang perlu memilih paket dan format.
- Jangan mengandalkan dump SQL sebagai satu-satunya sumber skema jangka panjang; buat migration yang dapat direproduksi.

## 14. Rencana pengujian

| ID | Skenario | Hasil yang diharapkan |
|---|---|---|
| T-01 | Login dengan akun aktif dan password benar | Login berhasil |
| T-02 | Login dengan password salah | Ditolak tanpa membocorkan informasi akun |
| T-03 | Login akun tidak aktif | Ditolak |
| T-04 | Membuat tutor dengan nama kosong | Validasi gagal |
| T-05 | Kapasitas sekolah nol/negatif | Ditolak jika kapasitas diisi |
| T-06 | Jam selesai ≤ jam mulai | Jadwal ditolak |
| T-07 | Jadwal bertabrakan untuk tutor yang sama | Ditolak atau diberi peringatan sesuai keputusan |
| T-08 | Absensi mengacu jadwal milik tutor lain | Ditolak |
| T-09 | Duplikasi absensi pada sesi yang sama | Dicegah sesuai constraint |
| T-10 | Kalkulasi honor dengan fixture resmi | Total tepat sesuai formula yang disahkan |
| T-11 | Pembayaran berjumlah nol/negatif | Ditolak |
| T-12 | Pengguna tanpa izin membuka modul pembayaran | Akses ditolak |
| T-13 | Periode laporan awal setelah akhir | Validasi gagal |
| T-14 | Filter laporan berdasarkan periode | Hanya data periode terpilih yang ditampilkan |
| T-15 | Pembayaran gagal | Tidak dihitung sebagai pembayaran berhasil |
| T-16 | Tutor memiliki riwayat honor lalu dinonaktifkan | Riwayat tetap tersedia |

## 15. Rencana implementasi bertahap

### Fase 0 — Validasi kebutuhan
- Konfirmasi peran dan matriks akses.
- Finalisasi formula honor dan sumber jam.
- Finalisasi jadwal berulang/sekali jalan.
- Tentukan pembayaran parsial dan aturan status.
- Tentukan format ekspor laporan.

### Fase 1 — Fondasi
- Setup Laravel 11, konfigurasi MySQL.
- Migration, model, relasi, seeder aman.
- Login, logout, middleware, policy.
- Layout dan dashboard dasar.

### Fase 2 — Data master dan jadwal
- CRUD tutor dan sekolah.
- CRUD jadwal dan validasi konflik.
- Testing feature.

### Fase 3 — Absensi dan penilaian
- Pencatatan absensi dan rekap.
- Penilaian tutor.
- Audit perubahan penting.

### Fase 4 — Honor dan pembayaran
- Kalkulasi honor yang telah disetujui.
- Workflow status honor.
- Pencatatan pembayaran dan rekonsiliasi.

### Fase 5 — Permintaan laporan dan ekspor
- Workflow permintaan.
- Laporan sesuai periode.
- Ekspor sesuai format yang disetujui.

### Fase 6 — UAT dan rilis
- UAT stakeholder.
- Uji keamanan, performa, backup/restore.
- Dokumentasi deployment dan pelatihan pengguna.

## 16. Risiko dan mitigasi

| Risiko | Dampak | Mitigasi |
|---|---|---|
| Formula honor belum jelas | Nilai pembayaran salah | Tahan implementasi kalkulasi final sampai disetujui |
| Password plaintext pada dump | Pengambilalihan akun | Reset kredensial, hash password, jangan deploy data contoh |
| Status honor dan pembayaran berbeda | Rekap tidak konsisten | Definisikan state machine dan sumber status |
| Model jadwal ambigu | Absensi dan jam salah | Putuskan recurring vs one-off |
| Hak akses hanya berdasarkan jabatan teks | Akses tidak konsisten | Policy/permission terpusat |
| Tidak ada tabel siswa | Kebutuhan jumlah siswa terbatas | Konfirmasi scope sebelum menambah entitas |
| Data contoh tidak konsisten | Hasil laporan menyesatkan | Bersihkan/validasi data sebelum migrasi produksi |

## 17. Keputusan terbuka sebelum development

1. Apakah aplikasi hanya untuk pimpinan atau tutor juga akan login?
2. Apa matriks akses pasti untuk CO Founder, Operasional, dan Human Resource?
3. Apa formula honor resmi, termasuk penyesuaian dan jumlah siswa?
4. Bagaimana sumber dan pembulatan total jam mengajar?
5. Apakah honor dapat dibayar sebagian atau dicicil?
6. Kapan status honor menjadi `Dibayar`?
7. Apakah jadwal merupakan jadwal mingguan berulang atau tanggal tertentu?
8. Apakah absensi dapat dibuat tanpa jadwal?
9. Apakah sekolah/ruangan memiliki konflik pemakaian yang harus diblokir?
10. Apakah laporan perlu diekspor ke Excel/PDF?
11. Apakah perlu tabel siswa, kelas, mata pelajaran, dan penugasan tutor?
12. Apa kebijakan audit, backup, retensi data, dan penghapusan?
13. Apakah perlu notifikasi email/WhatsApp?
14. Apakah data SQL contoh akan dimigrasikan atau hanya dijadikan referensi?

## 18. Definisi selesai (Definition of Done)

Fitur dianggap selesai jika:
- kebutuhan dan kriteria penerimaan terpenuhi;
- validasi dan otorisasi backend diterapkan;
- ada automated test untuk alur utama dan kasus gagal;
- migration dapat dijalankan dari database kosong;
- tidak ada rahasia atau password plaintext dalam source control;
- UI memiliki state kosong, loading, sukses, dan error yang sesuai;
- dokumentasi penggunaan dan deployment diperbarui;
- stakeholder menyetujui hasil UAT.

## 19. Lampiran — catatan analisis SQL sumber

SQL menyediakan sembilan tabel: `absensi`, `honor`, `jadwal`, `pembayaran_honor`, `penilaian`, `permintaan_laporan`, `petinggi`, `sekolah`, dan `tutor`. Foreign key utama sudah didefinisikan. Namun, dump tidak mendefinisikan tabel siswa, role/permission, audit log, atau konfigurasi honor.

Data contoh menyimpan nilai password akun `petinggi` dalam bentuk teks biasa. Data contoh honor dan pembayaran juga memperlihatkan nilai yang tidak cukup untuk menyimpulkan formula bisnis final. Karena itu, dokumen ini tidak menganggap formula atau aturan pembayaran tertentu sudah resmi.
