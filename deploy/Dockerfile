ARG CONTEXT=.
ARG PSYKHE_BUILD_ID=unknown
ARG PSYKHE_SRC_ID=unknown
ARG PHP_VERSION=7.4
ARG PHP_VARIANT=apache
ARG PHP_TAG=${PHP_VERSION}-${PHP_VARIANT}
ARG PHP_IMAGE=php:${PHP_TAG}
ARG PHP_EXT_FUZZY_INSTALL=0
ARG PHP_EXT_INSTALL="\
    bcmath \
    pdo_mysql \
    zip \
"
ARG PHP_EXT_REQUIRE="\
    ${PHP_EXT_INSTALL} \
    ctype \
    fileinfo \
    json \
    mbstring \
    openssl \
    pdo \
    tokenizer \
    xml \
"
ARG PHP_EXT_GD_BUILD_DEPS="\
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
"
ARG PHP_EXT_XSL_BUILD_DEPS="\
  libxslt-dev \
"
ARG PHP_EXT_ZIP_BUILD_DEPS="\
  libzip-dev \
"
ARG PHP_EXT_BUILD_DEPS="\
  ${PHP_EXT_ZIP_BUILD_DEPS} \
"

ARG PHP_EXT_GD_RUN_DEPS="\
  libfreetype6 \
  libjpeg62-turbo \
  libpng16-16 \
"
ARG PHP_EXT_XSL_RUN_DEPS="\
  libxslt1.1 \
"
ARG PHP_EXT_ZIP_RUN_DEPS="\
  libzip4 \
"
ARG PHP_EXT_RUN_DEPS="\
  ${PHP_EXT_ZIP_RUN_DEPS} \
"
ARG RUN_DEPS="\
    gettext \
    msmtp \
    ${PHP_EXT_RUN_DEPS} \
"

FROM ${PHP_IMAGE} as php-extensions
ARG PHP_EXT_INSTALL
ARG PHP_EXT_FUZZY_INSTALL
ARG PHP_EXT_BUILD_DEPS

RUN set -ex && apt-get update && apt-get install -y ${PHP_EXT_BUILD_DEPS} --no-install-recommends
RUN [ "$(printf " %s " "${PHP_EXT_INSTALL}" | sed 's/.*\s\+\(gd\)\s\+.*/\1/')" != "gd" ] || docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN set -ex \
 && docker-php-ext-install $(php -r '\
    $extensions = $_SERVER["argv"]; array_shift($extensions);\
    $fuzzy = !!intval(array_shift($extensions));\
    $required = [];\
    $already = [];\
    foreach ($extensions as $extension) {\
      if (extension_loaded($extension)) {\
        $already[] = $extension;\
      } else {\
        $required[] = $extension;\
      }\
    }\
    if ($already) {\
      file_put_contents("php://stderr", "skipping installation of already-loaded extensions: ".implode(" ", $already)."\n");\
      if (!$fuzzy) exit(1);\
    }\
    echo implode(" ", $required);\
  ' ${PHP_EXT_FUZZY_INSTALL} ${PHP_EXT_INSTALL})

FROM ${PHP_IMAGE} as web-base
ARG PHP_EXT_REQUIRE
ARG RUN_DEPS
ARG PHP_UID=
ARG PHP_GID=

