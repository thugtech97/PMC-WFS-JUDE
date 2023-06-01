<?php 
		
	// $serverName = "172.16.20.28";
	$serverName = "HO5CBAEFD50713L";	
	// $connectionInfo = array( "Database"=>"PMC-WFS", "UID"=>"sa", "PWD"=>"philsaga" );
$connectionInfo = array( "Database"=>"PMC-WFS", "UID"=>"sa", "PWD"=>"@Temp123!" );
	$conn = sqlsrv_connect($serverName, $connectionInfo);

?>