#!/bin/sh
set -e
cd /var/www
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
PORT=${PORT:-10000}
DB_WAIT_ENABLED=${DB_WAIT_ENABLED:-true}
DB_WAIT_STRICT=${DB_WAIT_STRICT:-false}
DB_WAIT_TIMEOUT=${DB_WAIT_TIMEOUT:-30}
DB_WAIT_INTERVAL=${DB_WAIT_INTERVAL:-3}
RUN_MIGRATIONS=${RUN_MIGRATIONS:-false}
RUN_SEEDERS=${RUN_SEEDERS:-false}
wait_for_database() {
    php -r "
    try {
        \$url = getenv('DB_URL');
        if (\$url) {
            \$parsed = parse_url(\$url);
            \$host = \$parsed['host'];
            \$port = \$parsed['port'] ?? 5432;
            \$database = ltrim(\$parsed['path'], '/');
            \$username = \$parsed['user'];
            \$password = \$parsed['pass'];
            \$sslmode = getenv('DB_SSLMODE') ?: 'require';
            \$dsn = 'pgsql:host=' . \$host . ';port=' . \$port . ';dbname=' . \$database . ';sslmode=' . \$sslmode;
        } else {
            \$driver = getenv('DB_CONNECTION') ?: 'mysql';
            \$host = getenv('DB_HOST');
            \$port = getenv('DB_PORT');
            \$database = getenv('DB_DATABASE');
            \$username = getenv('DB_USERNAME');
            \$password = getenv('DB_PASSWORD');
            \$sslmode = getenv('DB_SSLMODE') ?: 'prefer';
            if (\$driver === 'pgsql') {
                \$dsn = 'pgsql:host=' . \$host . ';port=' . \$port . ';dbname=' . \$database . ';sslmode=' . \$sslmode;
            } else {
                \$dsn = 'mysql:host=' . \$host . ';port=' . \$port . ';dbname=' . \$database;
            }
        }
        new PDO(\$dsn, \$username, \$password);
        exit(0);
    } catch (Throwable \$e) {
        fwrite(STDERR, \$e->getMessage() . PHP_EOL);
        exit(1);
    }
    "
}
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf
if [ "$DB_WAIT_ENABLED" = "true" ]; then
    elapsed=0
    while ! wait_for_database; do
        echo "Waiting for database... (${elapsed}s/${DB_WAIT_TIMEOUT}s)"
        sleep "$DB_WAIT_INTERVAL"
        elapsed=$((elapsed + DB_WAIT_INTERVAL))
        if [ "$DB_WAIT_TIMEOUT" -gt 0 ] && [ "$elapsed" -ge "$DB_WAIT_TIMEOUT" ]; then
            echo "Database is still unavailable after ${DB_WAIT_TIMEOUT}s."
            if [ "$DB_WAIT_STRICT" = "true" ]; then
                echo "Stopping container because DB_WAIT_STRICT=true."
                exit 1
            fi
            echo "Continuing to start php-fpm so the app does not stay behind a 502."
            break
        fi
    done
fi
if [ -z "${APP_KEY}" ]; then
    echo "APP_KEY is missing. Set APP_KEY in your Render environment variables."
    exit 1
fi
php artisan config:clear
if [ "$RUN_MIGRATIONS" = "true" ]; then
    php artisan migrate --force
fi
if [ "$RUN_SEEDERS" = "true" ]; then
    php artisan db:seed --force
fi
exec apache2-foreground