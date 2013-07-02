#!/bin/bash

#TODO INSTALL SCRIPT
#integriere passwd wg initial pw?;
#ggf. auch ueber git: git clone git@github.com:netzwelt/sponionpi.git

if [ ! -f "install-init" ]; then
	#create lock
	echo "$(date +%s):">>install.log;
	touch install-init|tee -a install.log;

	echo "$(date +%s):">>install.log;
	echo "SPONionPi Installation gestartet"|tee -a install.log;

	#erst sudooers anlegen fuer www-data user (apache)
	echo "$(date +%s):">>install.log;
	echo "lege sudoers-datei an"|tee -a install.log;

	echo "$(date +%s):">>install.log;
	sudo cp www-data /etc/sudoers.d/|tee -a install.log;

	echo "$(date +%s):">>install.log;
	#setuid auf 0!
	sudo chmod 400 /etc/sudoers.d/www-data|tee -a install.log;
	sudo chown root:root /etc/sudoers.d/www-data|tee -a install.log

	#sys update
	echo "$(date +%s):">>install.log;
	echo "update Betriebssystem"|tee -a install.log;

	echo "$(date +%s):">>install.log|tee -a install.log;
	sudo apt-get update;
	sudo apt-get upgrade -y;

	#install
	echo "$(date +%s):">>install.log;
	echo "installiere benoetigte Software"|tee -a install.log;

	echo "$(date +%s):">>install.log;
	sudo apt-get install -y php5 apache2 tor hostapd isc-dhcp-server git screen|tee -a install.log;
	#ggf noch denyhosts

	#aendere hostname und eintrag in
	echo "$(date +%s):">>install.log;
	echo "aendere Rechnernamen auf SPONionPi"|tee -a install.log;

	echo "$(date +%s):">>install.log;
	sudo sed -i 's/127.0.1.1.*raspberry.*/127.0.1.1 SPONionPi/' /etc/hosts|tee -a install.log;

	echo "$(date +%s):">>install.log;
	sudo bash -c "echo 'SPONionPi' > /etc/hostname"|tee -a install.log;

	echo "$(date +%s):">>install.log;
	echo "Bitte starten Sie den Rechner neu, indem Sie den Strom unterbrechen."|tee -a install.log;
	echo "Fuehren Sie im Anschluss das Installationsskript ERNEUT(!) aus mit folgenden Befehlen";
	echo "		cd SPONionPi";
	echo "		sudo sh install.sh"
	
	sleep 100;
	#echo "$(date +%s):">>install.log;
	#echo "versuche eigenstaendigen Reboot"|tee -a install.log;

	#echo "$(date +%s):">>install.log;
	#sudo /etc/init.d/reboot stop|tee -a install.log;
	#trotzdem strom reboot
fi

echo "$(date +%s):">>install.log;
echo "fuehre Installation des sponionpi fort"|tee -a install.log;

echo "$(date +%s):">>install.log;
echo "richte netzwerk ein"|tee -a install.log;

#network interfaces
echo "$(date +%s):">>install.log;
sudo cp interfaces /etc/network/interfaces|tee -a install.log;

echo "$(date +%s):">>install.log;
sudo chmod 644 /etc/network/interfaces|tee -a install.log;

#setup for hostapd
echo "$(date +%s):">>install.log;
echo "richte zugangspunkt ein"|tee -a install.log;

echo "$(date +%s):">>install.log;
sudo mv /etc/hostapd/hostapd.conf /etc/hostapd/hostapd.old|tee -a install.log;

echo "$(date +%s):">>install.log;
sudo cp hostapd.conf /etc/hostapd/hostapd.conf|tee -a install.log;
sudo chmod 644 /etc/hostapd/hostapd.conf|tee -a install.log;

echo "$(date +%s):">>install.log;
sudo sed -i 's/.*DAEMON_CONF.*/DAEMON_CONF="\/etc\/hostapd\/hostapd.conf"/' /etc/default/hostapd|tee -a install.log;

#set  ipforward
sudo sed -i 's/.*net.ipv4.ip_forward=1/net.ipv4.ip_forward=1/' /etc/sysctl.conf;
sudo sh -c "echo 1 > /proc/sys/net/ipv4/ip_forward";

#modified hostap for rtl wlan driver
echo "$(date +%s):">>install.log;
echo "tausche hostapd gegen adafruit-version aus"|tee -a install.log;

