## 💻 Install

1. **Clone Repository**

```bash
git clone https://gitlab.com/dyatna.id/kasirq.git
cd kasirq
composer install
npm install
copy .env.example .env
```

2. **Buka `.env` lalu ubah baris berikut sesuai dengan databasemu yang ingin dipakai**

```
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

3. **Instalasi website**

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

4. **Jalankan website**

```bash
php artisan serve
```

```bash
Admin Kasir
email : adminkasir@mail.com
password : adminkasir

Kasir
email : kasir@mail.com
password : kasir
```
