#!/bin/bash

echo -e "Preparation du projet Symfony...\n"

if [ -f /home/vagrant/.linux ]; then
    sudo rm /vagrant/app/cache/ -rf &&  sudo mkdir -p /dev/shm/cache &&  sudo ln -s /dev/shm/cache/ /vagrant/app/cache &&  sudo chmod 777 /vagrant/app/cache/
    sudo rm /vagrant/app/logs/ -rf &&  sudo mkdir -p /dev/shm/logs &&  sudo ln -s /dev/shm/logs/ /vagrant/app/logs && sudo chmod 777 /vagrant/app/logs/


    composer install
    php app/console assetic:dump

    php app/console assets:install

    npm install --no-bin-links
    bower install --allow-root

    sudo chown www-data:vagrant /dev/shm/cache -R
    sudo chown www-data:vagrant /dev/shm/logs -R

    echo -e "Linux Fin !\n"
else
    mkdir -p app/cache app/logs

    sudo rm -rf app/cache/*
    sudo rm -rf app/logs/*

    composer install
    php app/console assetic:dump

    php app/console assets:install

    npm install --no-bin-links
    bower install --allow-root

    sudo chown www-data:vagrant app/cache -R
    sudo chown www-data:vagrant app/logs -R

    echo -e "Windows Fin !\n"
fi
