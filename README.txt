----------------
SPONionPi v0.1
----------------
Der SPONionPi ist ein frei verfuegbares Projekt von SPIEGEL ONLINE, das auf dem Onion Pi basiert.
Der SPONionPi ist ein auf der Raspberry-Pi-Hardware aufbauender WLAN-AP, der ueber ein Web-Interface gesteuert, eine Anbindung an das Tor-Netzwerk bietet.
Das Tor-Netzwerk anonymisiet so Internet-Verkehr.

------------------------
ANLEITUNG ZUM SELBERBAU
------------------------
Eine vollstaendige Anleitung zum SPONionPi-Projekt koennen sie unter
	http://spiegel.de/netzwelt/gadgets/raspberry-pi-bauanleitung-des-anonymisierenden-tor-routers-sponionpi-a-907568.html
einsehen.

-------------------
BUGS MELDEN
--------------------
Sollten Sie einen Fehler in der Software melden wollen, koennen Sie dies unter peter_gotzner@spiegel.de tun.

-------------------------------------
INSTALLATION UND STANDARDPASSWOERTER
-------------------------------------
Zur Installation gehen Sie bitte in ihren Download-Order/SPONionPi-Order und fuehren in der shell den Befehl:
        
	sudo sh install.sh

aus. Folgen Sie dann den Anweisungen.

Nach erfolgreicher Installation, dem Einstecken von zwei WLAN-USB-Sticks und einem Neustart ist der SPONionPi ueber das WLAN-Netzwerk "SPONionPi-Tor" (WPA-Passwort:spiegelonline) zu erreichen.

Sollte der Browser Sie nach Login-Daten fragen, so entsprecgeb die stets dem WLAN-Namen und dem gesetzten WLAN-Passwort.
Diese sind daher anfangs "SPONionPi-Tor" und "spiegelonline".
Bitte beachten Sie die Groß-und Kleinschreibung!

---------------------
KONFIGURATION
----------------------
Die URL des SPONionPi ueber die Sie ihn mit dem Browser konfigurieren können, ist in seinem eigenen WLAN immer:
	
	sponionpi.local

------------------------------
UPDATES UND DOWNLOAD
-------------------------------
Sie koennen die aktuelle Version der Software fuer den SPONionPi unter
	https://github.com/spiegelonline/sponionpi
finden.

Sollte sie ein update ueber die shell starten wollen, so wechseln ueber "cd" in ihren SPONionPi-Ordern und fuehren
	sudo sh update.sh 
aus.

Ueber das Web-Interface koennen Sie ueber den Button "SPONionPi updaten" ein Update durchfuehren.

-----------------------
LIZENZ UND HAFTUNG
-----------------------

Bitte beachten Sie, dass es sich bei der Bastelanleitung und Software von SPIEGEL ONLINE um einen redaktionellen Service handelt.
Das Netzwelt-Ressort von SPIEGEL ONLINE hat den SPONionPi zwar selbst ausfuehrlich getestet und die Anleitung nach bestem Wissen erstellt,
uebernimmt aber keinerlei Gewaehr für die Anleitung und das Endprodukt. Der Nachbau erfolgt auf eigenes Risiko, SPIEGEL ONLINE uebernimmt insofern keine Haftung.

Die Software des SPONionPi-Projekts steht unter der GPLv3, die sie in der beigefuegten Datei GPLv3.txt einsehen koennen.

Copyright (C) 2012 Peter Gotzner

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software Foundation,
Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301  USA
