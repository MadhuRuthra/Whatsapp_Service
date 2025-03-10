<?php
/*
Authendicated users only allow to view this Summary Report page.
This page is used to view the list of Whatstapp Summary Report.
Here we can Filter, Copy, Export CSV, Excel, PDF, Search, Column visibility the Table

Version : 1.0
Author : Madhubala (YJ0009)
Date : 03-Jul-2023
*/

session_start(); //start session
error_reporting(0); // The error reporting function

include_once 'api/configuration.php'; // Include configuration.php
extract($_REQUEST); // Extract the request

// If the Session is not available redirect to index page
if ($_SESSION['yjwatsp_user_id'] == "") { ?>
  <script>window.location = "index";</script>
  <?php exit();
}

$site_page_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME); // Collect the Current page name
site_log_generate("Summary Report Page : User : " . $_SESSION['yjwatsp_user_name'] . " access the page on " . date("Y-m-d H:i:s"));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Summary Report :: <?= $site_title ?></title>
  <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">

  <script src="assets/multi-select/jquery.min.js"></script>
  <script src="assets/multi-select/jquery-2.2.4.min.js"></script>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">
  <!-- CSS Libraries -->
  <link rel="stylesheet" href="assets/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="assets/css/searchPanes.dataTables.min.css">
  <link rel="stylesheet" href="assets/css/select.dataTables.min.css">
  <link rel="stylesheet" href="assets/css/colReorder.dataTables.min.css">
  <link rel="stylesheet" href="assets/css/buttons.dataTables.min.css">
<!-- multiple select -->
  <link rel="stylesheet" href="assets/multi-select/bootstrap.min.css">
  <link rel="stylesheet" href="assets/multi-select/bootstrap-multiselect.css" type="text/css"/>

  <!--Date picker -->
  <script type="text/javascript" src="assets/js/daterangepicker.min.js" defer></script>
  <link rel="stylesheet" type="text/css" href="assets/css/daterangepicker.css" />

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <!-- style include in css -->
  <style>
  
    .multiselect {
       border: 1px solid #ced4da !important;
       border-radius:0.2rem !important;
        width: 235px !important;
        height:30px !important;
        background-color: #e9ecef;
    }
    .multiselect-container {
        width: 235px !important;
        background-color: #e9ecef;
    }
    .caret{
      display: none;
    }

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
            <h1>Summary Report</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
              <div class="breadcrumb-item">Summary Report</div>
            </div>
          </div>
<!-- Report Filter and list panel -->
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
 <!-- Choose User -->
 <div class="card-body">
                    <form method="post">
                      <div id="table-1_filter" class="dataTables_filter">
                      <? if ($_SESSION['yjwatsp_user_id'] == 1) { ?>
                        <div style="width: 20%; padding-right:1%; float: left;">Admin :
                          <select style="width: 100%; height:30px;border: 1px solid #ced4da; " id="srch1" name="srch1"
                            class="search">
                            <option value="">Choose Admin</option>
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
                          <? }?>
<!-- Choose Department -->
                        <? if ($_SESSION['yjwatsp_parent_id'] == 1) { ?>
                          <div style="width: 20%; padding-right:1%; float: left; display:none">Department :
                            <select style="width: 100%; height:30px;border: 1px solid #ced4da; " name ="srch_1" id='srch_1'
                              class="search">
                              <option value="">Choose Department</option>
                                  <!-- <option value="<?= $user_id ?>" <? if ($_REQUEST['srch_1'] == $user_id) { ?>
                                      selected <? } ?>><?= $user_name ?></option> -->
                                <? }?>
                             
                            </select></div>
<!--Campaign Name  -->

                        <div style="width: 20%; padding-right:1%; float: left; display:none">Campaign Name :
                            <select  multiple="multiple"  
                              id='campaign_name' name = "campaign_name[]" class="search form-control">
                              <!-- <option value="">Choose Campaign Name</option> -->
                           
                            </select></div>

                       <!-- date filter -->     
                        <div style="width: 20%; padding-right:1%; float: left;">Date :<input type="search" name="dates" id="dates" value="<?= $_REQUEST['dates'] ?>"
                            class="form-control form-control-sm search_1" placeholder="" style="width: 100%; "
                            aria-controls="table-1" /></div>
                             <!-- submit button -->
			<div style="width: 20%; padding-right:1%; float: left;">
                        <input type="submit" name="submit_1" id="submit_1" tabindex="10" value="Search"
                          class="btn btn-success " style="height:30px; margin-top: 20px;"></div>

                        
                         
                      </div>

                    </form>
                    <div class="table-responsive" id="id_business_summary_report" style="padding-top: 20px;">
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

  <script src="assets/multi-select/bootstrap.min.js"></script>
  <script src="assets/multi-select/bootstrap-multiselect.js"></script>
