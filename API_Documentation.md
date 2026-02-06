# API Documentation - Pegawai Login & Attendance System

## Overview
This API provides endpoints for employee login and data retrieval to be used with a Flutter-based Android attendance application.

## Base URL
```
http://your-domain.com/api
```

## Authentication
Currently, the API uses username/password authentication. For production, it's recommended to implement token-based authentication using Laravel Sanctum or JWT.

---

## Endpoints

### 1. Login Pegawai

Login endpoint for employees to authenticate and retrieve their profile data.

**Endpoint:** `POST /api/v1/pegawai/login`

**Request Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "username": "pegawai_username",
  "password": "pegawai_password"
}
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "role": "pegawai"
    },
    "pegawai": {
      "id": 1,
      "nik": "1234567890123456",
      "nama": "John Doe",
      "tgl_lahir": "1990-01-01",
      "jkel": "L",
      "telp": "081234567890",
      "alamat": "Jl. Contoh No. 123",
      "skpd_id": 1
    },
    "skpd": {
      "id": 1,
      "nama": "Dinas Komunikasi dan Informatika"
    }
  }
}
```

**Error Responses:**

401 Unauthorized (Invalid credentials):
```json
{
  "success": false,
  "message": "Username atau password salah"
}
```

403 Forbidden (Non-pegawai user):
```json
{
  "success": false,
  "message": "Akses ditolak. Hanya pegawai yang dapat login melalui aplikasi mobile"
}
```

404 Not Found (Pegawai data not found):
```json
{
  "success": false,
  "message": "Data pegawai tidak ditemukan"
}
```

**Example cURL Request:**
```bash
curl -X POST http://your-domain.com/api/v1/pegawai/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "username": "johndoe",
    "password": "password123"
  }'
```

**Flutter Example:**
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

---

### 2. Get Pegawai Profile

Retrieve complete employee profile including assigned locations.

**Endpoint:** `GET /api/v1/pegawai/profile?username={username}`

**Request Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Query Parameters:**
- `username` (required): Employee username

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Data pegawai berhasil diambil",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "role": "pegawai"
    },
    "pegawai": {
      "id": 1,
      "nik": "1234567890123456",
      "nama": "John Doe",
      "tgl_lahir": "1990-01-01",
      "jkel": "L",
      "telp": "081234567890",
      "alamat": "Jl. Contoh No. 123",
      "skpd_id": 1
    },
    "skpd": {
      "id": 1,
      "nama": "Dinas Komunikasi dan Informatika"
    },
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

**Error Responses:**

400 Bad Request:
```json
{
  "success": false,
  "message": "Username diperlukan"
}
```

404 Not Found:
```json
{
  "success": false,
  "message": "User tidak ditemukan atau bukan pegawai"
}
```

**Example cURL Request:**
```bash
curl -X GET "http://your-domain.com/api/v1/pegawai/profile?username=johndoe" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

**Flutter Example:**
```dart
Future<Map<String, dynamic>> getPegawaiProfile(String username) async {
  final response = await http.get(
    Uri.parse('http://your-domain.com/api/v1/pegawai/profile?username=$username'),
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
  );

  if (response.statusCode == 200) {
    return jsonDecode(response.body);
  } else {
    throw Exception('Failed to load profile: ${response.body}');
  }
}
```

---

### 3. Logout Pegawai

Logout endpoint for employees.

**Endpoint:** `POST /api/v1/pegawai/logout`

**Request Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token} (future implementation)
```

**Success Response (200 OK):**
```json
{
  "success": true,
  "message": "Logout berhasil"
}
```

**Example cURL Request:**
```bash
curl -X POST http://your-domain.com/api/v1/pegawai/logout \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

**Flutter Example:**
```dart
Future<Map<String, dynamic>> logoutPegawai() async {
  final response = await http.post(
    Uri.parse('http://your-domain.com/api/v1/pegawai/logout'),
    headers: {
      'Content-Type': 'application/json',
      'Accept': application/json',
    },
  );

  if (response.statusCode == 200) {
    return jsonDecode(response.body);
  } else {
    throw Exception('Logout failed: ${response.body}');
  }
}
```

---

## Data Models

### User Model
| Field | Type | Description |
|-------|------|-------------|
| id | int | User ID |
| name | string | User's full name |
| username | string | Unique username |
| role | string | User role (admin, skpd, pegawai) |

### Pegawai Model
| Field | Type | Description |
|-------|------|-------------|
| id | int | Pegawai ID |
| nik | string | National ID number (16 digits) |
| nama | string | Full name |
| tgl_lahir | date | Date of birth (YYYY-MM-DD) |
| jkel | string | Gender (L = Laki-laki, P = Perempuan) |
| telp | string | Phone number |
| alamat | string | Address |
| skpd_id | int | SKPD ID (foreign key) |

### SKPD Model
| Field | Type | Description |
|-------|------|-------------|
| id | int | SKPD ID |
| nama | string | SKPD name |

### Lokasi Model
| Field | Type | Description |
|-------|------|-------------|
| id | int | Location ID |
| nama | string | Location name |
| latitude | double | Latitude coordinate |
| longitude | double | Longitude coordinate |
| radius | int | Attendance radius in meters |

---

## Error Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 400 | Bad Request - Invalid input parameters |
| 401 | Unauthorized - Invalid credentials |
| 403 | Forbidden - Access denied |
| 404 | Not Found - Resource not found |
| 422 | Unprocessable Entity - Validation failed |
| 500 | Internal Server Error |

---

## Testing the API

### Using Postman or Thunder Client

1. **Login Test:**
   - Method: POST
   - URL: `http://your-domain.com/api/v1/pegawai/login`
   - Body (raw JSON):
     ```json
     {
       "username": "test_pegawai",
       "password": "password123"
     }
     ```

2. **Get Profile Test:**
   - Method: GET
   - URL: `http://your-domain.com/api/v1/pegawai/profile?username=test_pegawai`

---

## Recommendations for Production

1. **Implement Token-Based Authentication:**
   - Use Laravel Sanctum for API authentication
   - Generate and return API tokens on successful login
   - Validate tokens on protected routes

2. **Add Rate Limiting:**
   - Prevent brute force attacks on login endpoint
   - Implement request throttling

3. **Add Request Validation:**
   - More robust input validation
   - Sanitize user inputs

4. **Enable HTTPS:**
   - Always use HTTPS in production
   - Encrypt sensitive data transmission

5. **Add Logging:**
   - Log all authentication attempts
   - Monitor suspicious activities

6. **Add Password Reset:**
   - Implement forgot password functionality
   - Send password reset links via email/SMS

7. **Refresh Tokens:**
   - Implement token refresh mechanism
   - Set appropriate token expiration times

---

## Flutter Integration Tips

1. **Create a Model Class:**
   ```dart
   class Pegawai {
     final int id;
     final String nik;
     final String nama;
     final String? telp;
     final String? alamat;
     // Add other fields...
   }
   ```

2. **Use Shared Preferences:**
   - Store user session data locally
   - Remember login state

3. **Implement Error Handling:**
   - Show user-friendly error messages
   - Handle network errors gracefully

4. **Add Loading States:**
   - Show progress indicators during API calls
   - Improve user experience

5. **Location Services:**
   - Use the lokasi data for GPS-based attendance
   - Check if user is within specified radius

---

## Support

For issues or questions about the API, please contact the development team.