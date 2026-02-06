# API Documentation - Presensi Pegawai (Attendance)

## Overview
Dokumentasi ini menjelaskan API untuk sistem presensi pegawai yang digunakan bersama dengan aplikasi mobile berbasis Flutter untuk mencatat kehadiran datang dan pulang.

## Base URL
```
http://your-domain.com/api/v1
```

## Authentication
Saat ini menggunakan username sebagai identifikasi. Untuk production, disarankan untuk mengimplementasikan token-based authentication menggunakan Laravel Sanctum.

---

## Endpoints

### 1. Check-in Presensi (Datang)

Mencatat waktu kedatangan pegawai dengan validasi lokasi GPS.

**Endpoint:** `POST /api/v1/presensi/checkin`

**Request Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "username": "pegawai_username",
  "latitude": -3.588886,
  "longitude": 119.494444,
  "lokasi_id": 1
}
```

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| username | string | Yes | Username pegawai yang login |
| latitude | float | Yes | Koordinat GPS latitude saat check-in |
| longitude | float | Yes | Koordinat GPS longitude saat check-in |
| lokasi_id | integer | Yes | ID lokasi tempat presensi |

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Check-in berhasil",
  "data": {
    "presensi_id": 1,
    "tanggal": "2026-02-06",
    "jam_datang": "08:00:00",
    "lokasi": {
      "id": 1,
      "nama": "Kantor Dinas",
      "latitude": -3.588886,
      "longitude": 119.494444
    },
    "distance_from_location": "15.5 meter"
  }
}
```

**Error Responses:**

422 Unprocessable Entity (Validation error):
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "username": ["Username harus diisi"],
    "latitude": ["Latitude harus berupa angka"]
  }
}
```

404 Not Found (Pegawai tidak ditemukan):
```json
{
  "success": false,
  "message": "Pegawai tidak ditemukan"
}
```

403 Forbidden (Lokasi tidak ditugaskan):
```json
{
  "success": false,
  "message": "Anda tidak ditugaskan di lokasi ini"
}
```

403 Forbidden (Di luar radius):
```json
{
  "success": false,
  "message": "Anda berada di luar radius lokasi presensi",
  "data": {
    "distance": 150.5,
    "radius": 100,
    "distance_outside": 50.5
  }
}
```

400 Bad Request (Sudah check-in):
```json
{
  "success": false,
  "message": "Anda sudah check-in hari ini. Silakan check-out terlebih dahulu.",
  "data": {
    "jam_datang": "08:00:00"
  }
}
```

**Example cURL Request:**
```bash
curl -X POST http://your-domain.com/api/v1/presensi/checkin \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "username": "johndoe",
    "latitude": -3.588886,
    "longitude": 119.494444,
    "lokasi_id": 1
  }'
```

**Flutter Example:**
```dart
Future<Map<String, dynamic>> checkInPresensi(
  String username,
  double latitude,
  double longitude,
  int lokasiId,
) async {
  final response = await http.post(
    Uri.parse('http://your-domain.com/api/v1/presensi/checkin'),
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    body: jsonEncode({
      'username': username,
      'latitude': latitude,
      'longitude': longitude,
      'lokasi_id': lokasiId,
    }),
  );

  if (response.statusCode == 200) {
    return jsonDecode(response.body);
  } else {
    throw Exception('Check-in failed: ${response.body}');
  }
}
```

---

### 2. Check-out Presensi (Pulang)

Mencatat waktu kepulangan pegawai dengan validasi lokasi GPS.

**Endpoint:** `POST /api/v1/presensi/checkout`

**Request Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "username": "pegawai_username",
  "latitude": -3.588886,
  "longitude": 119.494444
}
```

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| username | string | Yes | Username pegawai yang login |
| latitude | float | Yes | Koordinat GPS latitude saat check-out |
| longitude | float | Yes | Koordinat GPS longitude saat check-out |

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Check-out berhasil",
  "data": {
    "presensi_id": 1,
    "tanggal": "2026-02-06",
    "jam_datang": "08:00:00",
    "jam_pulang": "17:00:00",
    "durasi_kerja": {
      "jam": 9,
      "menit": 0,
      "total": "9 jam 0 menit"
    },
    "lokasi": {
      "id": 1,
      "nama": "Kantor Dinas"
    },
    "distance_from_location": "12.3 meter"
  }
}
```

**Error Responses:**

400 Bad Request (Belum check-in):
```json
{
  "success": false,
  "message": "Anda belum check-in hari ini"
}
```

400 Bad Request (Sudah check-out):
```json
{
  "success": false,
  "message": "Anda sudah check-out hari ini",
  "data": {
    "jam_pulang": "17:00:00"
  }
}
```

**Example cURL Request:**
```bash
curl -X POST http://your-domain.com/api/v1/presensi/checkout \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "username": "johndoe",
    "latitude": -3.588886,
    "longitude": 119.494444
  }'
