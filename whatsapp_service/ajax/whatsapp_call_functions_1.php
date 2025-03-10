<?php
session_start();//start session
error_reporting(E_ALL);// The error reporting function
// Include configuration.php
include_once('../api/configuration.php');
// Include site_common_functions.php
include_once('site_common_functions.php');
extract($_REQUEST);
$bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].''; 

$current_date = date("Y-m-d H:i:s");
$milliseconds = round(microtime(true) * 1000);
$default_variale_msg = '-';

// Template List Page tmpl_call_function remove_template - Start
if (isset($_GET['tmpl_call_function']) == "remove_template") {
  $template_response_id = htmlspecialchars(strip_tags(isset($_REQUEST['template_response_id']) ? $conn->real_escape_string($_REQUEST['template_response_id']) : ""));
  $change_status = htmlspecialchars(strip_tags(isset($_REQUEST['change_status']) ? $conn->real_escape_string($_REQUEST['change_status']) : ""));

  $replace_txt = '{
    "template_id" : "' . $template_response_id . '"
  }';
     // To Get Api URL
  $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $api_url.'/template/delete_template',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'DELETE',
    CURLOPT_POSTFIELDS =>$replace_txt,
    CURLOPT_HTTPHEADER => array(
      $bearer_token,
      'Content-Type: application/json'
     
    ),
  ));

  site_log_generate("Template List Page : User : " . $_SESSION['yjwatsp_user_name'] . " send it to Service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
  $response = curl_exec($curl);
  curl_close($curl);
  $state1 = json_decode($response, false);
  site_log_generate("Template List Page : User : " . $_SESSION['yjwatsp_user_name'] . " get Service response [$response] on " . date("Y-m-d H:i:s"), '../');

  if ($state1->response_code == 1) {
    site_log_generate("Template List Page : User : " . $_SESSION['yjwatsp_user_name'] . " delete template success on " . date("Y-m-d H:i:s"), '../');
    $json = array("status" => 1, "msg" => $state1->response_msg );
  } else {
    site_log_generate("Template List Page : User : " . $_SESSION['yjwatsp_user_name'] . " delete template failure on " . date("Y-m-d H:i:s"), '../');
    $json = array("status" => 0, "msg" => 'Template delete failure.');
  }
}
// Template List Page remove_template - End

// Compose SMS Page getSingleTemplate_meta - Start
if (isset($_GET['getSingleTemplate_meta']) == "getSingleTemplate_meta") {
  $tmpl_name = explode('!', $tmpl_name);

  $wht_tmpl_url = htmlspecialchars(strip_tags(isset($_REQUEST['wht_tmpl_url']) ? $conn->real_escape_string($_REQUEST['wht_tmpl_url']) : ""));
  $wht_bearer_token = htmlspecialchars(strip_tags(isset($_REQUEST['wht_bearer_token']) ? $conn->real_escape_string($_REQUEST['wht_bearer_token']) : ""));
   // To Get Api URL
  $curl_get = $wht_tmpl_url . "/message_templates?name=" . $tmpl_name[0] . "&language=" . $tmpl_name[1];
  $curl = curl_init();
  curl_setopt_array(
    $curl,
    array(
      CURLOPT_URL => $curl_get,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_SSL_VERIFYPEER => 0,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $wht_bearer_token
      ),
    )
  );

  $yjresponse = curl_exec($curl);
  curl_close($curl);

  $yjresponseobj = json_decode($yjresponse, false);

  if (count($yjresponseobj->data) > 0) {
    $stateData = '';
    $stateData_box = '';
    $hdr_type = '';

    for ($ii = 0; $ii < count($yjresponseobj->data); $ii++) {
      if ($yjresponseobj->data[$ii]->components[0]->type == 'HEADER') {
        switch ($yjresponseobj->data[$ii]->components[0]->format) {
          case 'TEXT':
            $hdr_type .= "<input type='hidden' name='hid_txt_header_variable' id='hid_txt_header_variable' value='" . $yjresponseobj->data[$ii]->components[0]->text . "'>";

            $stateData_1 = '';
            $stateData_1 = $yjresponseobj->data[$ii]->components[0]->text;
            $stateData_2 = $stateData_1;

            $matches = null;
            $prmt = preg_match_all("/{{[0-9]+}}/", $yjresponseobj->data[$ii]->components[0]->text, $matches);
            $matches_a0 = $matches[0];
            rsort($matches_a0);
            sort($matches_a0);
            for ($ij = 0; $ij < count($matches_a0); $ij++) {
              $expl2 = explode("{{", $matches_a0[$ij]);
              $expl3 = explode("}}", $expl2[1]);
              $stateData_box = "</div><div style='float:left; padding: 0 5px;'> <input type='text' readonly tabindex='10' name='txt_header_variable[$expl3[0]][]' id='txt_header_variable' placeholder='{{" . $expl3[0] . "}} Value' title='Header Text' maxlength='20' value='-' style='width:100px;height: 30px;cursor: not-allowed;' class='form-control required'> </div><div style='float: left;'>";
              $stateData_1 = str_replace("{{" . $expl3[0] . "}}", $stateData_box, $stateData_1);
              $stateData_2 = $stateData_1;
            }

            if ($stateData_2 != '') {
              $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Header : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
            }
            break;


          case 'DOCUMENT':
            $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Header : </div><div style='float:left'><input type='file' class='form-control' name='file_document_header' id='file_document_header' tabindex='11' accept='application/pdf' data-toggle='tooltip' onblur='validate_filesizes(this)' onfocus='disable_texbox(\"file_document_header\", \"file_document_header_url\")' data-placement='top' data-html='true' title='Upload Any PDF file, below or equal to 5 MB Size' data-original-title='Upload Any PDF file, below or equal to 5 MB Size'></div><div style='float:left'><span style='color:#FF0000'>[OR]</span></div><div style='float:left'><div class='' data-toggle='tooltip' data-placement='top' title='Enter Document URL' data-original-title='Enter Document URL'>
                <div class='input-group'>
                  <input class='form-control form-control-primary' type='url' name='file_document_header_url' id='file_document_header_url' maxlength='100' title='Enter Document URL' onfocus='disable_texbox(\"file_document_header_url\", \"file_document_header\")' tabindex='12' placeholder='Enter Document URL'>
                </div>
              </div>
              </div></div>";
            break;


          case 'IMAGE':
            $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Header : </div><div style='float:left'><input type='file' class='form-control' name='file_image_header' id='file_image_header' tabindex='11' accept='image/png,image/jpg,image/jpeg' data-toggle='tooltip' onblur='validate_filesizes(this)' onfocus='disable_texbox(\"file_image_header\", \"file_image_header_url\")' data-placement='top' data-html='true' title='Upload Any PNG, JPG, JPEG files, below or equal to 5 MB Size' data-original-title='Upload Any PNG, JPG, JPEG files, below or equal to 5 MB Size'></div><div style='float:left'><span style='color:#FF0000'>[OR]</span></div><div style='float:left'><div class='' data-toggle='tooltip' data-placement='top' title='Enter Image URL' data-original-title='Enter Image URL'>
                <div class='input-group'>
                  <input class='form-control form-control-primary' type='url' name='file_image_header_url' id='file_image_header_url' maxlength='100' title='Enter Image URL' tabindex='12' onfocus='disable_texbox(\"file_image_header_url\", \"file_image_header\")' placeholder='Enter Image URL'>
                  <span class='input-group-addon'><i class='icofont icofont-ui-messaging'></i></span>
                </div>
              </div>
              </div></div>";
            break;


          case 'VIDEO':
            $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Header : </div><div style='float:left'><input type='file' class='form-control' name='file_video_header' id='file_video_header' tabindex='11' accept='video/mp4' data-toggle='tooltip' onblur='validate_filesizes(this)' onfocus='disable_texbox(\"file_video_header\", \"file_video_header_url\")' data-placement='top' data-html='true' title='Upload Any MP4 file, below or equal to 5 MB Size' data-original-title='Upload Any MP4, MPEG, WEBM file, below or equal to 5 MB Size'></div><div style='float:left'><span style='color:#FF0000'>[OR]</span></div><div style='float:left'><div class='' data-toggle='tooltip' data-placement='top' title='Enter Video URL' data-original-title='Enter Video URL'>
                <div class='input-group'>
                  <input class='form-control form-control-primary' type='url' name='file_video_header_url' id='file_video_header_url' maxlength='100' title='Enter Video URL' tabindex='12' onfocus='disable_texbox(\"file_video_header_url\", \"file_video_header\")' placeholder='Enter Video URL'>
                  <span class='input-group-addon'><i class='icofont icofont-ui-messaging'></i></span>
                </div>
              </div>
              </div></div>";
            break;
        }
      }

      if ($yjresponseobj->data[$ii]->components[1]->type == 'BODY') {
        $hdr_type .= "<input type='hidden' name='hid_txt_body_variable' id='hid_txt_body_variable' value='" . $yjresponseobj->data[$ii]->components[1]->text . "'>";

        $stateData_1 = '';
        $stateData_1 = $yjresponseobj->data[$ii]->components[1]->text;
        $stateData_2 = $stateData_1;

        $matches = null;
        $prmt = preg_match_all("/{{[0-9]+}}/", $yjresponseobj->data[$ii]->components[1]->text, $matches);
        $matches_a1 = $matches[0];
        rsort($matches_a1);
        sort($matches_a1);
        for ($ij = 0; $ij < count($matches_a1); $ij++) {
          $expl2 = explode("{{", $matches_a1[$ij]);
          $expl3 = explode("}}", $expl2[1]);
          $stateData_box = "</div><div style='float:left; padding: 0 5px;'> <input type='text' readonly name='txt_body_variable[$expl3[0]][]' id='txt_body_variable' placeholder='{{" . $expl3[0] . "}} Value' maxlength='20' title='Enter {{" . $expl3[0] . "}} Value' value='-' style='width:100px;height: 30px;cursor: not-allowed;' class='form-control required'> </div><div style='float: left;'>";
          $stateData_1 = str_replace("{{" . $expl3[0] . "}}", $stateData_box, $stateData_1);
          $stateData_2 = $stateData_1;
        }

        if ($stateData_2 != '') {
          $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Body : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
        }

      }

      if ($yjresponseobj->data[$ii]->components[0]->type == 'BODY') {
        $hdr_type .= "<input type='hidden' name='hid_txt_body_variable' id='hid_txt_body_variable' value='" . $yjresponseobj->data[$ii]->components[0]->text . "'>";

        $stateData_1 = '';
        $stateData_1 = $yjresponseobj->data[$ii]->components[0]->text;
        $stateData_2 = $stateData_1;

        $matches = null;
        $prmt = preg_match_all("/{{[0-9]+}}/", $yjresponseobj->data[$ii]->components[0]->text, $matches);
        $matches_a1 = $matches[0];
        rsort($matches_a1);
        sort($matches_a1);
        for ($ij = 0; $ij < count($matches_a1); $ij++) {
          $expl2 = explode("{{", $matches_a1[$ij]);
          $expl3 = explode("}}", $expl2[1]);
          $stateData_box = "</div><div style='float:left; padding: 0 5px;'> <input type='text' readonly name='txt_body_variable[$expl3[0]][]' id='txt_body_variable' placeholder='{{" . $expl3[0] . "}} Value' maxlength='20' tabindex='12' title='Enter {{" . $expl3[0] . "}} Value' value='-' style='width:100px;height: 30px;cursor: not-allowed;' class='form-control required'> </div><div style='float: left;'>";
          $stateData_1 = str_replace("{{" . $expl3[0] . "}}", $stateData_box, $stateData_1);
          $stateData_2 = $stateData_1;
        }
        if ($stateData_2 != '') {
          $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Body : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
        }
      }

      if ($yjresponseobj->data[$ii]->components[1]->type == 'BUTTONS') { // echo "$$$";
        $stateData_2 = '';
        if ($yjresponseobj->data[$ii]->components[1]->buttons[0]->type == 'URL') {
          $stateData_2 .= "<a href='" . $yjresponseobj->data[$ii]->components[1]->buttons[0]->url . "' target='_blank'>" . $yjresponseobj->data[$ii]->components[1]->buttons[0]->text . "</a>";
          $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Buttons URL : </div><div style='float:left'>" . $yjresponseobj->data[$ii]->components[1]->buttons[0]->url . " - " . $stateData_2 . "</div></div>";
        }

        if ($yjresponseobj->data[$ii]->components[1]->buttons[0]->type == 'PHONE_NUMBER') {
          $stateData_2 .= $yjresponseobj->data[$ii]->components[1]->buttons[0]->text . " - " . $yjresponseobj->data[$ii]->components[1]->buttons[0]->phone_number;
          $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Buttons Phone No. : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
        }

        for ($kk = 0; $kk < count($yjresponseobj->data[$ii]->components[1]->buttons); $kk++) {
          if ($yjresponseobj->data[$ii]->components[1]->buttons[$kk]->type == 'QUICK_REPLY') {
            $stateData_2 .= $yjresponseobj->data[$ii]->components[1]->buttons[$kk]->text;
            $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Buttons Quick Reply : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
          }
        }
      }

      if ($yjresponseobj->data[$ii]->components[1]->type == 'FOOTER') {
        $hdr_type .= "<input type='hidden' name='hid_txt_footer_variable' id='hid_txt_footer_variable' value='" . $yjresponseobj->data[$ii]->components[1]->text . "'>";

        $stateData_2 = '';
        $stateData_2 = $yjresponseobj->data[$ii]->components[1]->text;

        if ($stateData_2 != '') {
          $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Footer : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
        }
      }
      if ($yjresponseobj->data[$ii]->components[2]->type == 'BUTTONS') {
        $stateData_2 = '';

        if ($yjresponseobj->data[$ii]->components[2]->buttons[0]->type == 'URL') {
          $stateData_2 .= "<a href='" . $yjresponseobj->data[$ii]->components[2]->buttons[0]->url . "' target='_blank'>" . $yjresponseobj->data[$ii]->components[2]->buttons[0]->text . "</a>";
          $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Buttons URL : </div><div style='float:left'>" . $yjresponseobj->data[$ii]->components[2]->buttons[0]->url . " - " . $stateData_2 . "</div></div>";
        }

        if ($yjresponseobj->data[$ii]->components[2]->buttons[0]->type == 'PHONE_NUMBER') {
          $stateData_2 .= $yjresponseobj->data[$ii]->components[2]->buttons[0]->text . " - " . $yjresponseobj->data[$ii]->components[2]->buttons[0]->phone_number;
          $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Buttons Phone No. : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
        }

        for ($kk = 0; $kk < count($yjresponseobj->data[$ii]->components[2]->buttons); $kk++) {
          if ($yjresponseobj->data[$ii]->components[2]->buttons[$kk]->type == 'QUICK_REPLY') {
            $stateData_2 .= $yjresponseobj->data[$ii]->components[2]->buttons[$kk]->text;
            $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Buttons Quick Reply : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
          }
        }
      }
    }
    site_log_generate("Compose Whatsapp Template Page : User : " . $_SESSION['yjwatsp_user_name'] . " Get Meta Message Template available on " . date("Y-m-d H:i:s"), '../');
    $json = array("status" => 1, "msg" => $stateData . $hdr_type);
  } else {
    site_log_generate("Compose Whatsapp Template Page : User : " . $_SESSION['yjwatsp_user_name'] . " Get Message Template not available on " . date("Y-m-d H:i:s"), '../');
    $json = array("status" => 0, "msg" => '-');
  }
}
// Compose SMS Page getSingleTemplate_meta - End

