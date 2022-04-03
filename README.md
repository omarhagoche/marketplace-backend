# Food Delivery Flutter + PHP Laravel Admin Panel | Laravel 5

# Requirements

- php <= 7.3

# Usage

- infyom generator (https://infyom.com/open-source/laravelgenerator/docs/8.0/introduction).

# Installaion

1- Clone repository

```code
 https://github.com/alifaraun/sabek.git

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
