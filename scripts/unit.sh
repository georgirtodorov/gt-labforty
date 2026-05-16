#!/bin/bash

docker compose exec gtlabfortyapp php artisan test --testsuite=Unit
