FROM php:8.2-apache

# 啟用 mod_rewrite（必要的 Apache 模組）
RUN a2enmod rewrite

# 複製你專案的檔案到 Apache 的根目錄
COPY . /var/www/html/

# 設定權限
RUN chown -R www-data:www-data /var/www/html

# 設定上傳檔案大小（可選）
RUN echo "upload_max_filesize=10M\npost_max_size=10M" > /usr/local/etc/php/conf.d/uploads.ini
