FROM ubuntu:trusty
MAINTAINER Fernando Mayo <fernando@tutum.co>

# Install base packages
RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get -yq install \
        curl \
        apache2 \
        libapache2-mod-php5 \
        php5-mysql \
        php5-gd \
        php5-curl \
        php-pear \
        php5-redis \
        php-apc && \
    rm -rf /var/lib/apt/lists/* && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    sed -i "s/variables_order.*/variables_order = \"EGPCS\"/g" /etc/php5/apache2/php.ini && \
    echo "realpath_cache_size = 4096k" >> /etc/php5/apache2/php.ini && \
    echo "realpath_cache_ttl = 7200" >> /etc/php5/apache2/php.ini && \
    echo "upload_max_filesize = 64M" >> /etc/php5/apache2/php.ini && \
    echo "post_max_size = 64M" >> /etc/php5/apache2/php.ini && \
    echo "xdebug.remote_autostart=0\nxdebug.remote_enable=0\nxdebug.profiler_enable=0\n" >> /etc/php5/apache2/php.ini

# Permission fuckup
RUN usermod -u 1000 www-data

# Add image configuration and scripts
ADD initialize.sh /initialize.sh
ADD run.sh /run.sh
RUN chmod 755 /*.sh


EXPOSE 80

ADD apache.default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

RUN ln -s /data /var/www/muzar
WORKDIR /data

CMD ["/run.sh"]