<?php
session_start();//start session
error_reporting(E_ALL);// The error reporting function
// Include configuration.php
include_once('../api/configuration.php');
extract($_REQUEST);

$current_date = date("Y-m-d H:i:s");
$milliseconds = round(microtime(true) * 1000);

$bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].''; 
// Manage Sender ID Page save_mobile_api - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $tmpl_call_function == "save_mobile_api") {
  site_log_generate("Manage Sender ID Page : User : " . $_SESSION['yjwatsp_user_name'] . " access the page on " . date("Y-m-d H:i:s"), '../');
  // Get data
  $slt_service_category = htmlspecialchars(strip_tags(isset($_REQUEST['slt_service_category']) ? $conn->real_escape_string($_REQUEST['slt_service_category']) : ""));
  $exp1 = htmlspecialchars(strip_tags(isset($_REQUEST['txt_country_code']) ? $conn->real_escape_string($_REQUEST['txt_country_code']) : "101"));
  $mobile_number = htmlspecialchars(strip_tags(isset($_REQUEST['mobile_number']) ? $conn->real_escape_string($_REQUEST['mobile_number']) : ""));
  $txt_display_name = htmlspecialchars(strip_tags(isset($_REQUEST['txt_display_name']) ? $conn->real_escape_string($_REQUEST['txt_display_name']) : ""));
  site_log_generate("Manage Sender ID Page : Username => " . $_SESSION['yjwatsp_user_name'] . " access this page on " . date("Y-m-d H:i:s"), '../');

  $exp2 = explode("~~", $exp1);
  $txt_country_code = $exp2[0];
  $country_code = $exp2[1];

  $filename = '';
  if ($_FILES['fle_display_logo']['name'] != '') {
    $path_parts = pathinfo($_FILES["fle_display_logo"]["name"]);
    $extension = $path_parts['extension'];

    $filename = $_SESSION['yjwatsp_user_id'] . "_" . $milliseconds . "." . $extension;

    /* Location */
    $location = "../uploads/whatsapp_images/" . $filename;
    $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
    $imageFileType = strtolower($imageFileType);

    switch ($imageFileType) {
      case 'jpg':
      case 'jpeg':
        $mime_type = "image/jpeg";
        break;
      case 'png':
        $mime_type = "image/png";
        break;
    }

    /* Valid extensions */
    $valid_extensions = array("jpg", "jpeg", "png");

    $rspns = '';
    if (move_uploaded_file($_FILES['fle_display_logo']['tmp_name'], $location)) {
      site_log_generate("Manage Sender ID Page : User : " . $_SESSION['yjwatsp_user_name'] . " whatsapp_images file moved into Folder on " . date("Y-m-d H:i:s"), '../');
    }
  } else {
    $filename = '';
  }

  if ($_SESSION['yjwatsp_user_master_id'] == 1 or $_SESSION['yjwatsp_user_master_id'] == 2) {
    $qr_code_allowed = 'A';
  } else {
    $qr_code_allowed = 'U';
  }

  $replace_txt = '{
    "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '",
    "country_code" : "' . $country_code . '",
    "mobile_no" : "' . $mobile_number . '",
    "profile_name" : "' . $txt_display_name . '",
    "profile_image" : "' . $filename . '",
    "service_category" : "' . $slt_service_category . '"
  }';
    // To Get Api URL
  $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL =>  $api_url . '/sender_id/add_sender_id',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>$replace_txt,
    CURLOPT_HTTPHEADER => array(
      $bearer_token,
      'Content-Type: application/json'
     
    ),
  ));

  site_log_generate("Manage Sender ID Page : " . $_SESSION['yjwatsp_user_name'] . " logged in send it to Service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
  $response = curl_exec($curl);
  curl_close($curl);
  $sql = json_decode($response, false);
//  echo $response;
  site_log_generate("Manage Sender ID Page : Username => " . $_SESSION['yjwatsp_user_name'] . " executed the query reponse [$response] on " . date("Y-m-d H:i:s"), '../');
  if ($sql->response_code == 1) {
    site_log_generate("Manage Sender ID Page : " . $_SESSION['yjwatsp_user_name'] . " new mobile no added successfully on " . date("Y-m-d H:i:s"), '../');
    $json = array("status" => 1, "msg" => $sql->response_msg );
  }
  if ($sql->response_code == 0) {
    site_log_generate("Manage Sender ID Page : " . $_SESSION['yjwatsp_user_name'] . " new mobile no added successfully on " . date("Y-m-d H:i:s"), '../');
    $json = array("status" => 0, "msg" =>$sql->response_msg);
  }
  if ($response->errno) {
    site_log_generate("Manage Sender ID Page : " . $_SESSION['yjwatsp_user_name'] . " mobile no creation Failed [Invalid Inputs] on " . date("Y-m-d H:i:s"), '../');
    $json = array("status" => 0, "msg" => "Invalid Inputs. Kindly try again with the correct Inputs!");
  }
}
// Manage Sender ID Page save_mobile_api - End

