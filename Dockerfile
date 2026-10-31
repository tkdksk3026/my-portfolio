FROM php:8.3-fpm

# 1. 必要なシステムパッケージとPHPの拡張機能をインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    nginx

# 2. PHPの拡張機能をインストール（PDO, Mbstring, BCMath, PCRE等）
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# 3. Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. 作業ディレクトリの設定
WORKDIR /var/www/html

# 5. ソースコードをコピー
COPY . .

# 6. 依存関係のインストール（本番用）
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 7. 権限の調整
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Nginxの設定（デフォルト設定を公開ディレクトリに向ける）
# Renderではポート 10000 などが使われるため、必要に応じてNginxの設定を調整します
EXPOSE 80

# コンテナ起動時にNginxとPHP-FPMを同時に起動するスクリプトを指定するか、
# デフォルトの起動コマンドを設定します