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

			$insert = "insert into transactions (ref_req_no,source_app,source_url,details,requestor,totalamount,converted_amount,department,transid,email,status,created_at,currency,purpose,name,locsite) values ('".$_POST['refno']."','".$_POST['sourceapp']."','".$_POST['sourceurl']."','".$transaction_type."','".$_POST['requestor']."', '".$_POST['totalamount']."','".$_POST['converted_amount']."','".$_POST['department']."','".$_POST['transid']."','".$_POST['email']."','PENDING', GETDATE(),'".$_POST['currency']."','".$_POST['purpose']."','".$_POST['name']."','".$_POST['locsite']."'); SELECT SCOPE_IDENTITY()"; 

			$result = sqlsrv_query($conn, $insert); 
			sqlsrv_next_result($result);
	 		sqlsrv_fetch($result); 

	 	 	$insertedID = sqlsrv_get_field($result, 0); 

			if($result){

	 			$query = sqlsrv_query($conn,"select * from template_approvers where template_id = '".$data['template_id']."' ");

				while($qry = sqlsrv_fetch_array($query)){

					if($qry['is_dynamic']=='YES' && $qry['designation']=='MANAGER') {

							$gdept = sqlsrv_fetch_array(sqlsrv_query($conn,"select department from transactions where transid = '".$transid."' "));

							$gdivision = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where department like '%".$gdept['department']."%' "));
														
							$gmanager = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where  division like '%".$gdivision['division']."%' AND department like '%".$gdivision['department']."%' AND is_alternate = 0"));

							$alt_gm = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where  division like '%".$gdivision['division']."%' AND department like '%".$gdivision['department']."%' AND is_alternate = 1"));

							if(is_null($alt_gm)) { $alt_gm = ['id'	=> 0]; }

							sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at,is_current) values (".$insertedID.",'".$gmanager['id']."','".$alt_gm['id']."','".$qry['sequence_number']."','PENDING',GETDATE(),1) ");

					}elseif($qry['is_dynamic']=='YES' && $qry['designation']=='DIVISION MANAGER') {

							$gdept = sqlsrv_fetch_array(sqlsrv_query($conn,"select department,locsite from transactions where transid = '".$transid."' "));

							$gdivision = sqlsrv_fetch_array(sqlsrv_query($conn,"select division from users where department like '%".$gdept['department']."%' "));							
							if (empty($gdept['locsite'])) {
								$gdmanager = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where  designation like '%".$gdivision['division']."%' "));

								$alt_gdm = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where  designation like '%".$gdivision['division']."%' AND is_alternate = 1"));
								
							} else {
								$gdmanager = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where  designation like '%DAVAO HEAD OFFICE DIVISION%' "));

								$alt_gdm = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where  designation like '%DAVAO HEAD OFFICE DIVISION%' AND is_alternate = 1"));
							}

							if(is_null($alt_gdm)) { $alt_gdm = ['id'	=> 0]; }

							sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at,is_current) values (".$insertedID.",'".$gdmanager['id']."','".$alt_gdm['id']."','".$qry['sequence_number']."','PENDING',GETDATE(),0) ");
							

					}elseif(($qry['condition']==null || $qry['condition']=='') && $qry['designation']=='VERIFIER'){

						sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at,is_current) values (".$insertedID.",'".$qry['approver_id']."','".$qry['alternate_approver_id']."','".$qry['sequence_number']."','PENDING',GETDATE(),0) ");

						if (isset($_POST['locsite']) && $_POST['locsite']=='DAVAO') {

							sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at,is_current) values (".$insertedID.",112,0,3,'PENDING',GETDATE(),0) ");

							sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at,is_current) values (".$insertedID.",7,0,4,'PENDING',GETDATE(),0) ");

							break;
						}
						

					} else {						

						$gamount = sqlsrv_fetch_array(sqlsrv_query($conn,"select totalamount,converted_amount,locsite from transactions where transid = '".$transid."' "));	

						if ($gamount['converted_amount'] == '0.00'){

							if($gamount['totalamount']<=10000) {

								$gcondition = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from template_approvers where condition='<=10000' AND template_id='".$qry['template_id']."' "));

								$alt_gcondition = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from template_approvers where condition='<=10000' AND template_id='".$qry['template_id']."' AND is_alternate = 1"));

								if(is_null($alt_gcondition)) { $alt_gcondition = ['id'	=> 0]; }

								if(empty($gamount['locsite'])) {
								sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at,is_current) values (".$insertedID.",'".$gcondition['approver_id']."','".$alt_gcondition['id']."','".$qry['sequence_number']."','PENDING',GETDATE(),0) ");
								} else {
									sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at,is_current) values (".$insertedID.",112,0,3,'PENDING',GETDATE(),0) ");

									sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at,is_current) values (".$insertedID.",7,0,4,'PENDING',GETDATE(),0) ");
								}

										break;


							}elseif($gamount['totalamount']<=20000 && $gamount['totalamount']>10000){

								$gcondition = sqlsrv_query($conn,"select * from template_approvers where (condition='<=10000' OR condition='<=20000') AND template_id='".$qry['template_id']."' ");

								while($qrygcondition = sqlsrv_fetch_array($gcondition)){
									

								sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at,is_current) values (".$insertedID.",'".$qrygcondition['approver_id']."','".$qrygcondition['alternate_approver_id']."','".$qrygcondition['sequence_number']."','PENDING',GETDATE(),0) ");
										
									}

										break;

							}elseif($gamount['totalamount']>20000) {

								$gcondition = sqlsrv_query($conn,"select * from template_approvers where (condition='<=10000' OR condition='<=20000' OR condition='>20000') AND template_id='".$qry['template_id']."' ");

								while($qrygcondition = sqlsrv_fetch_array($gcondition)){

								sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at,is_current) values (".$insertedID.",'".$qrygcondition['approver_id']."','".$qrygcondition['alternate_approver_id']."','".$qrygcondition['sequence_number']."','PENDING',GETDATE(),0) ");
										
									}

										break;

							}
						} else {

							if($gamount['converted_amount']<=10000) {

								$gcondition = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from template_approvers where condition='<=10000' AND template_id='".$qry['template_id']."' "));

								sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at,is_current) values (".$insertedID.",'".$gcondition['approver_id']."','".$qry['alternate_approver_id']."','".$qry['sequence_number']."','PENDING',GETDATE(),0) ");

										break;


							}elseif($gamount['converted_amount']<=20000 && $gamount['converted_amount']>10000){

								$gcondition = sqlsrv_query($conn,"select * from template_approvers where (condition='<=10000' OR condition='<=20000') AND template_id='".$qry['template_id']."' ");

								while($qrygcondition = sqlsrv_fetch_array($gcondition)){
									

								sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at,is_current) values (".$insertedID.",'".$qrygcondition['approver_id']."','".$qrygcondition['alternate_approver_id']."','".$qrygcondition['sequence_number']."','PENDING',GETDATE(),0) ");
										
									}

										break;

							} elseif($gamount['converted_amount']>20000) {

								$gcondition = sqlsrv_query($conn,"select * from template_approvers where (condition='<=10000' OR condition='<=20000' OR condition='>20000') AND template_id='".$qry['template_id']."' ");

								while($qrygcondition = sqlsrv_fetch_array($gcondition)) {

								sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at,is_current) values (".$insertedID.",'".$qrygcondition['approver_id']."','".$qrygcondition['alternate_approver_id']."','".$qrygcondition['sequence_number']."','PENDING',GETDATE(),0) ");
										
									}

										break;

							}

						}

					}
					

				}

			}

		}

	}


?>