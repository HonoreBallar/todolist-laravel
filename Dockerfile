# 🧱 Étape 1 : Builder Node + Vite
FROM node:18 AS node-builder
WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build


# 🐘 Étape 2 : Image PHP avec extensions SQLite
FROM php:8.2-fpm

# 🔧 Installations Linux + extensions PHP
RUN apt-get update && apt-get install -y \
    sqlite3 libsqlite3-dev \
    zip unzip git curl \
    libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_sqlite

# 📦 Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 📁 Dossier de travail Laravel
WORKDIR /var/www/html

# 🗃️ Copier le code source
COPY . .

# 📂 Copier les assets compilés
COPY --from=node-builder /app/public/build public/build

# 🔑 Installation Laravel
RUN composer install --optimize-autoloader
RUN php artisan key:generate

# 🔒 Autorisations
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
