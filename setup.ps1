cd Backend

composer install
npm install

cp .env.example .env
php artisan migrate

cd ../Frontend
npm install
