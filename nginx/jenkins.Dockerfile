FROM nginx:stable-alpine

ENV TZ="Asia/Jakarta"
ENV PS1="\u@\h:\w\\$ "

RUN apk add --no-cache bash tzdata

# ✅ Copy nginx configuration
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

# ✅ COPY ALL Laravel directories and files
COPY app/ /var/www/html/app/
COPY bootstrap/ /var/www/html/bootstrap/
COPY config/ /var/www/html/config/
COPY public/ /var/www/html/public/
COPY routes/ /var/www/html/routes/
COPY storage/ /var/www/html/storage/
COPY tests/ /var/www/html/tests/
COPY scripts/ /var/www/html/scripts/

# ✅ COPY root files
COPY artisan /var/www/html/artisan
COPY composer.json /var/www/html/composer.json
COPY composer.lock /var/www/html/composer.lock
COPY .env.example /var/www/html/.env.example
COPY phpunit.xml /var/www/html/phpunit.xml
COPY vite.config.js /var/www/html/vite.config.js
COPY package.json /var/www/html/package.json
COPY package-lock.json /var/www/html/package-lock.json
COPY README.md /var/www/html/README.md
COPY test_consumer.php /var/www/html/test_consumer.php

# ✅ COPY config files
COPY .editorconfig /var/www/html/.editorconfig
COPY .gitattributes /var/www/html/.gitattributes
COPY .gitignore /var/www/html/.gitignore
COPY .prettierrc /var/www/html/.prettierrc

# Set proper permissions
RUN chown -R nginx:nginx /var/www/html && \
    chmod -R 755 /var/www/html

EXPOSE 80