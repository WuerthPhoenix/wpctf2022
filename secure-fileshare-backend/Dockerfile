FROM rockylinux:8

WORKDIR /var/www/html/

RUN dnf module enable php:8.0 -y

RUN dnf -y install httpd wget unzip

RUN dnf -y install epel-release

RUN dnf -y install php php-fpm php-mysqlnd php-opcache php-gd php-xml php-mbstring php-zip # php-pecl-xdebug

RUN dnf -y install supervisor

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"

COPY conf/supervisord.ini /etc/supervisord.d/
COPY conf/xdebug.ini /etc/php.d/
COPY conf/php-fpm-log-to-stdout.conf /etc/php-fpm.d/

# fix apache
COPY conf/no-dir-index-apache.conf /etc/httpd/conf.d/
RUN rm -f /etc/httpd/conf.d/welcome.conf

COPY flag-deadc0de /

COPY . .
RUN mv src api
RUN rm -rf conf db
RUN rm -rf composer.lock
RUN composer install
RUN rm -rf composer* .git .gitignore Dockerfile

RUN mkdir /run/php-fpm

RUN chown apache:apache uploads

RUN echo 'Header set Access-Control-Allow-Origin "*"' >> /etc/httpd/conf.d/h.conf

ENTRYPOINT [ "/usr/bin/supervisord", "--nodaemon" ]
