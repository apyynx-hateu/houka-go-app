# Product Requirements Document (PRD)
## ERP Houka GO – Revisi Role, Modul, dan Perhitungan Honor

**Versi:** 1.1  
**Status:** Draft implementasi  
**Framework:** Laravel 11  
**Database:** MySQL

## 1. Ringkasan Produk
ERP Houka GO adalah aplikasi web untuk pengelolaan tutor, sekolah, jadwal, absensi, honor, pembayaran, dan penilaian. Sistem memiliki dua role, yaitu ADMIN (Petinggi) dan TUTOR (Pengajar), dengan satu halaman login dan dashboard berbeda sesuai role.

## 2. Tujuan
- Memusatkan data tutor, sekolah, jadwal, absensi, honor, dan penilaian.
- Mengurangi kesalahan penghitungan honor.
- Memisahkan akses administratif dari akses tutor.
- Memberi tutor akses ke jadwal, absensi, profil, dan honor miliknya sendiri.

## 3. Role dan akses

### 3.1 ADMIN (Petinggi)
ADMIN dapat mengakses:
1. Dashboard Petinggi
2. Data Tutor
3. Data Sekolah
4. Jadwal Tutor
5. Rekap Absensi Tutor
6. Pembayaran Honor
7. Penilaian Tutor
8. Profil Petinggi

ADMIN mengelola data operasional sesuai kewenangannya. Data yang telah terkait dengan riwayat absensi, honor, pembayaran, atau penilaian sebaiknya dinonaktifkan atau dibatasi penghapusannya agar integritas data tetap terjaga.

### 3.2 TUTOR (Pengajar)
TUTOR dapat mengakses:
1. Dashboard Tutor
2. Profil Tutor
3. Jadwal Tutor
4. Absensi Tutor
5. Honor Tutor

TUTOR hanya boleh melihat atau memperbarui data pribadi yang diizinkan, jadwal yang ditugaskan kepadanya, absensinya sendiri, dan rincian honornya sendiri. TUTOR tidak boleh mengakses data tutor lain atau halaman ADMIN.

## 4. Login dan pengalihan role
1. Pengguna membuka satu halaman login.
2. Pengguna memasukkan kredensial.
3. Sistem memvalidasi kredensial dan menentukan role.
4. ADMIN diarahkan ke Dashboard Petinggi.
5. TUTOR diarahkan ke Dashboard Tutor.
6. Route internal memerlukan autentikasi dan otorisasi role.
7. Logout mengakhiri sesi.

**Keputusan teknis penting:** tabel `petinggi` dan `tutor` terpisah tidak otomatis bisa memakai satu autentikasi Laravel standar. Pilihan yang disarankan adalah tabel `users` pusat berisi kredensial dan role (`ADMIN`/`TUTOR`), dengan profil yang dihubungkan ke tabel `petinggi` atau `tutor`. Alternatifnya adalah multi-guard/multi-provider yang dikonfigurasi dan diuji secara eksplisit. Jangan membuat pencarian username/password ambigu di dua tabel.

## 5. Ruang lingkup

### Termasuk
- Login bersama dan pengalihan berdasarkan role.
- Dashboard ADMIN dan TUTOR.
- CRUD data tutor dan sekolah.
- Pengelolaan jadwal.
- Rekap dan akses absensi.
- Perhitungan serta pembayaran honor.
- Penilaian tutor.
- Profil petinggi dan tutor.
- Validasi data dan pembatasan akses berbasis role.

### Tidak termasuk
- Tabel/fitur `permintaan_laporan`.
- Login siswa atau sekolah.
- Pembayaran siswa.
- Integrasi bank/payroll otomatis.
- Notifikasi otomatis atau aplikasi mobile native, kecuali ditambahkan pada revisi berikutnya.

## 6. Kebutuhan fungsional

### FR-01 Login bersama
Satu form login menerima kedua role. Kredensial valid mengarahkan ADMIN ke Dashboard Petinggi dan TUTOR ke Dashboard Tutor. Kredensial tidak valid ditolak.

### FR-02 Otorisasi role
ADMIN dapat membuka modul administratif. TUTOR ditolak saat membuka route ADMIN dan hanya dapat mengakses data yang berhubungan dengan akun tutor miliknya. Pemeriksaan dilakukan di server, bukan hanya pada tampilan.

### FR-03 Dashboard Petinggi
Menampilkan ringkasan aktual seperti jumlah tutor aktif, sekolah, jadwal, absensi, dan ringkasan honor/pembayaran.

