FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    pkg-config \
    unzip \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    zip \
    gd \
    opcache

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (for layer caching)
COPY composer.json composer.lock ./

# Install PHP dependencies (no scripts yet - env not available)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy package files
COPY package.json package-lock.json ./

# Install Node dependencies (--legacy-peer-deps to handle vite peer dep conflicts)
RUN npm install --legacy-peer-deps

# Copy application code
COPY . .

# Build frontend assets
RUN npm run build

# Run composer scripts now (post-autoload-dump etc.)
RUN composer dump-autoload --optimize

# Set proper permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port (Railway/Render will set $PORT)
EXPOSE 8000

# Start command using php artisan serve (simple, works with $PORT)
CMD sh -c "php artisan storage:link --force && \
    php artisan config:clear && \
    php artisan migrate --force && \
    (php artisan db:seed --force 2>/dev/null; true) && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"
