# Catatan Pemenuhan Ketentuan DEMO FlipPlay

Repository Laravel: [Praktikum-Web-Modul-4](https://github.com/arjuansy/Praktikum-Web-Modul-4)

Berikut checklist ketentuan DEMO beserta bukti pada kode project.

## Checklist

- [x] **Repository GitHub dan project Laravel**
  - Project Laravel berada di repository terpisah [Praktikum-Web-Modul-4](https://github.com/arjuansy/Praktikum-Web-Modul-4).
  - Project Laravel 12 dan dependensi framework tercantum di [`composer.json`](composer.json#L1-L13).

- [x] **Minimal dua entitas dengan relasi Eloquent**
  - Model `Media` mendefinisikan relasi many-to-many ke `Pengguna` melalui `media_pengguna` di [`Media.php`](app/Models/Media.php#L16-L19).
  - Model `Pengguna` mendefinisikan relasi many-to-many ke `Media` di [`Pengguna.php`](app/Models/Pengguna.php#L12-L15).

- [x] **Migration entitas dan pivot table**
  - Tabel `penggunas` beserta kolom nama dan email dibuat di [`2026_11_22_172526_create_penggunas_table.php`](database/migrations/2026_11_22_172526_create_penggunas_table.php#L14-L19).
  - Tabel `media` beserta judul unik, jenis, genre, dan tahun dibuat di [`2026_11_22_172536_create_media_table.php`](database/migrations/2026_11_22_172536_create_media_table.php#L14-L21).
  - Pivot `media_pengguna` memiliki foreign key ke kedua tabel dan primary key gabungan di [`2026_11_22_172541_create_media_pengguna_table.php`](database/migrations/2026_11_22_172541_create_media_pengguna_table.php#L14-L18).

- [x] **CRUD Media**
  - Method `index`, `store`, `show`, `update`, dan `destroy` ada di [`MediaController.php`](app/Http/Controllers/MediaController.php#L11-L44).
  - `index` dan `show` mengembalikan `200 OK`; `store` secara eksplisit mengembalikan `201 Created`; `update` dan `destroy` mengembalikan `200 OK` di [`MediaController.php`](app/Http/Controllers/MediaController.php#L11-L44).
  - ID media yang tidak ditemukan menghasilkan `404 Not Found` melalui route model binding pada parameter `Media $media` di [`MediaController.php`](app/Http/Controllers/MediaController.php#L29-L43).
  - `index` menampilkan semua media dan mendukung pencarian judul melalui `search` (juga `judul` atau `q`) di [`MediaController.php`](app/Http/Controllers/MediaController.php#L11-L19).
  - `show` memuat relasi pengguna yang mengoleksi media di [`MediaController.php`](app/Http/Controllers/MediaController.php#L29-L31).

- [x] **Form Request Validation untuk POST/PATCH**
  - `store` dan `update` menggunakan `StoreMediaRequest` di [`MediaController.php`](app/Http/Controllers/MediaController.php#L22-L37).
  - Aturan judul wajib untuk POST, unik, serta opsional pada PATCH ditentukan di [`StoreMediaRequest.php`](app/Http/Requests/StoreMediaRequest.php#L15-L30).
  - Pesan validasi judul kosong dan duplikat ditentukan di [`StoreMediaRequest.php`](app/Http/Requests/StoreMediaRequest.php#L34-L40).

- [x] **API Resource dan relasi `whenLoaded()`**
  - Resource media mengatur format response dan menyertakan pengguna jika relasinya sudah dimuat di [`MediaResource.php`](app/Http/Resources/MediaResource.php#L15-L27).
  - Resource pengguna mengatur format koleksi dan menyertakan media dengan `whenLoaded()` di [`PenggunaResource.php`](app/Http/Resources/PenggunaResource.php#L15-L25).

- [x] **Endpoint terdaftar di `routes/api.php`**
  - CRUD media, daftar/detail pengguna, dan endpoint koleksi terdaftar di [`api.php`](routes/api.php#L7-L15).
  - Laravel memuat file API tersebut melalui [`bootstrap/app.php`](bootstrap/app.php#L8-L12).
  - Verifikasi daftar endpoint:
    ```bash
    php artisan route:list --path=api
    ```

- [x] **Custom action yang mengubah relasi**
  - `attach` menambahkan media ke koleksi dan mengembalikan 409 jika media sudah ada di [`PenggunaController.php`](app/Http/Controllers/PenggunaController.php#L21-L32).
  - `detach` menghapus media dari koleksi dan mengembalikan 404 jika relasi tidak ditemukan di [`PenggunaController.php`](app/Http/Controllers/PenggunaController.php#L34-L46).
  - Endpoint POST/DELETE untuk action tersebut didaftarkan di [`api.php`](routes/api.php#L14-L15).

- [x] **Kasus uji endpoint happy path dan failure path disiapkan untuk Postman**
  - Langkah dan status respons yang diharapkan dicatat pada bagian [Panduan Pengujian Postman](#panduan-pengujian-postman) di bawah.
  - Form Request menangani `422 Unprocessable Content`; skenario mencakup judul kosong dan judul duplikat.
  - Skenario relasi mencakup `409 Conflict` untuk attach duplikat dan `404 Not Found` untuk detach relasi yang tidak ada. Jalankan kasus-kasus ini di Postman dan simpan screenshot sebagai bukti pengujian.

## Ringkasan Endpoint

| Method | Endpoint | Fungsi |
| --- | --- | --- |
| GET | `/api/media` | Daftar media; pencarian, misalnya `?search=Interstellar` |
| POST | `/api/media` | Menambahkan media |
| GET | `/api/media/{media}` | Detail media beserta pengguna dalam koleksi |
| PATCH | `/api/media/{media}` | Mengubah media |
| DELETE | `/api/media/{media}` | Menghapus media |
| GET | `/api/penggunas` | Daftar pengguna beserta koleksinya |
| GET | `/api/penggunas/{pengguna}` | Detail koleksi pengguna |
| POST | `/api/penggunas/{pengguna}/media/{media}` | Menambahkan media ke koleksi |
| DELETE | `/api/penggunas/{pengguna}/media/{media}` | Menghapus media dari koleksi |

## Status Code

| Operasi | Berhasil | Gagal |
| --- | --- | --- |
| `GET /api/media` dan `GET /api/media/{media}` | `200 OK` | `404 Not Found` jika media tidak ditemukan |
| `POST /api/media` | `201 Created` | `422 Unprocessable Content` jika validasi gagal |
| `PATCH /api/media/{media}` | `200 OK` | `404 Not Found` jika media tidak ditemukan; `422 Unprocessable Content` jika validasi gagal |
| `DELETE /api/media/{media}` | `200 OK` | `404 Not Found` jika media tidak ditemukan |
| `POST /api/penggunas/{pengguna}/media/{media}` | `200 OK` | `404 Not Found` jika pengguna/media tidak ditemukan; `409 Conflict` jika relasi sudah ada |
| `DELETE /api/penggunas/{pengguna}/media/{media}` | `200 OK` | `404 Not Found` jika pengguna, media, atau relasinya tidak ditemukan |
| `GET /api/penggunas` dan `GET /api/penggunas/{pengguna}` | `200 OK` | `404 Not Found` jika pengguna tidak ditemukan |

## Panduan Pengujian Postman

Jalankan server dari folder project:

```bash
php artisan serve
```

Gunakan base URL `http://127.0.0.1:8000/api`. Untuk request POST/PATCH, pilih **Body → raw → JSON** dan kirim header `Accept: application/json` serta `Content-Type: application/json`.

### Happy path

| No. | Method dan URL | Isi request / pemeriksaan | Hasil yang diharapkan |
| --- | --- | --- | --- |
| 1 | `GET /media` | Tidak perlu body; pastikan daftar media tampil. | `200 OK` |
| 2 | `POST /media` | `{"judul":"Spirited Away","jenis":"Film","genre":"Fantasy","tahun":2001}`; simpan ID hasil response. | `201 Created` |
| 3 | `GET /media/{idBaru}` | Gunakan ID hasil langkah 2; response memuat data media dan properti `pengguna`. | `200 OK` |
| 4 | `GET /media?search=Interstellar` | Pastikan hasil sesuai kata pencarian. | `200 OK` |
| 5 | `PATCH /media/{idBaru}` | Body: `{"tahun":2002}`; pastikan tahun berubah. | `200 OK` |
| 6 | `GET /penggunas/1` | Pastikan detail pengguna dan koleksi `media` tampil. | `200 OK` |
| 7 | `POST /penggunas/1/media/2` | Attach media ID 2 ke pengguna ID 1 (gunakan relasi yang belum ada). | `200 OK` |
| 8 | `DELETE /penggunas/1/media/2` | Hapus relasi yang dibuat pada langkah 7. | `200 OK` |
| 9 | `DELETE /media/{idBaru}` | Hapus media yang dibuat pada langkah 2. | `200 OK` |

### Failure path

| No. | Method dan URL | Isi request / pemeriksaan | Hasil yang diharapkan |
| --- | --- | --- | --- |
| 1 | `POST /media` | Body: `{"jenis":"Film"}`; judul tidak dikirim. | `422 Unprocessable Content`, error `judul` |
| 2 | `POST /media` | Body dengan judul yang telah ada, misalnya `{"judul":"Interstellar","jenis":"Film"}`. | `422 Unprocessable Content`, judul duplikat |
| 3 | `GET /media/999999` | Gunakan ID yang tidak ada. | `404 Not Found` |
| 4 | `POST /penggunas/1/media/2` | Kirim dua kali tanpa menjalankan DELETE langkah happy path di antaranya. | Permintaan pertama `200 OK`, kedua `409 Conflict` |
| 5 | `DELETE /penggunas/1/media/2` | Jalankan lagi setelah relasi sudah dihapus pada happy path langkah 8. | `404 Not Found` |

Simpan hasil pengujian dengan screenshot request, response, dan status code dari Postman untuk bukti laporan.