// Compose SMS Page PreviewTemplate - Start
if (isset($_GET['previewTemplate_meta']) == "previewTemplate_meta") {

  $tmpl_name = explode('!', $tmpl_name);

  $wht_tmpl_url = htmlspecialchars(strip_tags(isset($_REQUEST['wht_tmpl_url']) ? $conn->real_escape_string($_REQUEST['wht_tmpl_url']) : ""));
  $wht_bearer_token = htmlspecialchars(strip_tags(isset($_REQUEST['wht_bearer_token']) ? $conn->real_escape_string($_REQUEST['wht_bearer_token']) : ""));
   // To Get Api URL
  $curl_get = $wht_tmpl_url . "/message_templates?name=" . $tmpl_name[0] . "&language=" . $tmpl_name[1];
  $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].''; 
  $curl = curl_init();
  curl_setopt_array(
    $curl,
    array(
      CURLOPT_URL => $curl_get,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_SSL_VERIFYPEER => 0,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        $bearer_token ,
        'Authorization: Bearer ' . $wht_bearer_token
      ),
    )
  );

  $yjresponse = curl_exec($curl);
  curl_close($curl);
// echo $yjresponse;
  $yjresponseobj = json_decode($yjresponse, false);

  if (count($yjresponseobj->data) > 0) {
    $stateData = '';
    $stateData_box = '';
    $hdr_type = '';

    for ($ii = 0; $ii < count($yjresponseobj->data); $ii++) {
      if ($yjresponseobj->data[$ii]->components[0]->type == 'HEADER') {
        switch ($yjresponseobj->data[$ii]->components[0]->format) {
          case 'TEXT':
            $hdr_type .= "<input type='hidden' name='hid_txt_header_variable' id='hid_txt_header_variable' value='" . $yjresponseobj->data[$ii]->components[0]->text . "'>";

            $stateData_1 = '';
            $stateData_1 = $yjresponseobj->data[$ii]->components[0]->text;
            $stateData_2 = $stateData_1;

            $matches = null;
            $prmt = preg_match_all("/{{[0-9]+}}/", $yjresponseobj->data[$ii]->components[0]->text, $matches);
            $matches_a0 = $matches[0];
            rsort($matches_a0);
            sort($matches_a0);
            for ($ij = 0; $ij < count($matches_a0); $ij++) {
              $expl2 = explode("{{", $matches_a0[$ij]);
              $expl3 = explode("}}", $expl2[1]);
              $stateData_box = "</div><div style='float:left; padding: 0 5px;'> <input type='text' readonly tabindex='10' name='txt_header_variable[$expl3[0]][]' id='txt_header_variable' placeholder='{{" . $expl3[0] . "}} Value' title='Header Text' maxlength='20' value='-' style='width:100px;height: 30px;cursor: not-allowed;' class='form-control required'> </div><div style='float: left;'>";
              $stateData_1 = str_replace("{{" . $expl3[0] . "}}", $stateData_box, $stateData_1);
              $stateData_2 = $stateData_1;
            }

            if ($stateData_2 != '') {
              $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Header : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
            }
            break;


          case 'DOCUMENT':
            $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Header : </div><div style='margin-left:60px;'><i class='fa fa-file' style='font-size: 18px'></i> <span> DOCUMENT</span></div> <span class='input-group-addon'><i class='icofont icofont-ui-messaging'></i></span>
                </div>
              </div>
              </div></div>";
            break;


          case 'IMAGE':
            $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Header : </div><div style='margin-left:60px;'><i class='fa fa-image' style='font-size: 18px'></i> <span> IMAGE</span> </div>
                  <span class='input-group-addon'><i class='icofont icofont-ui-messaging'></i></span>
                </div>
              </div>
              </div></div>";
            break;


          case 'VIDEO':
            $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Header : </div> <div style='margin-left:60px;'><i class='fa fa-play-circle' style='font-size: 18px'></i> <span> VIDEO</span> </div>
<span class='input-group-addon'><i class='icofont icofont-ui-messaging'></i></span>
                </div>
              </div>
              </div></div>";
            break;
        }
      }

      if ($yjresponseobj->data[$ii]->components[1]->type == 'BODY') {
        $hdr_type .= "<input type='hidden' name='hid_txt_body_variable' id='hid_txt_body_variable' value='" . $yjresponseobj->data[$ii]->components[1]->text . "'>";

        $stateData_1 = '';
        $stateData_1 = $yjresponseobj->data[$ii]->components[1]->text;
        $stateData_2 = $stateData_1;

        $matches = null;
        $prmt = preg_match_all("/{{[0-9]+}}/", $yjresponseobj->data[$ii]->components[1]->text, $matches);
        $matches_a1 = $matches[0];
        rsort($matches_a1);
        sort($matches_a1);
        for ($ij = 0; $ij < count($matches_a1); $ij++) {
          $expl2 = explode("{{", $matches_a1[$ij]);
          $expl3 = explode("}}", $expl2[1]);
          $stateData_box = "</div><div style='float:left; padding: 0 5px;'> <input type='text' readonly name='txt_body_variable[$expl3[0]][]' id='txt_body_variable' placeholder='{{" . $expl3[0] . "}} Value' maxlength='20' title='Enter {{" . $expl3[0] . "}} Value' value='-' style='width:100px;height: 30px;cursor: not-allowed;' class='form-control required'> </div><div style='float: left;'>";
          $stateData_1 = str_replace("{{" . $expl3[0] . "}}", $stateData_box, $stateData_1);
          $stateData_2 = $stateData_1;
        }

        if ($stateData_2 != '') {
          $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Body : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
        }

   
      }

      if ($yjresponseobj->data[$ii]->components[0]->type == 'BODY') {
        $hdr_type .= "<input type='hidden' name='hid_txt_body_variable' id='hid_txt_body_variable' value='" . $yjresponseobj->data[$ii]->components[0]->text . "'>";

        $stateData_1 = '';
        $stateData_1 = $yjresponseobj->data[$ii]->components[0]->text;
        $stateData_2 = $stateData_1;

        $matches = null;
        $prmt = preg_match_all("/{{[0-9]+}}/", $yjresponseobj->data[$ii]->components[0]->text, $matches);
        $matches_a1 = $matches[0];
        rsort($matches_a1);
        sort($matches_a1);
        for ($ij = 0; $ij < count($matches_a1); $ij++) {
          $expl2 = explode("{{", $matches_a1[$ij]);
          $expl3 = explode("}}", $expl2[1]);
          $stateData_box = "</div><div style='float:left; padding: 0 5px;'> <input type='text' readonly name='txt_body_variable[$expl3[0]][]' id='txt_body_variable' placeholder='{{" . $expl3[0] . "}} Value' maxlength='20' tabindex='12' title='Enter {{" . $expl3[0] . "}} Value' value='-' style='width:100px;height: 30px;cursor: not-allowed;' class='form-control required'> </div><div style='float: left;'>";
          $stateData_1 = str_replace("{{" . $expl3[0] . "}}", $stateData_box, $stateData_1);
          $stateData_2 = $stateData_1;
        }
        if ($stateData_2 != '') {
          $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Body : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
        }
      }

      if ($yjresponseobj->data[$ii]->components[1]->type == 'BUTTONS') { // echo "$$$";
        $stateData_2 = '';
        if ($yjresponseobj->data[$ii]->components[1]->buttons[0]->type == 'URL') {
          $stateData_2 .= "<a href='" . $yjresponseobj->data[$ii]->components[1]->buttons[0]->url . "' target='_blank'>" . $yjresponseobj->data[$ii]->components[1]->buttons[0]->text . "</a>";
          $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Buttons URL : </div><div style='float:left'>" . $yjresponseobj->data[$ii]->components[1]->buttons[0]->url . " - " . $stateData_2 . "</div></div>";
        }

        if ($yjresponseobj->data[$ii]->components[1]->buttons[0]->type == 'PHONE_NUMBER') {
          $stateData_2 .= $yjresponseobj->data[$ii]->components[1]->buttons[0]->text . " - " . $yjresponseobj->data[$ii]->components[1]->buttons[0]->phone_number;
          $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Buttons Phone No. : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
        }

        for ($kk = 0; $kk < count($yjresponseobj->data[$ii]->components[1]->buttons); $kk++) {
          if ($yjresponseobj->data[$ii]->components[1]->buttons[$kk]->type == 'QUICK_REPLY') {
            $stateData_2 .= $yjresponseobj->data[$ii]->components[1]->buttons[$kk]->text;
            $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Buttons Quick Reply : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
          }
        }
      }

      if ($yjresponseobj->data[$ii]->components[1]->type == 'FOOTER') {
        $hdr_type .= "<input type='hidden' name='hid_txt_footer_variable' id='hid_txt_footer_variable' value='" . $yjresponseobj->data[$ii]->components[1]->text . "'>";

        $stateData_2 = '';
        $stateData_2 = $yjresponseobj->data[$ii]->components[1]->text;

        if ($stateData_2 != '') {
          $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Footer : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
        }
      }

      if ($yjresponseobj->data[$ii]->components[2]->type == 'BUTTONS') {
        $stateData_2 = '';

        if ($yjresponseobj->data[$ii]->components[2]->buttons[0]->type == 'URL') {
          $stateData_2 .= "<a href='" . $yjresponseobj->data[$ii]->components[2]->buttons[0]->url . "' target='_blank'>" . $yjresponseobj->data[$ii]->components[2]->buttons[0]->text . "</a>";
          $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Buttons URL : </div><div style='float:left'>" . $yjresponseobj->data[$ii]->components[2]->buttons[0]->url . " - " . $stateData_2 . "</div></div>";
        }

        if ($yjresponseobj->data[$ii]->components[2]->buttons[0]->type == 'PHONE_NUMBER') {
          $stateData_2 .= $yjresponseobj->data[$ii]->components[2]->buttons[0]->text . " - " . $yjresponseobj->data[$ii]->components[2]->buttons[0]->phone_number;
          $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Buttons Phone No. : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
        }

        for ($kk = 0; $kk < count($yjresponseobj->data[$ii]->components[2]->buttons); $kk++) {
          if ($yjresponseobj->data[$ii]->components[2]->buttons[$kk]->type == 'QUICK_REPLY') {
            $stateData_2 .= $yjresponseobj->data[$ii]->components[2]->buttons[$kk]->text;
            $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Buttons Quick Reply : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
          }
        }
      }
    }
    site_log_generate("Compose Whatsapp Template Page : User : " . $_SESSION['yjwatsp_user_name'] . " Get Meta Message Template available on " . date("Y-m-d H:i:s"), '../');
    $json = array("status" => 1, "msg" => $stateData . $hdr_type);
  } else {
    site_log_generate("Compose Whatsapp Template Page : User : " . $_SESSION['yjwatsp_user_name'] . " Get Message Template not available on " . date("Y-m-d H:i:s"), '../');
    $json = array("status" => 0, "msg" => '-');
  }
}
// Compose SMS Page PreviewTemplate - End

