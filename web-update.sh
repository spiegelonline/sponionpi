#!/bin/bash

touch web-update-init;

sudo -u pi bash -c 'exec /home/pi/SPONionPi/update.sh';

rm web-update-init