<script src="assets/multi-select/multiple-select.min.js"></script>
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
$(document).ready(function() {
  $('#campaign_name').multiselect({
    buttonWidth: '400px',
    includeSelectAllOption: true,
    onSelectAll: function(options) {
      // alert("!!");
    }
  });
});
    
function toggle(source) {
    var checkboxes = document.querySelectorAll('input[class="check_option"]:checked');
    // alert(checkboxes.length);
    // alert($('input[name=campaign_name]:checked').length);
    // alert($(this).find('input[name="campaign_name[]"]:checked').length);
  
    var ab = 0;
    for (var i = 0; i < checkboxes.length; i++) {
      // alert("@@"+checkboxes[i].checked+"@@"+source.checked);
      ab++;
        /* if (checkboxes[i] != source) { alert("~~");
          // ab--; 
            checkboxes[i].checked = source.checked;
            // alert("##");
        } */
    }

    if(checkboxes.length == 0) {
      $(".multiselect-selected-text").html("None selected");
    } else {
      $(".multiselect-selected-text").html(ab+" selected");
    }
}
function toggle1(source) {
  let isChecked = source.checked
  // alert(isChecked);

    var checkboxes = document.querySelectorAll('input[class="check_option"]');
    // alert(checkboxes.length);
    // alert($('input[name=campaign_name]:checked').length);
    // alert($(this).find('input[name="campaign_name[]"]:checked').length);
    /* if(checkboxes.length == 0) {
      $(".multiselect-selected-text").html("None selected");
    } else {
      $(".multiselect-selected-text").html(checkboxes.length+" selected");
    } */

    for (var i = 0; i < checkboxes.length; i++) {
      // alert("@@");
        //if (checkboxes[i] != source) { alert("~~");
            checkboxes[i].checked = source.checked;
            // alert("##");
        // }
    }
    toggle(source);
}

var previous_values_1;
var check_box_values = [];

// On loading the page, this function will call
    $(document).ready(function (e) {    
    var valueSelected = <?php echo $_SESSION['yjwatsp_user_master_id']; ?>;
  if (valueSelected === 2) {
    default_dept_filter(valueSelected);
  } else if (valueSelected === 3) {
    default_dept_filter(valueSelected);
  } else if (valueSelected === 4) {
    default_dept_filter(valueSelected);
  }
  campaign_name();
    business_summary_report() ;
    });

  
    function default_dept_filter(valueSelected) {
$.ajax({
  type: 'post',
  url: "ajax/display_functions.php?call_function=dept_filter&user_value=" + valueSelected,
  dataType: 'html',
  beforeSend: function () {
    $('.theme-loader').show();
  },
  complete: function () {
    $('.theme-loader').hide();
  },
  success: function (response) { // Success
    const array_userid = response.split("&");
    const myArray = array_userid[0].split("$");
          var array_response =[];
          for(i=0; i< myArray.length;i++){
              if(myArray[i] == ''){
            }else{
              array_response.push('<option' + (i == 0 ? ' value=""' : '') + ' value="' + array_userid[i] + '">' + myArray[i] + '</option>'); 
            } 
          }    
          $("#srch_1").html(array_response);   
    $('.theme-loader').hide();
  },
  error: function (response, status, error) {
    $('.theme-loader').hide();
   }
   
});
}

    <?php if ($_SESSION['yjwatsp_user_master_id'] != 3) { ?>
      var srch_1 = $('#srch_1').val();
      // alert(srch_1);
  url = "&srch_1=" + srch_1 + "";
<?php } else{?>
  url = '';
<?}
?>
    $("#submit_1").click(function (e) {
      e.preventDefault();
     <?php if ($_SESSION['yjwatsp_user_master_id'] != 3) { ?>
      var srch_1 = $('#srch_1').val();
  url = "&srch_1=" + srch_1 + "";
<?php } else{?>
  url = '';
<?}
?>
  $('input[name="campaign_name[]"]:checked').each(function() {
      check_box_values.push($(this).val().replaceAll(/\s/g,''));
  });
      // check_box_values = [];
      $('input[name="campaign_name[]"]').prop('checked', false);

      console.log(check_box_values);
    // var  previous_values_1 =  $('#campaign_name :selected').val();
    if(check_box_values != 'undefined' ){
      // check_box_values = [];
      console.log("business_summary_report");
        business_summary_report();
    } 
    });

    $('#srch1').on('change', function (e) {
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
    dept_filter_function();
function dept_filter_function() {
$.ajax({
  type: 'post',
  url: "ajax/display_functions.php?call_function=dept_filter&user_value=" + valueSelected,
  dataType: 'html',
  beforeSend: function () {
    $('.theme-loader').show();
  },
  complete: function () {
    $('.theme-loader').hide();
  },
  success: function (response) { // Success
    const array_userid = response.split("&");
    const myArray = array_userid[0].split("$");
          var array_response =[];
          for(i=0; i< myArray.length;i++){
              if(myArray[i] == ''){
            }else{
              array_response.push('<option' + (i == 0 ? ' value=""' : '') + '  value="' + array_userid[i] + '">' + myArray[i] + '</option>'); 
            } 
          }    
          $("#srch_1").html(array_response);   
    $('.theme-loader').hide();
  },
  error: function (response, status, error) {
    $('.theme-loader').hide();
   }
});
$('#srch_1').on('change', function (e) {
    var optionSelected = $("option:selected", this);
    var valueSelected1 = this.value;
srch1 = valueSelected; 
campaign_name(srch1);
})
}
});
  // business_summary_report func
  function business_summary_report() {
      console.log("calling2");
      var srch1 = $('#srch1').val();
      var srch_1 = $('#srch_1').val();
      var dates = $('#dates').val();

console.log(check_box_values)
console.log("ajax/display_functions.php?call_function=business_summary_report&srch1=" + srch1 + "&dates=" + dates + "&campaign_name_filter="+ check_box_values + url +"")
      $.ajax({
        type: 'post',
        url: "ajax/display_functions.php?call_function=business_summary_report&srch1=" + srch1 + "&dates=" + dates + "&campaign_name_filter="+ check_box_values + url +"",
        dataType: 'html',
        beforeSend: function () {
          $('.theme-loader').show();
        },
        complete: function () {
          $('.theme-loader').hide();
        },
        success: function (response) {
          $("#id_business_summary_report").html(response);
          $('.theme-loader').hide();
          check_box_values = [];
          $('input[name="campaign_name[]"]').prop('checked', false);
          // alert(check_box_values);
 $(".multiselect-selected-text").html("None selected");
        },
        error: function (response, status, error) { }
      });
    
      campaign_name(srch1);
  // e.preventDefault();  
    }
   
  // Every 1 min (60000), it will call
