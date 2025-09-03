<?php
	$serverName = "172.16.20.43";
	$connectionInfo = array( "Database"=>"PMC-OREM", "UID"=>"apps_orem", "PWD"=>"philsaga" );
	$conn = sqlsrv_connect($serverName, $connectionInfo);

	date_default_timezone_set("Asia/Manila");
?>
