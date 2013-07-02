#!/bin/bash

sudo iptables -F;
sudo iptables -t nat -F;
sudo iptables -t nat -A PREROUTING -i wlan1 -p udp --dport 53 -j REDIRECT --to-ports 53;
sudo iptables -t nat -A PREROUTING -i wlan1 -p tcp --syn -j REDIRECT --to-ports 9040;
sudo sh -c "iptables-save > /etc/iptables.ipv4.nat";