```

**Flutter Example:**
```dart
Future<Map<String, dynamic>> checkOutPresensi(
  String username,
  double latitude,
  double longitude,
) async {
  final response = await http.post(
    Uri.parse('http://your-domain.com/api/v1/presensi/checkout'),
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    body: jsonEncode({
      'username': username,
      'latitude': latitude,
      'longitude': longitude,
    }),
  );

  if (response.statusCode == 200) {
    return jsonDecode(response.body);
  } else {
    throw Exception('Check-out failed: ${response.body}');
  }
}
```

---

### 3. Get Riwayat Presensi

Mengambil riwayat presensi pegawai dengan filter bulan dan tahun.

**Endpoint:** `GET /api/v1/presensi/history`

**Request Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| username | string | Yes | Username pegawai |
| month | integer | No | Filter bulan (1-12) |
| year | integer | No | Filter tahun (contoh: 2026) |

**Example URL:**
```
GET /api/v1/presensi/history?username=johndoe&month=2&year=2026
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Riwayat presensi berhasil diambil",
  "data": {
    "pegawai": {
      "nik": "1234567890123456",
      "nama": "John Doe"
    },
    "filter": {
      "month": 2,
      "year": 2026
    },
    "presensi_count": 20,
    "presensi_list": [
      {
        "id": 1,
        "tanggal": "2026-02-06",
        "jam_datang": "08:00:00",
        "jam_pulang": "17:00:00",
        "durasi_kerja": {
          "jam": 9,
          "menit": 0,
          "total": "9 jam 0 menit"
        },
        "lokasi": {
          "id": 1,
          "nama": "Kantor Dinas"
        },
        "skpd_id": 1
      }
    ]
  }
}
```

**Example cURL Request:**
```bash
curl -X GET "http://your-domain.com/api/v1/presensi/history?username=johndoe&month=2&year=2026" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

**Flutter Example:**
```dart
Future<Map<String, dynamic>> getPresensiHistory(
  String username, {
  int? month,
  int? year,
}) async {
  var queryParams = {'username': username};
  if (month != null) queryParams['month'] = month.toString();
  if (year != null) queryParams['year'] = year.toString();

  final uri = Uri.parse('http://your-domain.com/api/v1/presensi/history')
      .replace(queryParameters: queryParams);

  final response = await http.get(
    uri,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
  );

  if (response.statusCode == 200) {
    return jsonDecode(response.body);
  } else {
    throw Exception('Failed to load history: ${response.body}');
  }
}
```

---

### 4. Get Status Presensi Hari Ini

Mengecek status presensi pegawai untuk hari ini.

**Endpoint:** `GET /api/v1/presensi/today-status`

**Request Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| username | string | Yes | Username pegawai |

**Example URL:**
```
GET /api/v1/presensi/today-status?username=johndoe
```

