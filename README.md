# 🚀 ReviuStable

ReviuStable adalah aplikasi web yang dirancang sebagai **alat manajemen konten media sosial** internal. Aplikasi ini berfungsi sebagai platform kolaborasi bagi **staf media sosial** dan **reviewer konten/kepala divisi** untuk menilai, mengelola, dan memastikan kelayakan publikasi konten media sosial.



---

## 💻 Fitur Utama

* **Manajemen Konten:** Memungkinkan staf media sosial untuk mengunggah dan mengatur draf konten.
* **Sistem Review:** Menyediakan alur kerja di mana reviewer/kepala divisi dapat memberikan penilaian dan umpan balik terhadap konten.
* **Penentuan Kelayakan:** Menetapkan status konten (layak/tidak layak publish).
* **Kolaborasi Tim:** Memudahkan komunikasi dan koordinasi antara tim pembuat konten dan tim peninjau.

---

## ⚙️ Panduan Instalasi (Untuk Pengembang)

Ikuti langkah-langkah di bawah ini untuk mengatur dan menjalankan proyek secara lokal.

### Prasyarat

Pastikan Anda telah menginstal yang berikut ini di sistem Anda. Versi yang direkomendasikan adalah:

* **PHP:** Direkomendasikan **v8.3**
* **Composer**
* **Node.js:** Direkomendasikan **v20**
* **npm:** Direkomendasikan **v10.8**
* **Database** (MySQL, PostgreSQL, dll.)

### Langkah-langkah

1.  **Clone Repository**
    ```bash
    git clone [https://github.com/ferynnd/ReviuStable.git](https://github.com/ferynnd/ReviuStable.git)
    ```

2.  **Masuk ke Directory Proyek**
    ```bash
    cd ReviuStable
    ```

3.  **Instal Dependensi PHP (via Composer)**
    ```bash
    composer install
    ```

4.  **Konfigurasi Environment**
    Salin file contoh konfigurasi environment:
    ```bash
    cp .env.example .env
    ```

5.  **Generate Application Key**
    Hasilkan kunci aplikasi unik untuk keamanan:
    ```bash
    php artisan key:generate
    ```

6.  **Konfigurasi Database**
    Buka file **`.env`** yang baru dibuat dan sesuaikan pengaturan koneksi database (DB\_DATABASE, DB\_USERNAME, DB\_PASSWORD, dll.) dengan kredensial lokal Anda.

7.  **Jalankan Migrasi Database**
    Jika proyek Anda memiliki tabel yang didefinisikan dalam folder `database/migrations`:
    ```bash
    php artisan migrate
    ```

8.  **Isi Data Awal (Seeding)**
    Jalankan seeder untuk mengisi data awal (misalnya, akun pengguna default, peran, atau pengaturan dasar):
    ```bash
    php artisan db:seed
    ```

9.  **Buat Symbolic Link untuk Storage**
    Buat symlink agar file yang diunggah dapat diakses publik:
    ```bash
    php artisan storage:link
    ```

10. **Instal Dependensi Node.js**
    Instal semua paket front-end yang dibutuhkan:
    ```bash
    npm install
    ```

11. **Kompilasi Aset Frontend**
    Kompilasi atau transpilasi aset CSS dan JavaScript:
    ```bash
    npm run dev
    # Atau gunakan 'npm run watch' untuk pengembangan
    ```

12. **Jalankan Server Lokal**
    Mulai server pengembangan Laravel:
    ```bash
    php artisan serve
    ```
    Aplikasi sekarang akan dapat diakses, biasanya di `http://127.0.0.1:8000`.

---

