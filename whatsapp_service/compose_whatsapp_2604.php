<?php
session_start();
error_reporting(0);
include_once('api/configuration.php');
extract($_REQUEST);

if ($_SESSION['yjwatsp_user_id'] == "") { ?>
  <script>window.location = "index";</script>
  <?php exit();
}

$site_page_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
site_log_generate("Compose Whatsapp Page : User : " . $_SESSION['yjwatsp_user_name'] . " access the page on " . date("Y-m-d H:i:s"));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Compose Whatsapp :: <?= $site_title ?></title>
  <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">

  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <style>
    textarea {
      resize: none;
    }

    .btn-warning,
    .btn-warning.disabled {
      width: 100% !important;
    }

    .theme-loader {
      display: block;
      position: absolute;
      top: 0;
      left: 0;
      z-index: 100;
      width: 100%;
      height: 100%;
      background-color: rgba(192, 192, 192, 0.5);
      background-image: url("assets/img/loader.gif");
      background-repeat: no-repeat;
      background-position: center;
    }
  </style>
</head>

<body>
  <div class="theme-loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>

      <? include("libraries/site_header.php"); ?>

      <? include("libraries/site_menu.php"); ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Compose Whatsapp</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
              <div class="breadcrumb-item active"><a href="whatsapp_list">Whatsapp List</a></div>
              <div class="breadcrumb-item">Compose Whatsapp</div>
            </div>
          </div>

          <div class="section-body">
            <div class="row">

              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <form class="needs-validation" novalidate="" id="frm_compose_whatsapp" name="frm_compose_whatsapp"
                    action="#" method="post" enctype="multipart/form-data">
                    <div class="card-body">

                      <div class="form-group mb-2 row">
                        <label class="col-sm-3 col-form-label">Whatsapp Sender ID <label
                            style="color:#FF0000">*</label></label>
                        <div class="col-sm-7">
                          <?
                          $replace_txt = '{
                              "api_key" : "' . $_SESSION['yjwatsp_api_key'] . '",
                              "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
                            }';
                          $curl = curl_init();
                          curl_setopt_array($curl, array(
                            CURLOPT_URL => $api_url . '/list/whatsapp_senderid',
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
                          )
                          );
                          site_log_generate("Compose Whatsapp Page : " . $_SESSION['yjwatsp_user_name'] . " Execute the service  [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
                          $response = curl_exec($curl);
                          curl_close($curl);

                          $state1 = json_decode($response, false);
                          site_log_generate("Compose Whatsapp Page : " . $_SESSION['yjwatsp_user_name'] . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');
                          ?>
                          <table style="width: 100%;">
                            <?
                            if ($state1->num_of_rows > 0) {
                              for ($indicator = 0; $indicator < $state1->num_of_rows; $indicator++) {

                                if ($indicator % 2 == 0) { ?>
                                  <tr>
                                  <? } ?>
                                  <td>
                                    <input type="checkbox" checked class="cls_checkbox" id="txt_whatsapp_mobno"
                                      name="txt_whatsapp_mobno[]" tabindex="1" autofocus
                                      value="<?= $state1->report[$indicator]->store_id . "~~" . $state1->report[$indicator]->whatspp_config_id . "~~" . $state1->report[$indicator]->phonecode . $state1->report[$indicator]->mobile_no ?>">
                                    <label class="form-label">
                                      <?= $state1->report[$indicator]->phonecode . "" . $state1->report[$indicator]->mobile_no ?>
                                    </label>
                                  </td>
                                  <?
                                  if ($indicator % 2 == 1) { ?>
                                  </tr>
                                <? }
                              }
                            }
                            ?>
                          </table>
                        </div>
                        <div class="col-sm-2">
                        </div>
                      </div>

                      <div class="form-group mb-2 row" style="display: none">
                        <label class="col-sm-3 col-form-label">Campaign Name <label style="color:#FF0000">*</label>
                          <span data-toggle="tooltip"
                            data-original-title="Campaign Name allowed maximum 30 Characters. Unique values only allowed">[?]</span></label>
                        <div class="col-sm-7">
                          <input type="text" name="txt_campaign_name" id='txt_campaign_name' class="form-control"
                            value="Campaign Name" required="" maxlength="30" onblur="func_validate_campaign_name()"
                            placeholder="Enter Campaign Name" data-toggle="tooltip" data-placement="top" title=""
                            data-original-title="Enter Campaign Name">
                        </div>
                        <div class="col-sm-2">
                        </div>
                      </div>

                      <div class="form-group mb-2 row">
                        <label class="col-sm-3 col-form-label">Enter Mobile Numbers : <label
                            style="color:#FF0000">*</label> <span data-toggle="tooltip"
                            data-original-title="Mobile numbers allowed  with Country Code and without + symbol. Maximum 100 Mobile numbers only allowed. Upload Mobile numbers using Excel, CSV, TXT Files">[?]</span>
                          <label style="color:#FF0000">(With Country Code and without + symbol. New-Line Separated.
                            Maximum 1000 Numbers Allowed)</label></label>
                        <div class="col-sm-7">
                          <textarea id="txt_list_mobno" name="txt_list_mobno" tabindex="2" required=""
                            onblur="call_remove_duplicate_invalid()"
                            placeholder="919234567890,919234567891,919234567892,919234567893"
                            class="form-control form-control-primary required" data-toggle="tooltip"
                            data-placement="top" data-html="true" title=""
                            data-original-title="Enter Mobile Numbers. Each row must contains only one mobile no  with Country Code and without + symbol. For Ex : 919234567890,919234567891,919234567892,919234567893"
                            style="height: 150px !important; width: 100%;"></textarea>
                          <div id='txt_list_mobno_txt' class='text-danger'></div>
                        </div>
                        <div class="col-sm-2">
                          <div class="checkbox-fade fade-in-primary" style="display: none;">
                            <label data-toggle="tooltip" data-placement="top" data-html="true" title=""
                              data-original-title="Click here to Remove the Duplicates">
                              <input type="checkbox" name="chk_remove_duplicates" id="chk_remove_duplicates" checked
                                value="remove_duplicates" tabindex="10" onclick="call_remove_duplicate_invalid()">
                              <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                              <span class="text-inverse" style="color:#FF0000 !important">Remove Duplicates</span>
                            </label>
                          </div>
                          <div class="checkbox-fade fade-in-primary" style="display: none;">
                            <label data-toggle="tooltip" data-placement="top" data-html="true" title=""
                              data-original-title="Click here to remove Invalids Mobile Nos">
                              <input type="checkbox" name="chk_remove_invalids" id="chk_remove_invalids" checked
                                value="remove_invalids" tabindex="10" onclick="call_remove_duplicate_invalid()">
                              <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                              <span class="text-inverse" style="color:#FF0000 !important">Remove Invalids</span>
                            </label>
                          </div>
                          <div class="checkbox-fade fade-in-primary" style="display: none;">
                            <label data-toggle="tooltip" data-placement="top" data-html="true" title=""
                              data-original-title="Click here to remove Stop Status Mobile No's">
                              <input type="checkbox" name="chk_remove_stop_status" id="chk_remove_stop_status" checked
                                value="remove_stop_status" tabindex="10" onclick="call_remove_duplicate_invalid()">
                              <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                              <span class="text-inverse" style="color:#FF0000 !important">Remove Stop Status Mobile
                                No's</span>
                            </label>
                          </div>

                          <div class="checkbox-fade fade-in-primary" id='id_mobupload'>
                            <input type="file" class="form-control" name="upload_contact" id='upload_contact'
                              tabindex="3" <? if ($display_action == 'Add') { ?>required="" <? } ?>
                              accept="text/plain,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"
                              data-toggle="tooltip" data-placement="top" data-html="true" title=""
                              data-original-title="Upload the Mobile Numbers via Excel, CSV, Text Files"> <label
                              style="color:#FF0000">[Upload the Mobile Numbers via Excel, CSV, Text Files]</label>
                          </div>
                        </div>
                      </div>

                      <div class="form-group mb-3 row">
                        <label class="col-sm-3 col-form-label" style="float: left"></label>
                        <div class="col-sm-7" style="float: left">
                          <a href="#!" tabindex="4" style="float: left; width: 180px;" class="btn btn-info" onclick="call_generate_contacts()">Generate Contacts</a>
                          <div id='id_generate_csv' style="float: left; padding-left: 10px;"></div>
                          <a href="https://contacts.google.com/u/1/" style="display: none;width: 180px;float: right;" id='id_popup_view' target="popup" onclick="window.open('https://contacts.google.com/u/1/','popup','width=600,height=600'); return false;" tabindex="4" class="btn btn-info">Upload Contacts</a><br>
                          
                        </div>
                        <div class="col-sm-2">
                          <input type="hidden" name="hid_submit_alow" id="hid_submit_alow" value="0" >
                        </div>
                      </div>

                      <div class="form-group mb-3 row">
                        <label class="col-sm-3 col-form-label" style="float: left">Upload Media Files <label
                            style="color:#FF0000">*</label> <span data-toggle="tooltip"
                            data-original-title="Upload Any Media below or equal to 5 MB Size - Upload Only the Audio MP4, Video MP4, JPG, PNG, PDF file. Media Caption [Maximum - 150 Characters allowed]">[?]</span></label>
                        <div class="col-sm-7" style="float: left">
                          <input type="file" class="form-control mb-1" name="txt_media" id="txt_media" tabindex="4"
                            accept="image/jpeg,image/jpg,image/gif,image/png,application/pdf,video/h263,video/m4v,video/mp4,video/mpeg,video/mpeg4,video/webm"
                            data-toggle="tooltip" onblur="validate_filesize(this)" data-placement="top" data-html="true"
                            title=""
                            data-original-title="Upload Any Media below or equal to 5 MB Size - Upload Only the Audio MP4, Video MP4, JPG, PNG, PDF file">
                          <input type="text" name="txt_caption" id='txt_caption' tabindex="4" class="form-control"
                            value="" maxlength="150"
                            placeholder="Enter Media Caption [Maximum - 150 Characters allowed]" data-toggle="tooltip"
                            data-placement="top" title="" data-original-title="Enter Media Caption">
                        </div>
                        <div class="col-sm-2">
                        </div>
                      </div>

                      <div class="form-group mb-2 row">
                        <label class="col-sm-3 col-form-label">Enter Message Content <label
                            style="color:#FF0000">*</label> <span data-toggle="tooltip"
                            data-original-title="Message Content allowed 700 characters.">[?]</span></label>
                        <div class="col-sm-7">
                          <textarea id="txt_sms_content" name="txt_sms_content" onkeyup="call_getsmscount()" required=""
                            maxlength="700" tabindex="4" class="form-control form-control-primary" data-toggle="tooltip"
                            data-placement="top" placeholder="Message Content" title=""
                            data-original-title="Enter Message Content. Note: Confirm Message / Characters length and No. of Message before launching this Campaign. Maximum 700 Characters allowed."
                            style="height: 150px !important; width: 100%;"><?= $cn_msg ?></textarea>
                        </div>
                        <div class="col-sm-2" style="padding-right: 10px; padding-left: 10px; ">
                          <a class="btn btn-warning mb-1" href="javascript:void(0)" id='open_url' tabindex="5"
                            onclick="preview_open_url('open_url')" style="padding: 0.3rem 0rem !important;"><i
                              class="fas fa-link"></i> URL & Call Button</a>
                          <a class="btn btn-warning mb-1" href="javascript:void(0)" id='reply_button' tabindex="7"
                            onclick="preview_reply_button('reply_button')"><i class="fas fa-reply-all"></i> Reply
                            Button</a>
                          <a class="btn btn-warning" href="javascript:void(0)" id='option_list' tabindex="7"
                            onclick="preview_option_list('option_list')"><i class="fas fa-check-square"></i> Product
                            List</a>
                        </div>
                      </div>

                      <div class="form-group mb-2 row" id="id_open_url" style="display: none;">
                        <div class="form-group mb-2 row">
                          <label class="col-sm-3 col-form-label">URL Button</label>
                          <div class="col-sm-7">
                            <table class="table table-striped table-bordered m-0"
                              style="table-layout: fixed; white-space: inherit; width: 100%; overflow-x: scroll;">
                              <tbody>
                                <tr>
                                  <td class="col-md-6" style="width: 50%;">
                                    <input class="form-control" type="url" name="txt_open_url" tabindex="8"
                                      id="txt_open_url" maxlength="100" placeholder="URL [https://www.google.com]"
                                      onblur="return validate_url('txt_open_url')">
                                  </td>
                                  <td class="col-md-6" style="width: 50%;">
                                    <input class="form-control" type="text" name="txt_open_url_data" tabindex="8"
                                      id="txt_open_url_data" maxlength="25" placeholder="Button Name"
                                      title="Button Name">
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                          <div class="col-sm-2">
                          </div>
                        </div>

                        <div class="form-group mb-2 row">
                          <label class="col-sm-3 col-form-label">Call Button</label>
                          <div class="col-sm-7">
                            <table class="table table-striped table-bordered m-0"
                              style="table-layout: fixed; white-space: inherit; width: 100%; overflow-x: scroll;">
                              <tbody>
                                <tr>
                                  <td class="col-md-6" style="width: 40%;">
                                    <input class="form-control" type="text" name="txt_call_button" tabindex="9"
                                      id="txt_call_button" maxlength="13"
                                      onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"
                                      placeholder="Mobile Number" title="Mobile Number">
                                  </td>
                                  <td class="col-md-6" style="width: 40%;">
                                    <input class="form-control" type="text" name="txt_call_button_data" tabindex="9"
                                      id="txt_call_button_data" maxlength="50" placeholder="Button Name"
                                      title="Button Name">
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                          <div class="col-sm-2">
                          </div>
                        </div>
                      </div>

                      <div class="form-group mb-2 row" id="id_reply_button" style="display: none;">
                        <label class="col-sm-3 col-form-label">Reply Buttons <br>(Maximum 3 Allowed)</label>
                        <div class="col-sm-7">
                          <table class="table table-striped table-bordered m-0"
                            style="table-layout: fixed; white-space: inherit; width: 100%; overflow-x: scroll;">
                            <tbody>
                              <tr>
                                <td class="col-md-5" style="width: 40%;">
                                  <input class="form-control" type="text" tabindex="10" name="txt_reply_buttons[]"
                                    id="txt_reply_buttons_1" maxlength="25" placeholder="Reply" title="Reply">
                                </td>
                                <td class="col-md-5" style="width: 40%;">
                                  <input class="form-control" type="text" tabindex="10" name="txt_reply_buttons_data[]"
                                    id="txt_reply_buttons_data_1" maxlength="25" placeholder="Reply Button"
                                    title="Reply Button">
                                </td>
                                <td class="col-md-2" style="width: 20%; padding: 5px !important;">
                                  <input type="button" class="btn btn-success" value="+ Add Reply"
                                    onclick="add_column('text_suggested_replies')">
                                  <input type="hidden" name="hidcnt_text_suggested_replies"
                                    id="hidcnt_text_suggested_replies" value="1">
                                </td>
                              </tr>

                              <tr>
                                <td colspan="3" style="padding: 0px;">
                                  <table id="id_text_suggested_replies" style="width: 100% !important;">
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <div class="col-sm-2">
                        </div>
                      </div>

                      <div class="form-group mb-2 row" id="id_option_list" style="display: none;">
                        <label class="col-sm-3 col-form-label">Product List <br>(Maximum 4 Allowed)</label>
                        <div class="col-sm-7">
                          <table class="table table-striped table-bordered m-0"
                            style="table-layout: fixed; white-space: inherit; width: 100%; overflow-x: scroll;">
                            <tbody>
                              <tr>
                                <td class="col-md-8" style="width: 80%;">
                                  <input class="form-control" type="text" tabindex="10" name="txt_option_list[]"
                                    id="txt_option_list_1" maxlength="25" placeholder="Product" title="Product">
                                </td>
                              </tr>
                              <tr>
                                <td class="col-md-8" style="width: 80%;">
                                  <input class="form-control" type="text" tabindex="10" name="txt_option_list[]"
                                    id="txt_option_list_2" maxlength="25" placeholder="Product" title="Product">
                                </td>
                                <td class="col-md-3" style="width: 20%;">
                                  <input type="button" class="btn btn-success" value="+ Add Products"
                                    onclick="add_column('option_list')">
                                  <input type="hidden" name="hidcnt_text_option_list" id="hidcnt_text_option_list"
                                    value="1">
                                </td>
                              </tr>

                              <tr>
                                <td colspan="3" style="padding: 0px;">
                                  <table id="id_text_option_list" style="width: 100% !important;">
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <div class="col-sm-2">
                        </div>
                      </div>

                    </div>
                    <div class="card-footer text-center">
                      <span class="error_display" id='id_error_display'></span>
                      <input type="hidden" name="txt_sms_count" id="txt_sms_count" value="<?= $sms_ttl_chars ?>">
                      <input type="hidden" name="txt_char_count" id="txt_char_count" value="<?= $cnt_ttl_chars ?>">
                      <input type="hidden" class="form-control" name='tmpl_call_function' id='tmpl_call_function'
                        value='compose_whatsapp' />
                      <input type="submit" name="compose_submit" id="compose_submit" tabindex="11" value="Submit"
                        class="btn btn-success" disabled>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </section>

      </div>

      <? include("libraries/site_footer.php"); ?>

    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="assets/modules/jquery.min.js"></script>
  <script src="assets/modules/popper.js"></script>
  <script src="assets/modules/tooltip.js"></script>
  <script src="assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="assets/modules/moment.min.js"></script>
  <script src="assets/js/stisla.js"></script>

  <!-- JS Libraies -->

  <!-- Page Specific JS File -->

  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/custom.js"></script>

  <script src="assets/js/xlsx.core.min.js"></script>
  <script src="assets/js/xls.core.min.js"></script>

  <audio id="audio" style="display: none;"></audio>
  <input type="hidden" name="txt_media_duration" id="txt_media_duration" style="display: none;">
  <script>
    $(function () {
      $('.theme-loader').fadeOut("slow");
      init();
    });

    var f_duration = 0;
    document.getElementById('audio').addEventListener('canplaythrough', function (e) {
      //add duration in the input field #f_du
      f_duration = Math.round(e.currentTarget.duration);
      $('#txt_media_duration').val(f_duration);
    });
    var obUrl;
    document.getElementById('txt_media').addEventListener('change', function (e) {
      var file = e.currentTarget.files[0];
      $('#txt_media_duration').val('');
      //check file extension for audio/video type
      if (file.name.match(/\.(avi|mp3|mp4|mpeg|ogg)$/i)) {
        obUrl = URL.createObjectURL(file);
        document.getElementById('audio').setAttribute('src', obUrl);
      }
    });

    document.getElementById('hid_submit_alow').addEventListener('change', function (e) {
      // alert("HLO");
      if($('#hid_submit_alow').val() == 0) {
        $('#compose_submit').prop('disabled', true);
      } else {
        $('#compose_submit').prop('disabled', false);
      }
    });

    function validate_filesize(file_name) {
      $("#id_error_display").html("");
      var file_size = file_name.files[0].size;
      console.log(file_name.files[0].name);

      if (file_name.files[0].name.match(/\.(avi|mp3|mp4|mpeg|ogg)$/i)) {

        if (parseInt($('#txt_media_duration').val()) > 31) {
          $('#txt_media').val('');
        }
      }

      if (file_size > 5242880) { // 5 MB
        $("#id_error_display").html("Media File size must below 5 MB Size. Kindly try again!!");
        console.log("Failed");
      } else {
        console.log("Success");
      }
      $('#txt_media_duration').val('');
    }

    function call_generate_contacts() {
      var txt_list_mobno = $("#txt_list_mobno").val();
      $("#id_error_display").html('');
      $("#id_generate_csv").html('');

      if (txt_list_mobno != '') {
        var send_code = "&txt_list_mobno=" + txt_list_mobno;
        $.ajax({
          type: 'post',
	  async: true,
          url: "ajax/message_call_functions.php?tmpl_call_function=generate_contacts" + send_code,
          dataType: 'json',
          success: function (response) {

            if (response.status == 0) {
              $("#id_generate_csv").html('');
              $('#id_error_display').html(response.msg);
              $('#hid_submit_alow').val('0');
              $('#compose_submit').prop('disabled', true);
              $('#id_popup_view').css("display", "none");
            } else {
              window.location = response.msg;
              // $("#id_generate_csv").html(response.msg);
              $('#id_error_display').html('');
              $('#hid_submit_alow').val('1');
              $('#compose_submit').prop('disabled', false);
              $('#id_popup_view').css("display", "block");
            }
          },
          error: function (response, status, error) { }
        });
      }
    }

    function func_validate_campaign_name() {
      var txt_campaign_name = $("#txt_campaign_name").val();
      $("#id_error_display").html('');
      $('#txt_campaign_name').css('border-color', '#e4e6fc');

      if (txt_campaign_name != '') {
        var send_code = "&txt_campaign_name=" + txt_campaign_name;
        $.ajax({
          type: 'post',
          url: "ajax/call_functions.php?tmpl_call_function=validate_campaign_name" + send_code,
          dataType: 'json',
          success: function (response) {
            if (response.status == 0) {
              $('#txt_campaign_name').val('');
              $('#txt_campaign_name').focus();
              $("#txt_campaign_name").attr('data-original-title', response.msg);
              $("#txt_campaign_name").attr('title', response.msg);
              $('#txt_campaign_name').css('border-color', 'red');
              $('#id_error_display').html(response.msg);
            } else {
            }
          },
          error: function (response, status, error) { }
        });
      }
    }

    function call_getsmscount() {
      var lngth = $('#txt_sms_content').val().length;
      $('#txt_char_count').val(lngth);

      var sms_cnt = parseInt(lngth / 160);
      $('#txt_sms_count').val(+sms_cnt + 1);
    }

    function preview_open_url() {
      $("#txt_open_url").prop('required', true);
      $("#txt_open_url_data").prop('required', true);
      $("#txt_call_button").prop('required', true);
      $("#txt_call_button_data").prop('required', true);
      $('#id_open_url').toggle("display", "block");
    }
    function preview_call_button() {
      $("#txt_call_button").prop('required', true);
      $("#txt_call_button_data").prop('required', true);
      $('#id_call_button').toggle("display", "block");
    }
    function preview_reply_button() {
      $("#txt_reply_buttons_1").prop('required', true);
      $("#txt_reply_buttons_data_1").prop('required', true);
      $('#id_reply_button').toggle("display", "block");
    }
    function preview_option_list() {
      $("#txt_option_list_1").prop('required', true);
      $("#txt_option_list_2").prop('required', true);
      $('#id_option_list').toggle("display", "block");
    }

    function validate_url(url_site) {
      $('#compose_submit').prop('disabled', false);
      $("#id_error_display").html("");
      var url = $("#" + url_site).val();
      var pattern = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
      if (!pattern.test(url)) {
        $("#id_error_display").html("Invalid URL");
        $('#compose_submit').prop('disabled', true);
        return false;
      } else {
        $('#compose_submit').prop('disabled', false);
        return true;
      }
    }


    function init() {
      document.getElementById('upload_contact').addEventListener('change', handleFileSelect, false);
    }

    function handleFileSelect(event) {
      var flenam = document.querySelector('#upload_contact').value;
      var extn = flenam.split('.').pop();

      if (extn == 'xlsx' || extn == 'xls') {
        ExportToTable();
      } else {
        const reader = new FileReader()
        reader.onload = handleFileLoad;
        reader.readAsText(event.target.files[0])
      }
    }

    function handleFileLoad(event) {
      console.log(event);
      $('#txt_list_mobno').val(event.target.result);
      $('#txt_list_mobno').focus();
    }

    var value_list = new Array; ///this one way of declaring array in javascript
    function ExportToTable() {
      var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
      /*Checks whether the file is a valid excel file*/
      if (regex.test($("#upload_contact").val().toLowerCase())) {
        var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
        if ($("#upload_contact").val().toLowerCase().indexOf(".xlsx") > 0) {
          xlsxflag = true;
        }
        /*Checks whether the browser supports HTML5*/
        if (typeof (FileReader) != "undefined") {
          var reader = new FileReader();
          reader.onload = function (e) {
            var data = e.target.result;
            /*Converts the excel data in to object*/
            if (xlsxflag) {
              var workbook = XLSX.read(data, {
                type: 'binary'
              });
            } else {
              var workbook = XLS.read(data, {
                type: 'binary'
              });
            }
            /*Gets all the sheetnames of excel in to a variable*/
            var sheet_name_list = workbook.SheetNames;

            var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/
            sheet_name_list.forEach(function (y) {
              /*Iterate through all sheets*/
              /*Convert the cell value to Json*/
              if (xlsxflag) {
                var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);
              } else {
                var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);
              }
              if (exceljson.length > 0 && cnt == 0) {
                BindTable(exceljson, '#txt_list_mobno');
                cnt++;
              }
            });
            $('#txt_list_mobno').show();
            $('#txt_list_mobno').focus();
          }
          if (xlsxflag) {
            /*If excel file is .xlsx extension than creates a Array Buffer from excel*/
            reader.readAsArrayBuffer($("#upload_contact")[0].files[0]);
          } else {
            reader.readAsBinaryString($("#upload_contact")[0].files[0]);
          }
        } else {
          alert("Sorry! Your browser does not support HTML5!");
        }
      } else {
        alert("Please upload a valid Excel file!");
      }
    }

    function BindTable(jsondata, tableid) {
      /*Function used to convert the JSON array to Html Table*/
      var columns = BindTableHeader(jsondata, tableid); /*Gets all the column headings of Excel*/
      for (var i = 0; i < jsondata.length; i++) {
        for (var colIndex = 0; colIndex < columns.length; colIndex++) {
          var cellValue = jsondata[i][columns[colIndex]];
          if (cellValue == null)
            cellValue = "";
          value_list.push("\n" + cellValue);
        }
      }
      $(tableid).val(value_list);
    }

    function BindTableHeader(jsondata, tableid) {
      /*Function used to get all column names from JSON and bind the html table header*/
      var columnSet = [];
      for (var i = 0; i < jsondata.length; i++) {
        var rowHash = jsondata[i];
        for (var key in rowHash) {
          if (rowHash.hasOwnProperty(key)) {
            if ($.inArray(key, columnSet) == -1) {
              /*Adding each unique column names to a variable array*/
              columnSet.push(key);
              value_list.push("\n" + key);
            }
          }
        }
      }
      return columnSet;
    }

    function add_column(rcs_master_key) {
      var ret_value = "";
      switch (rcs_master_key) {
        case 'option_list':
          var sugqty = 4; // Maximum 4 Qty

          $('#errid_text_option_list').html("");
          $('#errid_text_option_list').css('display', 'none');
          var cnttxtsugrep = $('#hidcnt_text_option_list').val();
          var hidcnt_text_option_list = $('input[name="txt_option_list[]"]').length;

          if (hidcnt_text_option_list == sugqty) {
            $('#errid_text_option_list').html("Maximum " + hidcnt_text_option_list + " Qty available for this!!");
            $('#errid_text_option_list').css('display', 'block');
          } else {
            cnttxtsugrep++;
            ret_value = '<tr id="idcnttxtsugrep_' + cnttxtsugrep + '" style="width: 100%;"><td class="col-md-8" style="width: 40%;"><input class="form-control" type="text" maxlength="25" tabindex="10" name="txt_option_list[]" id="txt_option_list_' + cnttxtsugrep + '" placeholder="Product" required title="Product"></td><td class="col-md-3" style="width: 20%;"><span class="btn btn-danger btn-sm" tabindex="10" onclick=\"delete_column(' + "'text_option_list'" + ', ' + cnttxtsugrep + ')\"> - </span></td></tr>';
            $('#id_text_option_list').append(ret_value);
            $('#hidcnt_text_option_list').val(cnttxtsugrep);
          }
          break;

        case 'text_suggested_replies': // Plain Text
          var sugqty = 3; // Maximum 3 Qty

          $('#errid_text_suggested_replies').html("");
          $('#errid_text_suggested_replies').css('display', 'none');
          var cnttxtsugrep = $('#hidcnt_text_suggested_replies').val();
          var hidcnt_text_suggested_replies = $('input[name="txt_reply_buttons[]"]').length;

          if (hidcnt_text_suggested_replies == sugqty) {
            $('#errid_text_suggested_replies').html("Maximum " + hidcnt_text_suggested_replies + " Qty available for this!!");
            $('#errid_text_suggested_replies').css('display', 'block');
          } else {
            cnttxtsugrep++;
            ret_value = '<tr id="idcnttxtsugrep_' + cnttxtsugrep + '" style="width: 100%;"><td class="col-md-5" style="width: 40%;"><input class="form-control" type="text" maxlength="25" tabindex="10" required name="txt_reply_buttons[]" id="txt_reply_buttons_' + cnttxtsugrep + '" placeholder="Reply" title="Reply"></td><td class="col-md-5" style="width: 40%;"><input class="form-control" type="text" maxlength="25" tabindex="10" required name="txt_reply_buttons_data[]" id="txt_reply_buttons_data_' + cnttxtsugrep + '" placeholder="Reply Button" title="Reply Button"></td><td class="col-md-2" style="width: 20%;"><span class="btn btn-danger btn-sm" tabindex="10" onclick=\"delete_column(' + "'text_suggested_replies'" + ', ' + cnttxtsugrep + ')\"> - </span></td></tr>';
            $('#id_text_suggested_replies').append(ret_value);
            $('#hidcnt_text_suggested_replies').val(cnttxtsugrep);
          }
          break;

        default:
          break;
      }
    }

    function delete_column(rcs_master_key, delid) {
      var ret_value = "";
      switch (rcs_master_key) {
        case 'text_option_list':
          $('#idcnttxtsugrep_' + delid).remove();
          break;

        case 'text_suggested_replies':
          $('#idcnttxtsugrep_' + delid).remove();
          break;

        default:
          break;
      }
    }

    function call_remove_duplicate_invalid() {
      $("#txt_list_mobno_txt").html("");
      var txt_list_mobno = $("#txt_list_mobno").val();

      var chk_remove_duplicates = 0;
      if ($("#chk_remove_duplicates").prop('checked') == true) {
        chk_remove_duplicates = 1;
      }

      var chk_remove_invalids = 0;
      if ($("#chk_remove_invalids").prop('checked') == true) {
        chk_remove_invalids = 1;
      }

      var chk_remove_stop_status = 0;
      if ($("#chk_remove_stop_status").prop('checked') == true) {
        chk_remove_stop_status = 1;
      }

      $.ajax({
        type: 'post',
        url: "ajax/message_call_functions.php",
        data: { validateMobno: 'validateMobno', mobno: txt_list_mobno, dup: chk_remove_duplicates, inv: chk_remove_invalids },
        success: function (response_msg) {
          let response_msg_text = response_msg.msg;
          const response_msg_split = response_msg_text.split("||");
          $("#txt_list_mobno").val(response_msg_split[0]);
          if (response_msg_split[1] != '') {
            $("#txt_list_mobno_txt").html("Invalid Mobile Nos : " + response_msg_split[1]);
          }

          if (chk_remove_stop_status == 1) {

          }

        },
        error: function (response_msg, status, error) {
        }
      });
    }


    // function call_composesms() {
    $(document).on("submit", "form#frm_compose_whatsapp", function (e) {
      e.preventDefault();
      // alert($('#hid_submit_alow').val());
      if($('#hid_submit_alow').val() == 0) {
        $('#compose_submit').prop('disabled', true);
        $("#id_error_display").html("Kindly Generate and Upload the contacts First");
      } else {
        $('#compose_submit').prop('disabled', false);
        console.log("View Submit Pages");
        console.log("came Inside");
        $("#id_error_display").html("");
        $('#txt_list_mobno').css('border-color', '#a0a0a0');
        $('#chk_remove_duplicates').css('border-color', '#a0a0a0');
        $('#chk_remove_invalids').css('border-color', '#a0a0a0');

        $('#txt_sms_content').css('border-color', '#a0a0a0');
        $('#txt_char_count').css('border-color', '#a0a0a0');
        $('#txt_sms_count').css('border-color', '#a0a0a0');

        //get input field values 
        var txt_whatsapp_mobno = $('#txt_whatsapp_mobno').val();
        var txt_campaign_name = $('#txt_campaign_name').val();
        var txt_list_mobno = $('#txt_list_mobno').val();
        var chk_remove_duplicates = $('#chk_remove_duplicates').val();
        var chk_remove_invalids = $('#chk_remove_invalids').val();

        var flag = true;
        var len = $('.cls_checkbox:checked').length;
        if (len <= 0) {
          $("#id_error_display").html("Please check at least one Whatsapp Mobile No");
          $('#txt_whatsapp_mobno').focus();
          flag = false;
        }

        /********validate all our form fields***********/
        /* txt_whatsapp_mobno field validation  */
        if (txt_whatsapp_mobno == "") {
          $('#txt_whatsapp_mobno').css('border-color', 'red');
          console.log("##");
          flag = false;
        }

        /* txt_campaign_name field validation  */
        if (txt_campaign_name == "") {
          $('#txt_campaign_name').css('border-color', 'red');
          console.log("##");
          flag = false;
        }
        /* txt_list_mobno field validation  */
        if (txt_list_mobno == "") {
          $('#txt_list_mobno').css('border-color', 'red');
          console.log("##");
          flag = false;
        }
        /* chk_remove_duplicates field validation  */
        if (chk_remove_duplicates == "") {
          $('#chk_remove_duplicates').css('border-color', 'red');
          console.log("$$");
          flag = false;
        }
        /* chk_remove_invalids field validation  */
        if (chk_remove_invalids == "") {
          $('#chk_remove_invalids').css('border-color', 'red');
          console.log("%%");
          flag = false;
        }

        /* If all are ok then we send ajax request to ajax/master_call_functions.php *******/
        if (flag) {
          // console.log("***");

          var fd = new FormData(this);

          $.ajax({
            type: 'post',
            url: "ajax/message_call_functions.php",
            dataType: 'json',
            data: fd,
            contentType: false,
            processData: false,
            beforeSend: function () {
              $('#compose_submit').attr('disabled', true);
              $('.theme-loader').show();
            },
            complete: function () {
              $('#compose_submit').attr('disabled', false);
              $('.theme-loader').hide();
            },
            success: function (response) {
              console.log("SUCC");
              if (response.status == '0') {
                $('#id_slt_header').val('');
                $('#id_slt_template').val('');

                $('#txt_list_mobno').val('');
                $('#chk_remove_duplicates').val('');
                $('#chk_remove_invalids').val('');

                $('#txt_sms_content').val('');
                $('#txt_char_count').val('');
                $('#txt_sms_count').val('');

                $('#compose_submit').attr('disabled', false);
                $('#id_submit_composercs').attr('disabled', false);

                $("#id_error_display").html(response.msg);
              } else if (response.status == 2) {
                $('#compose_submit').attr('disabled', false);
                $("#id_error_display").html("Invalid User, Kindly try with valid User!!");
                $('#compose_submit').attr('disabled', false);
              } else if (response.status == 1) {
                $('#compose_submit').attr('disabled', false);
                $("#id_error_display").html("Whatsapp Campaign Created Successfully..");
                window.location = 'whatsapp_list';
              }
              $('.theme-loader').hide();
            },
            error: function (response, status, error) {
              console.log("FAL");
              $('#id_slt_header').val('');
              $('#id_slt_template').val('');

              $('#txt_list_mobno').val('');
              $('#chk_remove_duplicates').val('');
              $('#chk_remove_invalids').val('');

              $('#txt_sms_content').val('');
              $('#txt_char_count').val('');
              $('#txt_sms_count').val('');

              $('#compose_submit').attr('disabled', false);
              $('#id_submit_composercs').attr('disabled', false);

              $('.theme-loader').show();
              window.location = 'compose_whatsapp';
              $("#id_error_display").html(response.msg);
            }
          });
        }
        // });
      }
    });
  </script>
</body>

</html>
