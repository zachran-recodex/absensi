# Dokumentasi Fitur Lokasi Absen

## Deskripsi
Fitur ini memungkinkan admin untuk mengatur lokasi absen yang spesifik untuk setiap karyawan menggunakan peta interaktif. Setiap karyawan dapat memiliki lokasi absen yang berbeda dengan radius tertentu yang ditentukan oleh admin.

## Komponen Utama

### 1. Database
Perubahan pada struktur database:
- Tabel `users` ditambahkan kolom:
  - `location_latitude` (DECIMAL(9,6)): Koordinat latitude lokasi absen
  - `location_longitude` (DECIMAL(9,6)): Koordinat longitude lokasi absen
  - `location_radius` (INT): Radius dalam meter untuk validasi jarak absensi

### 2. Model
- `Location_model.php`: Menangani operasi database terkait lokasi absen
  - Menyimpan/memperbarui lokasi absen karyawan
  - Mengambil data lokasi absen karyawan
  - Mendapatkan daftar karyawan untuk pengaturan lokasi

### 3. Controller
- `Location.php`: Menangani request terkait pengaturan lokasi
  - Menampilkan halaman pengaturan lokasi
  - Menyimpan data lokasi absen
  - Mengambil data lokasi untuk karyawan tertentu
- `Absen.php` (diperbarui): Menggunakan lokasi yang telah ditentukan untuk validasi absensi

### 4. View
- `location/index.php`: Halaman pengaturan lokasi dengan peta interaktif menggunakan Leaflet JS

## Cara Penggunaan

### Untuk Admin:
1. Login sebagai admin
2. Klik menu "Lokasi Absen" pada sidebar
3. Pilih karyawan dari daftar di sebelah kiri
4. Klik pada peta untuk menentukan lokasi absen atau geser marker untuk menyesuaikan lokasi
5. Atur radius (dalam meter) yang diizinkan untuk absensi
6. Klik tombol "Simpan Lokasi"

### Untuk Karyawan:
1. Saat melakukan absensi, sistem akan memvalidasi lokasi karyawan
2. Jika karyawan berada di luar radius yang ditentukan, absensi akan ditolak
3. Jika lokasi belum diatur oleh admin, sistem akan menggunakan lokasi default

## Teknologi yang Digunakan
- Leaflet JS: Library JavaScript untuk menampilkan peta interaktif
- AJAX: Untuk komunikasi asinkron dengan server saat menyimpan data lokasi
- PHP/CodeIgniter: Framework backend untuk menangani logika aplikasi
- MySQL: Database untuk menyimpan data lokasi

## Catatan Implementasi
- Fitur ini hanya dapat diakses oleh pengguna dengan role "Admin"
- Radius default adalah 100 meter jika tidak ditentukan
- Lokasi default digunakan jika admin belum mengatur lokasi spesifik untuk karyawan
- Peta menggunakan OpenStreetMap sebagai provider tile
