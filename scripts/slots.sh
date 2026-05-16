#!/bin/bash

# ./slots.sh --start-date=2026-05-01 --end-date=2026-05-10 --start=09:00 --end=18:00 --interval=45
docker compose exec gtlabfortyapp php artisan app:generate-time-slots "$@"