// Compose SMS Page validateMobno - Start
if (isset($_POST['validateMobno']) == "validateMobno") {
  $mobno = str_replace('"', '', htmlspecialchars(strip_tags(isset($_POST['mobno']) ? $conn->real_escape_string($_POST['mobno']) : "")));
  $dup = htmlspecialchars(strip_tags(isset($_POST['dup']) ? $conn->real_escape_string($_POST['dup']) : ""));
  $inv = htmlspecialchars(strip_tags(isset($_POST['inv']) ? $conn->real_escape_string($_POST['inv']) : ""));

  $mobno = str_replace('\n', ',', $mobno);
  $newline = explode('\n', $mobno);
  $correct_mobno_data = [];
  $return_mobno_data = '';
  $issu_mob = '';
  $cnt_vld_no = 0;
  $max_vld_no = 1000;
  for ($i = 0; $i < count($newline); $i++) {
    $expl = explode(",", $newline[$i]);

    for ($ij = 0; $ij < count($expl); $ij++) {
      if ($inv == 1) {
        $vlno = validate_phone_number($expl[$ij]);
      } else {
        $vlno = $newline[$i];
      }

      if ($vlno == true) {
        if ($dup == 1) {
          if (!in_array($expl[$ij], $correct_mobno_data)) {
            if ($expl[$ij] != '') {
              $cnt_vld_no++;
              if ($cnt_vld_no <= $max_vld_no) {
                $correct_mobno_data[] = $expl[$ij];
                $return_mobno_data .= $expl[$ij] . ",\n";
              } else {
                $issu_mob .= $expl[$ij] . ",";
              }
            } else {
              $issu_mob .= $expl[$ij] . ",";
            }
          } else {
            $issu_mob .= $expl[$ij] . ",";
          }
        } else {
          if ($expl[$ij] != '') {
            $cnt_vld_no++;
            if ($cnt_vld_no <= $max_vld_no) {
              $correct_mobno_data[] = $expl[$ij];
              $return_mobno_data .= $expl[$ij] . ",\n";
            } else {
              $issu_mob .= $expl[$ij] . ", ";
            }
          } else {
            $issu_mob .= $expl[$ij] . ", ";
          }
        }
      } else {
        $issu_mob .= $expl[$ij] . ",";
      }
    }
  }

  $return_mobno_data = rtrim($return_mobno_data, ",\n");
  $json = array("status" => 1, "msg" => $return_mobno_data . "||" . $issu_mob);
}
// Compose SMS Page validateMobno - End

