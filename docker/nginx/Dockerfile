FROM nginx:1.15.8
ADD ./default.conf /etc/nginx/conf.d/
RUN echo "daemon off;" >> /etc/nginx/nginx.conf
CMD service nginx start