<?php 

	include('config.php');

	header ("Access-Control-Allow-Origin: *");
	header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
	header ("Access-Control-Allow-Methods: POST");
	header ("Access-Control-Allow-Headers: *");
     
	$transaction_type =  $_POST['type']; // ex OREM CASH ADVANCE	
	$transid = $_POST['transid']; // ex CA-000001
	

	$data = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from allowed_transactions where name = '".$transaction_type."' "));	

	if(isset($_POST['token'])){

		// orem submitted token request == orem token saved on workflow
		if($_POST['token'] == $data['token']){

			$insert = "insert into transactions (ref_req_no,source_app,source_url,details,requestor,totalamount,department,transid,status,created_at) values ('".$_POST['refno']."','".$_POST['sourceapp']."','".$_POST['sourceurl']."','".$transaction_type."','".$_POST['requestor']."', '".$_POST['totalamount']."','".$_POST['department']."','".$_POST['transid']."','PENDING', GETDATE()); SELECT SCOPE_IDENTITY()"; 

			$result = sqlsrv_query($conn, $insert); 
			sqlsrv_next_result($result);
	 		sqlsrv_fetch($result); 

	 		$insertedID = sqlsrv_get_field($result, 0); 

			if($result){

	 			$query = sqlsrv_query($conn,"select * from template_approvers where template_id = '".$data['template_id']."' ");

				while($qry = sqlsrv_fetch_array($query)){

					if($qry['is_dynamic']=='YES' && $qry['designation']=='MANAGER') {

							$gdept = sqlsrv_fetch_array(sqlsrv_query($conn,"select department from transactions where transid = '".$transid."' "));

							$gdivision = sqlsrv_fetch_array(sqlsrv_query($conn,"select division from users where department like '%".$gdept['department']."%' "));
							
							$gmanager = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where  division like '%".$gdivision['division']."%' "));

							sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at) values (".$insertedID.",'".$gmanager['id']."','".$qry['alternate_approver_id']."','".$qry['sequence_number']."','PENDING',GETDATE()) ");

					}elseif($qry['is_dynamic']=='YES' && $qry['designation']=='DIVISION MANAGER') {

							$gdept = sqlsrv_fetch_array(sqlsrv_query($conn,"select department from transactions where transid = '".$transid."' "));

							$gdivision = sqlsrv_fetch_array(sqlsrv_query($conn,"select division from users where department like '%".$gdept['department']."%' "));							
							
							$gdmanager = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where  designation like '%".$gdivision['division']."%' "));

							sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at) values (".$insertedID.",'".$gdmanager['id']."','".$qry['alternate_approver_id']."','".$qry['sequence_number']."','PENDING',GETDATE()) ");
							

					}elseif(($qry['condition']==null || $qry['condition']=='') && $qry['designation']=='SUPERVISOR'){

						sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at) values (".$insertedID.",'".$qry['approver_id']."','".$qry['alternate_approver_id']."','".$qry['sequence_number']."','PENDING',GETDATE()) ");
						

					} else {						

						$gamount = sqlsrv_fetch_array(sqlsrv_query($conn,"select totalamount from transactions where transid = '".$transid."' "));	

						if($gamount['totalamount']<=10000) {

							$gcondition = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from template_approvers where condition='<=10000' "));

							sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at) values (".$insertedID.",'".$gcondition['approver_id']."','".$qry['alternate_approver_id']."','".$qry['sequence_number']."','PENDING',GETDATE()) ");

									break;


						}elseif($gamount['totalamount']<=20000 && $gamount['totalamount']>10000){

							$gcondition = sqlsrv_query($conn,"select * from template_approvers where condition='<=10000' OR condition='<=20000' ");

							while($qrygcondition = sqlsrv_fetch_array($gcondition)){
								

							sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at) values (".$insertedID.",'".$qrygcondition['approver_id']."','".$qrygcondition['alternate_approver_id']."','".$qrygcondition['sequence_number']."','PENDING',GETDATE()) ");
									
								}

									break;

						}else {

							$gcondition = sqlsrv_query($conn,"select * from template_approvers where condition='<=10000' OR condition='<=20000' OR condition='>20000' ");

							while($qrygcondition = sqlsrv_fetch_array($gcondition)){

							sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at) values (".$insertedID.",'".$qrygcondition['approver_id']."','".$qrygcondition['alternate_approver_id']."','".$qrygcondition['sequence_number']."','PENDING',GETDATE()) ");
									
								}

									break;

						}

					}
					

				}

			}

		}

	}

	$start_time = microtime(true); 
	$val=1; 
	  
	// Start loop 
	for($i = 1; $i <=99999; $i++) 
	{ 
	    $val++; 
	}  
	  
	// End the clock time in seconds 
	$end_time = microtime(true); 
	  
	// Calculate the script execution time 
	$execution_time = ($end_time - $start_time); 
	  
	$log =  " It takes ".$execution_time." seconds to execute the script"; 

	file_put_contents('./log_'.date("j.n.Y").'.log', $log, FILE_APPEND);

?>