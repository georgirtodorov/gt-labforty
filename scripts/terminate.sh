#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

cd "${DIR}/.."

set -a
source .env
set +a

docker compose down

echo "Removing volumes and images..."
docker compose down -v --rmi local

echo "Removing orphan containers..."
docker compose down --remove-orphans
