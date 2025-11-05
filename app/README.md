# Shortener Laravel (Docker)
## Application Setup
### 1. Install Docker

```bash
sudo apt update && sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
sudo systemctl enable --now docker
```

### 2. Deploy and Initialize Application

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
```

### 3. Access in Browser

| Service      | URL                      |
|-------------|--------------------------|
| Application | http://localhost         |
| PhpMyAdmin  | http://localhost:8080/   |
| Horizon UI  | http://localhost/horizon |


### 4*. Useful Commands

| Task                 | Command                                                     |
|---------------------|-------------------------------------------------------------|
| Stop all containers  | `docker compose down`                                       |
| Rebuild app service  | `docker compose build app && docker compose up -d app`      |
| Run Artisan command  | `docker exec -it shortener_laravel_app php artisan migrate` |
| View logs            | `docker compose logs -f app nginx queue`                    |
| Restart all services | `docker compose restart`                                    |

### 5*. Horizon Management

| Action                  | Command / URL                                          |
|------------------------|--------------------------------------------------------|
| Start Horizon          | `docker compose up -d horizon`                          |
| Stop Horizon           | `docker compose stop horizon`                           |
| View Horizon logs      | `docker logs -f shortener_laravel_horizon`              |
| Open Dashboard         | http://localhost/horizon                                |
| Restart Workers        | `docker exec -it shortener_laravel_app php artisan horizon:terminate` |
| Dispatch a test job    | 1. `php artisan tinker` 2. `>>> dispatch(new App\Jobs\LinkHitJob(1, '127.0.0.1', 'Test Agent'));` | 



