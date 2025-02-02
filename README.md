## What is this?

DMS (Document Management System)

## Requirements

-   php >= 8.3
-   composer
-   node 20
-   [pnpm](https://pnpm.io/) (you can use npm or other if you like)
-   bootstrap 4.6 (AdminLTE 3)
-   database PostgreSQL

## How to start

-   Make sure you already have composer installed.
-   Install dependency manager use command :

    `composer install`

-   Install package manager for javascript asset :

    `pnpm install`

-   Run artisan command to create symbolink :

    `php artisan storage:link`

## Cheatsheet

-   Run server with port

    `php artisan serve --port=8000`

-   Easy way to create model, migration, controller, request

    `php artisan make:model Role -mscr`

    or

    `php artisan make:model Role --migration --seed --controller --resource`

-   Generate model for \_ide_helper

    `php artisan ide-helper:models`

-   Seed specific seeder (use this when you add new access in config/access.php file)

    `php artisan db:seed --class=RoleAccessSeeder`

-   Clear database and migrate with seed (ONLY USE THIS IN DEVELOPMENT ENVIRONMENT/STAGE)

    `php artisan migrate:fresh --seed`

-   Migrate specific table / migration :

    `php artisan migrate:refresh --path=/database/migrations/0001_01_01_000003_create_jobs_table.php`

-   Run specific schedule :

    `php artisan schedule:test`

    Select index of list (0,1,2 etc) then press enter
