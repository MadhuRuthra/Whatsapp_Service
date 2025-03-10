<?php
session_start();//start session
error_reporting(E_ALL);// The error reporting function
// Include configuration.php
include_once('../api/configuration.php');
extract($_REQUEST);
$bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].''; 
$current_date = date("Y-m-d H:i:s");


//Create Template Preview Page - Start

if ($_SERVER['REQUEST_METHOD'] == "POST" and $preview_functions == "preview_template" ) {

 foreach ($button_url_text as $btn_txt_url) {
  $btn_txt_url_name .= $btn_txt_url;

}
foreach ($button_text as $btn_txt) {
  $btn_txt_name .= $btn_txt;

}
foreach ($button_quickreply_text as $txt_button_qr_txt) {
  $txt_button_qr_text1 .= '"' . $txt_button_qr_txt . '"' . ',';
}

 $button_quickreply_text = $_POST['button_quickreply_text'];

 foreach ($reply_arr as $reply_arr1) {
  $reply_array_content = $reply_arr1;
}

foreach ($button_txt_phone_no as $btn_txt_phn) {
  $btn_txt_phn_no .= $btn_txt_phn;

}
foreach ($website_url as $web_url) {
  $web_url_link .= $web_url;

}


  $stateData = '';
  $stateData_box = '';
  $hdr_type = '';
  if($_SERVER['REQUEST_METHOD'] == "POST"){

if ($header) {
  
        switch ($header) {
          case ('TEXT'):
            $hdr_type .= "<input type='hidden' name='hid_txt_header_variable' id='hid_txt_header_variable' value='". $txt_header_name ."'>";

            $stateData_1 = '';
            $stateData_1 = $txt_header_name;
            $stateData_2 = $stateData_1;

            $matches = null;
            $prmt = preg_match_all("/{{[0-9]+}}/", $txt_header_name, $matches);
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


          case ($media_category == 'document'):
            $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Header : </div> <div style='margin-left:60px;'><i class='fa fa-file-text' style='font-size: 18px'></i> <span> DOCUMENT</span>
            </div>
                </div>
              </div>
              </div></div>";
            break;


          case ($media_category == 'image' ):
            $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Header : </div><div style='margin-left:60px;'><i class='fa fa-image' style='font-size: 18px'></i> <span> IMAGE</span></div>
                  <span class='input-group-addon'><i class='icofont icofont-ui-messaging'></i></span>
                </div>
              </div>
              </div></div>";
            break;


          case $media_category == 'video':
          
            $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Header :</div> <div style='margin-left:60px;'><i class='fa fa-play-circle' style='font-size: 18px'></i> <span> VIDEO</span>
            </div>
                  <span class='input-group-addon'><i class='icofont icofont-ui-messaging'></i></span>
                </div>
              </div>
              </div></div>";
            break;
        }
      }

      if ($textarea) {
        $hdr_type .= "<input type='hidden' name='hid_txt_body_variable' id='hid_txt_body_variable' value='" . $textarea . "'>";

        $stateData_1 = '';
        $stateData_1 = $textarea;
        $stateData_2 = $stateData_1;

        $matches = null;
        $prmt = preg_match_all("/{{[0-9]+}}/", $textarea, $matches);
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

      if ($select_action5 =  'VISIT_URL' || ($select_action1 = 'PHONE_NUMBER' || $select_action3 = 'PHONE_NUMBER') && $select_action == 'QUICK_REPLY' ) {
        $stateData_2 = '';
        if ($select_action5 =  'VISIT_URL' && $web_url_link ) {
          $stateData_2 .= "<a href='" . $web_url_link . "' target='_blank'>" . $btn_txt_url_name . "</a>";
          $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Buttons URL : </div><div style='float:left'>" . $web_url_link . " - " . $stateData_2 . "</div></div>";
        }

        if (($select_action1 = 'PHONE_NUMBER' || $select_action3 = 'PHONE_NUMBER') && $btn_txt_phn_no) {
          $stateData_2 .= $country_code . " - " . $btn_txt_phn_no;
          $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Buttons Phone No. : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
        }

        for ($kk = 0; $kk < count($button_quickreply_text); $kk++) {
          if ($select_action == 'QUICK_REPLY') {
            $stateData_2 = $button_quickreply_text[$kk];
            $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Buttons Quick Reply : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
          }
        }
      }
      if ($txt_footer_name) {
        $hdr_type .= "<input type='hidden' name='hid_txt_footer_variable' id='hid_txt_footer_variable' value='" . $txt_footer_name . "'>";

        $stateData_2 = '';
        $stateData_2 = $txt_footer_name;

        if ($stateData_2 != '') {
          $stateData .= "<div style='float:left; clear:both; line-height: 36px;'><div style='float:left; line-height: 36px;'>Footer : </div><div style='float:left'>" . $stateData_2 . "</div></div>";
        }
      }
      
      // echo $stateData . $hdr_type;
        site_log_generate("Template Create Preview Page : User : " . $_SESSION['yjwatsp_user_name'] . " Get Template available on " . date("Y-m-d H:i:s"), '../');
        site_log_generate("Template Create Preview Page : User : " . $_SESSION['yjwatsp_user_name'] .$stateData . $hdr_type . date("Y-m-d H:i:s"), '../');
        
        $json = array("status" => 1, "msg" => $stateData . $hdr_type);
    }
    else {   
    site_log_generate("Template Create Preview Page : User : " . $_SESSION['yjwatsp_user_name'] . " Get Template not available on " . date("Y-m-d H:i:s"), '../');
    $json = array("status" => 0, "msg" => '-');
  }
  
}
  //Create Template Preview Page - END
?>
  




<?

// Finally Close all Opened Mysql DB Connection
$conn->close();

// Output header with HTML Response
header('Content-type: application/json');
echo json_encode($json);
