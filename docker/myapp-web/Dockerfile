FROM php:8.0-apache

  # Node.js
  ARG node_ver=14.15.1
  ARG node_path=/usr/local/lib/nodejs
  ARG node_file_name=node-v${node_ver}-linux-x64
  ENV PATH ${node_path}/${node_file_name}/bin:$PATH
  RUN curl https://nodejs.org/dist/v${node_ver}/${node_file_name}.tar.xz > ${node_file_name}.tar.xz \
    && mkdir -p $node_path \
    && tar xJvf ${node_file_name}.tar.xz -C $node_path
  
  # Composer
  ARG composer_ver=2.0.8
  ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
  RUN chmod +x /usr/local/bin/install-php-extensions && sync \
    && install-php-extensions @composer-${composer_ver}
  
  # その他パッケージ、PHP拡張
  RUN apt-get update \
    # unzip (composer create-project で必要)
    && apt-get install -y unzip \
    # Vim 
    && apt-get install -y vim \
    # PHP BCMath
    && docker-php-ext-install bcmath \
    # PHP GD
    && apt-get install -y zlib1g-dev libpng-dev libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    # PHP intl
    && apt-get install -y libicu-dev \
    && docker-php-ext-install intl \
    # PHP PDO MySQL
    && docker-php-ext-install pdo_mysql \
    # mod_rewrite 有効化
    && a2enmod rewrite

# php.iniを作成して編集
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini \
  && sed -i -e 's/^;date.timezone =$/date.timezone=Asia\/Tokyo/' /usr/local/etc/php/php.ini \
  && sed -i -e '/^;error_log = php_errors.log$/a error_log=\/dev\/stderr' /usr/local/etc/php/php.ini