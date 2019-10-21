FROM php:7.3.10-apache

RUN apt-get update  && \
    apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip
RUN mkdir /opt/eqdkp-files && \
    cd /opt/eqdkp-files; curl https://github.com/EQdkpPlus/core/archive/2.3.17.zip
#RUN cd /opt/eqdkp-files; mkdir extract; unzip 2.2.14.zip -d extract; mv extract/* . ; rm -fr extract
#RUN chown -R www-data:www-data /opt/eq*
#ADD eqdkp.conf /etc/nginx/sites-available
#RUN ln -s /etc/nginx/sites-available/eqdkp.conf /etc/nginx/sites-enabled/eqdkp.conf
#RUN rm /etc/nginx/sites-enabled/default
#RUN mkdir /run/php
#ADD init.sh /usr/local/bin
#RUN chmod +x /usr/local/bin/init.sh
#CMD ["/usr/local/bin/init.sh"]