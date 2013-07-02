#!/bin/bash

#SPONionPi
#update.sh fuer system und anschliessend sponionpi
#2013-06-26 v0.1

datum=$(date +%s);
current=$(pwd);

cd /home/pi/SPONionPi;
echo "fuehre update durch";
#echo "$(date +%s):">>update.log;
touch update-init;
#|tee -a update.log;

echo "$(date +%s):">>update.log;
sudo apt-get update;
#|tee -a update.log;

echo "$(date +%s):">>update.log;
echo "fuehre system-upgrade durch"|tee -a update.log;
sudo apt-get upgrade -y
#|tee -a update.log;

cd ~;
#echo "lade update ueber git herunter";

#cd sponionpi-$datum;
echo "starte Aktualisierung ueber git";
cd /home/pi/SPONionPi;
git pull;
sudo -u pi bash -c 'cd /home/pi/SPONionPi/; sh git-update.sh;';

#wechsle in alten update ordner
#cd $current;
#echo "$(date +%s):">>update.log;
rm update-init|tee -a update.log;

echo "update beendet";
