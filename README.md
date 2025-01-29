# Project fil rouge

## setup

- setup .env file 
```bash
sail up
sail npm install
sail composer install

# migration
sail php artisan migrate:refresh --seed

# genereta a client key for password connection
sail php artisan passport:client --password

# run the front server
npm run dev 
```
