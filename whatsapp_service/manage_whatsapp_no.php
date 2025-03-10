<?php
session_start();//start session
error_reporting(0);// The error reporting function
include_once('api/configuration.php');// Include configuration.php
extract($_REQUEST);

if ($_SESSION['yjwatsp_user_id'] == "") { ?>
  <script>window.location = "index";</script>
  <?php exit();
}

$site_page_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
site_log_generate("Manage Sender ID Page : User : " . $_SESSION['yjwatsp_user_name'] . " access the page on " . date("Y-m-d H:i:s"));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Manage Sender ID ::
    <?= $site_title ?>
  </title>
  <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">

  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
</head>
<!-- style include in css -->
<style>
  .loader {
    width: 50;
    background-color: #ffffffcf;
  }

  .loader img {}
</style>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
<!-- include header function adding -->
      <? include("libraries/site_header.php"); ?>
<!-- include sitemenu function adding -->
      <? include("libraries/site_menu.php"); ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Manage Sender ID</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
              <div class="breadcrumb-item active"><a href="manage_store_list">Manage Sender ID List</a></div>
              <div class="breadcrumb-item">Manage Sender ID</div>
            </div>
          </div>

          <div class="section-body">
            <div class="row">

              <div class="col-12 col-md-8 col-lg-8 offset-2">
                <div class="card">
                  <form class="needs-validation" novalidate="" id="frm_store" name="frm_store" action="#" method="post"
                    enctype="multipart/form-data">
                    <div class="card-body">
                      <div class="form-group mb-2 row">
                        <label class="col-sm-3 col-form-label">Country Code</label>
                        <div class="col-sm-9">
                          <select id="txt_country_code" name="txt_country_code" class="form-control" tabindex="1">
                            <?
                            $replace_txt = '{
                             
                              "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
                            }';
                            $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                              CURLOPT_URL => $api_url.'/list/country_list',
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
                           
                            site_log_generate("Manage Sender ID Page : " . $uname . " Execute the service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
                            $response = curl_exec($curl);
                            curl_close($curl);
                            $state1 = json_decode($response, false);
                            site_log_generate("Manage Sender ID Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');

                            if ($state1->num_of_rows > 0) {
 // Looping the indicator is less than the count of reports.if the condition is true to continue the process and to get the details.if the condition are false to stop the process
                              for ($indicator = 0; $indicator < count($state1->report); $indicator++) {
                                $shortname = $state1->report[$indicator]->shortname;
                                $phonecode = $state1->report[$indicator]->phonecode;
                                $countryid = $state1->report[$indicator]->id;
                                ?>
                                <option value="<?= $countryid ?>||<?= $phonecode ?>" <? if ($shortname == 'IN') {
                                      echo "selected";
                                    } ?>><?=
                                       $shortname . " +" . $phonecode ?></option>
                              <?php }
                            }
                            ?>
                          </select>
                        </div>
                      </div>

                      <div class="form-group mb-2 row">
                        <label class="col-sm-3 col-form-label">Mobile Number</label>
                        <div class="col-sm-9">
                          <input type="text" name="mobile_number" id='mobile_number' class="form-control"
                            value="<?= $_REQUEST['mob'] ?>" autofocus tabindex="2" required="" maxlength="13"
                            placeholder="Mobile Number" data-toggle="tooltip" data-placement="top" title=""
                            data-original-title="Mobile Number"
                            onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"
                            onblur="return call_validate_mobileqrno()" <? if ($_REQUEST['mob'] != '') { ?> readonly <? } ?>>
                        </div>
                      </div>

                      <div class="card-footer text-center">
                        <span class="error_display" id='id_error_display'></span><br>
                        <input type="hidden" class="form-control" name='store_call_function' id='store_call_function'
                          value='qrcode' />
                        <a href="#!" name="submit" id="submit" tabindex="3" value="Submit"
                          class="btn btn-success">Click</a>

                        <div class="container">
                          <span class="error_display" style='font-size: 12px;' id='qrcode_display'></span>
                          <img src='./assets/img/loader.gif' id="id_qrcode" alt='QR Code'>
                        </div>
                      </div>


                  </form>

                </div>
              </div>
            </div>

            <div class="text-left">
              <span class="error_display1"><b>Note :</b> <br>1) Mobile Numbers for India - 10 digits allowed <br>2)
                Mobile Numbers for Foreign country - 5 to 13 digits allowed <br>3) It should be a whatsapp
                account</span>
            </div>
          </div>
        </section>
      </div>
<!-- include site footer -->
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

  <!--Remove dublicates numbers -->
  <script>
     // start function document
    $(function () {
      $('#id_qrcode').fadeOut("slow");
    });
// show_timeout function
    function show_timeout() {
      $('#id_qrcode').hide();
      $("#qrcode_display").html("Timed out, Waiting for new QR Code");
    }
// call_validate_mobileqrno function
    function call_validate_mobileqrno() {
      $('#id_qrcode').hide();
      $("#qrcode_display").html("");
      var mobile_number = $("#mobile_number").val();
      var stt = -1;
      if (mobile_number.length < 5 && mobile_number != '') {
        $('#mobile_number').css('border-color', '#red');
        $("#id_error_display").html("Mobile Number must contain 5 to 13 digits");
      }

      if (mobile_number.length > 5) {

        var letter = mobile_number.charAt(0);
        if (letter == 0 || letter == 1 || letter == 2 || letter == 3 || letter == 4 || letter == 5) {
          stt = 0;
        } else {
          stt = 1;
        }
        if (stt == 0) {
          $('#mobile_number').css('border-color', 'red');
          $("#id_error_display").html("Invalid Mobile Number");
        }
        else
          $('#mobile_number').css('border-color', '#ccc');
      }

      if (mobile_number.length >= 5 & mobile_number.length <= 13) {
        var flag = true;
        var mobile_number = $("#mobile_number").serialize();
        var txt_country_code = $("#txt_country_code").val();

        $.ajax({
          type: 'post',
          url: "ajax/store_call_functions.php?store_call_function=mobile_qrcode&txt_country_code=" + txt_country_code + "",
          dataType: 'json',
          data: mobile_number,
          beforeSend: function () {
            $('#id_qrcode').show();
            $('a').css("pointer-events", "none");
            $('#submit').addClass('btn-outline-light btn-disabled').removeClass('btn-success');
            $('#submit').css("pointer-events", "none");
          },
          complete: function () {
            $('#id_qrcode').show();
          },
          success: function (response) {
            $('a').css("pointer-events", "block");
            if (response.status == '1') {
              $('#id_qrcode').show();
              $("#id_qrcode").attr("src", response.qrcode);
              $("#qrcode_display").html('Please wait, automatically it will redirect after link!!');
              if (response.msg == 'QRCODE Already Scanned!') {
                window.location = "manage_whatsappno_list";
              }
            } else if (response.status == '3') {
              $('#id_qrcode').hide();
              $("#qrcode_display").html(response.msg);
            } else if (response.status == 0) {
              $('#id_qrcode').hide();
              $("#qrcode_display").html(response.msg);
              $('#submit').addClass('btn-success').removeClass('btn-outline-light btn-disabled');
              $('#submit').css("pointer-events", "block");
            }
	
            setInterval(show_timeout, 50000); // After 50 seconds show timeout msg
          },
          error: function (response, status, error) {
            $('a').css("pointer-events", "block");
            $('#mobile_number').val('');
            $('#id_qrcode').show();
            $("#id_error_display").html(response.msg);
          }
        })
      }
      return stt;

    }
    setInterval(call_validate_mobileqrno, 60000);
  </script>
</body>

</html>
