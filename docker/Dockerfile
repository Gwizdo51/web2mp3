# # SSL certificate creation
# FROM debian:bookworm AS certificate_generator_image
# RUN apt-get update
# RUN apt-get install -y openssl
# VOLUME [ "/mnt" ]
# # ENTRYPOINT [ "/bin/bash" ]
# ENTRYPOINT [ "openssl" ]
# # CMD [ "req", "-x509", "-nodes", "-days", "365", \
# #     "-newkey", "rsa:2048", \
# #     "-keyout", "/mnt/self.key", \
# #     "-out", "/mnt/self.crt", \
# #     "-subj", "/C=FR/ST=Dev/L=Local/O=LocalDev/OU=Dev/CN=localhost" ]

# PHP-FPM processor + Composer image
FROM php:8.4-fpm AS app_image
# ARG UID=1000
# ARG GID=1000
# ARG USERNAME=user
# install required PHP extensions
# https://github.com/mlocati/docker-php-extension-installer
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
# RUN install-php-extensions @composer intl bcmath pdo_mysql zip
RUN install-php-extensions @composer pdo_pgsql
RUN rm /usr/local/bin/install-php-extensions
# install ca-certificates to enable sending HTTPS requests to the windows server
# RUN apt-get update
# RUN apt-get install -y ca-certificates
# set the working directory
# RUN groupadd -g ${GID} ${USERNAME}
# RUN useradd -mNu ${UID} -g ${GID} -s /bin/bash ${USERNAME}
# USER ${USERNAME}
WORKDIR /var/www

# queue workers + yt-dlp image
FROM app_image AS supervisor_image
# install supervisor and ffmpeg
RUN apt-get update
RUN apt-get install -y supervisor ffmpeg
# install yt-dlp
RUN curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp_linux -o /usr/local/bin/yt-dlp
# allow anyone to run yt-dlp
RUN chmod a+x /usr/local/bin/yt-dlp
# run supervisor in the foreground
CMD ["supervisord", "-nc", "/etc/supervisor/conf.d/queue_worker.conf"]

FROM app_image AS laravel_image
# ARG USERNAME
# USER root
RUN composer global require laravel/installer
RUN chmod a+rx /root
ENV PATH="/root/.composer/vendor/laravel/installer/bin:${PATH}"
ENTRYPOINT ["laravel"]
# USER ${USERNAME}

# NGINX web server image
FROM nginx:1.29 AS nginx_image
# redirect default and error channels to the appropriate log files
RUN ln -sf /dev/stdout /var/log/nginx/access.log
RUN ln -sf /dev/stderr /var/log/nginx/error.log
# set the working directory
WORKDIR /var/www
