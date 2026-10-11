#!/bin/sh
set -e

echo "==> [MusicBook] Running startup tasks..."

# 1. Ensure .env exists
if [ ! -f /var/www/.env ]; then
  echo "==> [MusicBook] Creating .env from .env.example..."
  cp /var/www/.env.example /var/www/.env
fi

# 2. Ensure composer dependencies are installed
if [ ! -f /var/www/vendor/autoload.php ]; then
  echo "==> [MusicBook] Installing composer dependencies (this may take 1-2 minutes on first run)..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# 3. Ensure APP_KEY exists
if ! grep -q "^APP_KEY=base64:" /var/www/.env; then
  echo "==> [MusicBook] Generating APP_KEY..."
  php artisan key:generate --force
fi

# 4. Ensure proper storage directories and permissions
mkdir -p /var/www/storage/framework/cache \
         /var/www/storage/framework/sessions \
         /var/www/storage/framework/views \
         /var/www/storage/logs \
         /var/www/bootstrap/cache
chmod -R 777 /var/www/storage /var/www/bootstrap/cache

# 5. Wait for database readiness
echo "==> [MusicBook] Waiting for database to be ready..."
MAX_TRIES=30
COUNT=0
until php -r "new PDO('mysql:host=' . (getenv('DB_HOST') ?: 'db') . ';port=3306;dbname=musicbook', 'musicbook', 'secret');" 2>/dev/null; do
  COUNT=$((COUNT+1))
  if [ $COUNT -ge $MAX_TRIES ]; then
    echo "==> [MusicBook] Database wait timeout exceeded, proceeding anyway..."
    break
  fi
  sleep 2
done

# 6. Run database migrations
echo "==> [MusicBook] Running database migrations..."
php artisan migrate --force || true

# 7. Ensure storage symlink
php artisan storage:link || true

echo "==> [MusicBook] Startup tasks complete! Starting server..."
exec "$@"
