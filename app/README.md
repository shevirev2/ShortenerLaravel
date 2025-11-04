Deploy this project (/opt/shortener_laravel on Ubuntu):

sudo apt update && sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
sudo systemctl enable --now docker

cd /opt
git clone https://github.com/shevirev2/ShortenerLaravel.git shortener_laravel
cd shortener_laravel
cp app/.env.example app/.env
UID=$(id -u) GID=$(id -g) docker compose up --build -d
docker exec -it -u www-data shortener_laravel_app bash
cd /var/www/html/app
composer require laravel/horizon
composer install --no-interaction --prefer-dist --optimize-autoloader
//php artisan horizon:install
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force || true
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
chmod -R 777 storage bootstrap/cache
exit

-----------------.env------------------------
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=shortener_laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=redis

-----------permissions issue---------------------
on the host:
sudo chown -R 1000:1000 ./app
sudo chmod -R 775 ./app
-------------------------------------------------


-------------------------------------------------------------------------------------
| Task                 | Command                                                     |
| -------------------- | ----------------------------------------------------------- |
| Stop all containers  | `docker compose down`                                       |
| Rebuild app service  | `docker compose build app && docker compose up -d app`      |
| Run Artisan command  | `docker exec -it shortener_laravel_app php artisan migrate` |
| View logs            | `docker compose logs -f app nginx queue`                    |
| Restart all services | `docker compose restart`                                    |
--------------------------------------------------------------------------------------


---------------------------------Horizon-----------------------------------------------------
|
| Mode	                     Command	                                    Description
| Using Horizon       	     docker compose up -d horizon	            Starts Horizon UI + workers
| Stop Horizon	             docker compose stop horizon	            Stops Horizon
| View Horizon logs	         docker logs -f shortener_laravel_horizon	Live worker output
| Access Horizon Dashboard	 http://localhost/horizon                   visit
| Restart Horizon            docker exec -it shortener_laravel_app php artisan horizon:terminate
| Dispatch a test job        1. php artisan tinker  
|                            2. >>> dispatch(new App\Jobs\LinkHitJob(1, '127.0.0.1', 'Test Agent'));
|
--------------------------------------------------------------------------------------------------


app:        http://localhost
phpmyadmin: http://localhost:8080/
horizon:    http://localhost/horizon