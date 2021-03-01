#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
php artisan db:seed --class=MusicGenreTableSeeder
php artisan db:seed --class=MovieGenreTableSeeder
php artisan db:seed --class=UserTableSeder