### FR-04 Data Tutor
ADMIN dapat menambah, melihat, mengubah, mencari, dan menonaktifkan data tutor sesuai kolom tabel `tutor`.

### FR-05 Data Sekolah
ADMIN dapat menambah, melihat, mengubah, dan mencari data sekolah.

### FR-06 Jadwal Tutor
ADMIN dapat mengelola jadwal yang menghubungkan tutor dan sekolah valid. Tutor hanya dapat melihat jadwal yang ditugaskan kepadanya.

### FR-07 Rekap Absensi ADMIN
ADMIN dapat melihat dan mengelola absensi berdasarkan tutor, jadwal, tanggal, status, dan keterangan. Skema saat ini hanya mendukung `Hadir` dan `Tidak Hadir`; `Izin` dan `Sakit` memerlukan perubahan enum/database.

### FR-08 Absensi TUTOR
TUTOR dapat melihat riwayat absensinya. Jika tutor diperbolehkan mencatat kehadiran, server harus memastikan jadwal tersebut ditugaskan kepada tutor yang sedang login. Mekanisme detailnya belum dipastikan: input status hadir atau check-in/check-out. Kolom waktu check-in/check-out belum tersedia pada skema saat ini.

### FR-09 Perhitungan honor
Rumus yang digunakan:
**Total Honor = (Jumlah Sesi × Rp50.000) + Rp20.000 + Rp30.000**

Komponen:
- Tarif per sesi: Rp50.000
- Fee check-in: Rp20.000
- Fee transport: Rp30.000

Contoh 8 sesi:
- Honor sesi: 8 × Rp50.000 = Rp400.000
- Fee check-in: Rp20.000
- Fee transport: Rp30.000
- Total: **Rp450.000**

Asumsi sementara: dua fee tetap dibayarkan satu kali per periode honor. Jumlah sesi harus berasal dari data yang disepakati, idealnya sesi yang tervalidasi dari jadwal/absensi. Sesi batal dan ketidakhadiran harus memiliki aturan eksplisit.

**Perubahan skema wajib:** kolom `tarif_per_jam` dan `total_jam_mengajar` tidak mewakili rumus berbasis sesi. Revisi tabel `honor` agar menyimpan `jumlah_sesi`, `tarif_per_sesi`, `fee_check_in`, `fee_transport`, dan `total_honor` (atau nama kolom setara). Jangan menyimpan jumlah sesi pada kolom jam mengajar.

### FR-10 Pembayaran Honor
ADMIN dapat mencatat pembayaran dan memperbarui status transaksi (`Pending`, `Berhasil`, `Gagal`). Status honor (`Belum Dibayar`, `Diproses`, `Dibayar`) harus mengikuti aturan sinkronisasi yang konsisten. Transaksi gagal tidak boleh menandai honor sebagai sudah dibayar. Tentukan apakah pembayaran parsial diperbolehkan.

### FR-11 Honor TUTOR
TUTOR dapat melihat periode, jumlah sesi, rincian tarif/fee, total honor, dan status pembayaran miliknya sendiri.

### FR-12 Penilaian Tutor
ADMIN dapat mencatat tanggal, periode, kualitas mengajar, dan catatan kinerja. Skala nilai numerik belum ditentukan pada skema saat ini.

### FR-13 Profil
ADMIN dapat memperbarui profil petinggi yang diizinkan. TUTOR dapat memperbarui data pribadi yang diizinkan, tetapi tidak dapat mengubah status kerja atau data administratif sensitif.

### FR-14 Validasi
Semua input divalidasi di server, termasuk foreign key, tanggal, waktu, nilai numerik, periode, dan status.

### FR-15 Penghapusan permintaan laporan
Tabel `permintaan_laporan` dan seluruh fitur terkait tidak dibuat. Hapus referensinya dari migration, model, controller, route, menu, dan query aplikasi.

## 7. Struktur database setelah revisi
Gunakan delapan tabel:
1. `petinggi`: data ADMIN/petinggi.
2. `tutor`: profil pengajar.
3. `sekolah`: data sekolah dan ruangan.
4. `jadwal`: penugasan tutor ke sekolah beserta hari/jam.
5. `absensi`: status kehadiran tutor.
6. `honor`: ringkasan honor per tutor dan periode; perlu penyesuaian untuk rumus berbasis sesi.
7. `pembayaran_honor`: transaksi pembayaran untuk suatu data honor.
8. `penilaian`: penilaian tutor oleh petinggi.

