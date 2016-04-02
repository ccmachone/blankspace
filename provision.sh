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



