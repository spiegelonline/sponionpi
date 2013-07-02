#! /bin/bash

sudo sed -i 's/.*wpa-psk.*/wpa-psk '$1'/' /etc/network/interfaces;
