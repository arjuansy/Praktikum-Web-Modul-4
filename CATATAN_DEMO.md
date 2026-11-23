# Catatan Pemenuhan Ketentuan DEMO FlipPlay

Repository Laravel: [Praktikum-Web-Modul-4](https://github.com/arjuansy/Praktikum-Web-Modul-4)

Berikut checklist ketentuan DEMO beserta bukti pada kode project.

## Checklist

- [x] **Repository GitHub dan project Laravel**
  - Project Laravel berada di repository terpisah [Praktikum-Web-Modul-4](https://github.com/arjuansy/Praktikum-Web-Modul-4).
  - Project Laravel 12 dan dependensi framework tercantum di [`composer.json`](composer.json#L1-L13).

- [x] **Minimal dua entitas dengan relasi Eloquent**
  - Model `Media` mendefinisikan relasi many-to-many ke `Pengguna` melalui `media_pengguna` di [`Media.php`](app/Models/Media.php#L18-L21).
  - Model `Pengguna` mendefinisikan relasi many-to-many ke `Media` di [`Pengguna.php`](app/Models/Pengguna.php#L13-L16).
  - Peminjaman dicatat terpisah pada model [`Peminjaman.php`](app/Models/Peminjaman.php) dan relasi `hasMany` pada [`Media.php`](app/Models/Media.php#L23-L26) serta [`Pengguna.php`](app/Models/Pengguna.php#L18-L21). Koleksi tetap many-to-many; peminjaman eksklusif memakai `is_available`.
  - Saat pinjam, media otomatis ditambahkan ke koleksi peminjam sehingga muncul pada `GET /api/media`. Saat dikembalikan, relasi yang dibuat khusus oleh peminjaman dihapus; koleksi yang sudah ada sebelumnya tetap dipertahankan. Logika ada di [`PeminjamanController.php`](app/Http/Controllers/PeminjamanController.php).

- [x] **Migration entitas dan pivot table**
  - Tabel `penggunas` beserta kolom nama dan email dibuat di [`2026_11_22_172526_create_penggunas_table.php`](database/migrations/2026_11_22_172526_create_penggunas_table.php#L14-L19).
  - Tabel `media` beserta judul unik, jenis, genre, dan tahun dibuat di [`2026_11_22_172536_create_media_table.php`](database/migrations/2026_11_22_172536_create_media_table.php#L14-L21).
  - Pivot `media_pengguna` memiliki foreign key ke kedua tabel dan primary key gabungan di [`2026_11_22_172541_create_media_pengguna_table.php`](database/migrations/2026_11_22_172541_create_media_pengguna_table.php#L14-L18).
  - Status ketersediaan ditambahkan lewat [`2026_11_23_000000_add_is_available_to_media_table.php`](database/migrations/2026_11_23_000000_add_is_available_to_media_table.php).
  - Riwayat peminjaman dengan foreign key pengguna/media serta waktu pinjam/kembali dibuat di [`2026_11_23_000001_create_peminjaman_table.php`](database/migrations/2026_11_23_000001_create_peminjaman_table.php).
  - Penanda apakah media sudah berada di koleksi sebelum dipinjam dibuat di [`2026_11_23_000002_add_collection_existed_to_peminjaman_table.php`](database/migrations/2026_11_23_000002_add_collection_existed_to_peminjaman_table.php).

- [x] **Menambahkan pengguna melalui POST**
  - `POST /api/penggunas` memakai Form Request untuk validasi nama dan email, lalu mengembalikan `201 Created` di [`PenggunaController.php`](app/Http/Controllers/PenggunaController.php#L17-L22).
  - Aturan validasi nama/email berada di [`StorePenggunaRequest.php`](app/Http/Requests/StorePenggunaRequest.php#L15-L20).

- [x] **Status ketersediaan dan peminjaman eksklusif**
  - Kolom boolean `is_available` default `true` ditambahkan melalui migration [`2026_11_23_000000_add_is_available_to_media_table.php`](database/migrations/2026_11_23_000000_add_is_available_to_media_table.php#L9-L14); model meng-cast nilainya menjadi boolean di [`Media.php`](app/Models/Media.php#L11-L16).
  - Saat dipinjam, operasi update kondisional hanya berhasil jika media masih tersedia; jika tidak, API mengembalikan `409 Conflict` di [`PeminjamanController.php`](app/Http/Controllers/PeminjamanController.php#L13-L36).
  - Pengembalian mengisi `returned_at`, mengubah `is_available` kembali menjadi `true`, dan mencegah pengembalian kedua di [`PeminjamanController.php`](app/Http/Controllers/PeminjamanController.php#L39-L57).
  - Koleksi pribadi tetap many-to-many dan terpisah dari status pinjaman. Artinya media bisa dikoleksi beberapa pengguna, tetapi tidak bisa dipinjam aktif oleh dua pengguna sekaligus.

- [x] **CRUD Media**
  - Method `index`, `store`, `show`, `update`, dan `destroy` ada di [`MediaController.php`](app/Http/Controllers/MediaController.php#L11-L50).
  - `index` dan `show` mengembalikan `200 OK`; `store` secara eksplisit mengembalikan `201 Created`; `update` dan `destroy` mengembalikan `200 OK` di [`MediaController.php`](app/Http/Controllers/MediaController.php#L11-L50).
  - ID media yang tidak ditemukan menghasilkan `404 Not Found` melalui route model binding pada parameter `Media $media` di [`MediaController.php`](app/Http/Controllers/MediaController.php#L29-L43).
  - `index` menampilkan semua media dan mendukung pencarian judul melalui `search` (juga `judul` atau `q`) di [`MediaController.php`](app/Http/Controllers/MediaController.php#L11-L19).
  - `show` memuat relasi pengguna yang mengoleksi media di [`MediaController.php`](app/Http/Controllers/MediaController.php#L29-L31).

- [x] **Form Request Validation untuk POST/PATCH**
  - `store` dan `update` menggunakan `StoreMediaRequest` di [`MediaController.php`](app/Http/Controllers/MediaController.php#L22-L37).
  - Aturan judul wajib untuk POST, unik, serta opsional pada PATCH ditentukan di [`StoreMediaRequest.php`](app/Http/Requests/StoreMediaRequest.php#L15-L30).
  - Pesan validasi judul kosong dan duplikat ditentukan di [`StoreMediaRequest.php`](app/Http/Requests/StoreMediaRequest.php#L34-L40).

- [x] **API Resource dan relasi `whenLoaded()`**
  - Resource media mengatur format response, menyertakan `is_available`, dan memuat pengguna dengan `whenLoaded()` di [`MediaResource.php`](app/Http/Resources/MediaResource.php#L15-L28).
  - Resource pengguna mengatur format koleksi serta riwayat peminjaman di [`PenggunaResource.php`](app/Http/Resources/PenggunaResource.php#L15-L30).
  - Status pinjaman `dipinjam`/`dikembalikan` dan detail media ditampilkan oleh [`PeminjamanResource.php`](app/Http/Resources/PeminjamanResource.php#L10-L29).

- [x] **Endpoint terdaftar di `routes/api.php`**
  - CRUD media, GET/POST pengguna, endpoint koleksi, dan endpoint pinjam/kembali terdaftar di [`api.php`](routes/api.php#L8-L19).
  - Laravel memuat file API tersebut melalui [`bootstrap/app.php`](bootstrap/app.php#L8-L12).
  - Verifikasi daftar endpoint:
    ```bash
    php artisan route:list --path=api
    ```

- [x] **Custom action yang mengubah relasi**
  - `attach` menambahkan media ke koleksi dan mengembalikan 409 jika media sudah ada di [`PenggunaController.php`](app/Http/Controllers/PenggunaController.php#L29-L40).
  - `detach` menghapus media dari koleksi dan mengembalikan 404 jika relasi tidak ditemukan di [`PenggunaController.php`](app/Http/Controllers/PenggunaController.php#L42-L53).
  - Endpoint POST/DELETE untuk action tersebut didaftarkan di [`api.php`](routes/api.php#L16-L17).

- [x] **Kasus uji endpoint happy path dan failure path disiapkan untuk Postman**
  - Langkah dan status respons yang diharapkan dicatat pada bagian [Panduan Pengujian Postman](#panduan-pengujian-postman) di bawah.
  - Form Request menangani `422 Unprocessable Content`; skenario mencakup judul kosong dan judul duplikat.
  - Skenario relasi mencakup `409 Conflict` untuk attach duplikat dan `404 Not Found` untuk detach relasi yang tidak ada. Jalankan kasus-kasus ini di Postman dan simpan screenshot sebagai bukti pengujian.
  - Alur pinjam/kembali, pencegahan pinjaman ganda, status tersedia, dan POST pengguna diuji otomatis di [`FlipPlayLoanTest.php`](tests/Feature/FlipPlayLoanTest.php).

## Ringkasan Endpoint

| Method | Endpoint | Fungsi |
| --- | --- | --- |
| GET | `/api/media` | Daftar media; pencarian, misalnya `?search=Interstellar` |
| POST | `/api/media` | Menambahkan media |
| GET | `/api/media/{media}` | Detail media beserta pengguna dalam koleksi |
| PATCH | `/api/media/{media}` | Mengubah media |
| DELETE | `/api/media/{media}` | Menghapus media |
| GET | `/api/penggunas` | Daftar pengguna beserta koleksinya |
| POST | `/api/penggunas` | Menambahkan pengguna |
| GET | `/api/penggunas/{pengguna}` | Detail koleksi pengguna |
| POST | `/api/penggunas/{pengguna}/media/{media}` | Menambahkan media ke koleksi |
| DELETE | `/api/penggunas/{pengguna}/media/{media}` | Menghapus media dari koleksi |
| POST | `/api/peminjaman` | Meminjam media; body berisi `pengguna_id` dan `media_id` |
| PATCH | `/api/peminjaman/{peminjaman}/kembali` | Mengembalikan media |

## Status Code

| Operasi | Berhasil | Gagal |
| --- | --- | --- |
| `GET /api/media` dan `GET /api/media/{media}` | `200 OK` | `404 Not Found` jika media tidak ditemukan |
| `POST /api/media` | `201 Created` | `422 Unprocessable Content` jika validasi gagal |
| `PATCH /api/media/{media}` | `200 OK` | `404 Not Found` jika media tidak ditemukan; `422 Unprocessable Content` jika validasi gagal |
| `DELETE /api/media/{media}` | `200 OK` | `404 Not Found` jika media tidak ditemukan; `409 Conflict` jika ada riwayat peminjaman |
| `POST /api/penggunas/{pengguna}/media/{media}` | `200 OK` | `404 Not Found` jika pengguna/media tidak ditemukan; `409 Conflict` jika relasi sudah ada |
| `DELETE /api/penggunas/{pengguna}/media/{media}` | `200 OK` | `404 Not Found` jika pengguna, media, atau relasinya tidak ditemukan |
| `GET /api/penggunas` dan `GET /api/penggunas/{pengguna}` | `200 OK` | `404 Not Found` jika pengguna tidak ditemukan |
| `POST /api/penggunas` | `201 Created` | `422 Unprocessable Content` jika nama/email tidak valid atau email duplikat |
| `POST /api/peminjaman` | `201 Created` | `409 Conflict` jika media sedang dipinjam; `422 Unprocessable Content` jika ID pengguna/media tidak valid |
| `PATCH /api/peminjaman/{peminjaman}/kembali` | `200 OK` | `404 Not Found` jika pinjaman tidak ditemukan; `409 Conflict` jika sudah dikembalikan |

## Panduan Pengujian Postman

Pastikan schema dan data demo tersedia, lalu jalankan server dari folder project:

```bash
php artisan migrate --seed
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
| 6 | `POST /penggunas` | Body: `{"nama":"Pengguna Baru","email":"baru@example.com"}`; simpan ID hasil response sebagai `{idPenggunaBaru}`. | `201 Created` |
| 7 | `GET /penggunas/{idPenggunaBaru}` | Pastikan detail pengguna tampil. | `200 OK` |
| 8 | `POST /penggunas/1/media/2` | Attach media ID 2 ke pengguna ID 1 (gunakan relasi yang belum ada). | `200 OK` |
| 9 | `DELETE /penggunas/1/media/2` | Hapus relasi yang dibuat pada langkah 8. | `200 OK` |
| 10 | `DELETE /media/{idBaru}` | Hapus media yang dibuat pada langkah 2 sebelum membuat pinjaman. | `200 OK` |
| 11 | `POST /peminjaman` | Body: `{"pengguna_id":2,"media_id":1}`; ganti `2` dengan ID pengguna hasil langkah 6. ID 1 adalah media seed yang tersedia. | `201 Created`, status `dipinjam`, `is_available: false` |
| 12 | `GET /media` | Pastikan `pengguna` pada media ID 1 mencantumkan peminjam dari langkah 11. | `200 OK` |
| 13 | `GET /penggunas/{idPenggunaBaru}` | Pastikan `peminjaman` tampil dengan status `dipinjam`. | `200 OK` |
| 14 | `PATCH /peminjaman/{idPeminjaman}/kembali` | Gunakan ID hasil langkah 11. | `200 OK`, status `dikembalikan`, `is_available: true`; relasi koleksi otomatis yang dibuat saat pinjam dihapus |

### Failure path

| No. | Method dan URL | Isi request / pemeriksaan | Hasil yang diharapkan |
| --- | --- | --- | --- |
| 1 | `POST /media` | Body: `{"jenis":"Film"}`; judul tidak dikirim. | `422 Unprocessable Content`, error `judul` |
| 2 | `POST /media` | Body dengan judul yang telah ada, misalnya `{"judul":"Interstellar","jenis":"Film"}`. | `422 Unprocessable Content`, judul duplikat |
| 3 | `GET /media/999999` | Gunakan ID yang tidak ada. | `404 Not Found` |
| 4 | `POST /penggunas/1/media/2` | Kirim dua kali tanpa menjalankan DELETE langkah happy path di antaranya. | Permintaan pertama `200 OK`, kedua `409 Conflict` |
| 5 | `DELETE /penggunas/1/media/2` | Jalankan lagi setelah relasi sudah dihapus pada happy path langkah 8. | `404 Not Found` |
| 6 | `POST /peminjaman` | Setelah happy path langkah 11, kirim body `{"pengguna_id":1,"media_id":1}` untuk mencoba meminjam media yang sama sebagai pengguna lain. | `409 Conflict`; media tidak dipinjam dua kali bersamaan |
| 7 | `PATCH /peminjaman/{idPeminjaman}/kembali` | Jalankan lagi setelah pinjaman dikembalikan pada happy path langkah 14. | `409 Conflict`; pinjaman sudah dikembalikan |
| 8 | `POST /penggunas` | Body dengan email yang sudah dipakai. | `422 Unprocessable Content`; email duplikat |
| 9 | `DELETE /media/1` | Gunakan media yang sudah memiliki riwayat pinjaman. | `409 Conflict`; riwayat peminjaman dipertahankan |

Simpan hasil pengujian dengan screenshot request, response, dan status code dari Postman untuk bukti laporan.
