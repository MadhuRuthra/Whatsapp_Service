<?php
session_start();
error_reporting(E_ALL);

header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// Include configuration.php
include_once('../api/configuration.php');
// Paytm Operation - Start
extract($_REQUEST);

$bearer_token = "Authorization: " . $_SESSION["yjwatsp_bearer_token"] . ""; // To get bearertoken
$current_date = date("Y-m-d H:i:s");
$milliseconds = round(microtime(true) * 1000);

// echo "==".$_SERVER['REQUEST_METHOD']."=="; // print_r($_REQUEST);

// user_management Page razorpay_payment - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $action_process == "razorpay_payment") {
  $qur_cda = $conn->query("SELECT usrsmscrd_id, raise_sms_credits, sms_amount 
                              FROM user_sms_credit_raise 
                              where user_id = '" . $_SESSION['yjwatsp_user_id'] . "' 
                              order by usrsmscrd_id desc limit 1");

  while ($row_cda = $qur_cda->fetch_object()) {
    $cda = $row_cda->usrsmscrd_id;
  }

  // echo "==".$cda."==";
  if ($cda != '') {
    $_SESSION['user_cda'] = $cda;
    $qur_usc = $conn->query("SELECT usrsmscrd_id, raise_sms_credits, sms_amount 
                              FROM user_sms_credit_raise 
                              where usrsmscrd_id = '$cda'");

    if ($qur_usc->num_rows > 0) {
      while ($row_usc = $qur_usc->fetch_object()) {

        $orderId = time();
        $txnAmount = $row_usc->sms_amount;
        $custId = $_SESSION['yjwatsp_user_id'];
        $mobileNo = $_SESSION['yjwatsp_user_mobile'];
        $email = $_SESSION['yjwatsp_user_email'];

        $paytmParams = array();
        $paytmParams["ORDER_ID"] = $orderId;
        $paytmParams["CUST_ID"] = $custId;
        $paytmParams["MOBILE_NO"] = $mobileNo;
        $paytmParams["EMAIL"] = $email;
        $paytmParams["TXN_AMOUNT"] = $txnAmount;
        /* $paytmParams["MID"] 		        	= PAYTM_MERCHANT_MID;
        $paytmParams["CHANNEL_ID"] 	      = PAYTM_CHANNEL_ID;
        $paytmParams["WEBSITE"] 	        = PAYTM_MERCHANT_WEBSITE;
        $paytmParams["INDUSTRY_TYPE_ID"]  = PAYTM_INDUSTRY_TYPE_ID;
        $paytmParams["CALLBACK_URL"]      = PAYTM_CALLBACK_URL."&cda=".$cda;
        $paytmChecksum                    = getChecksumFromArray($paytmParams, PAYTM_MERCHANT_KEY);
        $transactionURL                   = PAYTM_TXN_URL; */

        // $transactionURL                = "https://securegw-stage.paytm.in/theia/processTransaction";
        // $transactionURL                = "https://securegw.paytm.in/theia/processTransaction"; // for production


        $data = [
          'payment_id' => htmlspecialchars(strip_tags(isset($_POST['razorpay_payment_id']) ? $_POST['razorpay_payment_id'] : "")),
          'amount' => htmlspecialchars(strip_tags(isset($_POST['totalAmount']) ? $_POST['totalAmount'] : "")),
          'product_id' => htmlspecialchars(strip_tags(isset($_POST['product_id']) ? $_POST['product_id'] : "")),
        ];

        //check payment is authrized or not via API call

        $razorPayId = htmlspecialchars(strip_tags(isset($_POST['razorpay_payment_id']) ? $_POST['razorpay_payment_id'] : ""));

        $ch = curl_init('https://api.razorpay.com/v1/payments/' . $razorPayId . '');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_USERPWD, $rp_keyid . ":" . $rp_keysecret); // Input your Razorpay Key Id and Secret Id here
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($ch));

        // print_r($response->status); // authorized

        // you can write your database insert code here

        // check that payment is authorized by razorpay or not
        if ($response->status == 'authorized') {
          $respval = array('msg' => 'Payment successfully credited', 'status' => true, 'productCode' => $_POST['product_id'], 'paymentID' => $_POST['razorpay_payment_id'], 'userEmail' => $_POST['useremail']);
          $respval1 = 'msg:Payment successfully credited, status:true, productCode:' . $_POST['product_id'] . ', paymentID:' . $_POST['razorpay_payment_id'] . ', userEmail' . $_POST['useremail'];

          $sql_updt_usc = $conn->query("UPDATE user_sms_credit_raise 
                                              SET usrsmscrd_status = 'A', usrsmscrd_status_cmnts = '" . $respval1 . "' 
                                            WHERE usrsmscrd_id = " . $_SESSION['user_cda']);

          // echo json_encode($respval);
        } else {
          $respval = array('msg' => 'Payment failed', 'status' => false, 'productCode' => $_POST['product_id'], 'paymentID' => $_POST['razorpay_payment_id'], 'userEmail' => $_POST['useremail']);
          $respval1 = 'msg:Payment failed, status:false, productCode:' . $_POST['product_id'] . ', paymentID:' . $_POST['razorpay_payment_id'] . ', userEmail' . $_POST['useremail'];

          $sql_updt_usc = $conn->query("UPDATE user_sms_credit_raise 
                                              SET usrsmscrd_status = 'F', usrsmscrd_status_cmnts = '" . $respval1 . "' 
                                            WHERE usrsmscrd_id = " . $_SESSION['user_cda']);

          // echo json_encode($respval);
        }
        // exit;
        // Paytm Operation - End
      }

    }
  }
}
// user_management Page razorpay_payment - End

// user_management Page razorpay_payment from Paytm - Start
/* 
if($_SERVER['REQUEST_METHOD'] == "POST" and $action_process == "razorpay_payment") {
  // echo "==".$_SESSION['user_cda']."==".$cda."==";
  $_SESSION['user_cda'] = $cda;
    $qur_usc = $conn->query("SELECT usrsmscrd_id, user_id, raise_sms_credits, sms_amount 
                              FROM user_sms_credit_raise 
                              where usrsmscrd_id = '".$_SESSION['user_cda']."'");
    if ($qur_usc->num_rows > 0) {
      while ($row_usc = $qur_usc->fetch_object()) {
        
        $paytmChecksum = "";
        $paramList = array();
        $isValidChecksum = "FALSE";
        $usrid = $row_usc->user_id;

        $paramList = $_POST;
        $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

        //Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
        $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.

        if($isValidChecksum == "TRUE") {
          // echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";
          
          // echo "<pre>";
          // print_r($_POST);
          // echo "<pre>";
          $resp_status = "STATUS:".$_POST["STATUS"]."! ORDERID:".$_POST["ORDERID"]."! MID:".$_POST["MID"]."! TXNID:".$_POST["TXNID"]."! TXNAMOUNT:".$_POST["TXNAMOUNT"]."! RESPCODE:".$_POST["RESPCODE"]."! RESPMSG:".$_POST["RESPMSG"]."! BANKTXNID:".$_POST["BANKTXNID"]."! GATEWAYNAME:".$_POST["GATEWAYNAME"]."! BANKNAME:".$_POST["BANKNAME"];

          if ($_POST["STATUS"] == "TXN_SUCCESS") {
            // echo "<b>Transaction status is success</b>" . "<br/>";
            //Process your transaction here as success transaction.
            //Verify amount & order id received from Payment gateway with your application's order id and amount.
            // echo "UPDATE user_sms_credit_raise SET usrsmscrd_status = 'A', usrsmscrd_status_cmnts = '".$resp_status."' WHERE usrsmscrd_id = ".$_SESSION['user_cda'];
            $sql_updt_usc = $conn->query("UPDATE user_sms_credit_raise 
                                              SET usrsmscrd_status = 'A', usrsmscrd_status_cmnts = '".$resp_status."' 
                                            WHERE usrsmscrd_id = ".$_SESSION['user_cda']);
        
          }
          else {
            // echo "<b>Transaction status is failure</b>" . "<br/>";
            // echo "UPDATE user_sms_credit_raise SET usrsmscrd_status = 'F', usrsmscrd_status_cmnts = '".$_POST["RESPMSG"]."' WHERE usrsmscrd_id = ".$_SESSION['user_cda'];
            $sql_updt_usc = $conn->query("UPDATE user_sms_credit_raise 
                                              SET usrsmscrd_status = 'F', usrsmscrd_status_cmnts = '".$_POST["RESPMSG"]."'
                                            WHERE usrsmscrd_id = ".$_SESSION['user_cda']);
          }

        }
        else {
          echo "<b>Checksum mismatched.</b>";
          //Process transaction as suspicious. 
        }
      }
    }

    $sql = "SELECT * FROM user_management where user_id = '$usrid'";
    $qur = $conn->query($sql);

    if ($qur->num_rows > 0) {
      while($row = $qur->fetch_assoc()) {
        extract($row);
        $_SESSION['yjwatsp_parent_id'] 		= $row["parent_id"];
        $_SESSION['yjwatsp_user_id'] 			= $row["user_id"];
        $_SESSION['yjwatsp_user_master_id']= $row["user_master_id"];
        $_SESSION['yjwatsp_user_name'] 		= $row["user_name"];
        $_SESSION['yjwatsp_api_key'] 			= $row["api_key"];

        $_SESSION['yjwatsp_login_id'] 			= $row["login_id"];
        $_SESSION['yjwatsp_user_email'] 		= $row["user_email"];
        $_SESSION['yjwatsp_user_mobile'] 	= $row["user_mobile"];
        $_SESSION['yjwatsp_price_per_sms'] = $row["price_per_sms"];
        $_SESSION['yjwatsp_netoptid'] 			= $row["network_operators_id"];
      }
    }
} */
// user_management Page razorpay_payment from Paytm - End

// Finally Close all Opened Mysql DB Connection
$conn->close();

/* if (headers_sent()){
  die('<script type="text/javascript">window.location = "../sms_credit_list";</script‌​>');
}else{
  header("Location: ../sms_credit_list");
  // die();
} */
// exit;
?>
