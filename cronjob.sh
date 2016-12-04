#!/bin/bash

run-one-until-success sudo apt-get update -y
sleep 30
echo "Installing apache2,php-xml,php,php-mysql,curl,zip,unzip,git"
run-one-until-success sudo apt-get install -y apache2 php php-xml php-mysql curl php-curl zip unzip git libapache2-mod-php php7.0-xml php7.0-cli php-gd
echo "Installed apache2,php-xml,php,php-mysql,curl,zip,unzip,git"
sleep 30
echo "Setting the environment vairable"
export COMPOSER_HOME=/root && /usr/bin/composer.phar self-update 1.0.0-alpha11
echo "Installing Composer"
curl -sS https://getcomposer.org/installer | php
php composer.phar require aws/aws-sdk-php

sudo systemctl enable apache2
echo "Starting apache2 server" 
sudo systemctl start apache2

echo "Cloning the website"
sudo git clone git@github.com:illinoistech-itm/sreddy7.git

sudo mv /vendor /var/www/html
sudo mv /sreddy7/IIT-logo.png /var/www/html
sudo mv /sreddy7/edit.php /var/www/html

echo "Place in /var/spool/cron folder..."
(crontab -1 2>/dev/null; echo "*/3 * * * * /usr/bin/php /var/www/html/edit.php") | crontab -

sleep 30

echo "Apache server Restarting";
sudo service apache2 restart

echo "Cron job executed successfully";

