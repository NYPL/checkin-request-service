#!/bin/bash

echo "[app_init.sh] Changing dir to $(dirname "$0")."
cd "$(dirname "$0")"

echo "[app_init.sh] Composer install."
composer install --no-interaction --ignore-platform-reqs --working-dir=.. --no-dev