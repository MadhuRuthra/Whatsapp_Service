<?php
session_start();
error_reporting(E_ALL);
// Include configuration.php
include_once('../api/configuration.php');
extract($_REQUEST);

$current_date = date("Y-m-d H:i:s");
$bearer_token_header = 'Authorization: ' . $_SESSION['yjwatsp_bearer_token'] . '';
// Messenger Response Page view_response - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $tmpl_call_function == "view_response") {
  site_log_generate("Messenger Response Page : User : " . $_SESSION['yjwatsp_user_name'] . " access this page on " . date("Y-m-d H:i:s"), '../');
  $message_id = htmlspecialchars(strip_tags(isset($_REQUEST['message_id']) ? $conn->real_escape_string($_REQUEST['message_id']) : ""));
  $message_from = htmlspecialchars(strip_tags(isset($_REQUEST['message_from']) ? $conn->real_escape_string($_REQUEST['message_from']) : ""));
  $message_to = htmlspecialchars(strip_tags(isset($_REQUEST['message_to']) ? $conn->real_escape_string($_REQUEST['message_to']) : ""));
  // $master_no = htmlspecialchars(strip_tags(isset($_REQUEST['master_number']) ? $conn->real_escape_string($_REQUEST['master_number']) : ""));
  ?>
  <div class="inner_div" id="chathist">
    <?php

    $replace_txt = '{
    "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '",
    "message_from" : "' . $message_from . '",
    "message_to" : "' . $message_to . '"
  }';
    // To Get Api URL
    $bearer_token_header = 'Authorization: ' . $_SESSION['yjwatsp_bearer_token'] . '';
    $curl = curl_init();
    curl_setopt_array(
      $curl,
      array(
        CURLOPT_URL => $api_url . '/list/messenger_view_response',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $replace_txt,
        CURLOPT_HTTPHEADER => array(
          $bearer_token_header,
          'Content-Type: application/json'
        ),
      )
    );
    site_log_generate("Messenger Response Page : " . $_SESSION['yjwatsp_user_name'] . " Execute the service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
    $response = curl_exec($curl);
    curl_close($curl);
    // echo $response;
    $header = json_decode($response, false);
    site_log_generate("Messenger Response Page : " . $_SESSION['yjwatsp_user_name'] . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');
    $i = 0;
    $first_message_from = '-';
    $last_msg_time = '';
    $first_occurrence = 0;
    $message_id = '';
    if ($header->num_of_rows > 0) {
      for ($indicator = 0; $indicator < $header->num_of_rows; $indicator++) {
        if($header->report[$indicator]->bearer_token != '') {
          $sndrid = $header->report[$indicator]->mobile_no;
        }

        if ($message_to == $header->report[$indicator]->mobile_no) {
          $sendfr = $message_to;
          $sendto = $message_from;
          // echo "if".$message_to.$message_from;
        } elseif ($message_from == $header->report[$indicator]->mobile_no) {
          // echo "else".$message_from.$message_to;
          $sendfr = $message_from;
          $sendto = $message_to;
        }
        $message_id .= $header->report[$indicator]->message_id . ", ";
        $entry_date = date('d-m-Y h:i:s A', strtotime($header->report[$indicator]->message_rec_date));
        $display_data = '';
        switch ($header->report[$indicator]->message_type) {
          case 'TEXT':
            $display_data = "Text : " . base64_decode($header->report[$indicator]->msg_text);
            break;
          case 'BUTTON':
            $display_data = "Reply Button : " . $header->report[$indicator]->msg_reply_button;
            break;
          case 'REACTION':
            $display_data = "Reaction : &#x" . $header->report[$indicator]->msg_reaction;
            break;
          case 'STICKER':
            $expl1 = explode("/", $header->report[$indicator]->msg_media_type);
            $display_data = "Sticker : <img src='uploads/response_media/" . $header->report[$indicator]->msg_media . "' style='max-width:200px !important; height: auto !important;'>";
            break;
          case 'AUDIO':
            $expl2 = explode(";", $header->report[$indicator]->msg_media_type);
            $display_data = ucwords($header->report[$indicator]->message_type) . ' : <audio controls>
            <source src="uploads/response_media/' . $header->report[$indicator]->msg_media . '" type="' . $expl2[0] . '">
          </audio>';
            break;
          case 'VIDEO':
            $display_data = ucwords($header->report[$indicator]->message_type) . ' : <video width="320" height="240" controls>
            <source src="uploads/response_media/' . $header->report[$indicator]->msg_media . '" type="' . $header->report[$indicator]->msg_media_type . '">
          </video>' . base64_decode($header->report[$indicator]->msg_media_caption);
            break;
          case 'IMAGE':
            $display_data = ucwords($header->report[$indicator]->message_type) . " : <img src='uploads/response_media/" . $header->report[$indicator]->msg_media . "' style='max-width:200px !important; height: auto !important;'>" . base64_decode($header->report[$indicator]->msg_media_caption);
            break;
          case 'DOCUMENT':
            $display_data = ucwords($header->report[$indicator]->message_type) . " : <a href='uploads/response_media/" . $header->report[$indicator]->msg_media . "'>Download</a>" . base64_decode($header->report[$indicator]->msg_media_caption);
            break;
          default:
            $display_data = "Text : " . base64_decode($header->report[$indicator]->msg_text);
            break;
        }
        if ($i == 0) {
          $i++;
          $first_message_from = $row;
        }
        $display_user = '';
        //echo $header->report[$indicator]->mobile_no ;
       //echo  $header->report[$indicator]->message_to;
        if ($header->report[$indicator]->mobile_no == $header->report[$indicator]->message_to) {
          $display_user = $header->report[$indicator]->message_from;
          if ($first_occurrence == 0) {
            $first_occurrence++;
            $last_msg_time = $entry_date;
          }
          ?>
          <div id="triangle" class="chat-item chat-right mb-2">
            <div id="message" class="chat-details">
              <span style="float:left;" class="chat-text">
                <?php echo $display_data; ?>
              </span> <br />
              <div>
                <span style="color:#606060;float:left;font-size:10px;clear:both;" class="chat-time">
                  <b>
                    <?php echo $display_user; ?>
                  </b>,
                  <?php echo $entry_date; ?>
                </span>
              </div>
            </div>
          </div>
          <?php
        } else {
          $display_user = $header->report[$indicator]->message_from;
          ?>
          <div id="triangle1" class="chat-item chat-left mb-2">
            <div id="message1" class="chat-details">
              <span style="color:#000;float:right;" class="chat-text1">
                <?php echo $display_data; ?>
              </span> <br />
              <div>
                <span style="color:#606060;float:right;font-size:10px;clear:both;">
                  <b>
                    <?php echo $display_user; ?>
                  </b>,
                  <?php echo $entry_date; ?>
                </span>
              </div>
            </div>
          </div>
          <?php
        }
      }
      $message_id = rtrim($message_id, ", ");
      $replace_txt = '{
        "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '",
        "message_id" : "' . $message_id . '"
      }';
      // To Get Api URL
      $bearer_token_header = 'Authorization: ' . $_SESSION['yjwatsp_bearer_token'] . '';
      $curl = curl_init();
      curl_setopt_array(
        $curl,
        array(
          CURLOPT_URL => $api_url . '/list/messenger_response_update',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $replace_txt,
          CURLOPT_HTTPHEADER => array(
            $bearer_token_header,
            'Content-Type: application/json'
          ),
        )
      );
      site_log_generate("Messenger Response Page : " . $_SESSION['yjwatsp_user_name'] . " Execute the service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
      $response = curl_exec($curl);
      curl_close($curl);
      $header = json_decode($response, false);
      site_log_generate("Messenger Response Page : " . $_SESSION['yjwatsp_user_name'] . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');
    }
    ?>
  </div>
  <div id="app_footer" style='clear: both; border-top: 1px dashed #e4e6fc; padding: 10px;'>
    <div>
      <form id="frm_reply" name="frm_reply" action="#" method="POST">
        <div>
          <div class="row align-items-center justify-content-center">
            <?
            $time1 = strtotime($last_msg_time);
            $time2 = strtotime(date("Y-m-d h:i:s A"));
            $difference = round(abs($time2 - $time1) / 3600, 2);

            if ($difference <= 23) { ?>
              <div style="width:80%; float: left">
                <textarea id="txt_reply" name="txt_reply" autofocus required tabindex="1"
                  style="height: 75px !important; width: 100%;" placeholder="Enter your reply"
                  class="form-control form-control-primary required"></textarea>
              </div>
              <div class="text-center" style="width:20%; float: left;">
                <span class="error_display" id='id_error_display'></span>
                <input type="hidden" class="form-control" name='message_id' id='message_id' value='<?= $message_id ?>' />
                <input type="hidden" class="form-control" name='message_from' id='message_from' value='<?= $sendfr ?>' />
                <input type="hidden" class="form-control" name='message_to' id='message_to' value='<?= $sendto ?>' />
                <input type="hidden" class="form-control" name='sender_id' id='sender_id' value='<?= $sndrid ?>' />
                <input type="hidden" class="form-control" name='admin_number' id='admin_number' value='<?= $admin_number ?>' />

                <input type="hidden" class="form-control" name='tmpl_call_function' id='tmpl_call_function'
                  value='messenger_reply' />
                <input type="submit" name="reply_submit" id="reply_submit" tabindex="2" value="Reply"
                  class="btn btn-success" style="margin-top: 18px;">
              </div>
            <? } else { ?>
              <span class="error_display">Last message received from client before 24 hours. So, We cannot send response
                here!!</span>
            <? } ?>
          </div>
        </div>
      </form>
    </div>
  </div>
  <?
  site_log_generate("Messenger Response Page : User : " . $_SESSION['yjwatsp_user_name'] . " Preview User on " . date("Y-m-d H:i:s"), '../');
}
// Messenger Response Page view_response - End

?>
<!-- script include -->
<script>
  $(document).ready(function () {
    $('#txt_reply').keydown(function () {
      var message = $("txt_reply").val();
      if (event.keyCode == 13) {
        if (message == "") { } else {
          $('#frm_reply').submit();
        }
        $("#txt_reply").val('');
        return false;
      }
    });
  });
</script>
<?

// Finally Close all Opened Mysql DB Connection
$conn->close();

// Output header with HTML Response
header('Content-type: text/html');
echo $result_value;

