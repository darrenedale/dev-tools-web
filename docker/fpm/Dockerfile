FROM darrenedale/equit:php-8.2-fpm
RUN printf "dev-tools\ndev-tools\n" | adduser -u 1000 -G www-data -h /srv/dev-tools dev-tools
USER dev-tools
