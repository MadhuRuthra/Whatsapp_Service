<?php
session_start();//start session
error_reporting(0);// The error reporting function
include_once 'api/configuration.php';// Include configuration.php
extract($_REQUEST);

if ($_SESSION['yjwatsp_user_id'] == "") { ?>
  <script>window.location = "index";</script>
  <?php exit();
}

$site_page_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
site_log_generate("Summary Report Page : User : " . $_SESSION['yjwatsp_user_name'] . " access the page on " . date("Y-m-d H:i:s"));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Summary Report :: <?= $site_title ?></title>
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
  </style>
</head>

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
            <h1>Summary Report</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
              <div class="breadcrumb-item">Summary Report</div>
            </div>
          </div>

          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <form method="post">
                      <div id="table-1_filter" class="dataTables_filter">
                        <label>User :
                          <select style="height:30px;border: 1px solid #ced4da; border-radius:0.2rem;" id="srch1"
                            name="srch1" class="search">
                            <option value="">Choose User</option>
                            <?
                            $replace_txt = '{
                            
                              "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
                            }';
                            $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
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
                            site_log_generate("Header Page : " . $_SESSION['yjwatsp_user_name'] . " Execute the service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
                            $response = curl_exec($curl);
                            curl_close($curl);

                            $header = json_decode($response, false);
                            site_log_generate("Header Page : " . $_SESSION['yjwatsp_user_name'] . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');

                            if ($header->num_of_rows > 0) {
                              for ($indicator = 0; $indicator < $header->num_of_rows; $indicator++) {
                                $user_id = $header->report[$indicator]->user_id;
                                $user_name = $header->report[$indicator]->user_name;
                                ?>
                                <option value="<?= $user_id ?>" <? if ($_REQUEST['srch1'] == $user_id) { ?> selected <? } ?>>
                                  <?= $user_name ?></option>
                              <? }
                            } ?>
                          </select></label>

                        <? if ($_SESSION['yjwatsp_user_master_id'] != 3) { ?>
                          <label>Department :
                            <select style="height:30px;border: 1px solid #ced4da;border-radius:0.2rem; " name="srch_1"
                              id='srch_1' class="search">
                              <option value="">Choose Department</option>
                              <?
                              $replace_txt = '{
                              
                              "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
                            }';
                            $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
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
                              site_log_generate("Header Page : " . $_SESSION['yjwatsp_user_name'] . " Execute the service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
                              $response = curl_exec($curl);
                              curl_close($curl);

                              $header = json_decode($response, false);
                              site_log_generate("Header Page : " . $_SESSION['yjwatsp_user_name'] . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');

                              if ($header->num_of_rows > 0) {
                                for ($indicator = 0; $indicator < $header->num_of_rows; $indicator++) {
                                  $user_master_id = $header->report[$indicator]->user_master_id;
                                  $user_type = $header->report[$indicator]->user_type;
                                  ?>
                                  <option value="<?= $user_master_id ?>" <? if ($_REQUEST['srch_1'] == $user_master_id) { ?>
                                      selected <? } ?>><?= $user_type ?></option>
                                <? }
                              } ?>
                            </select></label>
                        <? } ?>



                        <label>Campaign Name :
                            <select style="height:30px;border: 1px solid #ced4da;border-radius:0.2rem; " name="campaign_name"
                              id='campaign_name' class="search">
                              <option value="">Choose Campaign Name</option> 
                              

                            </select></label>

                            
                        <label>Date :<input type="search" name="dates" id="dates" value="<?= $_REQUEST['dates'] ?>"
                            class="form-control form-control-sm search_1" placeholder=""
                            aria-controls="table-1" /></label>
                            
                        <input type="submit" name="submit_1" id="submit_1" tabindex="10" value="Search"
                          class="btn btn-success " style="height:30px;">

                        
                         
                      </div>

                    </form><br></br>
                    <div class="table-responsive" id="id_business_summary_report">
                      Loading…
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
  <script type="text/javascript">
var previous_values_1;
// start function document
    $(document).ready(function (e) {    
      function business_summary_report() {
      console.log("calling1");
      var srch1 = $('#srch1').val();
      var srch_1 = $('#srch_1').val();
      var dates = $('#dates').val();
      $.ajax({
        type: 'post',
        url: "ajax/display_functions.php?call_function=business_summary_report&srch1=" + srch1 + "&srch_1=" + srch_1 + "&dates=" + dates + "",
        dataType: 'html',
        success: function (response) {
          $("#id_business_summary_report").html(response);
        },
        error: function (response, status, error) { }
      });
      campaign_name();
    }
    business_summary_report() 
    });

    $("#submit_1").click(function (e) {
      e.preventDefault();
    var  previous_values_1 =  $('#campaign_name :selected').val();
    if(previous_values_1 != 'undefined' ){
      console.log("business_summary_report");
        business_summary_report(previous_values_1);
    }
  
    });

   
  // business_summary_report func
    function business_summary_report(previous_values_1) {
      console.log("calling2");
      var srch1 = $('#srch1').val();
      var srch_1 = $('#srch_1').val();
      var dates = $('#dates').val();
      $.ajax({
        type: 'post',
        url: "ajax/display_functions.php?call_function=business_summary_report&srch1=" + srch1 + "&srch_1=" + srch_1 + "&dates=" + dates + "&campaign_name_filter="+ previous_values_1 +"",
        dataType: 'html',
        success: function (response) {
          $("#id_business_summary_report").html(response);
        },
        error: function (response, status, error) { }
      });
      campaign_name(e);
  e.preventDefault();  
    }
  setInterval(business_summary_report, 60000 ); // Every 1 min (60000), it will call
// campaign_name funtion
    function campaign_name(e) { 
      var dates = $('#dates').val();  
    var campaign_name = document.getElementById("campaign_name");
      campaign_name.options[campaign_name.options.selectedIndex].selected = true;
      $.ajax({
        type: 'post',
        url: "ajax/display_functions.php?call_function=camapign_name&srch1=" + srch1 + "&srch_1=" + srch_1 + "&dates=" + dates +"&campaign_name_filter=" + campaign_name +"" ,
        dataType: 'html',
        success: function (response) {
        var response_1 =  response.replaceAll(' ', '');
          const myArray = response_1.split("$");

          var array_response =[];
          for(i=0; i< myArray.length;i++){
            console.log(myArray[i]);
            array_response.push();
            array_response.push('<option' + (i == 0 ? ' value=""' : '') + ' value="' + myArray[i] + '">' + myArray[i] + '</option>'); 
          }       
          $("#campaign_name").html(array_response);
        },
        error: function (response, status, error) { }
      });
     
    }

    $(function () {
      var start = moment().subtract(30, 'days');
      var end = moment();
      function cb(start, end) {
        $('input[name="dates"]').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
      }
      $('input[name="dates"]').daterangepicker({
        autoUpdateInput: true,
        startDate: new Date(),
        endDate: end,
        locale: {       
      cancelLabel: 'Clear',
      format: 'YYYY/MM/DD'
        }
      });

      $('input[name="dates"]').on('apply.daterangepicker', function (ev, picker) {    
        $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
        var first_date = picker.startDate.format('YYYY/MM/DD');
        var second = picker.endDate.format('YYYY/MM/DD');
        campaign_name()
        // e.preventDefault();
      });
      $('input[name="dates"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
      });

    });
// function adding
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

