🏡 Aplikasi Desa

Sistem Informasi Desa berbasis Laravel 11 & Vue 3
  Aplikasi ini dirancang untuk membantu desa dalam mengelola data masyarakat, keluarga, serta penyelenggaraan acara secara modern.
  Dengan frontend Vue.js dan backend Laravel, sistem ini mendukung RESTful API, dashboard interaktif, serta integrasi Google Maps untuk pencatatan lokasi.



✨ Fitur Utama

    🔐 Authentication & Authorization (Laravel Sanctum, RBAC)
    
    👨‍👩‍👧‍👦 Manajemen Penduduk & Keluarga (kepala keluarga, anggota keluarga)
    
    📅 Manajemen Acara (CRUD event, pendaftaran peserta, status pembayaran)
    
    📊 Dashboard Statistik (grafik & tabel dengan Vue Good Table)
    
    🗑️ Soft Delete & Restore (mencegah kehilangan data penting)
    
    🌍 Integrasi Google Maps (lokasi & koordinat dengan draggable marker)
    
    ⚡ SPA Experience dengan Vue 3 + Vite

🛠️ Teknologi yang Digunakan

    Backend: Laravel 11, MySQL
    
    Frontend: Vue 3, Vite, TailwindCSS
    
    UI Library: Vue Good Table, Vue Select
    
    API Client: Axios
    
    Date Handling: Moment.js


⚙️ Instalasi
1. Clone Repository
  ```bash
  git clone https://github.com/username/aplikasi-desa-laravel-vue.git
  ```
  ```bash
  cd aplikasi-desa-laravel-vue
  ```

2. Backend (Laravel)
   ```bash
    cd Backend
    cp .env.example .env
    composer install
    php artisan key:generate
    php artisan migrate --seed
    php artisan serve


Backend berjalan di: 
  ```bash
  http://127.0.0.1:8000
  ```

3. Frontend (Vue.js)
   ```bash
    cd Frontend
    cp .env.example .env
    npm install
    npm run dev


Frontend berjalan di: 

    http://127.0.0.1:3030

🔑 Konfigurasi Environment
Backend (Backend/.env)

    APP_NAME=AplikasiDesa
    APP_ENV=local
    APP_KEY=base64:...
    APP_URL=http://127.0.0.1:8000
    
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=desa_db
    DB_USERNAME=root
    DB_PASSWORD=

Frontend (Frontend/.env)

    VITE_API_URL=http://127.0.0.1:8000/api
    VITE_PORT=3030
    VITE_GOOGLE_MAPS_API=YOUR_API_KEY

👨‍💻 Author
Developed by Najwan Zaky
💼 Fullstack Developer | Laravel & Vue.js Enthusiast
