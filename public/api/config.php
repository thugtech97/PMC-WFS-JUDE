<?php
	$serverName = "172.16.20.28";
	// $serverName = "HO5CBAEFD50713L";
	$connectionInfo = array( "Database"=>"PMC-OREM", "UID"=>"sa", "PWD"=>"@Temp123!" );

	$conn = sqlsrv_connect($serverName, $connectionInfo);

	date_default_timezone_set("Asia/Manila");
?>