COPY --from=php-extensions /usr/local/lib/php /usr/local/lib/php
COPY --from=php-extensions /usr/local/include/php /usr/local/include/php
COPY --from=php-extensions /usr/local/etc/php /usr/local/etc/php
RUN set -ex \
 && apt-get update && apt-get install -y ${RUN_DEPS} --no-install-recommends && rm -rf /var/lib/apt/lists/* \
 && php -r '\
    $extensions = $_SERVER["argv"]; array_shift($extensions);\
    foreach ($extensions as $extension) {\
      if (!extension_loaded($extension)) {\
        file_put_contents("php://stderr", "Required extension \"$extension\" not loaded\n");\
        exit(1);\
      }\
    }\
    exit(0);\
  ' ${PHP_EXT_REQUIRE}

# httpd
RUN ( [ -z "${PHP_UID}" -o -z "${PHP_GID}" ] || sed -i "s/^\\(www-data\\):x:\\([0-9]*\\):\\([0-9]*\\):\\([^:]*\\):/\\1:x:${PHP_UID}:${PHP_GID}:\\4:/" /etc/passwd ) \
 && ( [ -z "${PHP_UID}" -o -n "${PHP_GID}" ] || sed -i "s/^\\(www-data\\):x:\\([0-9]*\\):\\([0-9]*\\):\\([^:]*\\):/\\1:x:${PHP_UID}:\\3:\\4:/" /etc/passwd ) \
 && ( [ -n "${PHP_UID}" -o -z "${PHP_GID}" ] || sed -i "s/^\\(www-data\\):x:\\([0-9]*\\):\\([0-9]*\\):\\([^:]*\\):/\\1:x:\\2:${PHP_GID}:\\4:/" /etc/passwd ) \
 && ( [ -z "${PHP_GID}" ] || sed -i "s/^\\(www-data\\):x:\\([0-9]*\\):/\\1:x:${PHP_GID}:/" /etc/group )
ENV HTTPDMOD expires headers proxy proxy_http rewrite
ENV APACHE_HEADER_ACCESS_CONTROL_ALLOW_ORIGIN *
RUN for mod in $HTTPDMOD; do a2enmod $mod; done

# php
FROM ${PHP_IMAGE} as composer
WORKDIR /tmp
RUN php -r '$expect = file_get_contents("https://composer.github.io/installer.sig");\
    copy("https://getcomposer.org/installer", "composer-setup.php");\
    if (hash_file("sha384", "composer-setup.php") === $expect) exit(0);\
    exit(1);'
RUN php composer-setup.php
RUN chmod a+x composer.phar

FROM web-base as web-util

# composer
COPY --from=composer /tmp/composer.phar /usr/bin/composer.phar
RUN ln -s /usr/bin/composer.phar /usr/local/bin/composer

FROM web-util as vendor
ARG CONTEXT
ADD ${CONTEXT}/composer.json /var/psykhe/
ADD ${CONTEXT}/composer.lock /var/psykhe/
RUN chmod -R a+rwX /var/psykhe
RUN cd /var/psykhe \
 && composer install --no-ansi --no-interaction --no-scripts --no-suggest --no-progress --prefer-dist

FROM scratch as context
ARG CONTEXT
ADD ${CONTEXT} /

FROM web-util as prebuild
COPY --from=context --chown=root . /var/psykhe
COPY --from=vendor --chown=root /var/psykhe/vendor /var/psykhe/vendor
RUN cd /var/psykhe && make check

FROM prebuild as build
RUN rm -rf /var/psykhe/deploy
RUN chmod -R a+rwX /var/psykhe
RUN cd /var/psykhe && make build
RUN chmod -R a-w /var/psykhe
RUN chmod -R u+w /var/psykhe/storage/framework/cache
RUN chown -R www-data:www-data /var/psykhe/storage/framework/cache
RUN chmod -R u+w /var/psykhe/storage/framework/views
RUN chown -R www-data:www-data /var/psykhe/storage/framework/views
RUN cd /var/psykhe && make test

FROM web-base as web
ARG PSYKHE_BUILD_ID
ARG PSYKHE_SRC_ID
COPY --from=build /var/psykhe /var/psykhe
COPY --from=context /deploy/etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY --from=context /deploy/entrypoint.d /usr/local/entrypoint/etc/entrypoint.d
COPY --from=context /deploy/entrypoint.sh /entrypoint.sh
RUN sed -i \
        -e "s/'x-psykhe-build', 'unknown'/'x-psykhe-build', '${PSYKHE_BUILD_ID}'/" \
        -e "s/'x-psykhe-src', 'unknown'/'x-psykhe-src', '${PSYKHE_SRC_ID}'/" \
        /var/psykhe/app/Http/Middleware/BuildHeaders.php
ENTRYPOINT ["/entrypoint.sh"]
WORKDIR /var/psykhe
