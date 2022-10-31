FROM php:8.1-apache
LABEL author="Carlos J. Infante Ramos<dundivet@gmail.com>"

ENV APP_ENV "prod"

WORKDIR /var/www/html

# APACHE CONFIG
RUN a2enmod rewrite &&\
  echo '\
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

# COMPOSER & NODE DEPENDENCIES
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - &&\
  curl -sL https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /usr/share/keyrings/yarnkey.gpg >/dev/null &&\
  echo "deb [signed-by=/usr/share/keyrings/yarnkey.gpg] https://dl.yarnpkg.com/debian stable main" | tee /etc/apt/sources.list.d/yarn.list &&\
  curl -fsSL https://composer.github.io/installer.sig | tr -d '\n' > installer.sig &&\
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" &&\
  php -r "if (hash_file('SHA384', 'composer-setup.php') === file_get_contents('installer.sig')) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" &&\
  php composer-setup.php --install-dir="/usr/local/bin" --filename=composer &&\
  php -r "unlink('composer-setup.php'); unlink('installer.sig');"

RUN apt-get update

RUN apt-get install -y \
    zip \
    libpq-dev \
    nodejs \
    yarn &&\
  docker-php-ext-install -j$(nproc) pgsql pdo_pgsql opcache

# CLEANING UP
RUN apt-get clean &&\
  apt-get autoremove -y &&\
  rm -r /var/lib/apt/lists/*
