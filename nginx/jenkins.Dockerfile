FROM nginx:stable-alpine

ENV TZ="Asia/Jakarta"
ENV PS1="\u@\h:\w\\$ "

RUN apk add --no-cache bash tzdata

# Copy nginx configuration
COPY default.conf /etc/nginx/conf.d/default.conf

# ✅ JENKINS: Copy project and handle src structure
COPY . /tmp/project

# ✅ Move Laravel files from src to /var/www/html
RUN mkdir -p /var/www/html && \
    if [ -d "/tmp/project/src" ]; then \
        echo "Moving Laravel from src/ to /var/www/html..."; \
        cp -r /tmp/project/src/* /var/www/html/ 2>/dev/null || true; \
        cp -r /tmp/project/src/.* /var/www/html/ 2>/dev/null || true; \
        echo "Laravel files moved successfully"; \
    else \
        echo "No src directory found, copying project directly..."; \
        cp -r /tmp/project/* /var/www/html/ 2>/dev/null || true; \
        cp -r /tmp/project/.* /var/www/html/ 2>/dev/null || true; \
    fi

# Clean up
RUN rm -rf /tmp/project

# Set proper permissions
RUN chown -R nginx:nginx /var/www/html && \
    chmod -R 755 /var/www/html

EXPOSE 80