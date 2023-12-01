FROM darrenedale/equit:php-8.2-cli
RUN printf "dev-tools\ndev-tools\n" | adduser -u 1000 -G www-data -h /dev-tools dev-tools
USER dev-tools
