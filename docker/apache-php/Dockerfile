FROM tutum/apache-php

ADD apache.default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

WORKDIR /data
RUN ln -s /data /var/www/muzar

CMD ["/run.sh"]

