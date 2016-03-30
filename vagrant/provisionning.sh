#!/bin/bash

#set timezone
echo "Europe/Paris" > /etc/timezone
dpkg-reconfigure --frontend noninteractive tzdata

# PHP
apt-get update && apt-get upgrade
apt-get install -y php5 php5-curl php5-imagick php5-mysql php5-memcached libssh2-php php5-xdebug php5-intl php5-gd php5-mongo php5-pgsql g++ apache2

echo "xdebug.max_nesting_level = 1000" >> /etc/php5/cli/php.ini

# Git
apt-get install -y git

# Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Node
curl -sL https://deb.nodesource.com/setup_5.x | sudo -E bash -
apt-get install -y nodejs
npm install -g bower
npm install -g grunt-cli

# MySQL
debconf-set-selections <<< 'mysql-server mysql-server/root_password password vagrant'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password vagrant'
apt-get -y install mysql-server

# Sqlite
apt-get install -y php5-sqlite

# Ruby environment
apt-get install -y ruby1.9.1-full
gem install bundler
cd /vagrant
bundle install --deployment

# sass
sudo su -c "gem install sass"

# Accept MySql connections from outside
sed -i 's/bind-address/#bind-address = 0.0.0.0 #/g' /etc/mysql/my.cnf
echo "bind-address 0.0.0.0" >> /etc/mysql/my.cnf
mysql -u root -pvagrant -e "GRANT ALL PRIVILEGES  on *.* to root@'%' IDENTIFIED BY 'vagrant'; FLUSH PRIVILEGES;"
service mysql restart

# Databases creation
echo "create database travel; create database travel_test;" | mysql -u root -pvagrant

# Applying agentfowarding
echo -e "Host *\n    ForwardAgent yes" > /home/vagrant/.ssh/config

# Set environment variables needed
echo "SetEnv APPLICATION_ENV 'dev'" > /etc/apache2/conf-available/vagrant.conf
ln -s /etc/apache2/conf-available/vagrant.conf /etc/apache2/conf-enabled/vagrant.conf

# Apache conf
VM_VHOST="
<VirtualHost *:80>
       SetEnv APPLICATION_ENV dev
       ServerName localhost
       DocumentRoot /vagrant/web
       <Directory /vagrant/web>
               Options All
               AllowOverride All
               Require all granted
       </Directory>
</VirtualHost>
"

VM_CONF_FILE="/etc/apache2/sites-available/lemondeensac.conf"

[[ -f "$VM_CONF_FILE" ]] || touch "$VM_CONF_FILE"
[[ ! -f "$VM_CONF_FILE" ]] || echo "$VM_VHOST" > "$VM_CONF_FILE"

a2enmod rewrite
a2ensite lemondeensac.conf
a2dissite 000-default.conf

service apache2 reload

# configure xdebug
libpath=$(find / -name 'xdebug.so' 2> /dev/null);
hostip=$(netstat -r | grep default | cut -d ' ' -f 10);
printf "zend_extension=\"$libpath\"\nxdebug.remote_enable=1\nxdebug.remote_handler=\"dbgp\"\nxdebug.remote_port=9001\nxdebug.remote_autostart=1\nxdebug.remote_mode=\"req\"\nxdebug.remote_host=\"$hostip\"\nxdebug.idekey=\"vagrant\"\nxdebug.remote_log=\"/var/log/xdebug/xdebug.log\"\n" > /etc/php5/mods-available/xdebug.ini
service apache2 restart

# We add gitlab to known hosts
#ssh-keyscan -H gitlab.rvip.fr > /etc/ssh/ssh_known_hosts
ssh-keyscan -H github.com > /etc/ssh/ssh_known_hosts

# syslog
echo "local0.* /vagrant/app/logs/dev.log" > /etc/rsyslog.d/fortress.conf
sed -i 's/#$ModLoad imudp/$ModLoad imudp/g' /etc/rsyslog.conf
sed -i 's/#$UDPServerRun 514/$UDPServerRun 514/g' /etc/rsyslog.conf
service rsyslog restart

# nfs shared folder
apt-get install cachefilesd
echo "RUN=yes" > /etc/default/cachefilesd
