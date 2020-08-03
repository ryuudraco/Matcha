FROM nginx:1.10

ADD docker/config/vhost.conf /etc/nginx/conf.d/default.conf