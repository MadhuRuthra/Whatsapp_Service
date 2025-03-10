<?php
session_start();
error_reporting(E_ALL);
// Include configuration.php
include_once('../api/configuration.php');
include_once('site_common_functions.php');
extract($_REQUEST);

$current_date = date("Y-m-d H:i:s");
$milliseconds = round(microtime(true) * 1000);

// Compose Whatsapp Page validateMobno - Start
if (isset($_POST['validateMobno']) == "validateMobno") {
  $mobno = str_replace('"', '', htmlspecialchars(strip_tags(isset($_POST['mobno']) ? $conn->real_escape_string($_POST['mobno']) : "")));
  $dup   = htmlspecialchars(strip_tags(isset($_POST['dup']) ? $conn->real_escape_string($_POST['dup']) : ""));
  $inv   = htmlspecialchars(strip_tags(isset($_POST['inv']) ? $conn->real_escape_string($_POST['inv']) : ""));

  $mobno = str_replace('\n', ',', $mobno);
  $newline = explode('\n', $mobno); 
  
  $correct_mobno_data = [];
  $return_mobno_data = '';
  $issu_mob = '';
  $cnt_vld_no = 0;
  $max_vld_no = 100;
  for ($i=0; $i < count($newline); $i++) { 
    $expl = explode(",", $newline[$i]);

      for ($ij=0; $ij < count($expl); $ij++) { 
          
          if($inv == 1) {
              $vlno = validate_phone_number($expl[$ij]);
          } else {
              $vlno = $newline[$i];
          }

          if($vlno == true) {
              if($dup == 1) {
                  if(!in_array($expl[$ij], $correct_mobno_data)) {
                      if($expl[$ij] != '') {
                          $cnt_vld_no++;
                          if($cnt_vld_no <= $max_vld_no) {
                            $correct_mobno_data[] = $expl[$ij];
                            $return_mobno_data .= $expl[$ij].",\n";
                          } else {
                            $issu_mob .= $expl[$ij].",";
                          }
                      } else {
                          $issu_mob .= $expl[$ij].",";
                      }
                  } else {
                      $issu_mob .= $expl[$ij].",";
                  }
              } else {
                  if($expl[$ij] != '') {
                      $cnt_vld_no++;
                      if($cnt_vld_no <= $max_vld_no) {
                        $correct_mobno_data[] = $expl[$ij];
                        $return_mobno_data .= $expl[$ij].",\n";
                      } else {
                        $issu_mob .= $expl[$ij].", ";
                      }
                  } else {
                      $issu_mob .= $expl[$ij].", ";
                  }
              }
          } else {
              $issu_mob .= $expl[$ij].",";
          }
      }
  }
  
  $return_mobno_data = rtrim($return_mobno_data, ",\n");
  site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." validated Mobile Nos ($return_mobno_data||$issu_mob) on ".date("Y-m-d H:i:s"), '../');
  $json = array("status" => 1, "msg" => $return_mobno_data."||".$issu_mob);
}
// Compose Whatsapp Page validateMobno - End

// Compose Whatsapp Page delete_senderid - Start
if($_SERVER['REQUEST_METHOD'] == "POST" and $tmpl_call_function == "delete_senderid") {
  $whatspp_config_id1		= htmlspecialchars(strip_tags(isset($_REQUEST['whatspp_config_id']) ? $conn->real_escape_string($_REQUEST['whatspp_config_id']) : ""));
  $approve_status1  		= htmlspecialchars(strip_tags(isset($_REQUEST['approve_status']) ? $conn->real_escape_string($_REQUEST['approve_status']) : ""));

  $replace_txt = '{
    "api_key" : "'.$_SESSION['yjwatsp_api_key'].'",
    "user_id" : "'.$_SESSION['yjwatsp_user_id'].'",
    "whatspp_config_id" : "'.$whatspp_config_id1.'"
  }';
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $api_url.'/sender_id/delete_sender_id',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_CUSTOMREQUEST => 'DELETE',
    CURLOPT_POSTFIELDS => $replace_txt,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));
  site_log_generate("Compose Whatsapp Delete Sender ID Page : ".$_SESSION['yjwatsp_user_name']." Execute the service [$replace_txt] on ".date("Y-m-d H:i:s"), '../');
  $response = curl_exec($curl);
  curl_close($curl);
  
  $header = json_decode($response, false);
  site_log_generate("Compose Whatsapp Delete Sender ID Page : ".$_SESSION['yjwatsp_user_name']." get the Service response [$response] on ".date("Y-m-d H:i:s"), '../');
  
  if ($header->response_code == 1) {
    $json = array("status" => 1, "msg" => "Success");
  } else {
    $json = array("status" => 0, "msg" => "Failed");
  }
}
// Compose Whatsapp Page delete_senderid - Start

// Compose Whatsapp Page generate_contacts - Start
if($_SERVER['REQUEST_METHOD'] == "POST" and $tmpl_call_function == "generate_contacts") {
  $txt_list_mobno		= htmlspecialchars(strip_tags(isset($_REQUEST['txt_list_mobno']) ? $conn->real_escape_string($_REQUEST['txt_list_mobno']) : ""));

  $expld = explode(",", $txt_list_mobno);
  $mblno = '';
  for($i = 0; $i < count($expld); $i++) {
    $mblno .= '"'.$expld[$i].'", ';
  }
  $mblno = rtrim($mblno, ", ");

  $replace_txt = '{
    "api_key" : "'.$_SESSION['yjwatsp_api_key'].'",
    "mobile_number" : ['.$mblno.']
  }';
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $api_url.'/create_csv',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $replace_txt,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));
  site_log_generate("Compose Whatsapp Generate Contacts Page : ".$_SESSION['yjwatsp_user_name']." Execute the service [$replace_txt] on ".date("Y-m-d H:i:s"), '../');
  $response = curl_exec($curl);
  curl_close($curl);
  
  $header = json_decode($response, false);
  site_log_generate("Compose Whatsapp Generate Contacts Page : ".$_SESSION['yjwatsp_user_name']." get the Service response [$response] on ".date("Y-m-d H:i:s"), '../');
  
  if ($header->response_code == 1) {
    // $json = array("status" => 1, "msg" => "<a target='_blank' href='".$site_url.$header->file_location."' class='error_display'>Download Contacts CSV</a>");
    $json = array("status" => 1, "msg" => $site_url.$header->file_location);
  } else {
    $json = array("status" => 0, "msg" => "Failed to Generate Contact CSV. Kindly try again!!");
  }
}
// Compose Whatsapp Page generate_contacts - Start

