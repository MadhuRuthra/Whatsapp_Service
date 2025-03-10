<?php
/*
Authendicated users only allow to view this Whatsapp Message detailed report page.
This page is used to view the list of Whatsapp messages details.
Here we can Filter, Copy, Export CSV, Excel, PDF, Search, Column visibility the Table

Version : 1.0
Author : Madhubala (YJ0009)
Date : 03-Jul-2023
*/

session_start(); // start session
error_reporting(0); // The error reporting function

include_once 'api/configuration.php'; // Include configuration.php
extract($_REQUEST); // Extract the request

// If the Session is not available redirect to index page
if ($_SESSION['yjwatsp_user_id'] == "") { ?>
  <script>window.location = "index";</script>
  <?php exit();
}

$site_page_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME); // Collect the Current page name
site_log_generate("Detailed Report Page : User : " . $_SESSION['yjwatsp_user_name'] . " access the page on " . date("Y-m-d H:i:s"));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Detailed Report :: <?= $site_title ?></title>
  <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">

  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="assets/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="assets/css/searchPanes.dataTables.min.css">
  <link rel="stylesheet" href="assets/css/select.dataTables.min.css">
  <link rel="stylesheet" href="assets/css/colReorder.dataTables.min.css">
  <link rel="stylesheet" href="assets/css/buttons.dataTables.min.css">

  <!--Date picker -->
  <script type="text/javascript" src="assets/js/daterangepicker.min.js" defer></script>
  <link rel="stylesheet" type="text/css" href="assets/css/daterangepicker.css" />

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <!-- style include in css -->
  <style>
    element.style {}

    .custom-file,
    .custom-file-label,
    .custom-select,
    .custom-file-label:after,
    .form-control[type="color"],
    select.form-control:not([size]):not([multiple]) {
      height: calc(2.25rem + 6px);
    }

    .input-group-text,
    select.form-control:not([size]):not([multiple]),
    .form-control:not(.form-control-sm):not(.form-control-lg) {
      Loading… ￼ font-size: 14px;
      padding: 5px 15px;
    }

    .search {
      width: 200px;
      margin-right: 50px;
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

	<!-- include header function adding -->
      	<? include("libraries/site_header.php"); ?>

	<!-- include sitemenu function adding -->
      	<? include("libraries/site_menu.php"); ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
	  <!-- Title and Breadcrumbs -->
          <div class="section-header">
            <h1>Detailed Report</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
              <div class="breadcrumb-item">Detailed Report</div>
            </div>
          </div>

	  <!-- Report List Panel -->
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
<!-- Choose User -->
                  <div class="card-body">
                    <form method="post">
                      <div id="table-1_filter" class="dataTables_filter">
                        <div style="width: 20%; padding-right:1%; float: left;">User :
                          <select style="width: 100%; height:30px;border: 1px solid #ced4da; " id="srch1" name="srch1"
                            class="search">
                            <option value="">Choose User</option>
                            <? // To get the logged in user and their child users. Primary Admin can view all user
                            $replace_txt = '{
                              "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
                            }'; // Add user id
                            $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].''; // Add Bearer Token
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                              CURLOPT_URL => $api_url. '/report/report_filter_user',
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

			    // Send the data into API and execute                          
                            site_log_generate("Business Detailed Report Page : " . $_SESSION['yjwatsp_user_name'] . " Execute the service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
                            $response = curl_exec($curl);
                            curl_close($curl);

			    // After got response decode the JSON result
                            $header = json_decode($response, false);
                            site_log_generate("Business Detailed Report Page : " . $_SESSION['yjwatsp_user_name'] . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');

			    // To display the response data into option button
                            if ($header->num_of_rows > 0) {
// Looping the indicator is less than the num_of_rows.if the condition is true to continue the process.if the condition are false to stop the process
                              for ($indicator = 0; $indicator < $header->num_of_rows; $indicator++) {
                                $user_id = $header->report[$indicator]->user_id;
                                $user_name = $header->report[$indicator]->user_name;
                                ?>
                                <option value="<?= $user_id ?>" <? if ($_REQUEST['srch1'] == $user_id) { ?> selected <? } ?>>
                                  <?= $user_name ?></option>
                              <? }
                            } ?>
                          </select></div>
<!-- Choose Department -->
                        <? if ($_SESSION['yjwatsp_user_master_id'] != 3) { ?>
                          <div style="width: 20%; padding-right:1%; float: left;">Department :
                            <select style="width: 100%; height:30px;border: 1px solid #ced4da; " name="srch_1" id='srch_1'
                              class="search">
                              <option value="">Choose Department</option>
                              <? // To display the department list from Master API
                              $replace_txt = '{
                                "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
                              }'; // User id added
                              $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].''; // Add Bearer Token
                              $curl = curl_init();
                              curl_setopt_array($curl, array(
                                CURLOPT_URL => $api_url.'/report/report_filter_department',
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
                         
			      // Send the data into API and execute
                              site_log_generate("Business Detailed Report Page : " . $_SESSION['yjwatsp_user_name'] . " Execute the service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
                              $response = curl_exec($curl);
                              curl_close($curl);

			      // After got response decode the JSON result
                              $header = json_decode($response, false);
                              site_log_generate("Business Detailed Report Page : " . $_SESSION['yjwatsp_user_name'] . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');

			      // To display the response data into Option button
                              if ($header->num_of_rows > 0) {
                                for ($indicator = 0; $indicator < $header->num_of_rows; $indicator++) {
 // Looping the indicator is less than the num_of_rows.if the condition is true to continue the process.if the condition are false to stop the process
                                  $user_master_id = $header->report[$indicator]->user_master_id;
                                  $user_type = $header->report[$indicator]->user_type;
                                  ?>
                                  <option value="<?= $user_master_id ?>" <? if ($_REQUEST['srch_1'] == $user_master_id) { ?>
                                      selected <? } ?>><?= $user_type ?></option>
                                <? }
                              } ?>
                            </select></div>
                        <? } ?>
<!-- Choose Status -->
                        <div style="width: 20%; padding-right:1%; float: left;">Status :
                          <select style="width: 100%; height:30px;border: 1px solid #ced4da;" name="srch_status" id='srch_status'
                            class="search">
                            <option value="" selected>Choose Status</option>
                            <option value="SENT" <? if ($_REQUEST['srch_status'] == 'SENT') {
                              echo 'selected';
                            } ?>>SENT
                            </option>
                            <option value="YET TO SENT" <? if ($_REQUEST['srch_status'] == 'YET TO SENT') {
                              echo 'selected';
                            } ?>>YET TO SENT</option>
                            <option value="FAILED" <? if ($_REQUEST['srch_status'] == 'FAILED') {
                              echo 'selected';
                            } ?>>
                              FAILED</option>
                            <option value="INVALID" <? if ($_REQUEST['srch_status'] == 'INVALID') {
                              echo 'selected';
                            } ?>>
                              INVALID</option>

                            <option value="DELIVERED" <? if ($_REQUEST['srch_status'] == 'DELIVERED') {
                              echo 'selected';
                            } ?>>DELIVERED</option>
                            <option value="NOT DELIVERED" <? if ($_REQUEST['srch_status'] == 'NOT DELIVERED') {
                              echo 'selected';
                            } ?>>NOT DELIVERED</option>

                            <option value="READ" <? if ($_REQUEST['srch_status'] == 'READ') {
                              echo 'selected';
                            } ?>>READ
                            </option>
                            <option value="NOT READ" <? if ($_REQUEST['srch_status'] == 'NOT READ') {
                              echo 'selected';
                            } ?>>NOT READ</option>
                          </select></div>
<!-- date filter -->
			<div style="width: 20%; padding-right:1%; float: left;">Date : <input type="search" name="dates" id="dates" value="<?= $_REQUEST['dates'] ?>"
                            class="form-control form-control-sm search_1" placeholder=""
                            aria-controls="table-1" style="width: 100%; "/></div>
<!-- submit button -->
			<div style="width: 20%; padding-right:1%; float: left;">
                        <input type="submit" name="submit_1" id="submit_1" tabindex="10" value="Search"
                          class="btn btn-success " style="height:30px; margin-top: 20px;"></div>
                      </div>
                    </form>

                    <div class="table-responsive" id="id_business_details_report" style="padding-top: 20px;">
                      Loading..
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>


      </div>
      </section>
    </div>

	<!-- include site footer -->
    	<? include("libraries/site_footer.php"); ?>

  </div>
  </div>
 <!-- Modal Popup window content-->
 <div class="modal fade" id="default-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style=" max-width: 75% !important;">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Template Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="id_modal_display" style=" word-wrap: break-word; word-break: break-word;">
          <h5>No Data Available</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success waves-effect " data-dismiss="modal">Close</button>
        </div>
      </div>
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

  <script src="assets/js/jquery.dataTables.min.js"></script>
  <script src="assets/js/dataTables.buttons.min.js"></script>
  <script src="assets/js/dataTables.searchPanes.min.js"></script>
  <script src="assets/js/dataTables.select.min.js"></script>
  <script src="assets/js/jszip.min.js"></script>
  <script src="assets/js/pdfmake.min.js"></script>
  <script src="assets/js/vfs_fonts.js"></script>
  <script src="assets/js/buttons.html5.min.js"></script>
  <script src="assets/js/buttons.colVis.min.js"></script>

  <script>
    var previous_values_1;

    // On loading the page, this function will call
    $(document).ready(function (e) {
      business_details_report();
    });

    // To list the Whatsapp message from API
    function business_details_report() {

      $.ajax({
        type: 'post',
        url: "ajax/display_functions.php?call_function=business_details_report&dates=" + dates,
        dataType: 'html',
        beforeSend: function () {
          $('.theme-loader').show();
        },
        complete: function () {
          $('.theme-loader').hide();
        },
        success: function (response) { // Success
          $("#id_business_details_report").html(response);
          $('.theme-loader').hide();
        },
        error: function (response, status, error) {
          $('.theme-loader').hide();
         }
      });
    }

    function business_details(date) {  
      var srch1 = $('#srch1').val();
      var srch_1 = $('#srch_1').val();
      var srch_status = $('#srch_status').val();

var date = $("#dates").val();

      $.ajax({
        type: 'post',
        url: "ajax/display_functions.php?call_function=business_details_report&srch1=" + srch1 + "&srch_1=" + srch_1 + "&srch_status=" + srch_status + "&dates=" + date,
        dataType: 'html',
        beforeSend: function () {
          $('.theme-loader').show();
        },
        complete: function () {
          $('.theme-loader').hide();
        },
        success: function (response) { // Success
          $("#id_business_details_report").html(response);
          $('.theme-loader').hide();
        },
        error: function (response, status, error) {
          $('.theme-loader').hide();
         }        
      });
      setInterval(function() {
        // window.location
        window.location = 'business_details_report';
            document.getElementById('srch_1').value = "";
            document.getElementById('srch1').value = "";
      }, 60000);
    }
    // setInterval(business_details_report, 60000); // Every 1 min (60000), it will call

    // To show the Calendar
    $(function() {
var start = moment().subtract(29, 'days');
var end = moment();

function cb(start, end) {
    $('#dates').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
}

$('#dates').daterangepicker({
    startDate: start,
    endDate: end,  
    locale: {       
      cancelLabel: 'Clear',
      format: 'YYYY/MM/DD'
        }
}, cb);
cb(start, end);

});

    // While click the Submit button
    $("#submit_1").click(function (e) {
      // console.log("****4");
      e.preventDefault();  
      var date = $("#dates").val();
      var srch1 = $("#srch1").val();
      business_details(date);
      //  previous_values_1  = $("#dates").val();

     
    });

   // To get the Single Template details from List and show in Modal Popup Window
     function call_getsingletemplate(template_name_wh_content, indicatori,sender) {
      $("#slt_whatsapp_template_single").html("");
      $.ajax({
        type: 'post',
        url: "ajax/preview_call_functions.php?previewTemplate_dreport=previewTemplate_dreport&tmpl_name=" + template_name_wh_content+"&sender_number="+sender,
        success: function (response_msg) { // Success
          if (response_msg.msg == '-') {
            $("#id_modal_display").html('Template Name : ' + template_name_wh_content + '<br>No Data Available!!');
          } else {
            $("#id_modal_display").html('Template Name : ' + template_name_wh_content + '<br>' + response_msg.msg);
          }
          $('#default-Modal').modal({ show: true });
        },
        error: function (response_msg, status, error) { // Error
          $("#id_modal_display").html(response_msg.msg);
          $('#default-Modal').modal({ show: true });
        }
      });
    }
    // To Show Datatable with Export, search panes and Column visible
    $('#table-1').DataTable({
      dom: 'Bfrtip',
      colReorder: true,
      buttons: [{
        extend: 'copyHtml5',
        exportOptions: {
          columns: [0, ':visible']
        }
      }, {
        extend: 'csvHtml5',
        exportOptions: {
          columns: ':visible'
        }
      }, {
        extend: 'pdfHtml5',
        exportOptions: {
          columns: ':visible'
        }
      }, {
        extend: 'searchPanes',
        config: {
          cascadePanes: true
        }
      }, 'colvis'],
      columnDefs: [{
        searchPanes: {
          show: false
        },
        targets: [0]
      }]
    });
  </script>
</body>

</html>

