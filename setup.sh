#!/bin/bash

# =============================================================================
# DIGITALABS - Complete Fresh Setup Script
# =============================================================================
# Stack: PHP 8.3-FPM | Laravel 12 | Filament 5.x | Tailwind CSS 4 | MySQL 8.4
#
# This script creates a complete Laravel application from scratch following
# official documentation for all components.
#
# References:
# - Laravel: https://laravel.com/docs/12.x/installation
# - Filament: https://filamentphp.com/docs/5.x/introduction/installation
# - Livewire: https://livewire.laravel.com/docs/installation
# - Tailwind CSS: https://tailwindcss.com/docs/installation
# - Vite: https://laravel.com/docs/12.x/vite
# =============================================================================

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_step() {
    echo ""
    echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo -e "${GREEN}$1${NC}"
    echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ️  $1${NC}"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

echo ""
echo "╔══════════════════════════════════════════════════════════════════════╗"
echo "║              🚀 DIGITALABS - Complete Fresh Setup                    ║"
echo "║──────────────────────────────────────────────────────────────────────║"
echo "║   PHP 8.3-FPM | Laravel 12 | Filament 5.x | MySQL 8.4 | Nginx       ║"
echo "╚══════════════════════════════════════════════════════════════════════╝"

# =============================================================================
# STEP 1: Check Prerequisites
# =============================================================================
print_step "STEP 1: Checking prerequisites..."

if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed. Please install Docker first."
    exit 1
fi

if ! command -v docker compose &> /dev/null; then
    print_error "Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

print_success "Docker $(docker --version | cut -d' ' -f3 | tr -d ',')"
print_success "Docker Compose $(docker compose version --short)"

# =============================================================================
# STEP 1.5: Cleanup Previous Installation (if exists)
# =============================================================================
if [ -f "artisan" ] || [ -f "docker-compose.yml" ]; then
    print_step "STEP 1.5: Cleaning up previous installation..."
    
    # Stop and remove containers
    if [ -f "docker-compose.yml" ]; then
        docker compose down -v 2>/dev/null || true
        print_info "Containers and volumes removed"
    fi
    
    # Remove Laravel files but keep docs/ and setup.sh
    find . -maxdepth 1 ! -name '.' ! -name 'docs' ! -name 'setup.sh' ! -name '.git' -exec rm -rf {} + 2>/dev/null || true
    print_success "Previous installation cleaned up"
fi

# =============================================================================
# STEP 2: Add Domain to /etc/hosts
# =============================================================================
print_step "STEP 2: Adding domain to /etc/hosts..."

if grep -q "digitalabs.test" /etc/hosts; then
    print_info "Domain already exists in /etc/hosts"
else
    print_info "Adding digitalabs.test to /etc/hosts (requires sudo)..."
    echo "127.0.0.1 digitalabs.test" | sudo tee -a /etc/hosts > /dev/null
    print_success "Domain added to /etc/hosts"
fi

# =============================================================================
# STEP 3: Generate Docker Configuration
# =============================================================================
print_step "STEP 3: Generating Docker configuration..."

mkdir -p docker/nginx/sites docker/nginx/ssl docker/mysql

# -----------------------------------------------------------------------------
# Dockerfile (Development - lightweight with volumes)
# -----------------------------------------------------------------------------
cat > Dockerfile << 'EOF'
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    bash \
    curl \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    mysql-client \
    nodejs \
    npm \
    supervisor

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
        mbstring \
        exif

# Install Redis extension
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Install Composer (from official image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configure PHP for development
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory.ini \
    && echo "upload_max_filesize=100M" >> /usr/local/etc/php/conf.d/memory.ini \
    && echo "post_max_size=100M" >> /usr/local/etc/php/conf.d/memory.ini

# Configure OPcache for performance
RUN echo "opcache.enable=1" > /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/opcache.ini

# Set working directory
WORKDIR /var/www/html

# Create non-root user for better permission handling
RUN addgroup -g 1000 -S www && adduser -u 1000 -S www -G www

# Set permissions
RUN chown -R www:www /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
EOF
print_success "Dockerfile created"

# -----------------------------------------------------------------------------
# docker-compose.yml
# -----------------------------------------------------------------------------
cat > docker-compose.yml << 'EOF'
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: digitalabs-app
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - ./:/var/www/html
      - /var/www/html/vendor
      - /var/www/html/node_modules
    networks:
      - digitalabs-network
    depends_on:
      mysql:
        condition: service_healthy
      redis:
        condition: service_started

  nginx:
    image: nginx:alpine
    container_name: digitalabs-nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www/html
      - ./docker/nginx/sites:/etc/nginx/conf.d
      - ./docker/nginx/ssl:/etc/nginx/ssl
    networks:
      - digitalabs-network
    depends_on:
      - app

  mysql:
    image: mysql:8.4
    container_name: digitalabs-mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: digitalabs
      MYSQL_USER: digitalabs
      MYSQL_PASSWORD: secret
      MYSQL_ROOT_PASSWORD: secret
    volumes:
      - mysql-data:/var/lib/mysql
      - ./docker/mysql/my.cnf:/etc/mysql/conf.d/my.cnf
    ports:
      - "3306:3306"
    networks:
      - digitalabs-network
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost", "-uroot", "-psecret"]
      interval: 5s
      timeout: 5s
      retries: 10

  redis:
    image: redis:alpine
    container_name: digitalabs-redis
    restart: unless-stopped
    volumes:
      - redis-data:/data
    ports:
      - "6379:6379"
    networks:
      - digitalabs-network

  phpmyadmin:
    image: phpmyadmin:latest
    container_name: digitalabs-phpmyadmin
    restart: unless-stopped
    environment:
      PMA_HOST: mysql
      PMA_USER: root
      PMA_PASSWORD: secret
    ports:
      - "8080:80"
    networks:
      - digitalabs-network
    depends_on:
      - mysql

networks:
  digitalabs-network:
    driver: bridge

volumes:
  mysql-data:
  redis-data:
EOF
print_success "docker-compose.yml created"

# -----------------------------------------------------------------------------
# Nginx Configuration (Fixed for Livewire compatibility)
# -----------------------------------------------------------------------------
cat > docker/nginx/sites/default.conf << 'EOF'
# HTTP - Redirect to HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name digitalabs.test;
    return 301 https://$server_name$request_uri;
}

# HTTPS
server {
    listen 443 ssl;
    listen [::]:443 ssl;
    http2 on;
    
    server_name digitalabs.test;
    root /var/www/html/public;
    index index.php index.html;

    # SSL
    ssl_certificate /etc/nginx/ssl/digitalabs.test.crt;
    ssl_certificate_key /etc/nginx/ssl/digitalabs.test.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_min_length 1000;
    gzip_types text/plain text/css text/xml application/json application/javascript application/xml+rss application/atom+xml image/svg+xml;

    # Logging
    access_log /var/log/nginx/digitalabs-access.log;
    error_log /var/log/nginx/digitalabs-error.log;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Livewire routes - MUST go to PHP (not static files)
    location ~ ^/livewire {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Laravel main routing
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_buffering off;
        fastcgi_read_timeout 300;
    }

    # Deny hidden files
    location ~ /\. {
        deny all;
    }

    # Static assets caching (only for Vite build output)
    location ~* ^/build/.*\.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
    
    # Filament assets
    location ~* ^/(css|js|fonts)/filament/.*$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
EOF
print_success "Nginx configuration created"

# -----------------------------------------------------------------------------
# MySQL Configuration
# -----------------------------------------------------------------------------
cat > docker/mysql/my.cnf << 'EOF'
[mysqld]
character-set-server=utf8mb4
collation-server=utf8mb4_unicode_ci
default-storage-engine=InnoDB

# Performance
innodb_buffer_pool_size=256M
innodb_log_file_size=64M
max_connections=200

[client]
default-character-set=utf8mb4
EOF
print_success "MySQL configuration created"

# -----------------------------------------------------------------------------
# SSL Certificate (with SAN for browser trust)
# -----------------------------------------------------------------------------
print_info "Generating SSL certificate with SAN..."
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout docker/nginx/ssl/digitalabs.test.key \
    -out docker/nginx/ssl/digitalabs.test.crt \
    -subj "/CN=digitalabs.test" \
    -addext "subjectAltName=DNS:digitalabs.test,DNS:*.digitalabs.test,IP:127.0.0.1" 2>/dev/null
print_success "SSL certificate generated"

# =============================================================================
# STEP 4: Build and Start Docker Containers
# =============================================================================
print_step "STEP 4: Building and starting Docker containers..."

docker compose build --no-cache
print_success "Docker images built"

docker compose up -d
print_success "Containers started"

print_info "Waiting for MySQL to be ready..."
sleep 10

# Verify MySQL is healthy
until docker compose exec -T mysql mysqladmin ping -h localhost -uroot -psecret --silent 2>/dev/null; do
    print_info "Waiting for MySQL..."
    sleep 3
done
print_success "MySQL is ready"

# =============================================================================
# STEP 5: Create Fresh Laravel Application
# =============================================================================
print_step "STEP 5: Creating fresh Laravel 12 application..."

# Create Laravel project using composer create-project (official method)
docker compose exec -T app composer create-project laravel/laravel:^12.0 temp-laravel --prefer-dist --no-interaction

# Move files from temp to root (excluding vendor which we'll reinstall)
docker compose exec -T app sh -c "mv temp-laravel/* temp-laravel/.[!.]* . 2>/dev/null || true && rm -rf temp-laravel"

# IMPORTANT: Reinstall composer dependencies because vendor is mounted as a separate volume
# This ensures all packages are available in the volume-mounted vendor directory
print_info "Installing composer dependencies in vendor volume..."
docker compose exec -T app composer install --no-interaction
print_success "Composer dependencies installed"

print_success "Laravel 12 installed"

# =============================================================================
# STEP 6: Configure Environment
# =============================================================================
print_step "STEP 6: Configuring environment..."

# Update .env with our settings
docker compose exec -T app sh -c "cat > .env << 'ENVEOF'
APP_NAME=Digitalabs
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=https://digitalabs.test

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=digitalabs
DB_USERNAME=digitalabs
DB_PASSWORD=secret

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log

VITE_APP_NAME=\"\${APP_NAME}\"
ENVEOF"

# Generate application key
docker compose exec -T app php artisan key:generate
print_success "Environment configured"

# =============================================================================
# STEP 7: Install Livewire & Filament
# =============================================================================
print_step "STEP 7: Installing Livewire & Filament..."

# Install Livewire (Filament dependency)
docker compose exec -T app composer require livewire/livewire:^4.0 --no-interaction
print_success "Livewire 4 installed"

# Install Filament (as per official docs)
docker compose exec -T app composer require filament/filament:^5.0 -W --no-interaction
print_success "Filament 5 installed"

# Setup Filament panels
docker compose exec -T app php artisan filament:install --panels --no-interaction
print_success "Filament admin panel configured"

# =============================================================================
# STEP 8: Setup Tailwind CSS v4 (Official Method)
# =============================================================================
print_step "STEP 8: Setting up Tailwind CSS v4..."

# Install Tailwind CSS v4 with Vite plugin (as per official docs)
docker compose exec -T app npm install tailwindcss @tailwindcss/vite --save-dev

# Update vite.config.js for Tailwind CSS v4
docker compose exec -T app sh -c "cat > vite.config.js << 'VITEEOF'
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
VITEEOF"

# Update resources/css/app.css for Tailwind v4 (just @import)
docker compose exec -T app sh -c 'echo "@import \"tailwindcss\";" > resources/css/app.css'

print_success "Tailwind CSS v4 configured"

# =============================================================================
# STEP 9: Build Frontend Assets
# =============================================================================
print_step "STEP 9: Building frontend assets..."

docker compose exec -T app npm install
docker compose exec -T app npm run build
print_success "Frontend assets built"

# =============================================================================
# STEP 10: Run Database Migrations
# =============================================================================
print_step "STEP 10: Running database migrations..."

docker compose exec -T app php artisan migrate --force
print_success "Database migrations completed"

# =============================================================================
# STEP 11: Create Storage Link
# =============================================================================
print_step "STEP 11: Creating storage symlink..."

docker compose exec -T app php artisan storage:link
print_success "Storage symlink created"

# =============================================================================
# STEP 12: Create Admin User
# =============================================================================
print_step "STEP 12: Creating Filament admin user..."

echo ""
print_info "Please enter admin user details:"
docker compose exec app php artisan make:filament-user

# =============================================================================
# STEP 13: Optimize Application
# =============================================================================
print_step "STEP 13: Optimizing application..."

docker compose exec -T app php artisan optimize:clear
docker compose exec -T app php artisan optimize
docker compose exec -T app php artisan filament:optimize
print_success "Application optimized"

# =============================================================================
# STEP 14: Trust SSL Certificate (macOS)
# =============================================================================
if [[ "$OSTYPE" == "darwin"* ]]; then
    print_step "STEP 14: Trusting SSL certificate (macOS)..."
    print_info "Adding certificate to system keychain (requires sudo)..."
    sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain docker/nginx/ssl/digitalabs.test.crt 2>/dev/null || true
    print_success "SSL certificate trusted"
fi

# =============================================================================
# COMPLETE!
# =============================================================================
echo ""
echo "╔══════════════════════════════════════════════════════════════════════╗"
echo "║                     ✅ SETUP COMPLETE!                               ║"
echo "╚══════════════════════════════════════════════════════════════════════╝"
echo ""
echo "📍 Access Points:"
echo "   ├── Application  : https://digitalabs.test"
echo "   ├── Admin Panel  : https://digitalabs.test/admin"
echo "   └── phpMyAdmin   : http://localhost:8080"
echo ""
echo "🐳 Docker Commands:"
echo "   ├── Start        : docker compose up -d"
echo "   ├── Stop         : docker compose down"
echo "   ├── Logs         : docker compose logs -f app"
echo "   ├── Shell        : docker compose exec app bash"
echo "   └── Artisan      : docker compose exec app php artisan <command>"
echo ""
echo "🔧 Development:"
echo "   ├── Watch Assets : docker compose exec app npm run dev"
echo "   ├── Build Assets : docker compose exec app npm run build"
echo "   └── Clear Cache  : docker compose exec app php artisan optimize:clear"
echo ""
echo "📚 Documentation: ./docs/"
echo ""
