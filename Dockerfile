# 使用 PHP 8.2 + Apache 的官方映像
FROM php:8.2-apache

# 安裝必要的套件與 PDO MySQL 驅動
RUN docker-php-ext-install pdo pdo_mysql

# 啟用 Apache rewrite 模組（若你使用 .htaccess）
RUN a2enmod rewrite

# 將當前專案所有檔案複製進容器中
COPY . /var/www/html/

# 設定 Apache 網頁根目錄的權限（避免權限錯誤）
RUN chown -R www-data:www-data /var/www/html
