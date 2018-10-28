#!/usr/bin/php
<?php
define('TAB', "\t");

/* --- FUNCTIONS --- */
function getDiskInfos($diskName) {
	$diskInfos = array_values(array_filter(explode(' ', exec('df -lh | grep '.$diskName))));
	return array_combine(array('location', 'size', 'used', 'free', 'percent', 'mountPoint'), $diskInfos);
}

function checkService($serviceName) {
  $result = exec('service '.$serviceName.' status | sed -n 3p | cut -d " " -f5');
	return chr(27).($result == 'active' ? '[0;32m' : '[0;31m').$result.chr(27).'[0m';
}

echo '----- Device Monitor -----'.PHP_EOL;

/* --- DISKS --- */
$disks = array('sda1', 'sda2', 'sda3');
foreach($disks as $diskName) {
	$diskInfos = getDiskInfos($diskName);
	echo PHP_EOL.'> Disk usage : '.$diskInfos['mountPoint'].' ('.$diskInfos['location'].')'.PHP_EOL;
	echo TAB.'Total : '.$diskInfos['size'].' | Free : '.$diskInfos['free'].' | Used : '.$diskInfos['used'].' ('.$diskInfos['percent'].')'.PHP_EOL;
}

/* --- CPU --- */
echo PHP_EOL.'> Load Average :'.PHP_EOL;
echo TAB.exec('cat /proc/loadavg').PHP_EOL;

/* --- SERVICES --- */
echo PHP_EOL.'> Services :'.PHP_EOL;
echo TAB.'Apache '.checkService('apache2').PHP_EOL;
echo TAB.'Mysql '.checkService('mysql').PHP_EOL;
echo '- OpenVPN '.checkService('openvpn').PHP_EOL;
