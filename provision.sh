#!/bin/bash

sudo apt-get -y update
sudo apt-get -y install lamp-server^
sudo cp ./BlankSpace/192.168.20.10.conf /etc/apache2/sites-available/
sudo a2ensite 192.168.20.10
sudo service apache2 reload

cd /BlankSpace
php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
php -r "if (hash('SHA384', file_get_contents('composer-setup.php')) === '41e71d86b40f28e771d4bb662b997f79625196afcca95a5abf44391188c695c6c1456e16154c75a211d238cc3bc5cb47') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
php composer.phar self-update
sudo dd if=/dev/zero of=/swapfile bs=1024 count=512k
sudo mkswap /swapfile
sudo swapon /swapfile
sudo php composer.phar install

sudo adduser remote
# give it a password of 'remote'

cd /BlankSpace
cat blankspace.sql | mysql -u root -proot

sudo apt-get install openssl

sudo a2enmod ssl
sudo service apache2 restart
sudo mkdir /etc/apache2/ssl
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/apache2/ssl/apache.key -out /etc/apache2/ssl/apache.crt
# US
# Utah
# Provo
# BlankSpaceApp
# IT
# 192.168.20.10
# blankspaceapp@gmail.com
sudo cp /BlankSpace/192.168.20.10.secure.conf /etc/apache2/sites-available/
sudo a2ensite 192.168.20.10.secure.conf
sudo service apache2 restart
sudo apt-get install php5-curl
sudo service apache2 restart





