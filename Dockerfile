FROM php:8.1-apache
LABEL author="Carlos J. Infante Ramos<dundivet@gmail.com>"

ENV APP_ENV "prod"

WORKDIR /var/www/html

# APACHE CONFIG
RUN echo '\
<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    DirectoryIndex index.php\n\
\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Order Allow,Deny\n\
        Allow from All\n\
\n\
        FallbackResource /index.php\n\
\n\
        <IfModule mod_rewrite.c>\n\
            Options MultiViews FollowSymLinks\n\
            RewriteEngine On\n\
            RewriteCond %{REQUEST_FILENAME} !-f\n\
            RewriteRule ^(.*)$ index.php [QSA,L]\n\
        </IfModule>\n\
    </Directory>\n\
\n\
    ErrorLog /var/log/apache2/error.log\n\
    CustomLog /var/log/apache2/access.log combined\n\
</VirtualHost>\n\
' > /etc/apache2/sites-enabled/000-default.conf
