FROM php:fpm

RUN apt-get update && apt-get install -y --no-install-recommends --no-install-suggests -qq \
    git \
    unzip \
    locales \
    zlib1g-dev \
    libicu-dev

# SQLITE FOR TEST UNITS
RUN apt-get install -y sqlite3 libsqlite3-dev

RUN apt-get update && apt-get -y install cron

ADD birthday-cron /etc/cron.d/birthday-cron
RUN chmod 0644 /etc/cron.d/birthday-cron
RUN crontab /etc/cron.d/birthday-cron
RUN touch /var/log/cron.log
CMD cron && tail -f /var/log/cron.log

# Enable locales
RUN sed -i 's/# en_GB.UTF-8 UTF-8/en_GB.UTF-8 UTF-8/' /etc/locale.gen \
    && sed -i 's/# de_DE.UTF-8 UTF-8/de_DE.UTF-8 UTF-8/' /etc/locale.gen \
    && sed -i 's/# fr_FR.UTF-8 UTF-8/fr_FR.UTF-8 UTF-8/' /etc/locale.gen \
    && locale-gen

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Lisbon /etc/localtime
RUN "date"

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

RUN docker-php-ext-configure intl
# Type docker-php-ext-install to see available extensions
RUN docker-php-ext-install pdo pdo_mysql zip intl opcache

RUN usermod -u 1000 www-data

# Libreoffice need write rights in the www-data home directory
RUN chown -R www-data: /var/www

RUN mkdir -p /var/www/backend && chown -R www-data: /var/www/backend

WORKDIR /var/www/backend