// Compose Whatsapp Page compose_whatsapp - Start
if ($_SERVER['REQUEST_METHOD'] == "GET" and $tmpl_call_function == "compose_whatsapp") {
  site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " Compose Whatsapp failed [GET NOT ALLOWED] on " . date("Y-m-d H:i:s"), '../');
  $json = array("status" => 0, "msg" => "Get Method not allowed here!");
}
if ($_SERVER['REQUEST_METHOD'] == "POST" and $tmpl_call_function == "compose_whatsapp") {
  site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " access this page on " . date("Y-m-d H:i:s"), '../');

  if (isset($txt_header_variable)) {
    for ($i1 = 1; $i1 <= count($txt_header_variable); $i1++) {
      $stateData_1 = '';
      $stateData_1 = $hid_txt_header_variable;

      $matches = null;
      $prmt = preg_match_all("/{{[0-9]+}}/", $hid_txt_header_variable, $matches);
      $matches_a0 = $matches[0];
      rsort($matches_a0);
      sort($matches_a0);

      for ($ij = 0; $ij < count($matches_a0); $ij++) {
        $expl2 = explode("{{", $matches_a0[$ij]);
        $expl3 = explode("}}", $expl2[1]);
        $stateData_1 = str_replace("{{" . $expl3[0] . "}}", $txt_header_variable[$i1][0], $stateData_1);
      }

      $header_details = $stateData_1;
    }
  }

  $matches_a1 = [];
  if (isset($txt_body_variable)) {
    $path_parts = pathinfo($_FILES["fle_variable_csv"]["name"]);
    $extension = $path_parts['extension'];
    $filename = $_SESSION['yjwatsp_user_id'] . "_csv_" . $milliseconds . "." . $extension;
    /* Location */
    $location = "../uploads/compose_variables/" . $filename;
    $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
    $imageFileType = strtolower($imageFileType);

    /* Valid extensions */
    $valid_extensions = array("csv");
    $response = 0;
    /* Check file extension */
    if (in_array(strtolower($imageFileType), $valid_extensions)) {
      /* Upload file */
      if (move_uploaded_file($_FILES['fle_variable_csv']['tmp_name'], $location)) {
        $response = $location;
      }
    }

    $csvFile = fopen($location, 'r') or die("can't open file");
    // Skip the first line
    $vrble = '[';
    // Parse data from CSV file line by line
    while (($line = fgetcsv($csvFile)) !== FALSE) {
      $vrble .= "[";
      // Get row data
      $tmp = '';
      for ($txt_variable_counti = 0; $txt_variable_counti <= $txt_variable_count; $txt_variable_counti++) {
        if ($txt_variable_counti > 0) {
          if ($line[$txt_variable_counti] == '') {
            $tmp .= '"' . $default_variale_msg . '", ';
          } else {
            $tmp .= '"' . $line[$txt_variable_counti] . '", ';
          }
        }
      }
      $tmp = rtrim($tmp, ", ");
      $vrble .= $tmp . "], ";
    }
    $vrble = rtrim($vrble, ", ");
    $vrble = $vrble . "]";
    // Close opened CSV file
    fclose($csvFile);

    $body_vrble = $vrble;
  }

  // Get data
  $txt_list_mobno = htmlspecialchars(strip_tags(isset($_REQUEST['txt_list_mobno']) ? $_REQUEST['txt_list_mobno'] : ""));
  $chk_remove_duplicates = htmlspecialchars(strip_tags(isset($_REQUEST['chk_remove_duplicates']) ? $_REQUEST['chk_remove_duplicates'] : ""));
  $chk_remove_invalids = htmlspecialchars(strip_tags(isset($_REQUEST['chk_remove_invalids']) ? $_REQUEST['chk_remove_invalids'] : ""));
  $id_slt_contgrp = htmlspecialchars(strip_tags(isset($_REQUEST['id_slt_contgrp']) ? $_REQUEST['id_slt_contgrp'] : "0"));
  $txt_sms_type = htmlspecialchars(strip_tags(isset($_REQUEST['txt_sms_type']) ? $_REQUEST['txt_sms_type'] : "TEXT"));
  $txt_sms_type = strtoupper($txt_sms_type);
  $country_code = '';
  $mime_type = '';

  $id_slt_mobileno = htmlspecialchars(strip_tags(isset($_REQUEST['id_slt_mobileno']) ? $_REQUEST['id_slt_mobileno'] : "0"));
  $expl_id_slt_mobileno = explode('||', $id_slt_mobileno);
  $id_slt_mobileno = $expl_id_slt_mobileno[2];
  $wht_tmplsend_url = $expl_id_slt_mobileno[3];
  $wht_tmpl_url = $expl_id_slt_mobileno[1];
  $wht_bearer_token = $expl_id_slt_mobileno[0];

  $filename = '';
  if ($_FILES['txt_media']['name'] != '') {
    $path_parts = pathinfo($_FILES["txt_media"]["name"]);
    $extension = $path_parts['extension'];

    $filename = $_SESSION['yjwatsp_user_id'] . "_" . $milliseconds . "." . $extension;

    /* Location */
    $location = "../uploads/whatsapp_media/" . $filename;
    $send_location = realpath($_SERVER["DOCUMENT_ROOT"]) . "/whatsapp/uploads/whatsapp_media/" . $filename;
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
    $valid_extensions = array("jpg", "jpeg", "png", "pdf", "gif", "mp4", "webm");

    $rspns = '';
    /* Check file extension */
    /* Upload file */
    if (move_uploaded_file($_FILES['txt_media']['tmp_name'], $location)) {
      site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " whatsapp_media file moved into Folder on " . date("Y-m-d H:i:s"), '../');
    }
    // }
  } else {
    $filename = '';
  }

  $txt_sms_content = htmlspecialchars(strip_tags(isset($_REQUEST['txt_sms_content']) ? $_REQUEST['txt_sms_content'] : ""));
  $txt_caption = htmlspecialchars(strip_tags(isset($_REQUEST['txt_caption']) ? $_REQUEST['txt_caption'] : "Media"));
  $txt_char_count = htmlspecialchars(strip_tags(isset($_REQUEST['txt_char_count']) ? $_REQUEST['txt_char_count'] : "1"));
  $txt_sms_count = htmlspecialchars(strip_tags(isset($_REQUEST['txt_sms_count']) ? $_REQUEST['txt_sms_count'] : "1"));
  $txt_rcscard_title = htmlspecialchars(strip_tags(isset($_REQUEST['txt_rcscard_title']) ? $_REQUEST['txt_rcscard_title'] : ""));
  $chk_save_contact_group = htmlspecialchars(strip_tags(isset($_REQUEST['chk_save_contact_group']) ? $_REQUEST['chk_save_contact_group'] : ""));

  $expl_wht = explode("~~", $txt_whatsapp_mobno[0]);
  $storeid = $expl_wht[0];
  $confgid = $expl_wht[1];

  // Receiver Mobile Numbers
  $newline1 = explode("\n", $txt_list_mobno);
  $receive_mobile_nos = '';
  $cnt_mob_no = count($newline1);
  for ($i1 = 0; $i1 < count($newline1); $i1++) {
    $expl1 = explode(",", $newline1[$i1]);
    for ($ij1 = 0; $ij1 < count($expl1); $ij1++) {
      if (validate_phone_number($expl1[$ij1])) {
        $mblno[] = $expl1[$ij1];
        $receive_mobile_nos .= $expl1[$ij1] . ',';
      }
    }
  }
  $receive_mobile_nos = rtrim($receive_mobile_nos, ",");

  // Sender Mobile Numbers
  $sender_mobile_nos = '';
  for ($i1 = 0; $i1 < count($txt_whatsapp_mobno); $i1++) {
    $ex1 = explode('~~', $txt_whatsapp_mobno[$i1]);
    $sender_mobile_nos .= $ex1[2] . ',';
  }
  $sender_mobile_nos = rtrim($sender_mobile_nos, ",");

  $txt_sms_content = substr($txt_sms_content, 0, 1000);
  if (strlen($txt_sms_content) != mb_strlen($txt_sms_content, 'utf-8')) {
    // echo "Please enter English words only:("; 
    $txt_char_count = mb_strlen($txt_sms_content, 'utf-8');
    $txt_sms_count = ceil($txt_char_count / 70);
  } else {
    // echo "OK, English Detected!";
    $txt_char_count = strlen($txt_sms_content);
    $txt_sms_count = ceil($txt_char_count / 160);
  }

  $usr_id = $_SESSION['yjwatsp_user_id'];

  $replace_txt = '{
    "user_id" : "' . $usr_id . '"
  }';
     // To Get Api URL
  $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $api_url.'/list/available_credits',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_POSTFIELDS =>$replace_txt,
    CURLOPT_HTTPHEADER => array(
      $bearer_token,
      'Content-Type: application/json'
     
    ),
  ));
  site_log_generate("Compose Whatsapp Page : " . $_SESSION['yjwatsp_user_name'] . " Execute the service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
  $response = curl_exec($curl);
  curl_close($curl);

  $header = json_decode($response, false);
  site_log_generate("Compose Whatsapp Page : " . $_SESSION['yjwatsp_user_name'] . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');

  if ($header->num_of_rows > 0) {
    for ($indicator = 0; $indicator < $header->num_of_rows; $indicator++) {
      $alotsms = $header->report[$indicator]->available_messages;
      $expdate = date("Y-m-d H:i:s", strtotime($header->report[$indicator]->expiry_date));
    }
  } else {
    $alotsms = 0;
    $expdate = '';
  }

  $ttlmsgcnt = 0;
  if ($txt_sms_content != '') {
    $ttlmsgcnt++;
  }
  if ($filename != '') {
    $ttlmsgcnt++;
  }
  if ($txt_open_url != '' or $txt_call_button != '' or (count($txt_reply_buttons) > 0 and $txt_reply_buttons[0] != '')) {
    $ttlmsgcnt++;
  }
  if (count($txt_option_list) > 0 and $txt_option_list[0] != '') {
    $ttlmsgcnt++;
  }

  $ttl_sms_cnt = count($mblno);
  $txt_sms_content = str_replace("'", "\'", $txt_sms_content);
  $txt_sms_content = str_replace('"', '\"', $txt_sms_content);

  if ($alotsms == 0 and $_SESSION['yjwatsp_user_master_id'] != 1) {
    site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " Compose Whatsapp failed [Whatsapp Credits are not available..] on " . date("Y-m-d H:i:s"), '../');
    $json = array("status" => 0, "msg" => "Whatsapp Credits are not available. Kindly verify!!");
  } elseif ($alotsms < $ttl_sms_cnt and $_SESSION['yjwatsp_user_master_id'] != 1) {
    site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " Compose Whatsapp failed [Whatsapp Credits are not available.] on " . date("Y-m-d H:i:s"), '../');
    $json = array("status" => 0, "msg" => "Whatsapp Credits are not available. Kindly verify!!");
  } elseif ($txt_char_count > 1000) {
    site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " Compose Whatsapp failed [Morethan 1000 characters are not allowed for Whatsapp] on " . date("Y-m-d H:i:s"), '../');
    $json = array("status" => 0, "msg" => "Morethan 1000 characters are not allowed for Whatsapp. Kindly verify!!");
  } elseif ($expdate == '' and $_SESSION['yjwatsp_user_master_id'] != 1) {
    site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " Compose Whatsapp failed [Validity Period Expired.] on " . date("Y-m-d H:i:s"), '../');
    $json = array("status" => 0, "msg" => "Validity Period Expired. Kindly verify!");
  } elseif (strtotime($expdate) < strtotime($current_date) and $_SESSION['yjwatsp_user_master_id'] != 1) {
    site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " Compose Whatsapp failed [Validity Period Expired..] on " . date("Y-m-d H:i:s"), '../');
    $json = array("status" => 0, "msg" => "Validity Period Expired. Kindly verify!!");
  } else {

    // Send Whatsapp Message - Start
    $tmpl_name1 = explode('!', $slt_whatsapp_template);
    $curl_get = $wht_tmpl_url . "/message_templates?name=" . $tmpl_name1[0] . "&language=" . $tmpl_name1[1] . "&access_token=" . $wht_bearer_token;
    //Submit to server
    $yjresponse = file_get_contents($curl_get);
    $yjresponseobj = json_decode($yjresponse, false);

    $whatsapp_tmpl_hdtext = '';
    $whatsapp_tmpl_body = '';
    $whatsapp_tmpl_footer = '';
    $whtsap_send = '';

    if ($yjresponseobj->data[0]->components[0]->type == 'HEADER') {
      switch ($yjresponseobj->data[0]->components[0]->format) {
        case 'TEXT':
          if (isset($txt_header_variable)) {
            if (count($txt_header_variable) > 0) {
              $whtsap_send .= '[
                                    {
                                        "type": "HEADER",
                                        "parameters": [
                                            {
                                                "type": "text",
                                                "text": "' . $txt_header_variable[1][0] . '"
                                            }
                                        ]
                                    },';
            }
          } else {
            $whtsap_send .= '""';
          }
          break;

        case 'DOCUMENT':
          if (isset($_FILES['file_document_header']['name']) or $file_document_header_url != '') {
            $whtsap_send .= '[
                                    {
                                        "type": "HEADER",
                                        "parameters": [
                                            {
                                                "type": "document",';

            if (isset($_FILES['file_document_header']['name'])) {

              $filename = $_SESSION['yjwatsp_user_id'] . "_" . $milliseconds . "_" . $_FILES['file_document_header']['name'];

              /* Location */
              $location = "../uploads/whatsapp_docs/" . $filename;
              $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
              $imageFileType = strtolower($imageFileType);

              /* Valid extensions */
              $valid_extensions = array("pdf");

              $rspns = '';
              /* Check file extension */
              if (in_array(strtolower($imageFileType), $valid_extensions)) {
                /* Upload file */
                if (move_uploaded_file($_FILES['file_document_header']['tmp_name'], $location)) {
                  $rspns = $location;
                  site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " whatsapp_docs file moved into Folder on " . date("Y-m-d H:i:s"), '../');
                }
              }

              $whtsap_send .= '"document": {
                                                  "link": "' . $site_url . 'uploads/whatsapp_docs/' . $filename . '",
                                                  "filename": "File PDF"
                                              }';

            } elseif ($file_document_header_url != '') {
              $whtsap_send .= '"document": {
                                                  "link": "' . $file_document_header_url . '",
                                                  "filename": "File PDF"
                                              }';
            }

            $whtsap_send .= ' }
                              ]
                          },';
          }
          break;

        case 'IMAGE':
          if (isset($_FILES['file_image_header']['name']) or $file_image_header_url != '') {
            $whtsap_send .= '[
                                  {
                                      "type": "HEADER",
                                      "parameters": [
                                          {
                                              "type": "image",';

            if (isset($_FILES['file_image_header']['name'])) {

              $filename = $_SESSION['yjwatsp_user_id'] . "_" . $milliseconds . "_" . $_FILES['file_image_header']['name'];

              /* Location */
              $location = "../uploads/whatsapp_images/" . $filename;
              $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
              $imageFileType = strtolower($imageFileType);

              /* Valid extensions */
              $valid_extensions = array("png", "jpg", "jpeg");

              $rspns = '';
              /* Check file extension */
              if (in_array(strtolower($imageFileType), $valid_extensions)) {
                /* Upload file */
                if (move_uploaded_file($_FILES['file_image_header']['tmp_name'], $location)) {
                  $rspns = $location;
                  site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " whatsapp_images file moved into Folder on " . date("Y-m-d H:i:s"), '../');
                }
              }

              $whtsap_send .= '"image": {
                                              "link": "' . $site_url . 'uploads/whatsapp_images/' . $filename . '"
                                          }';

            } elseif ($file_image_header_url != '') {
              $whtsap_send .= '"image": {
                                              "link": "' . $file_image_header_url . '"
                                          }';
            }

            $whtsap_send .= ' }
                              ]
                          },';
          }
          break;

        case 'VIDEO':
          if (isset($_FILES['file_video_header']['name']) or $file_video_header_url != '') {
            $whtsap_send .= '[
                                  {
                                      "type": "HEADER",
                                      "parameters": [
                                          {
                                              "type": "video",';

            if (isset($_FILES['file_video_header']['name'])) {

              $filename = $_SESSION['yjwatsp_user_id'] . "_" . $milliseconds . "_" . $_FILES['file_video_header']['name'];

              /* Location */
              $location = "../uploads/whatsapp_videos/" . $filename;
              $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
              $imageFileType = strtolower($imageFileType);

              /* Valid extensions */
              $valid_extensions = array("mp4");

              $rspns = '';
              /* Check file extension */
              if (in_array(strtolower($imageFileType), $valid_extensions)) {
                /* Upload file */
                if (move_uploaded_file($_FILES['file_video_header']['tmp_name'], $location)) {
                  $rspns = $location;
                  site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " whatsapp_videos file moved into Folder on " . date("Y-m-d H:i:s"), '../');
                }
              }

              $whtsap_send .= '"video": {
                                              "link": "' . $site_url . 'uploads/whatsapp_videos/' . $filename . '"
                                          }';

            } elseif ($file_video_header_url != '') {
              $whtsap_send .= '"video": {
                                              "link": "' . $file_video_header_url . '"
                                          }';
            }

            $whtsap_send .= ' }
                            ]
                        },';
          }
          break;

        default:
          $whtsap_send .= '[
                  {
                      "type": "HEADER",
                      "parameters": [
                          {
                              "type": "text",
                              "text": "' . $hid_txt_header_variable . '"
                          }
                      ]
                  },
                  {
                    "type": "BODY",
                    "parameters": [
                        {
                            "type": "text",
                            "text": "' . $hid_txt_body_variable . '"
                        }
                    ]
                  },';
          break;
      }
      $whtsap_send = rtrim($whtsap_send, ",");
      $whtsap_send .= ']';


      if ($yjresponseobj->data[0]->components[1]->type == 'BODY') {
        if (count($matches_a1) > 0) {
          $whtsap_send .= '{
                                  "type": "BODY",
                                  "parameters": [ ';

          for ($ij1 = 1; $ij1 <= count($matches_a1); $ij1++) {
            $whtsap_send .= '{
                                    "type": "text",
                                    "text": "' . $txt_body_variable[$ij1][0] . '"
                                }';
            if ($ij1 != count($matches_a1)) {
              $whtsap_send .= ',';
            }
          }

          $whtsap_send .= ']
                        }]';
        }
      } else {
        $whtsap_send .= '
                    ]';
      }

      $whatsapp_tmpl_hdtext = $header_details;
      $whatsapp_tmpl_body = $body_details;
      $whatsapp_tmpl_footer = $yjresponseobj->data[0]->components[2]->text;
    }

    if ($yjresponseobj->data[0]->components[0]->type == 'BODY') {

      if (isset($matches_a1)) {
        if (count($matches_a1) > 0) {
          $whtsap_send .= '[
                                {
                                  "type": "BODY",
                                  "parameters": [';

          for ($ij1 = 1; $ij1 <= count($matches_a1); $ij1++) {
            $whtsap_send .= '{
                                    "type": "text",
                                    "text": "' . $txt_body_variable[$ij1][0] . '"
                                }';
            if ($ij1 != count($matches_a1)) {
              $whtsap_send .= ',';
            }
          }

          $whtsap_send .= ']
                        }]';
        } else {
          $whtsap_send .= '""';
        }
      }

    }

    $whtsap_send = rtrim($whtsap_send, ",");
    $whtsap_send = str_replace('""', '[]', $whtsap_send);
    $whatsapp_tmpl_body = $whtsap_send;
    $expld1 = explode("!", $slt_whatsapp_template);

    if ($txt_variable_count > 0) {
      $sendto_api = '{
                       
                        "user_id":"' . $_SESSION['yjwatsp_user_id'] . '",
                        "store_id":"1",
                        "sender_numbers":[' . $sender_mobile_nos . '],
                        "receiver_numbers":[' . $receive_mobile_nos . '],
                        "components":' . $whtsap_send . ',
                        "template_id":"' . $expld1[3] . '",
                        "variable_values":' . $body_vrble . '
                      }';
    } else {
      $sendto_api = '{
                       
                        "user_id":"' . $_SESSION['yjwatsp_user_id'] . '",
                        "store_id":"1",
                        "sender_numbers":[' . $sender_mobile_nos . '],
                        "receiver_numbers":[' . $receive_mobile_nos . '],
                        "components":' . $whtsap_send . ',
                        "template_id":"' . $expld1[3] . '"
                      }';
    }

    site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " api send text [$sendto_api] on " . date("Y-m-d H:i:s"), '../');
    $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
       // To Get Api URL
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url . '/compose_whatsapp_message',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $sendto_api,
      CURLOPT_HTTPHEADER => array(
        $bearer_token,
        "cache-control: no-cache",
        'Content-Type: application/json; charset=utf-8'
       
      ),
    ));

    $response = curl_exec($curl);
    // echo $response;
    curl_close($curl);
    $respobj = json_decode($response);
    site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " api send text - Response [$response] on " . date("Y-m-d H:i:s"), '../');

    $rsp_id = $respobj->response_status;
    if($respobj->data[0] != '') {
      $rsp_msg_1 = strtoupper($respobj->data[0]);
    } else {
      $rsp_msg = strtoupper($respobj->response_msg);
    }

    if ($rsp_id == 203) {
      $json = array("status" => 2, "msg" => "Invalid User, Kindly try again with Valid User!!");
      site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " [Invalid User, Kindly try again with Valid User!!] on " . date("Y-m-d H:i:s"), '../');
    } else if ($rsp_id == 201) {
      $json = array("status" => 0, "msg" => "Failure - $rsp_msg");
      site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " [Failure - $rsp_msg] on " . date("Y-m-d H:i:s"), '../');
    } else {
      $json = array("status" => 1, "msg" => "Campaign Name Created Successfully!!");
      site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " [Success] on " . date("Y-m-d H:i:s"), '../');
    }
  }
  site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " newconn new db connection closed on " . date("Y-m-d H:i:s"), '../');
}
// Compose Whatsapp Page compose_whatsapp - End

