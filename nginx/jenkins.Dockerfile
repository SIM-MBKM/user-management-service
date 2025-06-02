FROM nginx:stable-alpine

ENV TZ="Asia/Jakarta"
ENV PS1="\u@\h:\w\\$ "

RUN apk add --no-cache bash
RUN apk add --no-cache tzdata

# ✅ TAMBAHAN: Copy nginx configuration ke container (bukan mount)
COPY default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80