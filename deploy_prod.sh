#!/usr/bin/env sh

export SYMFONY_ENV=prod
export APP_ENV=prod

composer-php7.1 install --no-dev --optimize-autoloader
php7.1 bin/console doctrine:migrations:migrate --env=prod  --no-interaction
bower install
gulp build
php7.1 -d memory_limit=-1 bin/console cache:clear --no-warmup --env=prod --no-debug