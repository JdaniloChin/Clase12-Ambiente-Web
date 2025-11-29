# Usar la imagen oficial de PHP con APACHE
FROM php:8.2-apache

# Instalar extensiones necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli

#Habilitar mod_rewrite 
RUN a2enmod mod_rewrite

#configurar apache para permitir .htaccess
RUN echo  '<Directory /var/www/html>\n\
        AllowOverride All\n\
        Require all granted\n\
        </Directory>' > /etc/apache2/conf-availabe/override.conf && \
        a3enconf override

# copiar codigo fuente
COPY src/ /var/www/html

# establecer permisos
RUN chown -R www-data:www-data /var/www/html \
        && chmod -R 755 /var/www/html

# exponer el puerto
EXPOSE 80