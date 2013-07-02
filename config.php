<tr class="tech">
	<td class="category">Konfigurationsdateien</td>
</tr>
<tr class="tech">
	<td class="category">&nbsp;</td>
	<td>Zusammenfassung der Netzwerk-Konfiguration<br />/etc/network/interfaces auf dem SPONionPi</td>
	<td><textarea name="network_config" cols="50" rows="20" readonly="readonly"><?php echo $network_config ?></textarea></td>
</tr>
<tr class="tech">
	<td class="category">&nbsp;</td>
	<td>Zusammenfassung der TOR-Konfiguration<br /> /etc/tor/torrc auf dem SPONionPi</td>
	<td>
		<textarea name="tor_config" cols="50" rows="10" readonly>
		<?php echo $tor_config ?>
		</textarea>
	</td>
</tr>
<tr class="tech">
	<td class="category">&nbsp;</td>
	<td>Zusammenfassung der Hostap-Konfigurationn<br />/etc/hostapd/hostapd.conf auf dem SPONionPi</td>
	<td><textarea name="hostapd_config" cols="50" rows="10" readonly>
		<?php echo $hostapd_config ?></textarea>
	</td>
</tr>
<tr class="tech">
	<td class="category">&nbsp;</td>
	<td>Zusammenfassung der dhcp-Konfigurationn<br />/etc/dhcp/dhcp.conf auf dem SPONionPi</td>
	<td>
		<textarea name="hostapd_config" cols="50" rows="10" readonly>
			<?php echo $dhcpd_config ?>
		</textarea></td>
</tr>
<tr class="tech">
	<td class="category" class="tech">&nbsp;</td>
	<td>Zusammenfassung der isc-dhcp-Konfigurationn<br />/etc/default/isc-dhcp-server auf dem SPONionPi</td>
	<td>
		<textarea name="hostapd_config" cols="50" rows="10" readonly>
			<?php echo $isc_dhcp_config ?>
		</textarea></td>
</tr>
