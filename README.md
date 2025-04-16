# Website GRA (Gray Relational Analysis)
Gray Relational Analysis (GRA) adalah metode yang digunakan untuk menganalisis hubungan antara beberapa alternatif dan kriteria. Metode ini sangat berguna dalam pengambilan keputusan terutama ketika ada banyak alternatif yang harus dipertimbangkan berdasarkan sejumlah kriteria yang berbeda.
Pada proyek ini, GRA diterapkan untuk penentuan penerima beasiswa berdasarkan nilai-nilai yang dimiliki oleh setiap siswa. Proses GRA melibatkan langkah-langkah berikut:
- Normalisasi Data: Setiap nilai yang diperoleh siswa akan dinormalisasi agar berada dalam skala yang sama (misalnya 0 sampai 1).
- Menentukan Nilai Referensi Ideal: Nilai terbaik di antara semua kriteria akan dijadikan referensi atau patokan.
- Menghitung Selisih Absolut: Selisih antara data alternatif dan referensi dihitung untuk menentukan kedekatannya.
- Menghitung Koefisien Relasional: Koefisien ini menggambarkan hubungan antara data alternatif dan referensi.
- Menentukan Derajat Hubungan: Rata-rata koefisien relasional digunakan untuk mendapatkan peringkat dari setiap alternatif.
GRA memungkinkan untuk memperhitungkan berbagai kriteria secara objektif dan menghasilkan peringkat yang bisa dijadikan dasar dalam pengambilan keputusan yang lebih baik.


Sistem Pendukung Keputusan untuk seleksi beasiswa menggunakan metode Gray Relational Analysis (GRA).

## Fitur
- Upload data siswa
- Hitung nilai GRA otomatis
- Export hasil ke pdf
- Login Admin & Dashboard

## Teknologi yang Digunakan
- HTML, CSS
- PHP + MySQL

## Cara Menjalankan
1. Clone repository ini
2. Import database dari folder `db`
3. Jalankan di server lokal (XAMPP/Laragon)
4. Login dengan akun admin (default: `admin / admin123`)

## Lisensi
Hak Cipta © 2025 muhammd zaeid anwar haq
Kode ini boleh digunakan untuk keperluan pribadi dan pembelajaran.
Tidak diperbolehkan digunakan untuk keperluan komersial, dijual, atau disebarkan ulang tanpa izin tertulis dari pemilik.

Silakan hubungi muhammadzaeid02@gmail.com untuk kerja sama atau pertanyaan lisensi.
