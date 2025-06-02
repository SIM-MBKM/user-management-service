FROM nginx:stable-alpine

ENV TZ="Asia/Jakarta"
ENV PS1="\u@\h:\w\\$ "

RUN apk add --no-cache bash
RUN apk add --no-cache tzdata

COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

# ✅ JENKINS: Copy entire Laravel project for nginx access
COPY . /var/www/html

# Set proper permissions
RUN chown -R nginx:nginx /var/www/html && \
    chmod -R 755 /var/www/html

EXPOSE 80