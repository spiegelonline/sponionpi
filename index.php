<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta name="author" content="Peter Gotzner (peter_gotzner@spiegel.de)">
		<meta charset="UTF-8" content="">
		<!-- admin interface SPONionPi v0.1 2013-06-25 -->
		<?php $version="0.1"  ?>

		<title>SPONion Pi v<?php echo $version ?> admin interface</title>
		<?php $disclaimer = "Bitte beachten Sie, dass es sich bei der nachfolgenden Bastelanleitung 
				um einen redaktionellen Service handelt. Das Netzwelt-Ressort hat den SPONionPi zwar selbst ausf&uuml;hrlich getestet und die Anleitung nach bestem Wissen erstellt,
				&uuml;bernimmt aber keinerlei Gew&auml;hr für die Anleitung und das Endprodukt.
				Der Nachbau erfolgt auf eigenes Risiko, SPIEGEL ONLINE &uuml;bernimmt insofern keine Haftung."
		?>

		<style type="text/css">
			<!--
				html body {
					background-color: #AE0009;
					font-family: Arial;
					font-size: 120%;
					color: white;
				}
				
				A:link {
					color: white
				}

				table {
					border =0;
				}

				td {
					vertical-align: middle;
					padding: 15px;
				}

				A:visited {
					color: white;
				}
				
				#apply_button {
					padding: 25px;
				}

				.button {
					padding: 25px;
				}

				#error_log {
				}
				
				#disclaimer {
					font-style: italic;
					font-size: 120%;
				}

				.tech {
					visibility:hidden;
				}

				.category {
				}

				.info {
				}
	
				.impressum{
					text-align: center;
				}

				#wpa_pw_initial {
					font-style: italic;
				}
			-->
		</style>
		
		<script type="text/javascript">
 			//not in use
  			function mache_ip_readonly() {
   				var ip_textfieldA = document.getElementById("wlan0_ip_A");
   				var ip_textfieldB = document.getElementById("wlan0_ip_B");
   				var ip_textfieldC = document.getElementById("wlan0_ip_C");
   				var ip_textfieldD = document.getElementById("wlan0_ip_D");
   				var dropdown = document.getElementById("dhcp");
   				
   				var dropdown_auswahl = dropdown.selectedIndex;

    			if(dropdown_auswahl == 0) { //Auswahl dhcp
    				//ip_textfieldA.setAttribute("readOnly", "readOnly");
      				//check ob auch je drei ziffern,ggf alertmeldung 
      				ip_textfieldA.disabled=true;
      				ip_textfieldB.disabled=true;
      				ip_textfieldC.disabled=true;
      				ip_textfieldD.disabled=true;
    			} else if(dropdown_auswahl == 1) { //Auswahl manuell 
        			ip_textfieldA.disabled=false;
        			ip_textfieldB.disabled=false;
        			ip_textfieldC.disabled=false;
        			ip_textfieldD.disabled=false;
    			}
  			}

			function mache_pw_readonly() {
   				var pw_field1 = document.getElementById('router_pw1');
   				var pw_field2 = document.getElementById('router_pw2');
   				var dropdown = document.getElementById('router_encryption')
   				var dropdown_auswahl = dropdown.selectedIndex;
    			if(dropdown_auswahl == 1) { //Auswahl offen
     				pw_field1.disabled=true;
     				pw_field1.value="";   
     				pw_field2.disabled=true;
     				pw_field2.value="";
     			//TODO do not remove value  of pw as as soon as no wpa encryption chosen
    			} else if(dropdown_auswahl == 0) { //auswahl WPA
     				//maybe pw_field1.setAttribute('readOnly', '');
     				pw_field1.disabled=false;
				    pw_field2.disabled=false;
				}
			}

  			function show_messages(message) {
	  			if(message == null) {
		  		//do nothing
	  			} else {
		  			alert(message);
		  		}
			}
		</script>

		<?php
			error_reporting(E_ALL);

			$apply_status		= null;
			$apply_nachricht	= null;
			$error_nachricht	= null;
			
			$update_status=null;

			$set_tor_status = null;
			$set_hostapd_status= null;

			//tor exitnodes laendercodes
			$tor_countries = array("none" => "egal", "us" => "US", "de" => "DE", "uk" => "UK");
			$tor_country_now=$tor_countries["de"]; //default in Germany

			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				//injection verhindern: nur alphanumerisch, keine sonderzeichen!?
				$set_tor_status		= preg_replace("/[^0-9]+/","", $_POST['set_iptables_tor']);
				
				$set_hostapd_status	= preg_replace("/[^0-9]+/","", $_POST['set_iptables_hostapd']);
				
				$update_status		= preg_replace("/[^0-9]+/","", $_POST['update']); //via onclick 0 or 1 , but String!
				
				//even abort here for update or hostapd or tor start?
				
				$sponion_ssid		= preg_replace("/[^a-zA-Z0-9-._]+/","", $_POST['sponion_ssid']);

				//	ggf Sonderzeichen oder Umlaute in fremder SSID! also lockern: äÄüÜöÖ_- bzw
				$router_ssid		= preg_replace("/[^a-zA-Z0-9äÄüÜöÖ._-\s]+/","", $_POST['router_ssid']);

				$sponion_pw1		= preg_replace("/[^a-zA-Z0-9äÄüÜöÖ._-]+/","", $_POST['sponion_pw1']);
				$sponion_pw2		= preg_replace("/[^a-zA-Z0-9äÄüÜöÖ._-]+/","", $_POST['sponion_pw2']);

				
				//$dhcp					= preg_replace("/[^0-9]+/","", $_POST['dhcp']);

				//ip mit punkten
				//TODO ggf einzelne fields auswerten (3 ziffern)
				$wlan1_ip_fields	= preg_replace("/[^0-9.]+/","", $_POST['wlan1_ip_field_A'].".".$_POST['wlan1_ip_field_B'].".".$_POST['wlan1_ip_field_C'].".".$_POST['wlan1_ip_field_D']);

				//ggf Sonderzeichen Umlaute etc in pw; lockern aber keine "" etc
				$router_pw1			= preg_replace("/[^a-zA-Z0-9äÄüÜöÖ._-]+/","", $_POST['router_pw1']);
				$router_pw2			= preg_replace("/[^a-zA-Z0-9äÄüÜöÖ._-]+/","", $_POST['router_pw2']);

				$tor_country_now		= preg_replace("/[^A-Z]+/","", $_POST['tor_exit']);
				$encryption			= preg_replace("/[^a-zA-Z0-9]+/","", $_POST['router_encryption']);

			}

			function apply_config() {
				//schreibe alle param in hostapd.conf, interfaces torrc dhcp.conf
				//spaeter auch fuer interface wlan config siehe isc-dhcp-server
				
				//function should be able to see global vars!
				global $sponion_ssid, $sponion_pw1, $sponion_pw2;
				//status vars
				global $set_tor_status, $set_hostapd_status;
				global $router_ssid, $router_pw1, $router_pw2, $encryption;
				global $tor_countries, $tor_country_now;
				
				//global dhcp, wlan1

				//always save new router-ssid
				shell_exec("sudo sh /var/www/change_router_ssid.sh ".$router_ssid);
				$router_pw = null;

				//pw match ueberpruefen bei Verschluesselung
				if($encryption === "WPA"){
					if($router_pw1 === $router_pw2){
						$router_pw = $router_pw1;
						shell_exec("sudo sh /var/www/change_router_pw.sh ".$router_pw);
					} else {
						$error_nachricht="WLAN-Passwoerter router stimmen nicht &uuml;berein";
						return false;
					}
				//} elseif($encryption === "offen" or $encryption == ""){ //loeschen von wpa-psk
					//shell_exec("sudo sh /var/www/change_router_pw.sh #wpa-psk");
					//$router_pw="";
				//} else {//TODO temp
				//	shell_exec("sudo sh /var/www/change_router_pw.sh #wpa-psk");
					//$error_nachricht="error encryption setzen";
					//return false;
				}
	
				//sponion passphrase und ssid & pw match
				if($sponion_pw1 === $sponion_pw2){
					$sponion_pw = $sponion_pw1;
					//change 0ssid and pw for sponion in a script, in addition change apache conf for authentication with same credentials
					shell_exec("sh /var/www/change_sponion_ssidpw.sh ".$sponion_ssid." ".$sponion_pw.";");
					//restart of sponion needed anyway, therefore no apache restart!?
					//TODO apache restart after changed data?
				} else {
					$error_nachricht="WLAN-Passwoerter SPONionPi stimmen nicht &uuml;berein";
					return false;
				}
	
				//TODO ip of sponion in own wlan via hostapd
				//changes interfaces and torrc, dhcp
				//shell_exec("sudo sed -i -r -e '/.*wlan1.*/ { n;n; s/.*address.*/address ".$wlan1_ip_fields."/}' /etc/network/interfaces");
				//shell_exec("sudo sed -i 's/.*TransListenAddress.*/TransListenAddress ".$wlan1_ip_fields."/' /etc/tor/torrc");
				//shell_exec("sudo sed -i 's/.*DNSListenAddress.*/DNSListenAddress ".$wlan1_ip_fields."/' /etc/tor/torrc");
	
				//TODO dhcp set via fields
				//     if($dhcp == "dhcp"){
				//        interfaces:
				//       shell_exec("sudo sed -i 's/^iface wlan0 inet.*/iface wlan0 inet dhcp/' /etc/network/interfaces");
				//       shell_exec("sudo sed -i 's/^iface wlan0 inet.*/iface wlan0 inet static/' /etc/network/interfaces");
				//      } elseif($dhcp == "manuell"){
				//        hinzufuegen ip wlan0 sponionpi adresse und gateway???
				//     } else{
				//       $error_nachricht="error dhcp param uebernahme";
				//       return false;
				//      }
		
				//tor country
				if($tor_country_now !== $tor_countries["none"]){
					shell_exec("sudo sh /var/www/change_tor_country.sh ".$tor_country_now);
				} elseif ($tor_country_now == $tor_countries["none"]) {
				//aufruf von tor_country ohne param fuehrt zur "egal" einstellung:
					shell_exec("sudo sh /var/www/change_tor_country.sh;");
				} else {
					$error_nachricht = "Konnte Tor exitnode nicht aendern";
					return false; //stops apply config immediatly
				}

				if($set_tor_status == "1") {
					apply_iptables_hostapd(); //always start with making sponion pi a host with traffic forward
					apply_iptables_tor();
					$set_tor_status = 0; //reset status
				}
				//vielleicht sogar spaeter immer bei Aufruf der Seite gleich als Router ins Netz!
				if($set_hostapd_status = "1") { 	
					apply_iptables_hostapd();
					$set_hostapd_status = 0; //reset status
				}

				return true; //only the case if apply_config went well
			}

			function apply_iptables_hostapd() {
				shell_exec("sudo sh /var/www/iptables_hostapd.sh");
			}

			function apply_iptables_tor() {
				shell_exec("sudo sh /var/www/iptables_tor.sh");
			}

			if($_SERVER['REQUEST_METHOD'] == 'POST') { //only do this after post/after submit
				if($update_status == "1"){
					//installation via git, does not touch /var/www at all, since working system should not be touched
					shell_exec("sudo sh /var/www/web-update.sh");
					$apply_nachricht="F&uuml;hre Update durch.<br />Bitte in ein paar Minuten den SPONionPi durch Stromunterbrechung neu starten";
				}elseif($update_status != null) {
					$apply_status = apply_config(); //is stripping input fiels!
				}else {
					$apply_nachricht="Problem mit Update-Variable";
				}
			}

			//status message check
			if($apply_status!=null) { //apply_status is only null if there was no post/submit
				if($apply_status){ //apply was successful completely, shows the return value of apply_config
					$apply_nachricht = "Ihre Konfiguration ist &uuml;bernommen worden. Bitte starten Sie den SPONionPi dann durch Stromunterbrechung neu.";
				}
				if(!$apply_status) { //apply unsuccesful
					$apply_nachricht = "Problem beim Anwenden Ihrer Konfiguration.";
				}
			}

			//TODO: lsusb fuer wlan sticks?, ggf namen extrahieren?

			//auslesen und ggf ueberschreiben ok, apply_config schon durchgefuehrt vorher
			//TODO soll Werte Netzwerkin skripte einzeln auslagern: get_xyz
			$network_config     	= shell_exec("cat /etc/network/interfaces");
			$router_passphrase 	= shell_exec("cat /etc/network/interfaces|grep wpa-psk|cut -d' ' -f2");
			$router_ssid 		= shell_exec("cat /etc/network/interfaces|grep wpa-ssid|cut -d' ' -f2");

			//wlan0 ist Werte Verbindung zum anderen Router/Internet-Router ist werte
			$wlan0_ip_ist 		= shell_exec("/sbin/ifconfig|grep -A 5 wlan0|grep inet|cut -d':' -f2|cut -d' ' -f1");

			//wlan0 soll werte fuer config
			$wlan0_ip_config 	= shell_exec("cat /etc/network/interfaces|grep -A 2 wlan0|grep address|cut -d' ' -f2");
			list($wlan0_ip_config_A, $wlan0_ip_config_B, $wlan0_ip_config_C, $wlan0_ip_config_D) = explode(".", $wlan0_ip_config);

			//SOLL: eigene Adresse des SPONionPi im SPONionPi-WLAN, AP-IP; soll wert
			$wlan1_ip_config 	= shell_exec("cat /etc/dhcp/dhcpd.conf|grep routers|grep -v '#'|cut -d ' ' -f3|sed 's/;$//'");
			list($wlan1_ip_config_A, $wlan1_ip_config_B, $wlan1_ip_config_C, $wlan1_ip_config_D) = explode(".", $wlan1_ip_config);

			//Ist-Werte der Adresse des SPONionPi im SPONionPi-WLAN,
			//TODO Config values of wlan1 are in dhcp AND interfaces; primary
			//$wlan1_ip_current = shell_exec("cat /etc/network/interfaces|grep -A 2 wlan1|grep address|cut -d' ' -f2");

			$wlan1_ip_current = shell_exec("/sbin/ifconfig|grep -A 5 wlan1|grep inet|cut -d':' -f2|cut -d' ' -f1");

			//tor
			$tor_config = shell_exec("cat /etc/tor/torrc");
			$tor_active = shell_exec("sudo /etc/init.d/tor status|cut -d'.' -f1");

			//Access Point
			$hostapd_config			= shell_exec("cat /etc/hostapd/hostapd.conf");
			$hostapd_ssid			= shell_exec("cat /etc/hostapd/hostapd.conf|grep ssid|grep -v _|cut -d'=' -f2");
			$hostapd_channel 		= shell_exec("cat /etc/hostapd/hostapd.conf|grep channel|cut -d'=' -f2");
			$hostapd_interface  	= shell_exec("cat /etc/hostapd/hostapd.conf|grep interface|cut -d'=' -f2");
			$hostapd_wpa_passphrase = shell_exec("cat /etc/hostapd/hostapd.conf|grep wpa_passphrase|cut -d'=' -f2");

			//dhcp soll wert, sollte auch in interfaces gesetzt sein, automatische anpassung in beiden dateien ueber skript

			$hostapd_ip			= shell_exec("cat /etc/dhcp/dhcpd.conf|grep routers|grep -v '#'|cut -d ' ' -f3|sed 's/;$//'");
			$dhcpd_config		= shell_exec("cat /etc/dhcp/dhcpd.conf");
			$isc_dhcp_config	= shell_exec("cat /etc/default/isc-dhcp-server");

			//diagnosis
			function ping(){
				$ping_output=shell_exec("ping -c1 spiegel.de");
			}
			
			function get_ip(){
				//get ip via http
			}
		?>
	</head>

	<body onload="show_messages(<?php $apply_nachricht;echo $error_nachricht ?>)">

		<img src="sponionpi.png" alt="SPONionPI Logo"> Version <?php echo $version ?>
		<span id="disclaimer"><p><?php echo $disclaimer ?></p>
		<p>Eine ausf&uuml;hrliche Anleitung zum Bau des SPONionPi finden Sie auf
			<a href="http://spiegel.de/netzwelt/gadgets/raspberry-pi-bauanleitung-des-anonymisierenden-tor-routers-sponionpi-a-907568.html">SPIEGEL ONLINE: Bauanleitung des SPONionPi</a>
		</p>
		<p>Bitte fuehren Sie bei Problemen mit ihrem SPONionPi zuerst ein Update
		&uuml;ber den unteren Button durch, bevor sie einen <a href="mailto:peter_gotzner@spiegel.de">Fehler melden</a>
		</p>
		<p>ACHTUNG: Sobald sie ihren Internetverkehr zur Anonymiserung
			durch das Tor-Netzwerk leiten, ist diese Konfigurationsmaske nicht mehr
			erreichbar.<br /> Bitte starten Sie ihren SPONionPi dann ueber das Stromkabel
			neu, um ihn zu konfigurieren.
		</p>
		</span>

		<!-- tables sind "oldschool" - aber dafuer uebersichtlich und alles in einer datei ;-)-->
		<form action="index.php" method="post">
			<table id="table">
				<tr>
					<td colspan="3" id="log"><?php $apply_nachricht; echo $error_nachricht?></td>
				</tr>
				<tr>
					<td class="category"><h5>SPONionPi als Client</h5></td>
					<td class="info">Mit welchem fremden WLAN soll sich der SPONionPI verbinden?</td>
					<td class="status"><input type="textfield" size="20" name="router_ssid" id="router_ssid" value="<?php echo $router_ssid ?>"></td>
					<td class="tech">ssid router wlan0</td>
				</tr>
				<tr>
					<td class="category">&nbsp;</td>
					<td class="info">Wie ist das WLAN am fremden Internet-Router verschl&uuml;sselt?</td>
					<td class="status">
						<select name="router_encryption" id="router_encryption" size="1" onclick="mache_pw_readonly()">
							//TODO encryption status read from current status via php
							<option>WPA</option>
							<!--<option>offen</option> -->
						</select>
					</td>
					<td class="tech">enc router</td>
				</tr>
				<tr class="pw">
					<td class="category">&nbsp;</td>
					<td class="info">Wie lautet das WLAN-Passwort des fremden Internet-Routers?<br />ACHTUNG: Sonderzeichen fallen zur Sicherheit weg!</td>
					<td class="status pw"><input type="password" size="20" name="router_pw1" id="router_pw1"
						value="<?php echo $router_passphrase ?>"></td>
					<td class="tech">wpa-psk router</td>
				</tr>
				<tr class="match pw">
					<td>&nbsp;</td>
					<td>Bitte zur Kontrolle das WLAN-Passwort nochhmal eingeben</td>
					<td class="status pw match"><input type="password" size="20"
					name="router_pw2" id="router_pw2"
					value="<?php echo $router_passphrase ?>"></td>
					<td class="tech">wpa-psk router match</td>
				</tr>
		<!--	<tr>
	  				<td>&nbsp;</td>
          			<td class="info">Wie soll der SPONionPi seine IP-Adresse erhalten?<br /> dhcp (automatisch) ist h&auml;ufig ausreichend.</td>
	  				<td class="status">
	    				<select name="dhcp" id="dhcp" size="1" onClick="mache_ip_readonly()">
                			<option>dhcp</option>
                			<option>manuell</option>
            			</select>
	  				</td>
	  				<td class="tech">dhcp router fuer sponion-ip</td>
        		</tr>
        -->

				<tr>
					<td class="category">&nbsp;</td>
					<td class="info">IP-Adresse des SPONionPI am fremden Router <!-- wlan0 --></td>
					<td class="status">
						<?php echo $wlan0_ip_ist ?><br />
				<!--	<input type="text" size="3" disabled="true" id="wlan0_ip_A" value='<?php echo $wlan0_ip_config_A ?>' >.
            			<input type="text" size="3" disabled="true" id="wlan0_ip_B" value='<?php echo $wlan0_ip_config_B ?>' >.
            			<input type="text" size="3" disabled="true" id="wlan0_ip_C" value='<?php echo $wlan0_ip_config_C ?>' >.
            			<input type="text" size="3" disabled="true" id="wlan0_ip_D" value='<?php echo $wlan0_ip_config_D ?>' >
            	-->
            		</td>
            		<td class="tech">manual ip sponion@router</td>
				</tr>
				<tr>
					<td class="category"><h5>SPONionPi als Zugangspunkt</h5></td>
					<td class="info">Wie soll das vom SPONionPi erzeugte WLAN
						hei&szlig;en?</td>
					<td class="status"><input type="textfield" size="20"
					name="sponion_ssid" id="sponion_ssid"
					value="<?php echo $hostapd_ssid ?>"></td>
					<td class="tech">ssid sponion wlan1</td>
				</tr>
				<tr>
					<td class="category">&nbsp;</td>
					<td class="info">IP-Adresse des SPONionPI-Routers im eigenen
						WLAN (sponionpi.local):</td>
					<td class="status" id="ip_sponion-ap"><?php echo $wlan1_ip_current ?><br />
						<input type="hidden" name="wlan1_ip_field_A" id="wlan1_ip_field_A" size="3" value="<?php echo $wlan1_ip_field_A ?>">
						<input type="hidden" name="wlan1_ip_field_B" id="wlan1_ip_field_B" size="3" value="<?php echo $wlan1_ip_field_B ?>">
						<input type="hidden" name="wlan1_ip_field_C" id="wlan1_ip_field_C" size="3" value="<?php echo $wlan1_ip_field_C ?>">
						<input type="hidden" name="wlan1_ip_field_D" id="wlan1_ip_field_D" size="3" value="<?php echo $wlan1_ip_field_D ?>">
					</td>
					<td class="tech">ip sponion@sponion</td>
				</tr>
			<!--
				<tr>
	  				<td class="category">&nbsp;</td>
	  				<td class="info">Auf welchem Frequenz-Kanal soll der SPONionPi sein WLAN aufbauen?<br /> (channel)</td>
	  				<td class="status"><input type="textfield" size="20" id="hostapd_channel"value="<?php echo $hostapd_channel ?>" ></td>
				</tr>-->
			<!--
				<tr>
	  				<td class="category">&nbsp;</td>
          			<td class="info">Welcher WLAN-Stick soll der SPONionPi f&uuml;r den WLAN-Zugangspunkt nutzen?<br /> (interface) </td>
	  				<td class="status"><input type="textfield" size="20" name="hostapd_interface" value="<?php echo $hostapd_interface ?>"></td>
				</tr>
			-->
				<tr class="pw">
					<td class="category">&nbsp;</td>
					<td class="info">Wie soll das Kennwort f&uuml;r das
						SPONionPi-WLAN lauten?<br />mind. 8 Zeichen!, voreingestellt: <span
						id="wpa_pw_initial">spiegelonline</span>
					</td>
					<td class="status pw"><input type="password" size="20"
						name="sponion_pw1" id="sponion_pw1"
						value="<?php echo $hostapd_wpa_passphrase ?>"></td>
					<td class="tech">wpa2-psk,tkip sponion</td>
				</tr>
				<tr class="match pw">
					<td>&nbsp;</td>
					<td>Bitte zur Kontrolle das WLAN-Passwort nochhmal eingeben</td>
					<td class="status match pw"><input type="password" size="20"
						name="sponion_pw2" id="sponion_pw2"
						value="<?php echo $hostapd_wpa_passphrase ?>"></td>
					<td class="tech">wpa-psk sponion match</td>
				</tr>
			<!--<tr>
					<td class="category">Tor-Netzwerk zur Anonymisierung</td>
					<td class="info">L&auml;uft die Tor-Software auf dem SPONionPi?<br /> das heiSSt allein nocht NICHT, dass der Verkehr sicher umgeleitet wird</td>
					<td><?php echo $tor_active ?></td>
					<td class="tech">tor service</td>
				</tr>
			-->
			<!--
				<tr>
          			<td class="category">&nbsp;</td>
					<td class="info">Wird ihr Internetverkehr derzeit &uuml; das Tor-Netzwerk geleitet?</td>
          			<td><?php echo "" ?></td>
        			//TODO Show status of iptables configs
        		</tr>
        	-->
				<tr>
					<td class="category">&nbsp;</td>
					<td class="info">Welches als Herkunftsland f&uuml;r Tor?<br />gegen
						YouTube-Sperren: US
					</td>
					<td class="status=">
						<select name="tor_exit" id="tor_exit" size="1">
							<option><?php echo $tor_country_now ?></option>
							<?php 
								foreach($tor_countries as $value) {
									//TODO remove selected tor country from options!
									//if(!$value == $tor_country_now) {
										echo "<option>".$value."</option>";
									//}
								}
								
							?>
						</select>
					</td>
					<td class="tech">tor exitnode country</td>
				</tr>
				<tr>
					<td class="category">&nbsp;</td>
					<td class="info">Wollen Sie ihren Internet-Verkehr ab jetzt
					&uuml;ber das Tor-Netzwerk leiten?</td>
					<td class="status"><input type="hidden" name="set_iptables_tor"
						id="set_iptables_tor" value="0"><input type="submit"
						class="apply button"
						onclick="document.getElementById('set_iptables_tor').value='1'"
						value="&uuml;ber Tor-Netzwerk surfen"></td>
					<td class="tech">set iptables hostapd and tor</td>
				</tr>
			
				<!--<tr>
	  				<td class="category">Firewall (iptables)</td>
	 				<td class="info">Internetverkehr &uuml;ber den SPONionPi-Router leiten?r</td>

				
	 				<td class="status">-->
						<input type="hidden" id="set_iptables_hostapd" value="0">
					<!--	<input type="submit" onClick="document.getElementById('set_iptables_hostapd').value='1'" value="Internet-Traffic &uuml;ber SPONioPi leiten"></td>
				</tr>-->
			
				<tr>
	  				<td class="info">Wollen Sie die Konfiguration jetzt &uuml;bernehmen?<br />anschlie&szlig;ender Neustart &uuml;ber Stromzufuhr n&ouml;tig</td>
					<td id="apply_td">
						<input type="submit" name="apply_button"
							id="apply_button" class="button apply"
							value="Konfiguration &uuml;bernehmen">
					</td>
				</tr>
				<tr>
					<td>Updates f&uuml;r den SPONionPi installieren (Neustart!):</td>
					<td>   
                                                <input type="hidden" name="update" id="update" value="0">
                                                <input type="submit" class="button update" onclick="document.getElementById('update').value='1'"
                                                value="SPONionPi updaten"></td>
                                        </td>   
				</tr>
				<tr>
					<td class="impressum" colspan="3">

						Diese Software steht unter der GPLv3, die Sie <a href="GPLv3.txt">hier</a> einsehen koennen.
					</td>	
				</tr>
				<tr>

					<td class="impressum" colspan="3">
							Den Quellcode und Updates koennen Sie unter <a href="https://github.com/spiegelonline/sponionpi">https://github.com/spiegelonline/sponionpi</a>
							herunterladen.
					</td>
				<tr>
					<td class="impressum" colspan="3">	Eine Kurzanleitung finden Sie in der <a href="README.txt">README.txt</a>.
				</tr>
				<tr>	
					<td class="impressum" colspan="3">
							<a href="http://www.spiegel.de/netzwelt">SPON Netzwelt</a>
					</td>
				<tr>
					<td class="impressum" colspan="3">
							Fehler bitte <a href="mailto:peter_gotzner@spiegel.de">hier</a> melden.
					</td>
				</tr>

			<!-- show configs here-->
			<?php //include 'config.php' ?>
			<!-- show configs here, end -->

			<!--
				<tr class="tech">
					<td class="category" class="tech">Diagnose</td>
				</tr>

				<tr class="tech">
				  <td class="category" class="tech">&nbsp;</td>
				  <td><input type="button" name="ping spiegel.de" onCLick="window.location-href = /'index.php?action=ping" value="ping spiegel.de">
				  <td><textarea name="ping_output" cols="50"></textarea></td>
				</tr>
			-->
			</table>
		</form>
	</body>
</html>
