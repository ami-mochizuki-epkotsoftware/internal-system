FROM php:8.1.8-apache
# php.iniをコピー
COPY ./php.ini /usr/local/etc/php/
# composer インストール (マルチステージビルド)
COPY --from=composer:2.3.10 /usr/bin/composer /usr/bin/composer
# apt(Advanced Package Tool) パッケージインストール
# PDO driver インストール(mysql)
# mod_rewrite 有効化
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    vim \
    zip \
    npm \
    && docker-php-ext-install pdo_mysql \
    && a2enmod rewrite

# DocumentRoot 変更
RUN sed -i 's!/var/www/html!/var/www/root/public!g' /etc/apache2/sites-available/000-default.conf
