#! /bin/bash

if [ -z "$1" ]
then
	#no country selectd
	sudo sed -i 's/.*ExitNodes.*/#ExitNodes/' /etc/tor/torrc;
	sudo sed -i 's/.*StrictNodes.*/#StrictNodes 1/' /etc/tor/torrc;
else
	sudo sed -i 's/.*ExitNodes.*/ExitNodes \{'$1'\}/' /etc/tor/torrc;
	sudo sed -i 's/.*StrictNodes.*/StrictNodes 1/' /etc/tor/torrc;
fi

