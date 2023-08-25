FROM debian:bullseye-slim

ENV DEBIAN_FRONTEND noninteractive
ENV COMPOSER_HOME: '/home/docker/.composer'

RUN echo 'APT::Install-Recommends "0" ; APT::Install-Suggests "0" ;' > /etc/apt/apt.conf.d/01-no-recommended && \
    echo 'path-exclude=/usr/share/man/*' > /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    echo 'path-exclude=/usr/share/doc/*' >> /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    apt-get update && \
    apt-get --yes install apt-transport-https ca-certificates curl wget &&\
    wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg &&\
    sh -c 'echo "deb https://packages.sury.org/php/ bullseye main" > /etc/apt/sources.list.d/php.list' &&\
    apt-get update && \
    apt-get --yes install \
        curl \
        php8.1-cli \
        php8.1-curl \
        php8.1-xdebug \
        unzip &&\
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

COPY docker/xdebug.ini /etc/php/8.1/mods-available/nelson.ini
RUN phpenmod nelson

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN chmod +x /usr/local/bin/composer \
    && useradd -m docker \
    && mkdir -p /home/docker/.composer/cache \
    && chown -R 1000:1000 /home/docker/.composer