// Create Template create_template - Start

if ($_SERVER['REQUEST_METHOD'] == "POST" and $temp_call_function == "create_template") {

  $categories = htmlspecialchars(strip_tags(isset($_REQUEST['categories']) ? $conn->real_escape_string($_REQUEST['categories']) : ""));
 

  $textarea = htmlspecialchars(strip_tags(isset($_REQUEST['textarea']) ? $conn->real_escape_string($_REQUEST['textarea']) : ""));
  $textarea = str_replace("'", "\'", $textarea);
  $textarea = str_replace('"', '\"', $textarea);

  $txt_header_name = htmlspecialchars(strip_tags(isset($_REQUEST['txt_header_name']) ? $conn->real_escape_string($_REQUEST['txt_header_name']) : ""));

  $txt_footer_name = htmlspecialchars(strip_tags(isset($_REQUEST['txt_footer_name']) ? $conn->real_escape_string($_REQUEST['txt_footer_name']) : ""));


  $media_category = htmlspecialchars(strip_tags(isset($_REQUEST['media_category']) ? $conn->real_escape_string($_REQUEST['media_category']) : ""));

  $txt_header_variable = htmlspecialchars(strip_tags(isset($_REQUEST['txt_header_variable']) ? $conn->real_escape_string($_REQUEST['txt_header_variable']) : ""));

  foreach ($lang as $lang_id) {
    // echo $lang."<br/>";
    $langid .= $lang_id . "";
  }
  $language = explode("-", $langid);
  $language_code = $language[0];
  $language_id = $language[1];
if($language_code == 'en_GB' || $language_code == 'en_US'  ){
    $code .= "t";
  }else{

    $code .= "l";
  }

  $user_id = $_SESSION['yjwatsp_user_id'];



  foreach ($select_action1 as $slt_action1) {
    $slt_action_1 .= '"' . $slt_action1 . '"';

  }

  foreach ($select_action4 as $slt_action4) {
    $slt_action_4 .= '"' . $slt_action4 . '"';

  }
  foreach ($select_action5 as $slt_action5) {
    $slt_action_5 .= '"' . $slt_action5 . '"';

  }
  foreach ($select_action3 as $slt_action3) {
    $slt_action_3 .= '"' . $slt_action3 . '"';

  }
  foreach ($website_url as $web_url) {
    $web_url_link .= $web_url;

  }
  foreach ($button_url_text as $btn_txt_url) {
    $btn_txt_url_name .= $btn_txt_url;

  }
  foreach ($button_txt_phone_no as $btn_txt_phn) {
    $btn_txt_phn_no .= $btn_txt_phn;

  }
  foreach ($button_text as $btn_txt) {
    $btn_txt_name .= $btn_txt;

  }
  foreach ($txt_sample as $txt_variable) {
    $txt_sample_variable .= '"' . $txt_variable . '"' . ',';

  }
  $txt_variable = rtrim($txt_sample_variable, ",");



  foreach ($button_quickreply_text as $txt_button_qr_txt) {
    $txt_button_qr_text1 .= '"' . $txt_button_qr_txt . '"' . ',';
  }
  $txt_button_qr_text = explode(",", $txt_button_qr_text1);
  $txt_button_qr_text_1 = $txt_button_qr_text[0];
  $txt_button_qr_text_2 = $txt_button_qr_text[1];
  $txt_button_qr_text_3 = $txt_button_qr_text[2];

  $reply_arr = array();
  if ($txt_button_qr_text_1) {
    $reply_array .= '
  {"type":"QUICK_REPLY","text":' . $txt_button_qr_text_1 . '}';
    array_push($reply_arr, $reply_array);

  }

  if ($txt_button_qr_text_2) {
    $reply_array .= ',
  {"type":"QUICK_REPLY", "text":' . $txt_button_qr_text_2 . '}';
    array_push($reply_arr, $reply_array);
  }
  if ($txt_button_qr_text_3) {
    $reply_array .= ',
  {"type":"QUICK_REPLY", "text": ' . $txt_button_qr_text_3 . '}';
    array_push($reply_arr, $reply_array);
  }

  foreach ($reply_arr as $reply_arr1) {
    $reply_array_content = $reply_arr1;
  }
  

    $selectOption = $_POST['header'];
    $select_action = $_POST['select_action'];
    $select_action1 = $_POST['select_action1'];
    $select_action2 = $_POST['select_action2'];
    $select_action3 = $_POST['select_action3'];
    $select_action4 = $_POST['select_action4'];
    $select_action5 = $_POST['select_action5'];
    $country_code = $_POST['country_code'];
    $whtsap_send = '';

    $add_url_btn = '';

    $add_phoneno_btn = '';



    if ($textarea && $txt_variable) {

      $whtsap_send .= '[
    {
      "type":"BODY", 
      "text":"' . $textarea . '",
      "example":{"body_text":[[' . $txt_variable . ']]}
  }';
    }
    if ($textarea && !$txt_variable) {
      $whtsap_send .= '[ {
                          "type": "BODY",
                          "text": "' . $textarea . '"
                        }';

    }
    if ($selectOption == 'TEXT') {
      switch ($selectOption == 'TEXT') {

        case $txt_header_name && !$txt_header_variable:
   $code .=  "h";
          $whtsap_send .= ', 
        {
            "type":"HEADER", 
            "format":"TEXT",
            "text":"' . $txt_header_name . '"
        }';
break;
        case $txt_header_name && $txt_header_variable:
 $code .= "h";
          $whtsap_send .= ', 
        {
            "type":"HEADER", 
            "format":"TEXT",
            "text":"' . $txt_header_name . '",
            "example":{"header_text":["' . $txt_header_variable . '"]}
        }';
break;
 default:
            # code...
            break;
      }
    }
