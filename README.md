# Project fil rouge

## setup

- setup .env file 
```bash
sail up
sail php artisan migrate:refresh --seed

# genereta a client key for password connection
sail php artisan passport:client --password

npm run dev 
```
