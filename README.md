# PANDUAN INSTALASI APLIKASI RT - ADMINISTRASI IURAN & PENGELUARAN

# CLONE REPOSITORY
git clone https://github.com/dewaradtya/backend-administrasi-rt

# Masuk ke folder
cd backend

# Install dependency
composer install

# Salin .env
cp .env.example .env

# Atur koneksi database di file .env
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=

# Generate key
php artisan key:generate

# Migrasi dan seeding
php artisan migrate --seed

# Jalankan server
php artisan serve
