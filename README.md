# Slipstream Demo App

This project helps manage customers and contacts.

# Installation

1. Clone the repo - `git clone git@github.com:mathewparet/slipstream.git`
2. Go into the cloned directory and pull in composer dependencies - `composer install`
3. This dev project runs on Laravel Sail, to start run `vendor/bin/sail up -d`. When this command is executed, it will install some docker packages (mysql, etc).
4. Make a copy of `.env.example` as `.env`.
5. If not using sail, update the `DB_*` params in the `.env` file as necessary
6. I've already built the UI, but if you need to build it again you can run `vendor/bin/sail npm run build`
7. Run migrations, and seed the database - `vendor/bin/sail artisan migrate --seed`
9. Set application key - `vendor/bin/sail artisan key:generate`
8. Visit http://localhost/customers to access the application. (if not using sail you will need to run `php composer run dev` or `php artisan serve`)

# Adding additional data

I have made factories for customers and contacts, so if you want to seed these you may run the corresponding factory like:

`vendor\bin\sail artisan db:seed CustomerSeeder`
