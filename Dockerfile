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
    pkg-config \
    unzip \
    nodejs \
    npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    xml \
    zip \
    curl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./
# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy package files
COPY package.json package-lock.json ./

# Install Node dependencies and build assets
RUN npm install

# Copy application code
COPY . .
RUN npm run build
# Run composer scripts now
RUN composer dump-autoload --optimize

# NOTE: Do NOT cache config at build time - env vars (DB) are not available yet!
# Caching is done at runtime in the CMD below.

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

# Expose port
EXPOSE $PORT

# Start command: cache config at runtime (env vars are NOW available), then migrate and serve
CMD php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan storage:link --force && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