**Success Response (200 OK) - Belum Check-in:**
```json
{
  "success": true,
  "message": "Status presensi hari ini",
  "data": {
    "tanggal": "2026-02-06",
    "status": "belum_checkin",
    "message": "Anda belum check-in hari ini",
    "presensi": null
  }
}
```

**Success Response (200 OK) - Sudah Check-in:**
```json
{
  "success": true,
  "message": "Status presensi hari ini",
  "data": {
    "tanggal": "2026-02-06",
    "status": "sudah_checkin",
    "message": "Anda sudah check-in hari ini. Silakan check-out.",
    "presensi": {
      "id": 1,
      "jam_datang": "08:00:00",
      "jam_pulang": null,
      "lokasi": {
        "id": 1,
        "nama": "Kantor Dinas"
      }
    }
  }
}
```

**Success Response (200 OK) - Sudah Check-out:**
```json
{
  "success": true,
  "message": "Status presensi hari ini",
  "data": {
    "tanggal": "2026-02-06",
    "status": "sudah_checkout",
    "message": "Anda sudah menyelesaikan presensi hari ini.",
    "presensi": {
      "id": 1,
      "jam_datang": "08:00:00",
      "jam_pulang": "17:00:00",
      "lokasi": {
        "id": 1,
        "nama": "Kantor Dinas"
      }
    }
  }
}
```

**Example cURL Request:**
```bash
curl -X GET "http://your-domain.com/api/v1/presensi/today-status?username=johndoe" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

**Flutter Example:**
```dart
Future<Map<String, dynamic>> getTodayStatus(String username) async {
  final response = await http.get(
    Uri.parse('http://your-domain.com/api/v1/presensi/today-status?username=$username'),
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
  );

  if (response.statusCode == 200) {
    return jsonDecode(response.body);
  } else {
    throw Exception('Failed to load status: ${response.body}');
  }
}
```

### 5. Get Lokasi Absensi yang Dimiliki Pegawai

Mengambil daftar lokasi absensi yang ditugaskan kepada pegawai. Endpoint ini digunakan untuk menampilkan pilihan lokasi saat pegawai melakukan check-in.

**Endpoint:** `GET /api/v1/presensi/lokasi`

**Request Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| username | string | Yes | Username pegawai |

**Example URL:**
```
GET /api/v1/presensi/lokasi?username=johndoe
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Daftar lokasi absensi berhasil diambil",
  "data": {
    "pegawai": {
      "nik": "1234567890123456",
      "nama": "John Doe"
    },
    "lokasi_count": 2,
    "lokasi_list": [
      {
        "id": 1,
        "nama": "Kantor Dinas",
        "latitude": -3.588886,
        "longitude": 119.494444,
        "radius": 100,
        "radius_in_meters": "100 meter",
        "skpd_id": 1
      },
      {
        "id": 2,
        "nama": "Kantor Cabang",
        "latitude": -3.590000,
        "longitude": 119.500000,
        "radius": 50,
        "radius_in_meters": "50 meter",
        "skpd_id": 1
      }
    ]
  }
}
```

**Success Response (200 OK) - Tidak ada lokasi:**
```json
{
  "success": true,
  "message": "Daftar lokasi absensi berhasil diambil",
  "data": {
    "pegawai": {
      "nik": "1234567890123456",
      "nama": "John Doe"
    },
    "lokasi_count": 0,
    "lokasi_list": []
  }
}
```

**Error Responses:**

422 Unprocessable Entity (Validation error):
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "username": ["Username harus diisi"]
  }
}
```

404 Not Found (Pegawai tidak ditemukan):
```json
{
  "success": false,
  "message": "Pegawai tidak ditemukan"
}
```

**Example cURL Request:**
```bash
curl -X GET "http://your-domain.com/api/v1/presensi/lokasi?username=johndoe" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

**Flutter Example:**
```dart
Future<Map<String, dynamic>> getLokasiPresensi(String username) async {
  final response = await http.get(
    Uri.parse('http://your-domain.com/api/v1/presensi/lokasi?username=$username'),
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
  );

  if (response.statusCode == 200) {
    return jsonDecode(response.body);
  } else {
    throw Exception('Failed to load lokasi: ${response.body}');
  }
}

