#!/usr/bin/env bash
read -n1 -r -p "Edit .env - set database parameters... (Press any button when you're done)
" key
php composer.phar install
echo "Loading fixures - for example first, administrative user"
php bin/console doctrine:schema:update --force --env=dev
php bin/console doctrine:fixtures:load --env=dev
yarn install
yarn run encore dev
yarn run encore production
