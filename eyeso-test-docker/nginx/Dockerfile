FROM nginx

RUN apt-get update && apt-get install -yqq --no-install-recommends --no-install-suggests \
    cron \
    git \
    unzip \
    curl

RUN curl -sL https://deb.nodesource.com/setup_7.x | bash -

RUN apt-get install -yqq --no-install-recommends --no-install-suggests \
    nodejs

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Lisbon /etc/localtime

# Remove default nginx conf
RUN rm /etc/nginx/conf.d/default.conf

RUN usermod -u 1000 www-data

RUN mkdir -p /var/www/frontend && chown www-data: /var/www/frontend

WORKDIR /var/www/frontend

CMD nginx

EXPOSE 80
EXPOSE 443
