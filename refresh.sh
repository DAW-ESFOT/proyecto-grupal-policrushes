#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
php artisan migrate:refresh
php artisan db:seed --class=UserTableSeder
