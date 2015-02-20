FROM tutum/apache-php

RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get -yq install \
        php5-redis && \
    rm -rf /var/lib/apt/lists/*

ADD apache.default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

WORKDIR /data
RUN ln -s /data /var/www/muzar

CMD ["/run.sh"]

