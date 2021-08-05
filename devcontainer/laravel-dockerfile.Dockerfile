FROM ubuntu:20.04

ARG DEBIAN_FRONTEND=noninteractive

# Update packages and install apache2
RUN \
apt-get update -y \
&& apt-get install apache2 -y

# Install php@7.3 and curl and other php extensions
RUN \
apt-get install software-properties-common -y \
&& add-apt-repository ppa:ondrej/php \
&& apt-get install php7.3 -y \
&& apt-get install curl -y \
&& apt-get install php7.3-xml -y \
&& apt-get install php7.3-mbstring -y \
&& apt-get install php7.3-curl -y \
&& apt-get install php7.3-zip -y \
&& apt-get install php7.3-gd -y

# Copy our laravel application into container
COPY . app

# Get composer
RUN \
curl https://getcomposer.org/installer > install-composer.php \
&& php install-composer.php \
&& mv composer.phar /usr/bin/composer \
&& rm install-composer.php

# Remove apache site configurations
RUN \
cd /etc/apache2/sites-available \
&& rm -rf * \
&& cd .. \
&& cd sites-enabled \
&& rm -rf *

# Copy our own apache configuration
COPY ./devcontainer/site.conf /etc/apache2/sites-available

# configure apache
RUN \ 
a2ensite site.conf \
&& a2enmod rewrite \
&& service apache2 restart

# Install dependencies
RUN cd /app && composer install

# Copy .env from .env.example
RUN \
cd /app \
&& cp .env.example .env \
&& php artisan key:generate

# Set ENV parameters
ENV APP_URL http://localhost:8000

# Set www-data owner to application
RUN chown www-data: -R app

CMD [ "apachectl", "-D", "FOREGROUND" ]