// Compose Whatsapp Page save_phbabt - Start
if($_SERVER['REQUEST_METHOD'] == "POST" and $tmpl_call_function == "save_phbabt") {
  $whatspp_config_id		= htmlspecialchars(strip_tags(isset($_REQUEST['whatspp_config_id']) ? $conn->real_escape_string($_REQUEST['whatspp_config_id']) : ""));
  $fieldname  		= htmlspecialchars(strip_tags(isset($_REQUEST['fieldname']) ? $conn->real_escape_string($_REQUEST['fieldname']) : ""));
  $fieldvalue  		= htmlspecialchars(strip_tags(isset($_REQUEST['fieldvalue']) ? $conn->real_escape_string($_REQUEST['fieldvalue']) : ""));
  
  if($fieldname == 'phone_number_id') {
    $replace_txt = '{
      "api_key" : "'.$_SESSION['yjwatsp_api_key'].'",
      "user_id" : "'.$_SESSION['yjwatsp_user_id'].'",
      "whatspp_config_id" : "'.$whatspp_config_id.'",
      "phone_number_id" : "'.$fieldvalue.'"
    }';
  } elseif($fieldname == 'whatsapp_business_acc_id') {
    $replace_txt = '{
      "api_key" : "'.$_SESSION['yjwatsp_api_key'].'",
      "user_id" : "'.$_SESSION['yjwatsp_user_id'].'",
      "whatspp_config_id" : "'.$whatspp_config_id.'",
      "whatsapp_business_acc_id" : "'.$fieldvalue.'"
    }';
  } elseif($fieldname == 'bearer_token') {
    $replace_txt = '{
      "api_key" : "'.$_SESSION['yjwatsp_api_key'].'",
      "user_id" : "'.$_SESSION['yjwatsp_user_id'].'",
      "whatspp_config_id" : "'.$whatspp_config_id.'",
      "bearer_token" : "'.strtoupper($fieldvalue).'"
    }';
  }
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $api_url.'/list/save_phbabt',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $replace_txt,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));
  site_log_generate("Compose Whatsapp save_phbabt Page : ".$_SESSION['yjwatsp_user_name']." Execute the service [$replace_txt] on ".date("Y-m-d H:i:s"), '../');
  $response = curl_exec($curl);
  curl_close($curl);
  
  $header = json_decode($response, false);
  site_log_generate("Compose Whatsapp save_phbabt Page : ".$_SESSION['yjwatsp_user_name']." get the Service response [$response] on ".date("Y-m-d H:i:s"), '../');
  
  if ($header->response_code == 1) {
    $json = array("status" => 1, "msg" => "Success");
  } else {
    $json = array("status" => 0, "msg" => "Failed");
  }
}
// Compose Whatsapp Page save_phbabt - Start

// Compose Whatsapp Page approve_template - Start
if($_SERVER['REQUEST_METHOD'] == "POST" and $tmpl_call_function == "approve_template") {
  $template_id1			= htmlspecialchars(strip_tags(isset($_REQUEST['template_id']) ? $conn->real_escape_string($_REQUEST['template_id']) : ""));
  $approve_status1	= htmlspecialchars(strip_tags(isset($_REQUEST['approve_status']) ? $conn->real_escape_string($_REQUEST['approve_status']) : ""));
  
  $replace_txt = '{
    "api_key" : "'.$_SESSION['yjwatsp_api_key'].'",
    "user_id" : "'.$_SESSION['yjwatsp_user_id'].'",
    "change_status" : "'.$approve_status1.'",
    "template_id" : "'.$template_id1.'"
  }';
  
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $api_url.'/template/approve_reject_template',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $replace_txt,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));
  site_log_generate("Compose Whatsapp approve_template Page : ".$_SESSION['yjwatsp_user_name']." Execute the service [$replace_txt] on ".date("Y-m-d H:i:s"), '../');
  $response = curl_exec($curl);
  curl_close($curl);
  
  $header = json_decode($response, false);
  site_log_generate("Compose Whatsapp approve_template Page : ".$_SESSION['yjwatsp_user_name']." get the Service response [$response] on ".date("Y-m-d H:i:s"), '../');
  
  if ($header->response_code == 1) {
    $json = array("status" => 1, "msg" => "Success");
  } else {
    $json = array("status" => 0, "msg" => "Save option Failed!!. Must fill all fields!!");
  }
}
// Compose Whatsapp Page approve_template - Start

// Compose Whatsapp Page approve_qr_whatsappno - Start
if($_SERVER['REQUEST_METHOD'] == "POST" and $tmpl_call_function == "approve_qr_whatsappno") {
  $whatspp_config_id1		= htmlspecialchars(strip_tags(isset($_REQUEST['whatspp_config_id']) ? $conn->real_escape_string($_REQUEST['whatspp_config_id']) : ""));
  $approve_status1  		= htmlspecialchars(strip_tags(isset($_REQUEST['approve_status']) ? $conn->real_escape_string($_REQUEST['approve_status']) : ""));
  
  $replace_txt = '{
    "api_key" : "'.$_SESSION['yjwatsp_api_key'].'",
    "user_id" : "'.$_SESSION['yjwatsp_user_id'].'",
    "whatspp_config_status" : "'.$approve_status1.'",
    "whatspp_config_id" : "'.$whatspp_config_id1.'"
  }';
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $api_url.'/list/approve_whatsappno',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $replace_txt,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));
  site_log_generate("Compose Whatsapp approve_qr_whatsappno Page : ".$_SESSION['yjwatsp_user_name']." Execute the service [$replace_txt] on ".date("Y-m-d H:i:s"), '../');
  $response = curl_exec($curl);
  curl_close($curl);
  $sms = json_decode($response, false);
  site_log_generate("Compose Whatsapp approve_qr_whatsappno Page : ".$_SESSION['yjwatsp_user_name']." get the Service response [$response] on ".date("Y-m-d H:i:s"), '../');
  
  if ($sms->num_of_rows > 0) {
    $json = array("status" => 1, "msg" => "Success");
  } else {
    $json = array("status" => 0, "msg" => "Save option Failed!!. Must fill all fields!!");
  }
}
// Compose Whatsapp Page approve_qr_whatsappno - Start

