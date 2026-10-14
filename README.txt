BANK SAMPAH AVIN - WEBSITE PHP

1. Import database:
   Import bank_sampah_avin_lengkap.sql ke phpMyAdmin.

2. Letakkan folder:
   C:\xampp\htdocs\bank_sampah_avin_web

3. Jalankan Apache dan MySQL di XAMPP.

4. Buka:
   http://localhost/bank_sampah_avin_web/

AKUN:
Admin     : admin / admin123
Siswa     : siswa / siswa123
Guru      : guru / guru123
Pengepul  : pengepul / pengepul123

FITUR:
- Login sesuai role
- Dashboard
- Siswa/Guru: setor sampah dan tarik saldo
- Admin: kelola user, jenis sampah, verifikasi
- Pengepul: pencatatan setoran pengepul
- Riwayat transaksi

Catatan: Password contoh masih plaintext agar langsung cocok dengan database latihan.
Untuk proyek produksi, gunakan password_hash().
