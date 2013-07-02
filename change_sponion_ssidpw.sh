#! /bin/bash

#updates ssid and pw for sponion
#updates login data for apache

ssid=$1;
pw=$2;

sudo sed -i 's/.*wpa_passphrase=.*/wpa_passphrase='$pw'/' /etc/hostapd/hostapd.conf;
sudo sed -i 's/.*ssid=.*/ssid='$ssid'/' /etc/hostapd/hostapd.conf;

#aendere auch apache
#speichere altes pw wegen wiederherstellung nach pw-verlust
sudo mv /var/www/login /var/www/login.old;
sudo htpasswd -cb /var/www/login $ssid $pw;

#aendere auch apache conf
sudo sed -i 's/.*Require user.*/Require user '$ssid'/' /etc/apache2/sites-available/apache_sponionpi.conf;