// Compose Whatsapp Page approve_whatsappno - Start
if($_SERVER['REQUEST_METHOD'] == "POST" and $tmpl_call_function == "approve_whatsappno") {
  $whatspp_config_id1		= htmlspecialchars(strip_tags(isset($_REQUEST['whatspp_config_id']) ? $conn->real_escape_string($_REQUEST['whatspp_config_id']) : ""));
  $approve_status1  		= htmlspecialchars(strip_tags(isset($_REQUEST['approve_status']) ? $conn->real_escape_string($_REQUEST['approve_status']) : ""));
    
    $replace_txt = '{
      "api_key" : "'.$_SESSION['yjwatsp_api_key'].'",
      "user_id" : "'.$_SESSION['yjwatsp_user_id'].'",
      "whatspp_config_status" : "'.$approve_status1.'",
      "whatspp_config_id" : "'.$whatspp_config_id1.'"
    }';
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url.'/list/approve_whatsappno',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_SSL_VERIFYPEER => 0,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $replace_txt,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    site_log_generate("Approve Whatsappno Page : ".$_SESSION['yjwatsp_user_name']." Execute the service [$replace_txt] on ".date("Y-m-d H:i:s"), '../');
    $response = curl_exec($curl);
    curl_close($curl);
    $sms = json_decode($response, false);
    site_log_generate("Approve Whatsappno Page : ".$_SESSION['yjwatsp_user_name']." get the Service response [$response] on ".date("Y-m-d H:i:s"), '../');
    
    if ($sms->num_of_rows > 0) {
      for($indicator = 0; $indicator < $sms->num_of_rows; $indicator++){
        $mobile_no									= $sms->report[$indicator]->mobile_no;
        $phone_number_id						= $sms->report[$indicator]->phone_number_id;
        $whatsapp_business_acc_id		= $sms->report[$indicator]->whatsapp_business_acc_id;
        $bearer_token								= $sms->report[$indicator]->bearer_token;
      }
    }

    if($approve_status1 == 'Y') { // After Sender ID approval, it will create other template approvals
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url."/user",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
          "user_id":"'.$_SESSION['yjwatsp_user_id'].'",
          "api_key":"'.$_SESSION['yjwatsp_api_key'].'",
          "mobile_number": "'.$mobile_no.'",
          "phone_number_id": "'.$phone_number_id.'",
          "whatsapp_business_acc_id": "'.$whatsapp_business_acc_id.'",
          "bearer_token": "'.$bearer_token.'"
        }',
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json'
        ),
      ));
      $usr = '{
        "user_id":"'.$_SESSION['yjwatsp_user_id'].'",
        "api_key":"'.$_SESSION['yjwatsp_api_key'].'",
        "mobile_number": "'.$mobile_no.'",
        "phone_number_id": "'.$phone_number_id.'",
        "whatsapp_business_acc_id": "'.$whatsapp_business_acc_id.'",
        "bearer_token": "'.$bearer_token.'"
      }';
      site_log_generate("Approve Whatsappno Page : ".$_SESSION['yjwatsp_user_name']." executed the query API ($usr) on ".date("Y-m-d H:i:s"), '../'); 
      $response = curl_exec($curl);
      curl_close($curl);
      $yjresponseobj = json_decode($response, false);

      site_log_generate("Approve Whatsappno Page : ".$_SESSION['yjwatsp_user_name']." executed the query API response ($response) on ".date("Y-m-d H:i:s"), '../'); 
    }
    $json = array("status" => 1, "msg" => "Success");
}
// Compose Whatsapp Page approve_whatsappno - Start

// All Page Footer find_blocked_senderid - Start
if($_SERVER['REQUEST_METHOD'] == "POST" and $tmpl_call_function == "find_blocked_senderid") {
  $replace_txt = '{
    "api_key" : "'.$_SESSION['yjwatsp_api_key'].'",
    "user_id" : "'.$_SESSION['yjwatsp_user_id'].'"
  }';
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $api_url.'/list/find_blocked_senderid',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_POSTFIELDS => $replace_txt,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));
  site_log_generate("All Footer Page : ".$_SESSION['yjwatsp_user_name']." Execute the service [$replace_txt] on ".date("Y-m-d H:i:s"), '../');
  $response = curl_exec($curl);
  curl_close($curl);
  $sms = json_decode($response, false);
  site_log_generate("All Footer Page : ".$_SESSION['yjwatsp_user_name']." get the Service response [$response] on ".date("Y-m-d H:i:s"), '../');

  $sent_count_msg = '';	$ii = 0;
  if ($sms->num_of_rows > 0) {
    for($indicator = 0; $indicator < $sms->num_of_rows; $indicator++){
      $ii++;
      $wht_bearer_token = $sms->report[$indicator]->mobile_no;
      $sent_count_msg .= $ii.") Sender ID : ".$sms->report[$indicator]->mobile_no.", <br>";
    }
  }

  $sent_count_msg = rtrim($sent_count_msg, ",  <br>");
  if ($sent_count_msg != '') {
    $json = array("status" => 1, "msg" => $sent_count_msg);
  } else {
    $json = array("status" => 0, "msg" => "0");
  }
}
// All Page Footer find_blocked_senderid - Start

