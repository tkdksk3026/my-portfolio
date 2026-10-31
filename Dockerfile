FROM php:8.3-fpm

# 必要なシステムパッケージとPHPの拡張機能をインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nginx \
    gettext-base

# PHPの拡張機能をインストール
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 作業ディレクトリの設定
WORKDIR /var/www/html

# ソースコードをコピー
COPY . .

# composer.jsonからlaravel/sailを除外して本番インストールを実行
RUN composer remove laravel/sail --dev --no-interaction || true
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 権限の調整
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Nginxの設定ファイルを配置
COPY default.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# 起動スクリプトを配置して権限を付与
COPY start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]