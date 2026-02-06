# API Login Pegawai - Sistem Presensi Android

## Ringkasan

API login pegawai telah berhasil dibuat untuk digunakan dengan aplikasi presensi berbasis Flutter di Android.

## Fitur yang Telah Dibuat

### Authentication API

### 1. API Login Pegawai
- Endpoint: `POST /api/v1/pegawai/login`
- Fungsi: Autentikasi pegawai dan pengambilan data profil
- Validasi: Username dan password
- Keamanan: Hanya pegawai yang bisa login melalui API ini

### 2. API Get Profile Pegawai
- Endpoint: `GET /api/v1/pegawai/profile?username={username}`
- Fungsi: Mengambil data lengkap pegawai beserta lokasi yang ditugaskan
- Data: User, Pegawai, SKPD, dan Lokasi presensi

### 3. API Logout Pegawai
- Endpoint: `POST /api/v1/pegawai/logout`
- Fungsi: Logout dari aplikasi mobile

### Presensi API (Attendance)

### 4. API Check-in Presensi (Datang)
- Endpoint: `POST /api/v1/presensi/checkin`
- Fungsi: Mencatat waktu kedatangan pegawai
- Validasi:
  - Username pegawai
  - Koordinat GPS (latitude, longitude)
  - Lokasi ID yang ditugaskan
  - Jarak pegawai dari lokasi (menggunakan radius)
  - Belum check-in hari ini
- Fitur: Validasi lokasi GPS dengan rumus Haversine

### 5. API Check-out Presensi (Pulang)
- Endpoint: `POST /api/v1/presensi/checkout`
- Fungsi: Mencatat waktu kepulangan pegawai
- Validasi:
  - Username pegawai
  - Koordinat GPS (latitude, longitude)
  - Sudah check-in hari ini
  - Belum check-out hari ini
- Fitur: Menghitung durasi kerja otomatis

### 6. API Riwayat Presensi
- Endpoint: `GET /api/v1/presensi/history?username={username}&month={month}&year={year}`
- Fungsi: Mengambil riwayat presensi pegawai
- Filter:
  - Filter berdasarkan bulan (opsional)
  - Filter berdasarkan tahun (opsional)
- Data: Tanggal, jam datang, jam pulang, durasi kerja, lokasi

### 7. API Status Presensi Hari Ini
- Endpoint: `GET /api/v1/presensi/today-status?username={username}`
- Fungsi: Mengecek status presensi hari ini
- Status yang mungkin:
  - `belum_checkin` - Belum check-in
  - `sudah_checkin` - Sudah check-in, belum check-out
  - `sudah_checkout` - Sudah check-in dan check-out

## Struktur Data yang Dikembalikan

### Response Login Sukses
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "Nama Pegawai",
      "username": "username",
      "role": "pegawai"
    },
    "pegawai": {
      "id": 1,
      "nik": "1234567890123456",
      "nama": "Nama Pegawai",
      "tgl_lahir": "1990-01-01",
      "jkel": "L",
      "telp": "081234567890",
      "alamat": "Alamat lengkap",
      "skpd_id": 1
    },
    "skpd": {
      "id": 1,
      "nama": "Nama SKPD"
    }
  }
}
```

### Response Profile (Termasuk Lokasi)
```json
{
  "success": true,
  "message": "Data pegawai berhasil diambil",
  "data": {
    "user": {...},
    "pegawai": {...},
    "skpd": {...},
    "lokasis": [
      {
        "id": 1,
        "nama": "Kantor Dinas",
        "latitude": -3.588886,
        "longitude": 119.494444,
        "radius": 100
      }
    ]
  }
}
```

## File yang Dibuat/Diubah

### 1. Controller
- `app/Http/Controllers/AuthController.php`
  - Menambahkan method: `apiLogin()`, `apiLogout()`, `apiProfile()`

### 2. Routes
- `routes/api.php` (baru) - Mendefinisikan semua route API
- `bootstrap/app.php` - Mendaftarkan file routes/api.php

### 3. Dokumentasi
- `API_Documentation.md` - Dokumentasi lengkap API dengan contoh Flutter
- `API_README.md` - Ringkasan implementasi (file ini)
- `test_api_login.sh` - Script bash untuk testing API

## Cara Menggunakan API

### 1. Login Pegawai
```bash
curl -X POST http://your-domain.com/api/v1/pegawai/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "username": "username_pegawai",
    "password": "password_pegawai"
  }'
```

### 2. Ambil Profile Pegawai
```bash
curl -X GET "http://your-domain.com/api/v1/pegawai/profile?username=username_pegawai" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