else{
    
      $code .= "0";
    }

  if($selectOption == 'MEDIA'){
    switch ($media_category) {
      case 'image':
        $code .= "i00";
        break;
        case 'video':
          $code .= "0v0";
          break;
          case 'document':
            $code .= "00d";
            break;
            default:
            # code...
            break;
      }
    }
    else{  
      $code .= "000";
    }



    if ($select_action5 == "VISIT_URL" && $btn_txt_url_name && $web_url_link) {
      $add_url_btn .= ',
                                      {
                                              "type":"URL", "text": "' . $btn_txt_url_name . '","url":"' . $web_url_link . '"
                                      }';

    }
    if ($select_action4 == "PHONE_NUMBER" && $btn_txt_name && $btn_txt_phn_no && $country_code) {
      $add_phoneno_btn .= ',
                                        {"type":"PHONE_NUMBER","text":"' . $btn_txt_name . '","phone_number":"' . $country_code . '' . $btn_txt_phn_no . '" }';

    }
    
    if ($select_action1 == "PHONE_NUMBER" && $btn_txt_name && $btn_txt_phn_no && $country_code && $add_url_btn) {

      $code .= "cu";
    }else if ($select_action1 == "PHONE_NUMBER" && $btn_txt_name && $btn_txt_phn_no && $country_code) {
      $code .= "c0";
    }else if($select_action1 == "VISIT_URL" && $btn_txt_url_name && $web_url_link && $add_phoneno_btn){
      $code .= "cu";
    }
    else if($select_action1 == "VISIT_URL" && $btn_txt_url_name && $web_url_link){
      $code .= "0u";
    }
    else{
      
      $code .= "00";
    }
   if ($select_action == "QUICK_REPLY") {
      if ($txt_button_qr_text_1) {
        $code .= "r";
      }
    }
    else{
    
      $code .= "0";
    }
    if ($txt_footer_name) {
 $code .= "f";
      $whtsap_send .= ', 							
                      {
                        "type":"FOOTER", 
                        "text":"' . $txt_footer_name . '"
                    }';

    }else{
  
      $code .= "0";
    }


    if ($select_action1 == "PHONE_NUMBER" && $btn_txt_name && $btn_txt_phn_no && $country_code && $add_url_btn) {

      $whtsap_send .= ',
                                    {
                                      "type":"BUTTONS",
                                      "buttons":[{"type":"PHONE_NUMBER","text":"' . $btn_txt_name . '","phone_number":"' . $country_code . '' . $btn_txt_phn_no . '"} ' . $add_url_btn . ' ]
                                  
                                   }';
    } else if ($select_action1 == "PHONE_NUMBER" && $btn_txt_name && $btn_txt_phn_no && $country_code) {

      $whtsap_send .= ',
                                      {
                                        "type":"BUTTONS",
                                        "buttons":[{"type":"PHONE_NUMBER","text":"' . $btn_txt_name . '","phone_number":"' . $country_code . '' . $btn_txt_phn_no . '"}]
                                    
                                      }';
    }


    if ($select_action1 == "VISIT_URL" && $btn_txt_url_name && $web_url_link && $add_phoneno_btn) {

      $whtsap_send .= ',
                                    {
                                      "type":"BUTTONS",
                                          "buttons":[{"type":"URL", "text": "' . $btn_txt_url_name . '","url":"' . $web_url_link . '"}
                                          ' . $add_phoneno_btn . '	]	
                                          }';
    } else if ($select_action1 == "VISIT_URL" && $btn_txt_url_name && $web_url_link) {

      $whtsap_send .= ',
                                            {
                                              "type":"BUTTONS",
                                                  "buttons":[{"type":"URL", "text": "' . $btn_txt_url_name . '","url":"' . $web_url_link . '"}
                                                    ]
                                                    }';
    }
    if ($select_action == "QUICK_REPLY") {
      if ($txt_button_qr_text_1) {
        $whtsap_send .= ',
                                      {
                                        "type":"BUTTONS",
                      "buttons":[' . $reply_array_content . ']
                                      }';


      }
    }

    $whtsap_send .= '
                                    ]';


    if ($selectOption == 'MEDIA') {
      switch ($media_category) {
        case 'image':


          if (isset($_FILES['file_image_header']['name'])) {
            /* Location */

            $image_size = $_FILES['file_image_header']['size'];
            $image_type = $_FILES['file_image_header']['type'];
            $file_type = explode("/", $image_type);

            $filename = $_SESSION['yjwatsp_user_id'] . "_" . $milliseconds . "." . $file_type[1];
            $location = $full_pathurl . "uploads/whatsapp_images/" . $filename;
 $location_1 = $site_url . "uploads/whatsapp_images/" . $filename;
            $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
            $imageFileType = strtolower($imageFileType);
 //$location = $site_url . "uploads/whatsapp_images/" . $filename;
            /* Valid extensions */
            $valid_extensions = array("png", "jpg", "jpeg");

            $rspns = '';
            /* Check file extension */
            // if (in_array(strtolower($imageFileType), $valid_extensions)) {
            /* Upload file */
            if (move_uploaded_file($_FILES['file_image_header']['tmp_name'], $location)) {
              $rspns = $location;
              site_log_generate("Create Template Page : User : " . $_SESSION['yjwatsp_user_name'] . " whatsapp_images file moved into Folder on " . date("Y-m-d H:i:s"), '../');
            }
          }
             // To Get Api URL
          $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL =>  $template_get_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
      "language" : "' . $language_code . '",
      "category" : "' . $categories . '",
 "code" : "'.$code.'",
      "media_url": "' . $location_1 . '",
      "components" : ' . $whtsap_send . '
    }',
            CURLOPT_HTTPHEADER => array(
              $bearer_token,
              'Content-Type: application/json'
             
            ),
          ));

          $log_1 = '{
			"language" : "' . $language_code . '",
			"category" : "' . $categories . '",
 "code" : "'.$code.'",
			"media_url": "' . $location_1 . '",
			"components" : ' . $whtsap_send . '
	}';
          site_log_generate("Create Template Page : " . $_SESSION['yjwatsp_user_name'] . " executed the log ($log_1) on " . date("Y-m-d H:i:s"), '../');

          $response = curl_exec($curl);
          curl_close($curl);
          site_log_generate("Create Template Page : " . $_SESSION['yjwatsp_user_name'] . " executed the response ($response) on " . date("Y-m-d H:i:s"), '../');
          $obj = json_decode($response);
          if ($obj->response_code == 0) {
            $json = array("status" => 0, "msg" => "$obj->response_msg");
          }
          if ($obj->response_code == 1) {
            $json = array("status" => 1, "msg" => "$obj->response_msg");
          }
          if ($obj->response_code == 2) {
            $json = array("status" => 2, "msg" => "Invalid User, Kindly try with valid User!!");
          }

          break;
        case 'document':
          if (isset($_FILES['file_image_header']['name'])) {

            $image_size = $_FILES['file_image_header']['size'];
            $image_type = $_FILES['file_image_header']['type'];
            $file_type = explode("/", $image_type);

            $filename = $_SESSION['yjwatsp_user_id'] . "_" . $milliseconds ."." . $file_type[1];
           $location = $full_pathurl . "uploads/whatsapp_docs/" . $filename;
            $location_1 = $site_url . "uploads/whatsapp_docs/" . $filename;
            $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
            $imageFileType = strtolower($imageFileType);
//$location = $site_url . "uploads/whatsapp_docs/" . $filename;
            /* Valid extensions */
            $valid_extensions = array("pdf");

            $rspns = '';
            /* Check file extension */
            if (in_array(strtolower($imageFileType), $valid_extensions)) {
              /* Upload file */
              if (move_uploaded_file($_FILES['file_image_header']['tmp_name'], $location)) {
                $rspns = $location;
                site_log_generate("Create Template Page : User : " . $_SESSION['yjwatsp_user_name'] . " whatsapp_docs file moved into Folder on " . date("Y-m-d H:i:s"), '../');
              }
            }
          }
   // To Get Api URL
          $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => $template_get_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
