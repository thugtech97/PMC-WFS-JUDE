<?php 
		
	$serverName = "172.16.20.43";
	$connectionInfo = array( "Database"=>"PMC-WFS", "UID"=>"apps_wfs", "PWD"=>"Phahd2fe" );
	$conn = sqlsrv_connect($serverName, $connectionInfo);

?>