// Message Credit Page message_credit - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $tmpl_call_function == "message_credit") {
  site_log_generate("Message Credit Page : User : " . $_SESSION['yjwatsp_user_name'] . " access the page on " . date("Y-m-d H:i:s"), '../');
  // Get data
  $txt_parent_user = htmlspecialchars(strip_tags(isset($_REQUEST['txt_parent_user']) ? $conn->real_escape_string($_REQUEST['txt_parent_user']) : ""));
  $txt_receiver_user = htmlspecialchars(strip_tags(isset($_REQUEST['txt_receiver_user']) ? $conn->real_escape_string($_REQUEST['txt_receiver_user']) : ""));
  $txt_message_count = htmlspecialchars(strip_tags(isset($_REQUEST['txt_message_count']) ? $conn->real_escape_string($_REQUEST['txt_message_count']) : ""));
  site_log_generate("Message Credit Page : Username => " . $_SESSION['yjwatsp_user_name'] . " access this page on " . date("Y-m-d H:i:s"), '../');

  $replace_txt = '{
    "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '",
    "parent_user" : "' . $txt_parent_user . '",
    "receiver_user" : "' . $txt_receiver_user . '",
    "message_count" : "' . $txt_message_count . '"
  }';
  // To Get Api URL
  $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL =>  $api_url . '/list/add_message_credit',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>$replace_txt,
    CURLOPT_HTTPHEADER => array(
      $bearer_token,
      'Content-Type: application/json'
     
    ),
  ));
  site_log_generate("Message Credit Page : " . $_SESSION['yjwatsp_user_name'] . " Execute the service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
  $response = curl_exec($curl);
  curl_close($curl);

  $header = json_decode($response, false);
  site_log_generate("Message Credit Page : " . $_SESSION['yjwatsp_user_name'] . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');

  if ($header->response_code == 1) {
    site_log_generate("Message Credit Page : " . $user_name . " Message Credit updation Success on " . date("Y-m-d H:i:s"), '../');
    $json = array("status" => 1, "msg" => "Message Credit updated.");
  } else {
    site_log_generate("Message Credit Page : " . $user_name . " Message Credit updation Failed [Invalid Inputs] on " . date("Y-m-d H:i:s"), '../');
    $json = array("status" => 0, "msg" => "Message Credit updation failed [Invalid Inputs]. Kindly try again with the correct Inputs!");
  }
}
// Message Credit Page message_credit - End

// Mobile number sending - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $store_call_function == "mobile_qrcode") {
  $mobile_number = htmlspecialchars(strip_tags(isset($_REQUEST['mobile_number']) ? $conn->real_escape_string($_REQUEST['mobile_number']) : ""));
  $txt_country_code = htmlspecialchars(strip_tags(isset($_REQUEST['txt_country_code']) ? $conn->real_escape_string($_REQUEST['txt_country_code']) : ""));

  $exp1 = explode("||", $txt_country_code);
  $country_code = $exp1[1];
  $country_id = $exp1[0];

  site_log_generate("Mobile number QR Code Scan Page : Username => " . $_SESSION['yjwatsp_user_name'] . " executed the query [SELECT * FROM whatsapp_config where whatspp_config_status in ('L', 'Y') and mobile_no = " . $mobile_number . " and user_id = " . $_SESSION['yjwatsp_user_id'] . " ORDER BY whatspp_config_id Asc] on " . date("Y-m-d H:i:s"), '../');
  $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].''; 
    // To Get Api URL 
  $curl2 = curl_init();
  curl_setopt_array($curl2, array(
    CURLOPT_URL => $api_url . '/select_query',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
      "query":"SELECT * FROM whatsapp_config where whatspp_config_status in (\'M\') and mobile_no = \'' . $mobile_number . '\' and user_id = \'' . $_SESSION['yjwatsp_user_id'] . '\' ORDER BY whatspp_config_id Asc"
      }',
      CURLOPT_HTTPHEADER => array(
        $bearer_token,
        'Content-Type: application/json'
       
      ),
  )
  );
 

  $response2 = curl_exec($curl2);
  curl_close($curl2);

  $obj2 = json_decode($response2);
  site_log_generate("Mobile number QR Code Scan Page : Username => " . $_SESSION['yjwatsp_user_name'] . " got the response ($response2) on " . date("Y-m-d H:i:s"), '../');

  if ($obj2->num_of_rows > 0) {
    $json = array("status" => 0, "msg" => "Given Mobile No and Scanned Mobile No Mismatched!");
  } else {
    site_log_generate("Mobile number QR Code Scan Page : Username => " . $_SESSION['yjwatsp_user_name'] . " executed the query [SELECT * FROM whatsapp_config where whatspp_config_status in ('L', 'Y') and mobile_no = " . $mobile_number . " and user_id = " . $_SESSION['yjwatsp_user_id'] . " ORDER BY whatspp_config_id Asc] on " . date("Y-m-d H:i:s"), '../');
  // To Get Api URL
    $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url.'/select_query',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => '{
        "query":"SELECT * FROM whatsapp_config where whatspp_config_status in (\'L\', \'Y\') and mobile_no = \'' . $mobile_number . '\' and user_id = \'' . $_SESSION['yjwatsp_user_id'] . '\' ORDER BY whatspp_config_id Asc"
        }',
      CURLOPT_HTTPHEADER => array(
        $bearer_token,
        'Content-Type: application/json'
       
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);

    site_log_generate("Mobile number QR Code Scan Page : Username => " . $_SESSION['yjwatsp_user_name'] . " executed the query response [$response] on " . date("Y-m-d H:i:s"), '../');
    $obj = json_decode($response);

    $vlu = 0;

    if ($obj->num_of_rows <= 0) {
      $vlu = 0;
    } else {
      $vlu = 1;
    }

    if ($vlu == 0) {
  // To Get Api URL
      $curl1 = curl_init();
      curl_setopt_array($curl1, array(
        CURLOPT_URL => $qrcode_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
              "mobile_number":"' . $mobile_number . '",
              "store_id":"1",
              "user_id":"' . $_SESSION['yjwatsp_user_id'] . '",
              "country_code":"' . $country_code . '",
              "country_id":"' . $country_id . '"
            }',
            CURLOPT_HTTPHEADER => array(
              $bearer_token,
              'Content-Type: application/json'
             
            ),
      )
      );

      $response1 = curl_exec($curl1);
      curl_close($curl1);

      site_log_generate("Mobile number QR Code Scan Page : Username => " . $_SESSION['yjwatsp_user_name'] . " executed the query2 [{
          \"mobile_number\":" . $mobile_number . ",
          \"store_id\":\"1\",
          \"user_id\":" . $_SESSION['yjwatsp_user_id'] . ",
          \"country_code\":" . $country_code . ",
          \"country_id\":" . $country_id . "
        }] on " . date("Y-m-d H:i:s"), '../');

      $obj1 = json_decode($response1);

      site_log_generate("Mobile number QR Code Scan Page : Username => " . $_SESSION['yjwatsp_user_name'] . " executed the query2 response [$response1] on " . date("Y-m-d H:i:s"), '../');

      $_SESSION['qrcode'] = $obj1->qr_code;

      if ($obj1->response_code == '0') {
        $json = array("status" => 1, "msg" => $obj1->response_msg, "qrcode" => $obj1->qr_code);
      } else if ($obj1->response_code == '1') {
        $json = array("status" => 0, "msg" => $obj1->response_msg);
      } else if ($obj1->response_code == '2') {
        $json = array("status" => 3, "msg" => "Invalid User");
      } else {
        $json = array("status" => 2, "msg" => "loading");
      }
    } else {
        // To Get Api URL
      $curl2 = curl_init();
      curl_setopt_array($curl2, array(
        CURLOPT_URL => $api_url . '/select_query',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
          "query":"SELECT * FROM whatsapp_config where whatspp_config_status in (\'L\', \'Y\') and mobile_no = \'' . $mobile_number . '\' and user_id = \'' . $_SESSION['yjwatsp_user_id'] . '\' ORDER BY whatspp_config_id Asc"
          }',
          CURLOPT_HTTPHEADER => array(
            $bearer_token,
            'Content-Type: application/json'
           
          ),
      )
      );
      site_log_generate("Mobile number QR Code Scan Page : Username => " . $_SESSION['yjwatsp_user_name'] . " executed the query3 [SELECT * FROM whatsapp_config where whatspp_config_status in ('L', 'Y') and mobile_no = " . $mobile_number . " and user_id = " . $_SESSION['yjwatsp_user_id'] . " ORDER BY whatspp_config_id Asc] on " . date("Y-m-d H:i:s"), '../');
      $response2 = curl_exec($curl2);
      curl_close($curl2);

      $obj2 = json_decode($response2);
      site_log_generate("Mobile number QR Code Scan Page : Username => " . $_SESSION['yjwatsp_user_name'] . " executed the query3 response [$response2] on " . date("Y-m-d H:i:s"), '../');

      if ($obj2->num_of_rows > 0) {
        $json = array("status" => 1, "msg" => "QRCODE Already Scanned!");
      } else {
        $json = array("status" => 0, "msg" => "Kindly rescan!!");
      }
    }
  }

}

// Finally Close all Opened Mysql DB Connection
$conn->close();

// Output header with JSON Response
header('Content-type: application/json');
echo json_encode($json);
