

## List of commands to get the system up and running:

i. Build the image:

    docker-compose build

ii. Get the container up:

    docker-compose up -d

 iii. Fix storage folder permissions:
 
    docker-compose exec sabek-app chmod -R 777 /var/www/storage

iv. Composer install:

    docker-compose exec sabek-app composer install
v. Artisan:

    docker-compose exec sabek-app php artisan key:generate
    docker-compose exec sabek-app php artisan serve

You can access the system via the specified port in docker-compose.yml (defaulted at 2222):

    http://localhost:2222/
If you need it to be `http://localhost/` change the port to 80.

## Extra commands:

For any commands inside the laravel container:

    docker-compose exec sabek-app #command#

Or SSH directly inside the container:

    docker-compose exec sabek-app /bin/bash/

For any commands inside the database container:

    docker-compose exec sabek-db #command#
    docker-compose exec sabek-app /bin/bash/

## Enjoy.