// Penggunaan dengan Dropdown
class LokasiDropdown extends StatefulWidget {
  final String username;
  
  @override
  _LokasiDropdownState createState() => _LokasiDropdownState();
}

class _LokasiDropdownState extends State<LokasiDropdown> {
  List<Map<String, dynamic>> lokasiList = [];
  int? selectedLokasiId;
  bool isLoading = false;

  @override
  void initState() {
    super.initState();
    _loadLokasi();
  }

  Future<void> _loadLokasi() async {
    setState(() => isLoading = true);
    try {
      final response = await getLokasiPresensi(widget.username);
      setState(() {
        lokasiList = List<Map<String, dynamic>>.from(
          response['data']['lokasi_list']
        );
        isLoading = false;
      });
    } catch (e) {
      setState(() => isLoading = false);
      print('Error loading lokasi: $e');
    }
  }

  @override
  Widget build(BuildContext context) {
    return isLoading
        ? CircularProgressIndicator()
        : DropdownButton<int>(
            value: selectedLokasiId,
            hint: Text('Pilih Lokasi'),
            items: lokasiList.map((lokasi) {
              return DropdownMenuItem<int>(
                value: lokasi['id'],
                child: Text(
                  '${lokasi['nama']} (Radius: ${lokasi['radius']}m)'
                ),
              );
            }).toList(),
            onChanged: (value) {
              setState(() => selectedLokasiId = value);
              // Gunakan selectedLokasiId untuk check-in
            },
          );
  }
}
```

**Use Case:**
1. Load daftar lokasi saat pegawai login atau membuka halaman check-in
2. Tampilkan lokasi dalam dropdown atau list
3. Pegawai memilih lokasi yang sesuai
4. Gunakan `lokasi_id` untuk request check-in

**Integration with Check-in:**
```dart
// Contoh integrasi dengan check-in
Future<void> performCheckIn(String username, int lokasiId) async {
  try {
    // Ambil posisi GPS
    Position position = await getCurrentPosition();
    
    // Lakukan check-in
    final response = await checkInPresensi(
      username,
      position.latitude,
      position.longitude,
      lokasiId,
    );
    
    if (response['success']) {
      print('Check-in berhasil: ${response['data']['jam_datang']}');
    }
  } catch (e) {
    print('Error: $e');
  }
}
```

---

## Validasi Lokasi (GPS)

### Cara Kerja
1. API menerima koordinat GPS dari aplikasi mobile
2. Menghitung jarak antara posisi pegawai dan lokasi yang ditugaskan
3. Menggunakan rumus Haversine untuk perhitungan jarak akurat
4. Membandingkan jarak dengan radius yang diizinkan
5. Hanya mengizinkan check-in/check-out jika berada dalam radius

### Rumus Haversine
```
Jarak = 2 * R * arcsin(√(sin²(Δlat/2) + cos(lat1) * cos(lat2) * sin²(Δlon/2)))

Dimana:
- R = Radius bumi (6,371,000 meter)
- Δlat = selisih latitude (dalam radian)
- Δlon = selisih longitude (dalam radian)
```

### Flutter GPS Example
```dart
Future<Position> getCurrentPosition() async {
  bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
  if (!serviceEnabled) {
    return Future.error('Location services are disabled');
  }

  LocationPermission permission = await Geolocator.checkPermission();
  if (permission == LocationPermission.denied) {
    permission = await Geolocator.requestPermission();
    if (permission == LocationPermission.denied) {
      return Future.error('Location permissions are denied');
    }
  }

  if (permission == LocationPermission.deniedForever) {
    return Future.error('Location permissions are permanently denied');
  }

  return await Geolocator.getCurrentPosition(
    desiredAccuracy: LocationAccuracy.high,
  );
}

