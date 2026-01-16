#!/bin/bash
set -e

echo "🚀 Starting Abjadia Store..."

# Create database file if using SQLite and doesn't exist
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    DB_PATH="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    if [ ! -f "$DB_PATH" ]; then
        echo "📝 Creating SQLite database..."
        mkdir -p "$(dirname "$DB_PATH")"
        touch "$DB_PATH"
        chmod 664 "$DB_PATH"
    fi
fi

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Seed database if SEED_DATABASE is set and database is empty
if [ "$SEED_DATABASE" = "true" ]; then
    # Check if users table has any records
    USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null || echo "0")
    if [ "$USER_COUNT" = "0" ]; then
        echo "🌱 Seeding database..."
        php artisan db:seed --force
    else
        echo "ℹ️  Database already seeded, skipping..."
    fi
fi

# Clear and cache config
echo "⚙️  Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "🔒 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

echo "✨ Abjadia Store is ready!"

# Execute the main command
exec "$@"