// campaign_name funtion
    function campaign_name(srch1) { 
      var dates = $('#dates').val(); 
      var srch1 = $('#srch1').val(); 
      <?php if ($_SESSION['yjwatsp_user_master_id'] != 3) { ?>
      var srch_1 = $('#srch_1').val();
  url = "&srch_1=" + srch_1 + "";
<?php } ?>
      $.ajax({
        type: 'post',
        url: "ajax/display_functions.php?call_function=camapign_name&srch1=" + srch1 + "&dates=" + dates +"&campaign_name_filter=" + check_box_values + url + "" ,
        dataType: 'html',
        beforeSend: function () {
          // $('.theme-loader').show();
        },
        complete: function () {
          // $('.theme-loader').hide();
        },
        success: function (response) {
          const myArray = response.split("$");
          var array_response =[];
          var check_box = ['<li class="multiselect-item multiselect-all"><a tabindex="0" class="multiselect-all"><label class="checkbox"><input type="checkbox" onclick="toggle1(this);" value="multiselect-all">  Select all</label></a></li>'];  
 // Looping the i is less than the myArray .if the condition is true to continue the process and to get the details.if the condition are false to stop the process
          for(i=0; i< myArray.length;i++){
              if(myArray[i] == '' || myArray[i] == '\n  '){
                
            }else{
              array_response.push('<option' + (i == 0 ? ' value=""' : '') + ' onclick="toggle(this);" class="check_option" value="' + myArray[i] + '">' + myArray[i] + '</option>'); 
              check_box.push('<li><a tabindex="0"><label class="checkbox"><input type="checkbox" onclick="toggle(this);" class="check_option" value="'+myArray[i]+'"> '+myArray[i]+'</label></a></li>')      
            } 
          }       
          $('.theme-loader').hide();
          $("#campaign_name").html(array_response);
          $(".multiselect-container").html(check_box);
          check_box_values = [];
          $('input[name="campaign_name[]"]').prop('checked', false);
          // alert(check_box_values);
 $(".multiselect-selected-text").html("None selected");
        },
        error: function (response, status, error) { }
      });
      // setInterval(function() {
        // window.location = 'business_summary_report';
            // document.getElementById('srch_1').value = "";
             //document.getElementById('srch1').value = "";
       //}, 60000);
      
    }
// date picker function adding
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
        campaign_name(srch_1);
        // e.preventDefault();
      });
      $('input[name="dates"]').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
      });

    });
// function adding to the filters
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