// Messenger Reply Page messenger_reply - Start
if($_SERVER['REQUEST_METHOD'] == "POST" and $tmpl_call_function == "messenger_reply") {
  site_log_generate("Messenger Reply Page : User : ".$_SESSION['yjwatsp_user_name']." access this page on ".date("Y-m-d H:i:s"), '../');

  $txt_reply				= htmlspecialchars(strip_tags(isset($_REQUEST['txt_reply']) ? $conn->real_escape_string($_REQUEST['txt_reply']) : ""));
  $receiver_mobile	= htmlspecialchars(strip_tags(isset($_REQUEST['message_to']) ? $conn->real_escape_string($_REQUEST['message_to']) : ""));
  $sender_id				= htmlspecialchars(strip_tags(isset($_REQUEST['sender_id']) ? $conn->real_escape_string($_REQUEST['sender_id']) : ""));
  $send_reply = '{
    "user_id":"'.$_SESSION['yjwatsp_user_id'].'",
    "api_key":"'.$_SESSION['yjwatsp_api_key'].'",
    "sender_mobile":"'.$sender_id.'",
    "receiver_mobile":"'.$receiver_mobile.'",
    "reply_msg":"'.$txt_reply.'"
  }';
  site_log_generate("Messenger Reply Page : User : ".$_SESSION['yjwatsp_user_name']." reply [$send_reply] on ".date("Y-m-d H:i:s"), '../');
  // exit;

  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $api_url."/message/reply_message",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
      "user_id":"'.$_SESSION['yjwatsp_user_id'].'",
      "api_key":"'.$_SESSION['yjwatsp_api_key'].'",
      "sender_mobile":"'.$sender_id.'",
      "receiver_mobile":"'.$receiver_mobile.'",
      "reply_msg":"'.$txt_reply.'"
    }',
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));
  $response = curl_exec($curl);
  site_log_generate("Messenger Reply Page : User : ".$_SESSION['yjwatsp_user_name']." reply reseponse [$response] on ".date("Y-m-d H:i:s"), '../');
  curl_close($curl);
  $yjresponseobj = json_decode($response, false);
  
  if($yjresponseobj->response_status == 200) {
    $json = array("status" => 1, "msg" => "Success");
  } else {
    $json = array("status" => 0, "msg" => "Failed while saving the Reply!!");
  }
}
// Messenger Reply Page messenger_reply - Start