"language" : "' . $language_code . '",
"category" : "' . $categories . '",
"code" : "'.$code.'",
"media_url": "' . $location_1 . '",
"components" : ' . $whtsap_send . '
}',
            CURLOPT_HTTPHEADER => array(
              $bearer_token,
              'Content-Type: application/json'            
            ),
          ));

          $log_2 = '{
			"language" : "' . $language_code . '",
			"category" : "' . $categories . '",
 "code" : "'.$code.'",
			"media_url": "' . $location_1 . '",
			"components" : ' . $whtsap_send . '
	}';
          $response = curl_exec($curl);
          site_log_generate("Create Template Page : " . $_SESSION['yjwatsp_user_name'] . " executed the log ($log_2) on " . date("Y-m-d H:i:s"), '../');

          curl_close($curl);
          site_log_generate("Create Template Page : " . $_SESSION['yjwatsp_user_name'] . " executed the response ($response) on " . date("Y-m-d H:i:s"), '../');
          $obj = json_decode($response);
          if ($obj->response_code == 0) {
            $json = array("status" => 0, "msg" => "$obj->response_msg");
          }
          if ($obj->response_code == 1) {
            $json = array("status" => 1, "msg" => "$obj->response_msg");
          }
          if ($obj->response_code == 2) {
            $json = array("status" => 2, "msg" => "Invalid User, Kindly try with valid User!!");
          }
          break;
        case 'video':
          if (isset($_FILES['file_image_header']['name'])) {

	$image_size = $_FILES['file_image_header']['size'];
											$image_type = $_FILES['file_image_header']['type'];
											$file_type = explode("/", $image_type);
            $filename = $_SESSION['yjwatsp_user_id'] . "_" . $milliseconds ."." .$file_type[1];

            /* Location */
           $location = $full_pathurl . "uploads/whatsapp_videos/" . $filename;
 $location_1 = $site_url . "uploads/whatsapp_videos/" . $filename;

            $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
            $imageFileType = strtolower($imageFileType);
            $image_size = $_FILES['file_image_header']['size'];
            $image_type = $_FILES['file_image_header']['type'];
//$location = $site_url . "uploads/whatsapp_videos/" . $filename;
            /* Valid extensions */
            $valid_extensions = array("mp4");

            $rspns = '';
            /* Check file extension */
            if (in_array(strtolower($imageFileType), $valid_extensions)) {
              /* Upload file */
              if (move_uploaded_file($_FILES['file_image_header']['tmp_name'], $location)) {
                $rspns = $location;
                site_log_generate("Create Template Page : User : " . $_SESSION['yjwatsp_user_name'] . " whatsapp_videos file moved into Folder on " . date("Y-m-d H:i:s"), '../');
              }
            }
          }
             // To Get Api URL
          $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => $template_get_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
"language" : "' . $language_code . '",
"category" : "' . $categories . '",
"code" : "'.$code.'",
"media_url": "' . $location_1 . '",
"components" : ' . $whtsap_send . '
}',
            CURLOPT_HTTPHEADER => array(
              $bearer_token,
              'Content-Type: application/json'
             
            ),
          ));

          $log_3 = '{
			"language" : "' . $language_code . '",
			"category" : "' . $categories . '",
 "code" : "'.$code.'",
			"media_url": "' . $location_1 . '",
			"components" : ' . $whtsap_send . '
	}';
          site_log_generate("Create Template Page : " . $_SESSION['yjwatsp_user_name'] . " executed the log ($log_3) on " . date("Y-m-d H:i:s"), '../');
          $response = curl_exec($curl);

          curl_close($curl);
          site_log_generate("Create Template Page : " . $_SESSION['yjwatsp_user_name'] . " executed the response ($response) on " . date("Y-m-d H:i:s"), '../');
          $obj = json_decode($response);
          if ($obj->response_code == 0) {
            $json = array("status" => 0, "msg" => "$obj->response_msg");
          }
          if ($obj->response_code == 1) {
            $json = array("status" => 1, "msg" => "$obj->response_msg");
          }
          if ($obj->response_code == 2) {
            $json = array("status" => 2, "msg" => "Invalid User, Kindly try with valid User!!");
          }

          break;
        default:
          # code...
          break;
      }
    }
    else {
   // To Get Api URL
      $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $template_get_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
"language" : "' . $language_code . '",
"code" : "'.$code.'",
"category" : "' . $categories . '",
"components" : ' . $whtsap_send . '
}',
        CURLOPT_HTTPHEADER => array(
          $bearer_token,
          'Content-Type: application/json'
         
        ),
      ));

      $log_4 = '{
"language" : "' . $language_code . '",
 "code" : "'.$code.'",
"category" : "' . $categories . '",
"components" : ' . $whtsap_send . '
}';
      site_log_generate("Create Template Page : " . $_SESSION['yjwatsp_user_name'] . " executed the log ($log_4) on " . date("Y-m-d H:i:s"), '../');
      $response = curl_exec($curl);

      curl_close($curl);
      site_log_generate("Create Template Page : " . $_SESSION['yjwatsp_user_name'] . " executed the response ($response) on " . date("Y-m-d H:i:s"), '../');
      $obj = json_decode($response);
      if ($obj->response_code == 0) {
        $json = array("status" => 0, "msg" => "$obj->response_msg");
      }
      if ($obj->response_code == 1) {
        $json = array("status" => 1, "msg" => "$obj->response_msg");
      }
      if ($obj->response_code == 2) {
        $json = array("status" => 2, "msg" => "Invalid User, Kindly try with valid User!!");
      }
    }

}
// Create Template create_template - End

