<?php 

	include('config.php');

	header ("Access-Control-Allow-Origin: *");
	header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
	header ("Access-Control-Allow-Methods: POST");
	header ("Access-Control-Allow-Headers: *");
    
 //    echo "<pre>";
	// var_dump($_POST); 
	//die;
	
	$_POST['type'] = "OREM CASH ADVANCE ";
	$transaction_type =  $_POST['type']; // ex OREM CASH ADVANCE

	$_POST['transid'] = " CA-000001";
	$transid = $_POST['transid']; // ex CA-000001

	$_POST['token'] ="base64:Ql3H5Ng0whuQGF64YaQn6RiagxcIrINasHt4bLu3333=";
	$_POST['sourceapp'] = "Online Request Expense Monitoring";
	$_POST['refno'] = "1";
	$_POST['sourceurl']= "http://172.16.20.28:8010/report/ca/preview/1";
	$_POST['requestor'] = "ICT";
	$_POST['totalamount'] = "1250";
	$_POST['department'] ="INFORMATION AND COMMUNICATIONS TECHNOLOGY";


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

							echo "MANAGER ";
							

					}elseif($qry['is_dynamic']=='YES' && $qry['designation']=='DIVISION MANAGER') {

							$gdept = sqlsrv_fetch_array(sqlsrv_query($conn,"select department from transactions where transid = '".$transid."' "));

							$gdivision = sqlsrv_fetch_array(sqlsrv_query($conn,"select division from users where department like '%".$gdept['department']."%' "));							
							
							$gdmanager = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where  designation like '%".$gdivision['division']."%' "));

							sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at) values (".$insertedID.",'".$gdmanager['id']."','".$qry['alternate_approver_id']."','".$qry['sequence_number']."','PENDING',GETDATE()) ");

							echo "DIVISION MANAGER ";

					} else {

					sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at) values (".$insertedID.",'".$qry['approver_id']."','".$qry['alternate_approver_id']."','".$qry['sequence_number']."','PENDING',GETDATE()) ");

					echo "FIXED APPROVERS ";

					}
					

				}

			}

		}

	}

?>