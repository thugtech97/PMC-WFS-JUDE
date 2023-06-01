<?php
include("config.php");

      header ("Access-Control-Allow-Origin: *");
      header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
      header ("Access-Control-Allow-Methods: POST");
      header ("Access-Control-Allow-Headers: *");
// print_r($_POST); die();
$token = "base64:Hxle0o3dpTUGQlpJy3dBbMhlDu9Y98uMqZEqFe/Upcs="; // workflow app_key

$id               = $_POST['trans_id'];
$workflow_token   = $_POST['workflow_token'];
$details          = $_POST['details'];
$approver         = $_POST['approver'];
$current_approver = $_POST['current_approver'];
$approver_remarks = $_POST['approver_remarks'];
$status           = $_POST['overallstatus'];
$overallstatus    = $_POST['status'];
$nextapprover     = $_POST['nextapprover'];


if($token == $workflow_token){


	// $sql_insert = "INSERT INTO transactions (trans_id
 //      ,details
 //      ,status
 //      ,approver
 //      ,approver_remarks
 //      ,created_at
 //      ,updated_at
 //       )
 //      VALUES ('".$id."'
 //      ,'".$details."'
 //      ,'".$status."'
 //      ,'".$approver."'
 //      ,'".$approver_remarks."'
 //      ,GETDATE()
 //      ,GETDATE())";

      // die($sql_insert);

//	$insert = sqlsrv_query($conn,$sql_insert);

    if ($details == 'OREM CASH ADVANCE' || $details =='DAVAO OREM CASH ADVANCE'){
        $table ="caheaders";
    }elseif ($details=='OREM TRAVEL ORDER' || $details =='DAVAO OREM TRAVEL ORDER'){
        $table ="toheaders";
    }elseif ($details =='OREM LIQUIDATION' || $details =='DAVAO OREM LIQUIDATION'){
        $table ="liqheaders";
    }else{
        $table ="rfpheaders";
    }


    //  if ($details == 'Cash Advance'){
    //       $table ="caheaders";
    // }elseif ($details=='Travel Order'){
    //       $table ="toheaders";
    // }elseif ($details =='Liquidation'){
    //       $table ="liqheaders";
    // }else{
    //       $table ="rfpheaders";
    // }

    if($status!='CANCELLED'){

      if (($details!='OREM LIQUIDATION' || $details!='DAVAO OREM LIQUIDATION') && $status=='FULLY APPROVED') {

        $saccounting='Processing Request';

        // $sql_update = "UPDATE ".$table." SET status='".$status."', overallstatus='".$overallstatus."', saccounting='".$saccounting."' ,current_approver='".$approver."', next_approver='".$nextapprover."', approver_remarks='".$approver_remarks."', stages=CONCAT(stages,'->','".$approver."',' ',FORMAT (GETDATE(), 'MM-dd-yyyy hh:mm:ss ')) WHERE id=".$id;

         $sql_update = "UPDATE ".$table." SET status='".$status."', overallstatus='".$overallstatus."', saccounting='".$saccounting."' ,current_approver='".$approver."', next_approver='".$nextapprover."', approver_remarks='".$approver_remarks."', stages=CONCAT(stages,'->','".$status."','>','".$approver."', ' ','(',FORMAT (GETDATE(), 'MM-dd-yyyy hh:mm:ss '),')') WHERE id=".$id;

      } elseif (($details!='OREM LIQUIDATION' || $details!='DAVAO OREM LIQUIDATION') && $status!='FULLY APPROVED') {

        // $sql_update = "UPDATE ".$table." SET status='".$status."', overallstatus='".$overallstatus."', current_approver='".$approver."', next_approver='".$nextapprover."', approver_remarks='".$approver_remarks."', stages=CONCAT(stages,'->','".$approver."',' ',FORMAT (GETDATE(), 'MM-dd-yyyy hh:mm:ss ')) WHERE id=".$id;

         $sql_update = "UPDATE ".$table." SET status='".$status."', overallstatus='".$overallstatus."', current_approver='".$approver."', next_approver='".$nextapprover."', approver_remarks='".$approver_remarks."', stages=CONCAT(stages,'->','".$status."','>','".$approver."', ' ','(',FORMAT (GETDATE(), 'MM-dd-yyyy hh:mm:ss '),')') WHERE id=".$id;

      } else {
            $saccounting='Liquidated';

            // $sql_update = "UPDATE ".$table." SET status='".$status."', overallstatus='".$overallstatus."', saccounting='".$saccounting."', current_approver='".$approver."', next_approver='".$nextapprover."', approver_remarks='".$approver_remarks."', stages=CONCAT(stages,'->','".$approver."', ' ',FORMAT (GETDATE(), 'MM-dd-yyyy hh:mm:ss ')) WHERE id=".$id;

             $sql_update = "UPDATE ".$table." SET status='".$status."', overallstatus='".$overallstatus."', saccounting='".$saccounting."', current_approver='".$approver."', next_approver='".$nextapprover."', approver_remarks='".$approver_remarks."',  stages=CONCAT(stages,'->','".$status."','>','".$approver."', ' ','(',FORMAT (GETDATE(), 'MM-dd-yyyy hh:mm:ss '),')')  WHERE id=".$id;
      }

    } else {

      // $sql_update = "UPDATE ".$table." SET status='".$status."', overallstatus='".$overallstatus."', current_approver='".$approver."', next_approver='".$nextapprover."', approver_remarks='".$approver_remarks."', stages=CONCAT(stages,'->','".$approver."', ' ',FORMAT (GETDATE(), 'MM-dd-yyyy hh:mm:ss ')) WHERE id=".$id;


            $sql_update = "UPDATE ".$table." SET status='".$status."', overallstatus='".$overallstatus."', current_approver='".$approver."', next_approver='".$nextapprover."', approver_remarks='".$approver_remarks."', stages=CONCAT(stages,'->','".$status."','>','".$approver."', ' ','(',FORMAT (GETDATE(), 'MM-dd-yyyy hh:mm:ss '),')') WHERE id=".$id;

    }


      // die($sql_update);

      $update = sqlsrv_query($conn,$sql_update);

      // echo $sql_update; die();
      // echo $id."<br>";
      // echo $workflow_token."<br>";
      // echo $approver."<br>";
      // echo $current_approver."<br>";
      // echo $approver_remarks."<br>";
      // echo $details."<br>";
      // echo $status."<br>";
      // echo $table."<br>";

      echo "success";


}
else{
	echo "invalid token";
}

?>
