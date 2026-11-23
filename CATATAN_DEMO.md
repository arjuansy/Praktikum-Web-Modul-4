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