// Compose Whatsapp Page compose_whatsapp - Start
if($_SERVER['REQUEST_METHOD'] == "POST" and $tmpl_call_function == "compose_whatsapp") {
  site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." access this page on ".date("Y-m-d H:i:s"), '../');
  
  // Get data
  $txt_list_mobno 				= htmlspecialchars(strip_tags(isset($_REQUEST['txt_list_mobno']) ? $_REQUEST['txt_list_mobno'] : ""));

  $chk_remove_duplicates	= htmlspecialchars(strip_tags(isset($_REQUEST['chk_remove_duplicates']) ? $_REQUEST['chk_remove_duplicates'] : ""));
  $chk_remove_invalids		= htmlspecialchars(strip_tags(isset($_REQUEST['chk_remove_invalids']) ? $_REQUEST['chk_remove_invalids'] : ""));
  $id_slt_contgrp					= htmlspecialchars(strip_tags(isset($_REQUEST['id_slt_contgrp']) ? $_REQUEST['id_slt_contgrp'] : "0"));
  $txt_sms_type    				= htmlspecialchars(strip_tags(isset($_REQUEST['txt_sms_type']) ? $_REQUEST['txt_sms_type'] : "TEXT"));
  $txt_sms_type         	= strtoupper($txt_sms_type);
  $country_code						= '';
  $mime_type							= '';

  $filename = '';
    if($_FILES['txt_media']['name'] != '') {
      $path_parts = pathinfo($_FILES["txt_media"]["name"]);
      $extension = $path_parts['extension'];

      $filename = $_SESSION['yjwatsp_user_id']."_".$milliseconds.".".$extension;
      /* Location */
      $location = "../uploads/whatsapp_media/".$filename;
      $send_location = $full_pathurl."/uploads/whatsapp_media/".$filename;
      $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
      $imageFileType = strtolower($imageFileType);
      
      switch($imageFileType) {
        case 'jpg':
        case 'jpeg':
          $mime_type = "image/jpeg";
          break;
        case 'png':
          $mime_type = "image/png";
          break;
        case 'gif':
          $mime_type = "image/gif";
          break;
          
        case 'pdf':
          $mime_type = "application/pdf";
          break;
        case 'mp4':
          $mime_type = "video/mp4";
          break;
        case 'webm':
          $mime_type = "video/webm";
          break;
      }

      /* Valid extensions */
      $valid_extensions = array("jpg","jpeg","png","pdf","gif","mp4","webm");

      $rspns = '';
      /* Check file extension */
      if(move_uploaded_file($_FILES['txt_media']['tmp_name'], $location)){
          site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." whatsapp_media file moved into Folder on ".date("Y-m-d H:i:s"), '../');
      }
    } else {
      $filename = '';
    }

  $txt_sms_content			= htmlspecialchars(strip_tags(isset($_REQUEST['txt_sms_content']) ? $_REQUEST['txt_sms_content'] : ""));
  $txt_caption 						= htmlspecialchars(strip_tags(isset($_REQUEST['txt_caption']) ? $_REQUEST['txt_caption'] : "Media"));
  $txt_char_count 				= htmlspecialchars(strip_tags(isset($_REQUEST['txt_char_count']) ? $_REQUEST['txt_char_count'] : "1"));
  $txt_sms_count 	        = htmlspecialchars(strip_tags(isset($_REQUEST['txt_sms_count']) ? $_REQUEST['txt_sms_count'] : "1"));
  $txt_rcscard_title			= htmlspecialchars(strip_tags(isset($_REQUEST['txt_rcscard_title']) ? $_REQUEST['txt_rcscard_title'] : ""));
    
  $chk_save_contact_group	= htmlspecialchars(strip_tags(isset($_REQUEST['chk_save_contact_group']) ? $_REQUEST['chk_save_contact_group'] : ""));
  
  $expl_wht = explode("~~", $txt_whatsapp_mobno[0]); 
  $storeid = $expl_wht[0];
  $confgid = $expl_wht[1];

  $txt_caption = str_replace("'", "\'", $txt_caption);
  $txt_caption = str_replace('"', '\"', $txt_caption);

    // Receiver Mobile Numbers
    $newline1 = explode("\n", $txt_list_mobno); 
    $receive_mobile_nos = '';
    $cnt_mob_no = count($newline1);
    for ($i1=0; $i1 < count($newline1); $i1++) { 
        $expl1 = explode(",", $newline1[$i1]);
        for ($ij1=0; $ij1 < count($expl1); $ij1++) { 
            if(validate_phone_number($expl1[$ij1])) {
                $mblno[] = $expl1[$ij1];
                $receive_mobile_nos .= $expl1[$ij1].',';
            }
        }
    }
    $receive_mobile_nos = rtrim($receive_mobile_nos, ",");

    // Sender Mobile Numbers
    $sender_mobile_nos = '';
    for ($i1=0; $i1 < count($txt_whatsapp_mobno); $i1++) { 
        $ex1 = explode('~~', $txt_whatsapp_mobno[$i1]);
        $sender_mobile_nos .= $ex1[2].',';
    }
    $sender_mobile_nos = rtrim($sender_mobile_nos, ",");

    $txt_sms_content = substr($txt_sms_content, 0, 700);
    if(strlen($txt_sms_content) != mb_strlen($txt_sms_content, 'utf-8'))
    {
        // echo "Please enter English words only:("; 
        $txt_char_count     = mb_strlen($txt_sms_content, 'utf-8');
        $txt_sms_count      = ceil($txt_char_count / 70);
    }
    else {
        // echo "OK, English Detected!";
        $txt_char_count     = strlen($txt_sms_content);
        $txt_sms_count      = ceil($txt_char_count / 160);
    }

    $usr_id = $_SESSION['yjwatsp_user_id'];

    $replace_txt = '{
      "api_key" : "'.$_SESSION['yjwatsp_api_key'].'",
      "user_id" : "'.$usr_id.'"
    }';
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url.'/list/available_credits',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_SSL_VERIFYPEER => 0,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_POSTFIELDS => $replace_txt,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    site_log_generate("Compose Whatsapp Page : ".$_SESSION['yjwatsp_user_name']." Execute the service [$replace_txt] on ".date("Y-m-d H:i:s"), '../');
    $response = curl_exec($curl);
    curl_close($curl);
    $header = json_decode($response, false);
    site_log_generate("Compose Whatsapp Page : ".$_SESSION['yjwatsp_user_name']." get the Service response [$response] on ".date("Y-m-d H:i:s"), '../');
    
    if ($header->num_of_rows > 0) {
      for($indicator = 0; $indicator < $header->num_of_rows; $indicator++){
        $alotsms = $header->report[$indicator]->available_messages;
        $expdate = date("Y-m-d H:i:s", strtotime($header->report[$indicator]->expiry_date));
      }
    } else {
        $alotsms = 0;
        $expdate = '';
    }

    $ttlmsgcnt = 0;
    if($txt_sms_content != '') {
      $ttlmsgcnt++;
    }
    if($filename != '') { // echo "}}";
      $ttlmsgcnt++;
    }
    if($txt_open_url != '' or $txt_call_button != '' or (count($txt_reply_buttons) > 0 and $txt_reply_buttons[0] != '')) {
      $ttlmsgcnt++;
    }
    if(count($txt_option_list) > 0 and $txt_option_list[0] != '') {
      $ttlmsgcnt++;
    }
    
    $ttl_sms_cnt = count($mblno);
    $txt_sms_content = str_replace("'", "\'", $txt_sms_content);
    $txt_sms_content = str_replace('"', '\"', $txt_sms_content);
    
    if($alotsms == 0 and $_SESSION['yjwatsp_user_master_id'] != 1) {
        site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." Compose Whatsapp failed [Whatsapp Credits are not available..] on ".date("Y-m-d H:i:s"), '../');
        $json = array("status" => 0, "msg" => "Whatsapp Credits are not available. Kindly verify!!");
    }
    elseif($alotsms < $ttl_sms_cnt and $_SESSION['yjwatsp_user_master_id'] != 1) {
        site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." Compose Whatsapp failed [Whatsapp Credits are not available.] on ".date("Y-m-d H:i:s"), '../');
        $json = array("status" => 0, "msg" => "Whatsapp Credits are not available. Kindly verify!!");
    }
    elseif($txt_char_count > 700) {
        site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." Compose Whatsapp failed [Morethan 700 characters are not allowed for Whatsapp] on ".date("Y-m-d H:i:s"), '../');
        $json = array("status" => 0, "msg" => "Morethan 700 characters are not allowed for Whatsapp. Kindly verify!!");
    }
    elseif($expdate == '' and $_SESSION['yjwatsp_user_master_id'] != 1) {
        site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." Compose Whatsapp failed [Validity Period Expired.] on ".date("Y-m-d H:i:s"), '../');
        $json = array("status" => 0, "msg" => "Validity Period Expired. Kindly verify!");
    }
    elseif(strtotime($expdate) < strtotime($current_date) and $_SESSION['yjwatsp_user_master_id'] != 1) {
        site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." Compose Whatsapp failed [Validity Period Expired..] on ".date("Y-m-d H:i:s"), '../');
        $json = array("status" => 0, "msg" => "Validity Period Expired. Kindly verify!!");
    } else {

        $new_dbname = "whatsapp_messenger_".$usr_id;
        $new_indicator = $usr_id;
        $newconn = new mysqli($servername, $username, $password, $new_dbname);
        // Check connection
        if ($newconn->connect_error) {
            die("Connection failed: " . $newconn->connect_error);
        } else {
            // echo "Connected";
        }
        mysqli_query($newconn, "SET SESSION sql_mode = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");		site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." connected the new DB [$new_dbname] on ".date("Y-m-d H:i:s"), '../');

        $sql_cw = "SELECT max(compose_whatsapp_id) mxcompose_whatsapp_id FROM compose_whatsapp_".$new_indicator." 
                      ORDER BY compose_whatsapp_id desc limit 1";
        $qur_cw = $newconn->query($sql_cw);
        site_log_generate("Compose Whatsapp Page : ".$_SESSION['yjwatsp_user_name']." executed the query ($sql_cw) on ".date("Y-m-d H:i:s"), '../'); 
        if ($qur_cw->num_rows > 0) {
          while($row_cw = $qur_cw->fetch_assoc()) {
            $mxcompose_whatsapp_id .= $row_cw["mxcompose_whatsapp_id"];
          }
        }

        $txt_campaign_name = $_SESSION['yjwatsp_user_id']."_".date('z', strtotime(date("d-m-Y")))."_".$mxcompose_whatsapp_id;

        // Insert data into data base
        $sql_csms = "INSERT INTO compose_whatsapp_".$new_indicator." ".
                        "(compose_whatsapp_id, user_id, store_id, whatspp_config_id, mobile_nos, sender_mobile_nos, whatsapp_content, message_type, total_mobileno_count, content_char_count, content_message_count, campaign_name, whatsapp_status, whatsapp_entry_date) "."VALUES ".
                        "(NULL, '".$usr_id."', '1', '$confgid', '$txt_list_mobno', '$sender_mobile_nos', '$txt_sms_content', 'TEXT', '$cnt_mob_no', '$txt_char_count', '$ttl_sms_cnt', '$txt_campaign_name', 'Y', '".$current_date."')";

        if ($newconn->query($sql_csms)) {
            // echo "!!";
            // Get last insert id
            $lastid = $newconn->insert_id;
            site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." executed the compose_whatsapp_".$new_indicator." query ($sql_csms) on ".date("Y-m-d H:i:s"), '../');

            $sql_limit = "UPDATE message_limit SET available_messages = available_messages - $ttl_sms_cnt WHERE user_id = ".$usr_id;
            $conn->query($sql_limit);
            site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." executed the query [$sql_limit] on ".date("Y-m-d H:i:s"), '../');

            $newline = explode("\n", $txt_list_mobno); 
            $correct_mobno_data = [];
            $return_mobno_data = '';
            $mobile_numbers_insertid = '';
            for ($i=0; $i < count($newline); $i++) { 
                $expl = explode(",", $newline[$i]);

                for ($j=0; $j < count($expl); $j++) { // For loop Started
                    $vlno = validate_phone_number($expl[$j]);

                    if($vlno == true) {
                      $sql_cstt = "INSERT INTO compose_whatsapp_status_".$new_indicator." ".
                                    "(comwtap_status_id, compose_whatsapp_id, country_code, mobile_no, comments, comwtap_status, 
                                    comwtap_entry_date, response_status, response_message, response_id, response_date, delivery_status, delivery_date, read_date, read_status) VALUES ".
                                    "(NULL, '".$lastid."', NULL, '".$expl[$j]."', '-', 'Y', '".$current_date."', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";
                      $newconn->query($sql_cstt);
                      $last_rcsstatid = $newconn->insert_id;
                      $mobile_numbers_insertid .= $last_rcsstatid.",";
                      site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." executed the compose_whatsapp_status_".$new_indicator." query ($sql_cstt) on ".date("Y-m-d H:i:s"), '../');
                    }
                } // For loop End
            }
            $mobile_numbers_insertid = rtrim($mobile_numbers_insertid, ",");

            $update_txt = '';
            // Send Whatsapp Message - Start
            // TEXT Messages - Start
            $sql_whatsapp_text = "INSERT INTO whatsapp_text_".$new_indicator." ".
                            "(whatsapp_text_id, compose_whatsapp_id, sms_type, whatsapp_text_title, text_data, text_reply, text_number, text_address, text_name, text_url, text_title, text_description, text_start_time, text_end_time, carousel_fileurl, carousel_srno, whatsapp_text_status, whatsapp_text_entry_date) "."VALUES ".
                            "(NULL, '".$lastid."', 'TEXT', 'text', '$txt_sms_content', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', '".$current_date."')";
            site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." executed the query [$sql_whatsapp_text] on ".date("Y-m-d H:i:s"), '../');
            $newconn->query($sql_whatsapp_text);
            $txt_sms_content = str_replace("\'", "'", $txt_sms_content);
            $update_txt .= '{
              "msg_type" : "text",
              "text_msg" : "'.base64_encode($txt_sms_content).'"
            },';
            site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." executed the text [$update_txt] on ".date("Y-m-d H:i:s"), '../');
            // TEXT Messages - End

            // Media Messages - Start
            if($filename != '') {
              $sql_whatsapp_text0 = "INSERT INTO whatsapp_text_".$new_indicator." ".
                              "(whatsapp_text_id, compose_whatsapp_id, sms_type, whatsapp_text_title, text_data, text_reply, text_number, text_address, text_name, text_url, text_title, text_description, text_start_time, text_end_time, carousel_fileurl, carousel_srno, whatsapp_text_status, whatsapp_text_entry_date) 
                              "."VALUES "."(NULL, '".$lastid."', 'TEXT', 'media', '".$filename."', NULL, NULL, NULL, NULL, NULL, '".$txt_caption."', NULL, NULL, NULL, NULL, NULL, 'Y', '".$current_date."')";
              site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." executed the query [$sql_whatsapp_text0] on ".date("Y-m-d H:i:s"), '../');
              $newconn->query($sql_whatsapp_text0);

              // $img_location = file_get_contents($send_location);
              // $img_location = file_get_contents("http://localhost/whatsapp/assets/img/yeejai-logo.png");
              $update_txt .= '{
                "msg_type" : "media",
                "media_file" : "'.$send_location.'",
                "file_name" : "'.$filename.'",
                "media_caption" : "'.$txt_caption.'"
              },';
              site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." executed the text [$update_txt] on ".date("Y-m-d H:i:s"), '../');
            }
            // Media Messages - End

            // Button Messages (URL & CALL & REPLY) - Start
            if($txt_open_url != '' and $txt_call_button != '' and (count($txt_reply_buttons) > 0 and $txt_reply_buttons[0] != '')) {
              $update_txt .= '{
                "msg_type" : "button",';

              $sql_whatsapp_text1 = "INSERT INTO whatsapp_text_".$new_indicator." ".
                              "(whatsapp_text_id, compose_whatsapp_id, sms_type, whatsapp_text_title, text_data, text_reply, text_number, text_address, text_name, text_url, text_title, text_description, text_start_time, text_end_time, carousel_fileurl, carousel_srno, whatsapp_text_status, whatsapp_text_entry_date) "."VALUES ".
                              "(NULL, '".$lastid."', 'TEXT', 'url_button', '$txt_open_url_data', NULL, NULL, NULL, NULL, '$txt_open_url', NULL, NULL, NULL, NULL, NULL, NULL, 'Y', '".$current_date."')";
              site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." executed the query [$sql_whatsapp_text1] on ".date("Y-m-d H:i:s"), '../');
              $newconn->query($sql_whatsapp_text1);
              $update_txt .= '
                "btn_content" : "'.$txt_open_url_data.'",
                "url_btn" : "'.$txt_open_url.'",
                "url_text" : "'.$txt_open_url_data.'",';

              $sql_whatsapp_text2 = "INSERT INTO whatsapp_text_".$new_indicator." ".
                                  "(whatsapp_text_id, compose_whatsapp_id, sms_type, whatsapp_text_title, text_data, text_reply, text_number, text_address, text_name, text_url, text_title, text_description, text_start_time, text_end_time, carousel_fileurl, carousel_srno, whatsapp_text_status, whatsapp_text_entry_date) "."VALUES ".
                                  "(NULL, '".$lastid."', 'TEXT', 'call_button', '$txt_call_button_data', NULL, '$txt_call_button', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', '".$current_date."')";
              site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." executed the query [$sql_whatsapp_text2] on ".date("Y-m-d H:i:s"), '../');
              $newconn->query($sql_whatsapp_text2);
              $update_txt .= '
                "call_btn" : "+91'.$txt_call_button.'",
                "call_text" : "'.$txt_call_button_data.'",';

              $opt = ''; $tsr_ii = 0;
              for($tsr_i = 0; $tsr_i < count($txt_reply_buttons); $tsr_i++) {
                $tsr_ii++;
                $sql_whatsapp_text4 = "INSERT INTO whatsapp_text_".$new_indicator." ".
                                  "(whatsapp_text_id, compose_whatsapp_id, sms_type, whatsapp_text_title, text_data, text_reply, text_number, text_address, text_name, text_url, text_title, text_description, text_start_time, text_end_time, carousel_fileurl, carousel_srno, whatsapp_text_status, whatsapp_text_entry_date) "."VALUES ".
                                  "(NULL, '".$lastid."', 'TEXT', 'reply_button', '$txt_reply_buttons_data[$tsr_i]', '$txt_reply_buttons[$tsr_i]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', '".$current_date."')";
                site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." executed the query [$sql_whatsapp_text4] on ".date("Y-m-d H:i:s"), '../');
                $newconn->query($sql_whatsapp_text4);
                $opt .= '"reply_btn'.$tsr_ii.'" : "'.$txt_reply_buttons[$tsr_i].'",';								
              }
              $opt = rtrim($opt, ",");

              $update_txt .= '{
                "msg_type" : "button",
                "btn_content" : "Reply",
                '.$opt.'
              },';

              $update_txt = rtrim($update_txt, ",");
              $update_txt .= '},';

              // Button Messages (URL & CALL & REPLY) - End
            } else {
              // Button Messages (URL) - Start
              if($txt_open_url != '' or $txt_call_button != '') {
                $update_txt .= '{
                  "msg_type" : "button",';
              }
              if($txt_open_url != '') {
                $sql_whatsapp_text1 = "INSERT INTO whatsapp_text_".$new_indicator." ".
                                "(whatsapp_text_id, compose_whatsapp_id, sms_type, whatsapp_text_title, text_data, text_reply, text_number, text_address, text_name, text_url, text_title, text_description, text_start_time, text_end_time, carousel_fileurl, carousel_srno, whatsapp_text_status, whatsapp_text_entry_date) "."VALUES ".
                                "(NULL, '".$lastid."', 'TEXT', 'url_button', '$txt_open_url_data', NULL, NULL, NULL, NULL, '$txt_open_url', NULL, NULL, NULL, NULL, NULL, NULL, 'Y', '".$current_date."')";
                site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." executed the query [$sql_whatsapp_text1] on ".date("Y-m-d H:i:s"), '../');
                $newconn->query($sql_whatsapp_text1);
                $update_txt .= '
                  "btn_content" : "'.$txt_open_url_data.'",
                  "url_btn" : "'.$txt_open_url.'",
                  "url_text" : "'.$txt_open_url_data.'",';
              }
              // Button Messages (URL) - End

              // Button Messages (CALL) - Start
              if($txt_call_button != '') {
                $sql_whatsapp_text2 = "INSERT INTO whatsapp_text_".$new_indicator." ".
                                    "(whatsapp_text_id, compose_whatsapp_id, sms_type, whatsapp_text_title, text_data, text_reply, text_number, text_address, text_name, text_url, text_title, text_description, text_start_time, text_end_time, carousel_fileurl, carousel_srno, whatsapp_text_status, whatsapp_text_entry_date) "."VALUES ".
                                    "(NULL, '".$lastid."', 'TEXT', 'call_button', '$txt_call_button_data', NULL, '$txt_call_button', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', '".$current_date."')";
                site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." executed the query [$sql_whatsapp_text2] on ".date("Y-m-d H:i:s"), '../');
                $newconn->query($sql_whatsapp_text2);
                $update_txt .= '
                  "call_btn" : "+91'.$txt_call_button.'",
                  "call_text" : "'.$txt_call_button_data.'",';
              }
              if($txt_open_url != '' or $txt_call_button != '') {
                $update_txt = rtrim($update_txt, ",");

                $update_txt .= '},';
                site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." executed the text [$update_txt] on ".date("Y-m-d H:i:s"), '../');
              }
              // Button Messages (CALL) - Start

              // Button Messages (REPLY) - Start
              if(count($txt_reply_buttons) > 0 and $txt_reply_buttons[0] != '') {
                $opt = ''; $tsr_ii = 0;
                for($tsr_i = 0; $tsr_i < count($txt_reply_buttons); $tsr_i++) {
                  $tsr_ii++;
                  $sql_whatsapp_text4 = "INSERT INTO whatsapp_text_".$new_indicator." ".
                                    "(whatsapp_text_id, compose_whatsapp_id, sms_type, whatsapp_text_title, text_data, text_reply, text_number, text_address, text_name, text_url, text_title, text_description, text_start_time, text_end_time, carousel_fileurl, carousel_srno, whatsapp_text_status, whatsapp_text_entry_date) "."VALUES ".
                                    "(NULL, '".$lastid."', 'TEXT', 'reply_button', '$txt_reply_buttons_data[$tsr_i]', '$txt_reply_buttons[$tsr_i]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', '".$current_date."')";
                  site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." executed the query [$sql_whatsapp_text4] on ".date("Y-m-d H:i:s"), '../');
                  $newconn->query($sql_whatsapp_text4);
                  $opt .= '"reply_btn'.$tsr_ii.'" : "'.$txt_reply_buttons[$tsr_i].'",';								
                }
                $opt = rtrim($opt, ",");

                $update_txt .= '{
                  "msg_type" : "button",
                  "btn_content" : "Reply",
                  '.$opt.'
                },';
                site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." executed the text [$update_txt] on ".date("Y-m-d H:i:s"), '../');
              }
              // Button Messages (REPLY) - End
            }
            
            // List Messages - Start
            if(count($txt_option_list) > 0 and $txt_option_list[0] != '') {
              $opt1 = '';
              for($tsr_i = 0; $tsr_i < count($txt_option_list); $tsr_i++) {
                $sql_whatsapp_text3 = "INSERT INTO whatsapp_text_".$new_indicator." ".
                                  "(whatsapp_text_id, compose_whatsapp_id, sms_type, whatsapp_text_title, text_data, text_reply, text_number, text_address, text_name, text_url, text_title, text_description, text_start_time, text_end_time, carousel_fileurl, carousel_srno, whatsapp_text_status, whatsapp_text_entry_date) "."VALUES ".
                                  "(NULL, '".$lastid."', 'TEXT', 'list', '$txt_option_list[$tsr_i]', '$txt_option_list[$tsr_i]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', '".$current_date."')";
                site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." executed the query [$sql_whatsapp_text3] on ".date("Y-m-d H:i:s"), '../');
                $newconn->query($sql_whatsapp_text3);
                $opt1 .= '{
                  "id": "'.strtolower($txt_option_list[$tsr_i]).'", "title": "'.$txt_option_list[$tsr_i].'"
                },';
              }
              $opt1 = rtrim($opt1, ",");

              $update_txt .= '{
                "msg_type" : "list",
                "title" : "Select",
                "body" : "Deals",
                "btn_text" : "View",
                "list_title" : "List", 
                "list_items" : [
                    '.$opt1.'
                ]
              },';
            }
            $update_txt = rtrim($update_txt, ",");
            site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." executed the text [$update_txt] on ".date("Y-m-d H:i:s"), '../');
            // List Messages - End

            $sendto_api = '{
              "user_id":"'.$_SESSION['yjwatsp_user_id'].'",
              "api_key":"'.$_SESSION['yjwatsp_api_key'].'",
              "db_name":"'.$new_dbname.'",
              "table_names":["compose_whatsapp_'.$new_indicator.'", "compose_whatsapp_status_'.$new_indicator.'", "whatsapp_text_'.$new_indicator.'"],
              "compose_whatsapp_id":"'.$lastid.'",
              "sender_numbers":['.$sender_mobile_nos.'],
              "mobile_numbers":['.$receive_mobile_nos.'],
              "mobile_numbers_insertid":['.$mobile_numbers_insertid.'],
              "messages" : [
                  '.$update_txt.'
                ]
              }';
              site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." api send text [$sendto_api] on ".date("Y-m-d H:i:s"), '../');
            // exit;


            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $watsp_msg_url.'/send_msg',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_SSL_VERIFYPEER => 0,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => $sendto_api,
              CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                'Content-Type: application/json; charset=utf-8'
              ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $respobj = json_decode($response);
            // echo $response;

            $rsp_id = $respobj->response_status;
            if($rsp_id == 403) {
              $json = array("status" => 2, "msg" => "Invalid User, Kindly try with valid User!!");
              site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." [Invalid User, Kindly try with valid User!!] on ".date("Y-m-d H:i:s"), '../');
            } elseif($rsp_id == 201) {
              $json = array("status" => 0, "msg" => "Failure");
              site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." [Failure] on ".date("Y-m-d H:i:s"), '../');
            } else {
              $json = array("status" => 1, "msg" => "Success");
              site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." [Success] on ".date("Y-m-d H:i:s"), '../');
            }
            // echo "@@";
        }
        if ($newconn->errno) {
            // echo "##";
            site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." Compose Whatsapp failed [Invalid Inputs] on ".date("Y-m-d H:i:s"), '../');
            $json = array("status" => 0, "msg" => "Invalid Inputs. Kindly try again with the correct Inputs!");
            // echo "$$";
        }
        $newconn->close();
    }
    site_log_generate("Compose Whatsapp Page : User : ".$_SESSION['yjwatsp_user_name']." newconn new db connection closed on ".date("Y-m-d H:i:s"), '../');
}
// Compose Whatsapp Page compose_whatsapp - End

// Finally Close all Opened Mysql DB Connection
$conn->close();

// Output header with JSON Response
header('Content-type: application/json');
echo json_encode($json);