### 3. Logout Pegawai
```bash
curl -X POST http://your-domain.com/api/v1/pegawai/logout \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

## Testing API

### Menggunakan Script Test
```bash
# Berikan izin eksekusi
chmod +x test_api_login.sh

# Jalankan script test
./test_api_login.sh
```

### Menggunakan Postman/Thunder Client
1. Import dokumentasi dari `API_Documentation.md`
2. Set base URL: `http://your-domain.com/api/v1`
3. Test endpoint satu per satu

## Integrasi dengan Flutter

Contoh implementasi login di Flutter:

```dart
Future<Map<String, dynamic>> loginPegawai(String username, String password) async {
  final response = await http.post(
    Uri.parse('http://your-domain.com/api/v1/pegawai/login'),
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    body: jsonEncode({
      'username': username,
      'password': password,
    }),
  );

  if (response.statusCode == 200) {
    return jsonDecode(response.body);
  } else {
    throw Exception('Login failed: ${response.body}');
  }
}
```

## Rekomendasi untuk Production

### 1. Token-Based Authentication
Untuk keamanan yang lebih baik, implementasikan Laravel Sanctum:
```bash
composer require laravel/sanctum
```

### 2. Rate Limiting
Tambahkan rate limiting untuk mencegah brute force attack:
```php
Route::middleware('throttle:5,1')->post('/pegawai/login', ...);
```

### 3. HTTPS
Selalu gunakan HTTPS di production untuk enkripsi data.

### 4. Logging
Tambahkan logging untuk semua percobaan login.

### 5. Password Reset
Implementasikan fitur lupa password.

## Status API

### Authentication API
| Endpoint | Method | Status |
|----------|--------|--------|
| `/api/v1/pegawai/login` | POST | ✅ Ready |
| `/api/v1/pegawai/profile` | GET | ✅ Ready |
| `/api/v1/pegawai/logout` | POST | ✅ Ready |

### Presensi API
| Endpoint | Method | Status |
|----------|--------|--------|
| `/api/v1/presensi/checkin` | POST | ✅ Ready |
| `/api/v1/presensi/checkout` | POST | ✅ Ready |
| `/api/v1/presensi/history` | GET | ✅ Ready |
| `/api/v1/presensi/today-status` | GET | ✅ Ready |

## Masalah yang Diketahui

### Saat Ini:
- Menggunakan username sebagai parameter untuk profile endpoint (seharusnya token-based di production)
- Tidak ada token generation untuk session management
- Tidak ada refresh token mechanism

### Solusi yang Disarankan:
1. Install Laravel Sanctum untuk token-based authentication
2. Tambahkan `api_token` column di tabel users
3. Implementasikan token refresh mechanism
4. Tambahkan middleware untuk validasi token pada protected routes

## Langkah Selanjutnya untuk Pengembangan Lanjutan

API presensi telah siap digunakan. Untuk pengembangan lebih lanjut:

1. **Token-Based Authentication**
   - Implementasikan Laravel Sanctum
   - Gunakan token untuk validasi request
   - Refresh token mechanism

2. **Upload Foto Presensi**
   - Upload foto saat check-in/check-out
   - Validasi waktu foto (anti-cheating)
   - Compress images sebelum upload

3. **Notifikasi**
   - Push notification untuk reminder presensi
   - Notifikasi saat check-in/check-out berhasil
   - Reminder jika belum check-in pada jam tertentu

4. **Offline Mode**
   - Simpan data presensi secara lokal
   - Sync ke server saat koneksi tersedia
   - Handle network errors gracefully

5. **Laporan dan Statistik**
   - Statistik kehadiran bulanan
   - Grafik presensi
   - Export data ke PDF/Excel

## Dokumentasi Tambahan

- **Dokumentasi Lengkap Presensi API:** `PRESENSI_API_Documentation.md`
- **Script Testing Presensi:** `test_presensi_api.sh`
- **Dokumentasi API Login:** `API_Documentation.md`

## Cara Testing Presensi API

### Menggunakan Script Test
```bash
# Berikan izin eksekusi
chmod +x test_presensi_api.sh

# Jalankan script test presensi
./test_presensi_api.sh
```

Script ini akan menguji semua endpoint presensi:
1. Login pegawai
2. Cek status presensi hari ini (sebelum check-in)
3. Check-in presensi
4. Cek status presensi hari ini (setelah check-in)
5. Check-out presensi
6. Cek status presensi hari ini (setelah check-out)
7. Ambil riwayat presensi
8. Ambil riwayat dengan filter bulan/tahun
9. Test error handling (check-in ganda, check-out ganda)

## Dukungan

Untuk pertanyaan atau masalah, hubungi tim development.

---

**Dibuat:** 6 Februari 2026  
**Versi:** 1.0.0  
**Framework:** Laravel 11