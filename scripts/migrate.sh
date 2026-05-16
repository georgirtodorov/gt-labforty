#!/bin/bash

docker compose exec gtlabfortyapp php artisan migrate --seed
