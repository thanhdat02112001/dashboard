#!/bin/bash

# Exit on fail
set -e

# Composer install
composer install --no-autoloader --no-scripts --no-interaction --dev

composer dump-autoload --optimize --no-interaction

# Waiting for dependent containers
/wait-for-it.sh mysql:3306 -t 300


# Start services
php artisan serve --port=8080 --host=0.0.0.0

# Finally call command issued to the docker service
exec "$@"
