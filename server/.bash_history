certbot -d musicwithamaster.com -d www.musicwithamaster.com --manual --preferred-challenges dns certonly
ls -al /etc/nginx/conf.d
sudo nano /etc/nginx/conf.d/wordpress_http.conf