### Relasi
- `jadwal.id_tutor` → `tutor.id_tutor`
- `jadwal.id_sekolah` → `sekolah.id_sekolah`
- `absensi.id_tutor` → `tutor.id_tutor`
- `absensi.id_jadwal` → `jadwal.id_jadwal` (nullable)
- `honor.id_tutor` → `tutor.id_tutor`
- `pembayaran_honor.id_honor` → `honor.id_honor`
- `penilaian.id_tutor` → `tutor.id_tutor`
- `penilaian.id_petinggi` → `petinggi.id_petinggi`

## 8. Aturan bisnis
1. Hanya ada role ADMIN dan TUTOR.
2. Satu halaman login digunakan oleh kedua role.
3. Role menentukan dashboard dan hak akses.
4. Tutor hanya dapat melihat data pribadi, jadwal, absensi, dan honor miliknya.
5. Tarif per sesi Rp50.000.
6. Fee check-in Rp20.000 dan fee transport Rp30.000, sementara diasumsikan sekali per periode.
7. Honor dihitung berdasarkan jumlah sesi yang valid.
8. Status transaksi pembayaran dan status honor harus konsisten.
9. Password disimpan sebagai hash Laravel, bukan plaintext.
10. Data finansial yang telah dibayar tidak boleh diubah tanpa prosedur koreksi yang tercatat.
11. Fitur permintaan laporan tidak termasuk versi ini.

## 9. Kebutuhan nonfungsional
- **Keamanan:** hashing password, CSRF, validasi server-side, middleware role, dan perlindungan akses data lintas pengguna.
- **Integritas data:** foreign key dan transaksi database pada proses pembayaran.
- **Kemudahan penggunaan:** antarmuka responsif dan label berbahasa Indonesia.
- **Pemeliharaan:** pisahkan model, controller, request validation, middleware/policy, service, dan Blade view.
- **Privasi:** tutor hanya melihat informasi keuangan dan absensinya sendiri.

## 10. Rekomendasi struktur route Laravel
- `/login`
- `/admin/dashboard`
- `/admin/tutors`
- `/admin/schools`
- `/admin/schedules`
- `/admin/attendance`
- `/admin/honors`
- `/admin/payments`
- `/admin/evaluations`
- `/admin/profile`
- `/tutor/dashboard`
- `/tutor/profile`
- `/tutor/schedules`
- `/tutor/attendance`
- `/tutor/honors`

Route di atas adalah rekomendasi. Gunakan middleware autentikasi dan role. Model dengan tabel/primary key khusus perlu mengatur `$table` dan `$primaryKey`. Letakkan perhitungan honor pada service agar rumus tidak tersebar di controller.

## 11. Skenario pengujian
- Login ADMIN masuk ke dashboard ADMIN.
- Login TUTOR masuk ke dashboard TUTOR.
- Kredensial salah ditolak.
- TUTOR tidak bisa membuka route ADMIN.
- TUTOR tidak bisa mengakses honor/absensi tutor lain dengan mengganti ID.
- CRUD tutor, sekolah, dan jadwal berjalan dengan validasi.
- Delapan sesi menghasilkan total Rp450.000 dengan asumsi fee tetap per periode.
- Pembayaran gagal tidak mengubah honor menjadi `Dibayar`.
- Tutor hanya melihat data miliknya.
- Tidak ada fitur atau query yang menggunakan `permintaan_laporan`.
- Password tersimpan dalam bentuk hash.

## 12. Keputusan yang harus dituntaskan sebelum coding
1. Pilih tabel `users` pusat atau autentikasi multi-guard.
2. Konfirmasi fee check-in/transport berlaku per periode atau per sesi.
3. Tentukan cara menghitung jumlah sesi dan perlakuan sesi batal/tidak hadir.
4. Tentukan apakah tutor melakukan check-in/check-out atau hanya melihat/mencatat status absensi.
5. Tentukan apakah status `Izin` dan `Sakit` diperlukan.
6. Tentukan apakah pembayaran parsial diizinkan.
7. Tentukan format penilaian tutor.

## 13. Definition of Done
- Seluruh modul scope tersedia.
- Login bersama dan redirect role berfungsi.
- Route dan data dilindungi berdasarkan role.
- Data tutor, sekolah, jadwal, absensi, honor, pembayaran, dan penilaian tersimpan dengan valid.
- Perhitungan honor sesuai rumus yang disepakati.
- Status honor dan pembayaran konsisten.
- Tidak ada akses silang data antar tutor.
- Tabel/fitur `permintaan_laporan` tidak digunakan.
- Seluruh skenario pengujian kritis lulus.
