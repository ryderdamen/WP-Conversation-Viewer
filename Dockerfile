FROM wordpress:latest

# Install Composer
WORKDIR /
RUN curl -sS https://getcomposer.org/installer -o /composer-setup.php
RUN php /composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Add PHP Unit (and other composer-specified deps)
ADD composer.json /composer.json
RUN composer update

# Reset Workdir
WORKDIR /var/www/html