echo "$(date +%s):">>install.log;
wget http://www.adafruit.com/downloads/adafruit_hostapd.zip|tee -a install.log;

echo "$(date +%s):">>install.log;
unzip adafruit_hostapd.zip|tee -a install.log;

#echo "$(date +%s):">>install.log;
#rm  adafruit_hostapd.zip|tee -a install.log;

echo "$(date +%s):">>install.log;
sudo mv /usr/sbin/hostapd /usr/sbin/hostapd.ORIG|tee -a install.log;

echo "$(date +%s):">>install.log;
sudo cp hostapd /usr/sbin|tee -a install.log;

echo "$(date +%s):">>install.log;
sudo chmod 555 /usr/sbin/hostapd|tee -a install.log;

#setup tor
echo "$(date +%s):">>install.log;
echo "richte Tor ein"|tee -a install.log;

echo "$(date +%s):">>install.log;
sudo mv /etc/tor/torrc /etc/tor/torrc.old|tee -a install.log;

echo "$(date +%s):">>install.log;
sudo cp torrc /etc/tor/torrc|tee -a install.log;
sudo chmod 644 /etc/tor/torrc|tee -a install.log;

#setup dhcp
echo "$(date +%s):">>install.log;
echo "richte dhcp ein"|tee -a install.log;

echo "$(date +%s):">>install.log;
sudo mv /etc/default/isc-dhcp-server /etc/default/isc-dhcp-server.old|tee -a install.log;
sudo mv /etc/dhcp/dhcpd.conf /etc/dhcp/dhcpd.conf.old

echo "$(date +%s):">>install.log;
sudo cp isc-dhcp-server /etc/default/isc-dhcp-server|tee -a install.log;
sudo chmod 644 /etc/default/isc-dhcp-server|tee -a install.log;
sudo cp dhcpd.conf /etc/dhcp/dhcpd.conf|tee -a install.log;
sudo chmod 644 /etc/dhcp/dhcpd.conf|tee -a install.log;

#setup apache, vorerst ohne pw und ssl?
echo "$(date +%s):">>install.log;
echo "richte apache ein"|tee -a install.log;
#wg default problemen mit .php apache setup

echo "$(date +%s):">>install.log;
echo "passe apache conf login an";
sudo cp apache_sponionpi.conf /etc/apache2/sites-available/|tee -a install.log;
sudo chmod 644 /etc/apache2/sites-available/apache_sponionpi.conf|tee -a install.log;

echo "$(date +%s):">>install.log;
sudo a2dissite default|tee -a install.log;
sudo a2ensite apache_sponionpi.conf|tee -a install.log;
#nichts an apache conf aendern und ports.conf aendern 
#TODO pw module apache

#pw file login apache kopieren
echo "$(date +%s):">>install.log;
sudo cp login /var/www/;
sudo chmod 640 /var/www/login;

echo "$(date +%s):">>install.log;
sudo cp sponionpi.png /var/www/;
sudo cp favicon.ico /var/www/;
sudo rm /var/www/index.html;
sudo cp index.php /var/www/;
sudo cp *.txt /var/www/;
sudo chmod 550 /var/www/index.php;

#skript iptables fuer hostapd und tor etc kopieren in /var/www
echo "$(date +%s):">>install.log;
sudo cp *.sh /var/www/;
sudo chmod 750 *.sh
sudo chown -R www-data /var/www/;

#remove lock
echo "$(date +%s):">>install.log;
rm install-init|tee -a install.log;

echo "$(date +%s):">>install.log;
echo "Installation war erfolgreich. Ihr SPONionPI ist gleich im Browser ueber die Adresse 'sponionpi.local' erreichbar"|tee -a install.log;
echo "Verbinden Sie sich dafuer mit dem WLAN namens SPONionPi-Tor. Das Standard-WPA-Passwort ist stets spiegelonline.";
echo "Bitte denken Sie daran auch die Tastatur abzuziehen, beide(!) USB-WLAN-Sticks einzustecken und das LAN-Kabel zu entfernen"|tee -a install.log;
echo "Bitte starten Sie ihren SPONionPi jetzt neu (unter Umstaenden sogar mehrmals), indem Sie den Strom unterbrechen"|tee -a install.log;

#trotzdem Strom-Reboot noetig!
sleep 12;
echo "$(date +%s):">>install.log;
echo "versuche eingenstaendigen Reboot"|tee -a install.log;

echo "$(date +%s):">>install.log;
sudo /etc/init.d/reboot stop|tee -a install.log;
