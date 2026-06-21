FROM php:8.4-apache

# เปิดใช้งาน mod_rewrite สำหรับระบบ Routing ของ Laravel
RUN a2enmod rewrite

# ติดตั้งส่วนเสริม pdo และ pdo_mysql สำหรับเชื่อมต่อฐานข้อมูล
RUN docker-php-ext-install pdo pdo_mysql

# เปลี่ยน Apache Document Root ไปยังโฟลเดอร์ public ของ Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY . /var/www/html/

# ปรับสิทธิ์โฟลเดอร์เก็บข้อมูลเพื่อให้ Apache เขียนไฟล์ได้
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
