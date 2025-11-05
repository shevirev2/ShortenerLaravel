# Shortener Laravel (Docker)

## Application Setup

### 1. Install Docker

```bash
sudo apt update && sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
sudo systemctl enable --now docker

**### 2. Deploy and Initialize Application**

```bash
git clone https://github.com/shevirev2/ShortenerLaravel.git shortener_laravel
cd shortener_laravel
cp app/.env.example app/.env

UID=$(id -u) GID=$(id -g) docker compose up --build -d

docker exec -it -u www-data shortener_laravel_app bash
cd /var/www/html/app

composer install --no-interaction --prefer-dist --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force || true

php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

chmod -R 777 storage bootstrap/cache















exit

