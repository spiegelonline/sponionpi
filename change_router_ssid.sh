#! /bin/bash

sudo sed -i 's/.*wpa-ssid.*/wpa-ssid '$1'/' /etc/network/interfaces;