// Penggunaan
try {
  Position position = await getCurrentPosition();
  await checkInPresensi(
    username,
    position.latitude,
    position.longitude,
    lokasiId,
  );
} catch (e) {
  print('Error: $e');
}
```

---

## Flow Presensi

### Check-in Flow
1. User membuka aplikasi Flutter
2. Aplikasi mengambil lokasi GPS saat ini
3. User memilih lokasi presensi dari daftar lokasi yang ditugaskan
4. Aplikasi mengirim request ke API dengan:
   - Username
   - Latitude, Longitude dari GPS
   - Lokasi ID
5. API memvalidasi:
   - Pegawai ada dan valid
   - Lokasi valid
   - Pegawai ditugaskan di lokasi tersebut
   - Posisi GPS dalam radius lokasi
   - Belum check-in hari ini
6. API menyimpan data presensi
7. API mengembalikan response sukses

### Check-out Flow
1. User membuka aplikasi Flutter
2. Aplikasi mengambil lokasi GPS saat ini
3. Aplikasi mengirim request ke API dengan:
   - Username
   - Latitude, Longitude dari GPS
4. API memvalidasi:
   - Pegawai ada dan valid
   - Sudah check-in hari ini
   - Belum check-out hari ini
   - Posisi GPS dalam radius lokasi (opsional)
5. API mengupdate data presensi dengan jam pulang
6. API menghitung durasi kerja
7. API mengembalikan response sukses dengan durasi kerja

---

## Error Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 400 | Bad Request - Sudah check-in/checkout, belum check-in |
| 403 | Forbidden - Lokasi tidak ditugaskan, di luar radius |
| 404 | Not Found - Pegawai tidak ditemukan |
| 422 | Unprocessable Entity - Validasi gagal |

---

## Testing

### Menggunakan Postman/Thunder Client

1. **Check-in Test:**
   - Method: POST
   - URL: `http://your-domain.com/api/v1/presensi/checkin`
   - Body (raw JSON):
     ```json
     {
       "username": "test_pegawai",
       "latitude": -3.588886,
       "longitude": 119.494444,
       "lokasi_id": 1
     }
     ```

2. **Check-out Test:**
   - Method: POST
   - URL: `http://your-domain.com/api/v1/presensi/checkout`
   - Body (raw JSON):
     ```json
     {
       "username": "test_pegawai",
       "latitude": -3.588886,
       "longitude": 119.494444
     }
     ```

3. **Get History Test:**
   - Method: GET
   - URL: `http://your-domain.com/api/v1/presensi/history?username=test_pegawai`

4. **Get Today Status Test:**
   - Method: GET
   - URL: `http://your-domain.com/api/v1/presensi/today-status?username=test_pegawai`

---

## Rekomendasi Production

1. **Token-Based Authentication:**
   - Gunakan Laravel Sanctum untuk token
   - Validasi token pada setiap request
   - Refresh token mechanism

2. **Rate Limiting:**
   - Mencegah spam check-in/check-out
   - Batasi request per user per menit

3. **HTTPS:**
   - Wajib gunakan HTTPS di production
   - Enkripsi data GPS dan lokasi

4. **Logging:**
   - Log semua aktivitas presensi
   - Monitor suspicious activities
   - Audit trail untuk compliance

5. **Offline Mode:**
   - Implementasikan offline data storage
   - Sync ketika koneksi tersedia
   - Handle network errors gracefully

6. **Photo Upload:**
   - Tambahkan upload foto saat presensi
   - Validasi waktu foto (anti-cheating)
   - Compress images sebelum upload

7. **Battery Optimization:**
   - Optimalkan penggunaan GPS
   - Request location only when needed
   - Battery-friendly background services

---

## Support

Untuk pertanyaan atau issues terkait API presensi, hubungi tim development.

---

**Dibuat:** 6 Februari 2026  
**Versi:** 1.0.0  
**Framework:** Laravel 11