//chatbot flow - start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "chat_bot") {

	$start_name = htmlspecialchars(strip_tags(isset($_REQUEST['start_name']) ? $conn->real_escape_string($_REQUEST['start_name']) : ""));
  $restart_name = htmlspecialchars(strip_tags(isset($_REQUEST['restart_name']) ? $conn->real_escape_string($_REQUEST['restart_name']) : ""));
  $invalid_name = htmlspecialchars(strip_tags(isset($_REQUEST['invalid_name']) ? $conn->real_escape_string($_REQUEST['invalid_name']) : ""));
  
  $button_txt_st = htmlspecialchars(strip_tags(isset($_REQUEST['button_txt_st']) ? $conn->real_escape_string($_REQUEST['button_txt_st']) : ""));
  $txtarea_msg_st = htmlspecialchars(strip_tags(isset($_REQUEST['txtarea_msg']) ? $conn->real_escape_string($_REQUEST['txtarea_msg']) : ""));
  $txtarea_reply_st = htmlspecialchars(strip_tags(isset($_REQUEST['txtarea_reply']) ? $conn->real_escape_string($_REQUEST['txtarea_reply']) : ""));
  $txtarea_list_st = htmlspecialchars(strip_tags(isset($_REQUEST['txtarea_list_st']) ? $conn->real_escape_string($_REQUEST['txtarea_list_st']) : ""));

  $reply_array = array();
  $reply_array_add = array();
  // $list_array = array();

  foreach ($bot_array as $bot_array1) {
    $bot_array_list .= '"' . $bot_array1 . '"';
  }

$chatbot_array = explode(',', $bot_array);

// print_r($myArray[0]);

  foreach ($textarea_reply as $text_area_rpy) {
    $text_area_reply .= '"' . $text_area_rpy . '"';

  }
  foreach ($textarea_list as $text_area_lt) {
    $text_area_list .= '"' . $text_area_lt . '"';

  }

  foreach ($list_button as $list_btn) {
    $list_btn_txt .= '"' . $list_btn . '"';

  }

  foreach ($button_txt as $button_text) {
    $button_text_list .= '"' . $button_text . '"';

  }

  $reply_trim_adding = '';

  
 

  for($i= 0;$i < count($reply_button_st);$i++){
 $reply .=   '{
      "type": "reply",
      "reply": {
          "id": "'.$reply_button_st[$i].'",
          "title": "'.$reply_button_st[$i].'"
      }
     },';
    // echo $reply;
  }
 
  $reply_trim = trim($reply,",");
 
  if($list_button_st){
  for($i= 0;$i < count($list_button_st);$i++){
    $list .=   '{
      "id": "'.$list_button_st[$i].'",
      "title": "'.$list_button_st[$i].'"
  },';
   
     
     }
    }
    $list_trim = trim($list,",");
    

  $textarea = str_replace("'", "\'", $textarea);
  $textarea = str_replace('"', '\"', $textarea);

 $chat_bot = array();
 

	$chat_bot_send = '';
	$chat_bot_send .= '[
		';

if($txtarea_msg_st|| $txtarea_reply_st||$txtarea_list_st ){

  switch ($txtarea_msg_st|| $txtarea_reply_st||$txtarea_list_st) {
    case $txtarea_msg_st:
      $chat_bot_send .= ' { 
        "id": "1",
        "parent": [
               "0"
       ],
       "pattern": "/'.	$start_name. '/",
       "type": ["text"],
       "message": [
         {
                 "type": "text",
                 "text": {
                         "body": "'.$txtarea_msg_st.'"
                 }
         }
        ],
       "restart": [
        {
            "type": "text",
            "text": {
                "body": "'.$restart_name.'"
            }
        }
    ],
    "invalid": [
        {
            "type": "text",
            "text": {
                "body": "'.$invalid_name.'"
            }
        }
    ]
    },';
       break;
       case $txtarea_reply_st:
        $chat_bot_send .= ' { 
          "id": "1",
          "parent": [
                 "0"
         ],
         "pattern": "/'.$start_name. '/",
         "type":["text"],
         "message": [
          {
              "type": "interactive",
              "interactive": {
                  "type": "button",
                  "body": {
                      "text": "'.$txtarea_reply_st.'"
                  },
                  "action": {
                      "buttons":['.$reply_trim.'] 
          }
        }
      }
       ],
      "restart": [
        {
            "type": "text",
            "text": {
                "body": "'.$restart_name.'"
            }
        }
    ],
    "invalid": [
        {
            "type": "text",
            "text": {
                "body": "'.$invalid_name.'"
            }
        }
    ]
},';
         break;
         case $txtarea_list_st:
          $chat_bot_send .= ' { 
            "id": "1",
            "parent": [
                   "0"
           ],
           "pattern": "/'.$start_name. '/",
           "type": ["text"],
           "message": [
            {
                "type": "interactive",
                "interactive": {
                    "type": "list",
                    "body": {
                        "text":  "'.$txtarea_list_st.'"
                    },
                    "action": {
                        "button":  "'.$button_txt_st.'",
                        "sections": [
                            {
                                "rows": ['.$list_trim.']
                            }
                        ]
                    }
                }
            }
        ],
        "restart": [
          {
              "type": "text",
              "text": {
                  "body": "'.$restart_name.'"
              }
          }
      ],
      "invalid": [
          {
              "type": "text",
              "text": {
                  "body": "'.$invalid_name.'"
              }
          }
      ]
  }, ';
           break;

}
}

if($chatbot_array != ''){
for($i= 0;$i < count($chatbot_array);$i++){
  switch ($chatbot_array != '') {
    case ($chatbot_array[$i] == 'Replybutton_1'|| $chatbot_array[$i] == 'Replybutton_2' || $chatbot_array[$i] == 'Replybutton_3'):
      $myArray = explode('_', $chatbot_array[$i]);
      
// print_r($myArray);
$reply_trim_adding ='';
$reply ='';

      $chat_bot_send .= ' { 
        "id": "'.($i+2).'",
        "parent": [
          "'.($i+1).'"
       ],   
       "pattern": "/'.$_POST[''."reply_pattern_".($i+1).'']. '/",
       "type":["text"],
       "message": [
        {
            "type": "interactive",
            "interactive": {
                "type": "button",
                "body": { 
                    "text": "'.$_POST[''."textarea_reply".($i+1).''].'"
                },
                "action": {
                  ';
                  
                  for($j = 0;$j < $myArray[1]; $j++ ){
                  
                  $reply .= '{
             "type": "reply",
             "reply": {
                 "id": "'.$_POST[''.($i+1)."_reply_button_".($j+1).''].'",
                 "title":"'.$_POST[''.($i+1)."_reply_button_".($j+1).''].'"
             }
            },';
          };
          $reply_trim_adding = trim($reply,",");
            $chat_bot_send .=  '"buttons":['.$reply_trim_adding.'
            ] 
        }
      }
    }
     ]
  },';

break;
      case ($chatbot_array[$i] == 'List_1' || $chatbot_array[$i] == 'List_2'|| $chatbot_array[$i] == 'List_3'|| $chatbot_array[$i] == 'List_4'|| $chatbot_array[$i] == 'List_5'||$chatbot_array[$i] =='List_6'||$chatbot_array[$i] ==  'List_7'||$chatbot_array[$i] == 'List_8' || $chatbot_array[$i] == 'List_9'||$chatbot_array[$i] == 'List_10' ):

        $myArray = explode('_', $chatbot_array[$i]);
        // print_r($myArray);
        $reply_trim_adding ='';
$reply ='';
        $chat_bot_send .= ' { 
          "id": "'.($i+2).'",
          "parent": [
            "'.($i+1).'"
         ],
         "pattern": "/'.$_POST[''."list_pattern_".($i+1).'']. '/",
         "type": ["text"],
         "message": [
          {
              "type": "interactive",
              "interactive": {
                  "type": "list",
                  "body": {
                      "text":  "'.$_POST[''."textarea_list_".($i+1).''].'"
                  },
                  "action": {
                    "button": "'.$_POST[''."button_txt_".($i+1).''].'",
                    '; 
                    for($j = 0;$j < $myArray[1]; $j++ ){

                    $reply .= '{
               "type": "reply",
               "reply": {
                   "id": "'.$_POST[''.($i+1)."_list_button_".($j+1).''].'",
                   "title":"'.$_POST[''.($i+1)."_list_button_".($j+1).''].'"
               }
              },';
            };
            $reply_trim_adding = trim($reply,",");
              $chat_bot_send .=  '"sections":[{
                "rows": [
                '.$reply_trim_adding.'
                ]
              }] 
          }
          }
              
          }
      ]
        },';
        break;
        case ($chatbot_array[$i] == 'Message'):
          
          $chat_bot_send .= ' { 
            "id": "'.($i+2).'",
            "parent": [
                   "'.($i+1).'"
           ],
           "pattern": "/'.$_POST[''."message_name_".($i+1).'']. '/",
           "type": ["text"],
           "message": [
             {
                     "type": "text",
                     "text": {
                             "body": "'.$_POST[''."textarea_msg_".($i+1).''].'"
                     }
             }
            ]
            },';
          // }
            break;
}
}

}
$chat_bot_send_trim = trim($chat_bot_send,",");

$chat_bot_send_trim .= ']';

$sql = "INSERT INTO master_flow (flow_id, whatsapp_config_id, flow_msg,flow_json,flow_status,flow_ent_date)
VALUES ('John', 'Doe', 'john@example.com')";

// INSERT INTO master_flow (CustomerName, ContactName, Address, City, PostalCode, Country)
// VALUES ('Cardinal', 'Tom B. Erichsen', 'Skagen 21', 'Stavanger', '4006', 'Norway');

echo $chat_bot_send_trim;
  $json = array("status" => 1, "msg" => "success");
}
//chatbot flow - End


// Finally Close all Opened Mysql DB Connection
$conn->close();

// Output header with JSON Response
header('Content-type: application/json');
echo json_encode($json);
