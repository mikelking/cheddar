#!/bin/bash -ex

## Provisioning:

# This script will take a raw instance of Ubuntu 14.04 Trusty and turn it into
# LAMP stack suitable for developing this website.

# Default MySQL passwords to 'password'
echo "mysql-server mysql-server/root_password password password" | debconf-set-selections
echo "mysql-server mysql-server/root_password_again password password" | debconf-set-selections


# Find a newer version of nodejs in this repository
add-apt-repository -y ppa:chris-lea/node.js
apt-get update

# Install the standard packages. PHP 5 in Ubuntu Trusty is 5.5.9 as of 5/2015.
apt-get -y install \
    build-essential \
    vim curl git-core subversion htop \
    php5 php5-cli php5-cgi php5-intl php5-mcrypt php5-xdebug php5-dev php5-xsl php5-curl php-apc php-pear \
    openjdk-7-jre openjdk-7-jre-headless \
    mysql-server mysql-client php5-mysql \
    apache2 libapache2-mod-php5 \
    xvfb firefox \
    nodejs

# Since brunch does not have an apt-get package we'll use npm
sudo npm install -g brunch

