FROM php:7.4-apache

RUN apt-get update && apt-get install -y curl git zip unzip openssh-client nano vim --no-install-recommends 

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get autoremove -y && apt-get clean && apt-get autoclean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Disable default VH on apache
RUN a2dissite 000-default.conf
RUN a2enmod rewrite

# Copy and enable the VH
COPY ./app.conf /etc/apache2/sites-available/
RUN a2ensite app.conf
RUN service apache2 restart
