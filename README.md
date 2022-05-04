
# Sabek Laravel System (New Operations Dashboard Repository)

# Requirements

- PHP <= 7.3

# Usage

- infyom generator (https://infyom.com/open-source/laravelgenerator/docs/8.0/introduction).

# Docker Deployment

## List of commands to get the system up and running:

i. Rename `env.docker.example` file to `.env` and if needed insert api tokens (provided by backend team).

    cp .env.docker.example .env

ii. Navigate to the docker directory:

    cd docker

iii. Build the image:

    docker-compose build

iv. Get the container up:

    docker-compose up -d

 v. Fix `storage` folder permissions:
 
    docker-compose exec sabek-app chmod -R 777 /var/www/storage

vi. Composer install:

    docker-compose exec sabek-app composer install
vii. Artisan:

    docker-compose exec sabek-app php artisan key:generate
    docker-compose exec sabek-app php artisan serve

## System Access

You can access the system via the specified port in docker-compose.yml (defaulted at 2222):

    http://localhost:2222/
If you need it to be `http://localhost/` change `2222` port to `80`

Login information for admin user:

     username : admin@demo.com
     password : 123456

## Extra commands:

For any commands inside the laravel container:

    docker-compose exec sabek-app #command#

Or SSH directly inside the container:

    docker-compose exec sabek-app /bin/bash/

For any commands inside the database container:

    docker-compose exec sabek-db #command#
    docker-compose exec sabek-app /bin/bash/

# Non-Docker:

1- Clone repository

```code
 https://github.com/SabekLY/sabek.git

```

2- Import database

```code
 dump-foods_test-202202021853.sql
```

3- Rename .env.example to .env

4- Edit databse configuration in .env file

```code
 DB_DATABASE=name_of_database
 DB_USERNAME=user_of_database
 DB_PASSWORD=password_of_database
```

5- Install composer dependencies

```code
 composer install
```

- before doing command composer install make sure you Install or enable PHP's grpc extension

6- generate a key for your application

```code
 php artisan key:generate
```

7- Finally run the server

```code
 php artisan serve
```

8- login information for admin user

```code
 username : admin@demo.com
 password : 123456

 * Note : make sure you did step number 2
```

# Notes

Instead of insert permissions manually , you can load all permissions to system (Database) depends on routes names by executing the command below

```code
php artisan db:seed --class=PermissionsTableSeeder
```

## Important Links

[Server Requirements](https://support.smartersvision.com/help-center/articles/3/4/3/introduction).

[How to Update to last version?](https://support.smartersvision.com/help-center/articles/3/4/9/update).

[FAQ](https://support.smartersvision.com/help-center/categories/6/laravel-application-faq).
