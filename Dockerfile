FROM richarvey/nginx-php-fpm:latest

# ソースコードをコピー
COPY . .

# 環境変数の設定
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1

# 依存関係のインストール（本番用）
RUN composer install --no-dev --optimize-autoloader

# 権限の調整
RUN chown -R www-data:www-data /var/www/html