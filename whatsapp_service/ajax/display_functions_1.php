<?php
session_start();//start session
error_reporting(E_ALL);// The error reporting function
// Include configuration.php
include_once('../api/configuration.php');
extract($_REQUEST);

$current_date = date("Y-m-d H:i:s");
$bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  

// Dashboard Page dashboard_count - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "dashboard_count") {
  site_log_generate("Dashboard Page : User : " . $_SESSION['yjwatsp_user_name'] . " access this page on " . date("Y-m-d H:i:s"), '../');
  $replace_txt = '{
    "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
  }';
  // To Get Api URL
  $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $api_url.'/dashboard/dashboard',
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

$response = curl_exec($curl);

curl_close($curl);
// echo $response;
$state1 = json_decode($response, false);
// echo print_r($state1); 
site_log_generate("Dashboard Page : " . $_SESSION['yjwatsp_user_name'] . " Execute the service [$replace_txt,$bearer_token] on " . date("Y-m-d H:i:s"), '../');
site_log_generate("Dashboard Page : ".$_SESSION['yjwatsp_user_name']." get the Service response [$response] on ".date("Y-m-d H:i:s"), '../');
$total_msg = 0;
$total_success = 0;
$total_failed = 0;
$total_invalid = 0;
$total_waiting = 0;
// To get the one by one data
if ($state1->response_code == 1) {
  for($indicator = 0; $indicator < count($state1->report); $indicator++){ 
    $header_title 			= $state1->report[$indicator]->header_title;
    $user_id 				= $state1->report[$indicator]->user_id;
    $user_master_id	= $state1->report[$indicator]->user_master_id;
    $user_name 			= $state1->report[$indicator]->user_name;
    $api_key 				= $state1->report[$indicator]->api_key;
    $available_messages = $state1->report[$indicator]->available_messages;
    $total_msg = $state1->report[$indicator]->total_msg;
    $total_success 			= $state1->report[$indicator]->total_success;
    $total_failed 		= $state1->report[$indicator]->total_failed ;
    $total_invalid 		= $state1->report[$indicator]->total_invalid;
    $total_waiting	= $state1->report[$indicator]->total_waiting;
  if ($user_id == $_SESSION['yjwatsp_user_id']) { ?>
    <div class="col-lg-12 col-md-12 col-sm-12">
<? } else { ?>
    <div class="col-lg-6 col-md-6 col-sm-12">
<? } ?>
  <div class="card card-statistic-2">
    <div class="card-stats">
      <div class="card-stats-title mb-2"><?= strtoupper($user_name) ?> - <?= $header_title ?> Summary
      </div>
      <div class="card-stats-items" style="margin: 10px 0 20px 0;">
        <div class="card-stats-item">
          <div class="card-stats-item-count"><?= $total_waiting ?></div>
          <div class="card-stats-item-label">In processing</div>
        </div>
        <div class="card-stats-item">
          <div class="card-stats-item-count"><?= ($total_failed + $total_invalid) ?></div>
          <div class="card-stats-item-label">Failed</div>
        </div>
        <div class="card-stats-item">
          <div class="card-stats-item-count"><?= $total_success ?></div>
          <div class="card-stats-item-label">Delivered</div>
        </div>

        <div class="card-stats-item">
          <div class="card-stats-item-count"><?= $available_messages ?></div>
          <div class="card-stats-item-label">Available Credits</div>
        </div>
        <div class="card-stats-item">
          <div class="card-stats-item-count"><?= $total_msg ?></div>
          <div class="card-stats-item-label">Total Messages</div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
  }
  site_log_generate("Index Page : ".$uname." logged in success on ".date("Y-m-d H:i:s"), '../');
  $json = array("status" => 1, "info" => $result);
  site_log_generate("Dashboard Page : User : " . $response . " Preview on " . date("Y-m-d H:i:s"), '../');
}
else {
  
  if ($user_id == $_SESSION['yjwatsp_user_id']) { 
    // for($indicator = 0; $indicator < count($state1->report); $indicator++){ 
    //   $available_messages = $state1->report[$indicator]->available_messages;
      ?>
      <div class="col-lg-12 col-md-12 col-sm-12">
  <? } else { ?>
      <div class="col-lg-6 col-md-6 col-sm-12">
  <? } ?>
    <div class="card card-statistic-2">
      <div class="card-stats">
        <div class="card-stats-title mb-2"><?= strtoupper($_SESSION['yjwatsp_user_name']) ?> - Whatsapp OTP Summary
        </div>
        <div class="card-stats-items" style="margin: 10px 0 20px 0;">
          <div class="card-stats-item">
            <div class="card-stats-item-count">0</div>
            <div class="card-stats-item-label">In processing</div>
          </div>
          <div class="card-stats-item">
            <div class="card-stats-item-count">0</div>
            <div class="card-stats-item-label">Failed</div>
          </div>
          <div class="card-stats-item">
            <div class="card-stats-item-count">0</div>
            <div class="card-stats-item-label">Delivered</div>
          </div>

          <div class="card-stats-item">
            <div class="card-stats-item-count"><?= $available_messages ?></div>
            <div class="card-stats-item-label">Available Credits</div>
          </div>
          <div class="card-stats-item">
            <div class="card-stats-item-count">0</div>
            <div class="card-stats-item-label">Total Messages</div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?
}
}
// }
// Dashboard Page dashboard_count - End

// template_list Page template_list - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "template_list") {
  site_log_generate("Template List Page : User : " . $_SESSION['yjwatsp_user_name'] . " Preview on " . date("Y-m-d H:i:s"), '../');
  ?>
    <table class="table table-striped text-center" id="table-1">
      <thead>
        <tr class="text-center">
          <th>#</th>
          <th>User</th>
          <th>Template Name</th>
          <th>Template Category</th>
          <th>Template Details</th>
          <th>Sender ID</th>
          <th>Status</th>
          <th>Entry Date</th>
          <th>Approved Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?
     $replace_txt = '{
      "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
    }';
      // To Get Api URL
      $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
      $curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $api_url.'/list/p_template_list',
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

$response = curl_exec($curl);

curl_close($curl);
// echo $response;
      site_log_generate("Template List Page : " . $_SESSION['yjwatsp_user_name'] . " Execute the service [$replace_txt,$bearer_token] on " . date("Y-m-d H:i:s"), '../');
      $sms = json_decode($response, false);
      site_log_generate("Template List Page : " . $_SESSION['yjwatsp_user_name'] . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');

      $indicatori = 0;
      // To get the one by one data
      if ($sms->num_of_rows > 0) {
        for ($indicator = 0; $indicator < $sms->num_of_rows; $indicator++) {
          $indicatori++;
          $approve_date = '-';

	  if ($sms->templates[$indicator]->template_entdate != ''and $sms->templates[$indicator]->template_entdate != '00-00-0000 12:00:00 AM') {
            $entry_date = date('d-m-Y h:i:s A', strtotime($sms->templates[$indicator]->template_entdate));
          }
          if ($sms->templates[$indicator]->approve_date != '' and $sms->templates[$indicator]->approve_date != '0000-00-00 00:00:00' and $sms->templates[$indicator]->approve_date != '00-00-0000 12:00:00 AM') {
            $approve_date = date('d-m-Y h:i:s A', strtotime($sms->templates[$indicator]->approve_date));
          }

          $wht_tmpl_url = $whatsapp_tmplate_url . $sms->templates[$indicator]->whatsapp_business_acc_id;
          $wht_bearer_token = $sms->templates[$indicator]->bearer_token;
          ?>
            <tr>
              <td><?= $indicatori ?></td>
              <td class="text-left no-wrap"><?= $sms->templates[$indicator]->receiver_username; ?></td>
              <td class="text-left"><?= $sms->templates[$indicator]->template_name; ?></td>
              <td class="text-left"><?= $sms->templates[$indicator]->template_category ?></td>
              <td class="text-left" id='id_display_template_<?= $indicatori ?>'>
                <?= $sms->templates[$indicator]->template_message ?>
              </td>
              <td><?= $sms->templates[$indicator]->country_code . $sms->templates[$indicator]->mobile_no ?></td>
              <td id='id_template_status_<?= $indicatori ?>'>
                <? if ($sms->templates[$indicator]->template_status == 'Y') { ?><a href="#!" class="btn btn-outline-success btn-disabled">Approved</a><? } elseif ($sms->templates[$indicator]->template_status == 'N') { ?><a href="#!" class="btn btn-outline-warning btn-disabled">Inactive</a><? } elseif ($sms->templates[$indicator]->template_status == 'R') { ?><a href="#!" class="btn btn-outline-danger btn-disabled">Rejected</a><? } elseif ($sms->templates[$indicator]->template_status == 'F') { ?><a href="#!" class="btn btn-outline-dark btn-disabled">Failed</a><? } elseif ($sms->templates[$indicator]->template_status == 'D') { ?><a href="#!" class="btn btn-outline-danger btn-disabled">Deleted</a><? } elseif ($sms->templates[$indicator]->template_status == 'S') { ?><a href="#!" class="btn btn-outline-info btn-disabled">Waiting</a><? } ?>
              </td>
              <td><?= $entry_date ?></td>
              <td><?= $approve_date ?></td>
	      <td><a href="#!" onclick="call_getsingletemplate('<?= $sms->templates[$indicator]->template_name ?>!<?= $sms->templates[$indicator]->language_code ?>', '<?= $wht_tmpl_url ?>', '<?= $wht_bearer_token ?>', '<?= $indicatori ?>')">View</a> <? if ($sms->templates[$indicator]->template_response_id != '-' and $sms->templates[$indicator]->template_status != 'D') { ?>/ <a href="#!" onclick="remove_template('<?= $sms->templates[$indicator]->unique_template_id ?>', '<?= $indicatori ?>')">Delete</a><? } ?></td>
            </tr>
          <?
        }
      }
      ?>
      </tbody>
    </table>

    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/dataTables.searchPanes.min.js"></script>
    <script src="assets/js/dataTables.select.min.js"></script>
    <script src="assets/js/jszip.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/buttons.html5.min.js"></script>
    <script src="assets/js/buttons.colVis.min.js"></script>
<!-- filter using -->
    <script>
    $('#table-1').DataTable( {
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
        },
        {
          targets: -6,
          visible: false
        }
        ]
    } );
    </script>
  <?
}
// template_list Page template_list - End

// whatsapp_no_api_list Page whatsapp_no_api_list - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "whatsapp_no_api_list") {
  site_log_generate("Manage Sender ID List Page : User : " . $_SESSION['yjwatsp_user_name'] . " Preview on " . date("Y-m-d H:i:s"), '../');
  ?>
    <table class="table table-striped text-center" id="table-1">
      <thead>
        <tr class="text-center">
          <th>#</th>
          <th>User</th>
          <th>Mobile No</th>
          <th>Profile Details</th>
          <th>Available Meta Credits</th>
          <th>Used Credits</th>
          <th>Status</th>
          <th>Entry Date</th>
          <th>Approved Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?
       $replace_txt = '{
        "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
      }';
      // To Get Api URL
      $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL =>$api_url . '/list/sender_id_list',
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
      
      $response = curl_exec($curl);
      site_log_generate("Manage Sender ID List Page : " . $uname . " Execute the service [$replace_txt,$bearer_token] on " . date("Y-m-d H:i:s"), '../');
     
      curl_close($curl);
      // echo $response;
      $sms = json_decode($response, false);
      site_log_generate("Manage Sender ID List Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');
// To get the one by one data
      $indicatori = 0;
      if ($sms->num_of_rows > 0) {
        for ($indicator = 0; $indicator < $sms->num_of_rows; $indicator++) {
          $indicatori++;
          $entry_date = date('d-m-Y h:i:s A', strtotime($sms->sender_id[$indicator]->whatspp_config_entdate));
          if ($sms->sender_id[$indicator]->whatspp_config_apprdate != '' and $sms->sender_id[$indicator]->whatspp_config_apprdate != '0000-00-00 00:00:00') {
            $approved_date = date('d-m-Y h:i:s A', strtotime($sms->sender_id[$indicator]->whatspp_config_apprdate));
          }
          ?>
          <tr>
            <td><?= $indicatori ?></td>
            <td><?= strtoupper($sms->sender_id[$indicator]->user_name) ?></td>
            <td><?= $sms->sender_id[$indicator]->country_code . $sms->sender_id[$indicator]->mobile_no ?></td>
            <td>
              <? echo $sms->sender_id[$indicator]->wht_display_name . "<br>";
              if ($sms->sender_id[$indicator]->wht_display_logo != '') {
                echo "<img src='uploads/whatsapp_images/" . $sms->sender_id[$indicator]->wht_display_logo . "' style='width:100px; max-height: 200px;'>";
              } ?>
            </td>
            <td><b><? if ($sms->sender_id[$indicator]->whatspp_config_status == 'Y') {
              echo ($sms->sender_id[$indicator]->available_credit - $sms->sender_id[$indicator]->sent_count);
            } else {
              echo "0";
            } ?></b></td>
            <td><b><? if ($sms->sender_id[$indicator]->whatspp_config_status == 'Y') {
              echo $sms->sender_id[$indicator]->sent_count;
            } else {
              echo "0";
            } ?></b></td>
            <td>
              <? if ($sms->sender_id[$indicator]->whatspp_config_status == 'Y') { ?><a href="#!" class="btn btn-outline-success btn-disabled">Active</a><? } elseif ($sms->sender_id[$indicator]->whatspp_config_status == 'D') { ?><a href="#!" class="btn btn-outline-danger btn-disabled">Deleted</a><? } elseif ($sms->sender_id[$indicator]->whatspp_config_status == 'B') { ?><a href="#!" class="btn btn-outline-dark btn-disabled">Blocked</a><? } elseif ($sms->sender_id[$indicator]->whatspp_config_status == 'N') { ?><a href="#!" class="btn btn-outline-danger btn-disabled">Inactive</a><? } elseif ($sms->sender_id[$indicator]->whatspp_config_status == 'M') { ?><a href="#!" class="btn btn-outline-danger btn-disabled">Mobile No Mismatch</a><? } elseif ($sms->sender_id[$indicator]->whatspp_config_status == 'I') { ?><a href="#!" class="btn btn-outline-warning btn-disabled">Invalid</a><? } elseif ($sms->sender_id[$indicator]->whatspp_config_status == 'P') { ?><a href="#!" class="btn btn-outline-info btn-disabled">Processing</a><? } elseif ($sms->sender_id[$indicator]->whatspp_config_status == 'R') { ?><a href="#!" class="btn btn-outline-danger btn-disabled">Rejected</a><? } elseif ($sms->sender_id[$indicator]->whatspp_config_status == 'X') { ?><a href="#!" class="btn btn-outline-primary btn-disabled">Need Rescan</a><? } elseif ($sms->sender_id[$indicator]->whatspp_config_status == 'L') { ?><a href="#!" class="btn btn-outline-info btn-disabled">Linked</a><? } elseif ($sms->sender_id[$indicator]->whatspp_config_status == 'U') { ?><a href="#!" class="btn btn-outline-warning btn-disabled">Unlinked</a><? } ?>
            </td>
            <td><?= $entry_date ?></td>
            <td><?= $approved_date ?></td>
            <td id='id_approved_lineno_<?= $indicatori ?>'>
              <? if ($sms->sender_id[$indicator]->whatspp_config_status != 'D') { ?>
                  <button type="button" title="Delete Sender ID" onclick="remove_senderid('<?= $sms->sender_id[$indicator]->whatspp_config_id ?>', 'D', '<?= $indicatori ?>')" class="btn btn-icon btn-danger" style="padding: 0.3rem 0.41rem !important;">Delete</button>
              <? } else { ?>
                  <a href="#!" class="btn btn-outline-light btn-disabled" style="padding: 0.3rem 0.41rem !important;cursor: not-allowed;">Delete</a>
              <? } ?>
            </td>
          </tr>
        <?
        }
      }
      ?>
      </tbody>
    </table>

    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/dataTables.searchPanes.min.js"></script>
    <script src="assets/js/dataTables.select.min.js"></script>
    <script src="assets/js/jszip.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/buttons.html5.min.js"></script>
    <script src="assets/js/buttons.colVis.min.js"></script>
<!-- filter using -->
    <script>
    $('#table-1').DataTable( {
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
    } );
    </script>
  <?
}
// whatsapp_no_api_list Page whatsapp_no_api_list - End

// approve_whatsapp_no_api Page approve_whatsapp_no_api - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "approve_whatsapp_no_api") {
  site_log_generate("Approve Sender ID List Page : User : " . $_SESSION['yjwatsp_user_name'] . " Preview on " . date("Y-m-d H:i:s"), '../');
  ?>
    <table class="table table-striped" id="table-1">
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Mobile No</th>
          <th>Phone No ID</th>
          <th>Business Account ID</th>
          <th>Bearer Token</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?
$replace_txt = '{
  "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
}';
// To Get Api URL
$bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL =>$api_url . '/list/approve_whatsapp_no_api',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $replace_txt,
  CURLOPT_HTTPHEADER => array(
    $bearer_token,
    'Content-Type: application/json'
  ),
));
$response = curl_exec($curl);
      site_log_generate("Approve Sender ID Page : " . $uname . " Execute the service [$replace_txt,$bearer_token] on " . date("Y-m-d H:i:s"), '../');
      curl_close($curl);
      // echo $response;
      $sms = json_decode($response, false);
      site_log_generate("Approve Sender ID Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');
// To get the one by one data
      $indicatori = 0;
      if ($sms->num_of_rows > 0) {
        for ($indicator = 0; $indicator < $sms->num_of_rows; $indicator++) {
          $indicatori++;
          ?>
          <tr>
            <td><?= $indicatori ?></td>
            <td><?= $sms->report[$indicator]->user_name ?></td>
            <td><?= $mobile_number = $sms->report[$indicator]->country_code . $sms->report[$indicator]->mobile_no ?></td>

            <td><input type='text' autofocus  id="phone_number_id_<?= $indicatori ?>" name="phone_number_id_<?= $indicatori ?>" value="<?= $sms->report[$indicator]->phone_number_id ?>" placeholder="Phone No ID" maxlength="20" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" ></td>

            <td><input type='text'  id="whatsapp_business_acc_id_<?= $indicatori ?>" name="whatsapp_business_acc_id_<?= $indicatori ?>" value="<?= $sms->report[$indicator]->whatsapp_business_acc_id ?>" placeholder="Business Account ID" maxlength="20" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" ></td>

            <td><input type='text' id="bearer_token_value_<?= $indicatori ?>" name="bearer_token_value_<?= $indicatori ?>" value="<?= $sms->report[$indicator]->bearer_token ?>" placeholder="Bearer Token" maxlength="300" style="text-transform: uppercase;"></td>

            <td>
              <?
              switch ($sms->report[$indicator]->whatspp_config_status) {
                case 'N':
                  ?><a href="#!" class="btn btn-outline-primary btn-disabled">New</a><?
                  break;

                case 'L':
                  ?><a href="#!" class="btn btn-outline-info btn-disabled">Whatsapp Linked</a><?
                  break;
                case 'U':
                  ?><a href="#!" class="btn btn-outline-warning btn-disabled">Whatsapp Unlinked</a><?
                  break;
                case 'X':
                  ?><a href="#!" class="btn btn-outline-primary btn-disabled">Rescan</a><?
                  break;

                case 'Y':
                  ?><a href="#!" class="btn btn-outline-success btn-disabled">Super Admin Approved</a><?
                  break;
                case 'R':
                  ?><a href="#!" class="btn btn-outline-danger btn-disabled">Super Admin Rejected</a><?
                  break;

                default:
                  ?><a href="#!" class="btn btn-outline-dark btn-disabled">Invalid</a><?
                  break;
              } ?>
            </td>
            <td style="text-align:center;" id='id_approved_lineno_<?= $indicatori ?>'>
            <div class="btn-group mb-3" role="group" aria-label="Basic example">
              <button type="button" title="Approve" onclick="func_save_phbabt('<?= $sms->report[$indicator]->whatspp_config_id ?>', 'Y', '<?= $indicatori ?>','phone_number_id','whatsapp_business_acc_id','bearer_token_value',<?= $mobile_number?>)" class="btn btn-icon btn-success"><i class="fas fa-check"></i></button>
              <button type="button" title="Reject" onclick="change_status('<?= $sms->report[$indicator]->whatspp_config_id ?>', 'R', '<?= $indicatori ?>')" class="btn btn-icon btn-danger"><i class="fas fa-times"></i></button>
            </div>
            </td>
          </tr>
        <?
        }
      }
      ?>
      </tbody>
    </table>

    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/dataTables.searchPanes.min.js"></script>
    <script src="assets/js/dataTables.select.min.js"></script>
    <script src="assets/js/jszip.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/buttons.html5.min.js"></script>
    <script src="assets/js/buttons.colVis.min.js"></script>
<!-- filter using -->
    <script>
    $('#table-1').DataTable( {
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
    } );
    </script>
  <?
}
// approve_whatsapp_no_api Page approve_whatsapp_no_api - End

// template_whatsapp_list Page template_whatsapp_list - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "template_whatsapp_list") {
  site_log_generate("Template Whatsapp List Page : User : " . $_SESSION['yjwatsp_user_name'] . " Preview on " . date("Y-m-d H:i:s"), '../');

  ?>
    <table class="table table-striped text-center" id="table-1">
      <thead>
        <tr>
          <th>#</th>
          <? /* <th>User</th> */ ?>
          <th>Campaign</th>
          <th>Count</th>
          <th>Mobile No</th>
          <th>Status</th>
          <th>Delivery Status</th>
          <th>Read Status</th>
        </tr>
      </thead>
      <tbody>
      <?
      $replace_txt = '{
        "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
      }';
      // To Get Api URL
      $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL =>$api_url . '/list/get_sent_messages_status_list',
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
      $response = curl_exec($curl);

      site_log_generate("Template Whatsapp List Page : " . $uname . " Execute the service [$replace_txt,$bearer_token] on " . date("Y-m-d H:i:s"), '../');
      curl_close($curl);
      $sms = json_decode($response, false);
      site_log_generate("Template Whatsapp List Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');
     // To get the one by one data
      $increment = 0;
      if ($sms->num_of_rows > 0) {
        for ($indicator = 0; $indicator < $sms->num_of_rows; $indicator++) {
          $increment++;

          $compose_whatsapp_id = $sms->report[$indicator]->compose_whatsapp_id;
          $user_id = $sms->report[$indicator]->user_id;
          $user_name = $sms->report[$indicator]->user_name;
          $campaign_name = $sms->report[$indicator]->campaign_name;
          $whatsapp_content = $sms->report[$indicator]->whatsapp_content;

          $message_type = $sms->report[$indicator]->message_type;
          $total_mobileno_count = $sms->report[$indicator]->total_mobileno_count;
          $content_char_count = $sms->report[$indicator]->content_char_count;
          $content_message_count = $sms->report[$indicator]->content_message_count;
          $mobile_no = $sms->report[$indicator]->country_code . $sms->report[$indicator]->mobile_no;

          $sender = $sms->report[$indicator]->sender;
          $send_date = date('d-m-Y h:i:s A', strtotime($sms->report[$indicator]->comwtap_entry_date));
          if ($sms->report[$indicator]->response_date != '') {
            $response_date = date('d-m-Y h:i:s A', strtotime($sms->report[$indicator]->response_date));
          } else {
            $response_date = '';
          }

          if ($sms->report[$indicator]->delivery_date != '') {
            $delivery_date = date('d-m-Y h:i:s A', strtotime($sms->report[$indicator]->delivery_date));
          } else {
            $delivery_date = '';
          }

          if ($sms->report[$indicator]->read_date != '') {
            $read_date = date('d-m-Y h:i:s A', strtotime($sms->report[$indicator]->read_date));
          } else {
            $read_date = '';
          }

          $response_status = $sms->report[$indicator]->response_status;
          $response_message = $sms->report[$indicator]->response_message;
          $response_id = $sms->report[$indicator]->response_id;
          $delivery_status = $sms->report[$indicator]->delivery_status;
          $read_status = $sms->report[$indicator]->read_status;

          $disp_stat = '';
          switch ($response_status) {
            case 'S':
              $disp_stat = '<div class="badge badge-success">SENT</div>';
              break;
            case 'F':
              $disp_stat = '<div class="badge badge-danger">FAILED</div>';
              break;
            case 'I':
              $disp_stat = '<div class="badge badge-warning">INVALID</div>';
              break;

            default:
              $disp_stat = '<div class="badge badge-info">YET TO SEND</div>';
              break;
          }

          $disp_stat1 = '';
          switch ($delivery_status) {
            case 'Y':
              $disp_stat1 = '<div class="badge badge-success">DELIVERED</div>';
              break;

            default:
              $disp_stat1 = '<div class="badge badge-danger">NOT DELIVERED</div>';
              break;
          }

          $disp_stat2 = '';
          switch ($read_status) {
            case 'Y':
              $disp_stat2 = '<div class="badge badge-success">READ</div>';
              break;

            default:
              $disp_stat2 = '<div class="badge badge-danger">NOT READ</div>';
              break;
          }
          ?>
          <tr>
              <td><?= $increment ?></td>
              <? /* <td><?= $user_name ?></td> */ ?>
              <td><?= $campaign_name ?></td>
              <td>Total Mobile No : <?= $total_mobileno_count ?><? /* <br>Total Messages : <?= $content_message_count */ ?></td>
              <td class="text-left" style='width: 180px !important;'>
                <div><div style='float: left'>Sender : </div><div style='float: right; width: 100px; margin-right: 15px;'><a href="#!" class="btn btn-outline-primary btn-disabled" style='width: 140px;'><?= $sender ?></a></div></div>
                <div style='clear: both;'><div style='float: left'>Receiver : </div><div style='float: right;  width:100px; margin-right: 15px;'><a href="#!" class="btn btn-outline-success btn-disabled" style='width: 140px;'><?= $mobile_no ?></a></div></div>
              </td>
              <td><?= $response_date . "<br>" . $disp_stat ?></td>
              <td><?= $delivery_date . "<br>" . $disp_stat1 ?></td>
              <td><?= $read_date . "<br>" . $disp_stat2 ?></td>
          </tr>
        <?
        }
      } 
      ?>
      </tbody>
    </table>

    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/dataTables.searchPanes.min.js"></script>
    <script src="assets/js/dataTables.select.min.js"></script>
    <script src="assets/js/jszip.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/buttons.html5.min.js"></script>
    <script src="assets/js/buttons.colVis.min.js"></script>
<!-- filter using -->
    <script>
    $('#table-1').DataTable( {
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
    } );
    </script>
  <?
}
// template_whatsapp_list Page template_whatsapp_list - End

// message_credit_list Page message_credit_list - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "message_credit_list") {
  site_log_generate("Message Credit List Page : User : " . $_SESSION['yjwatsp_user_name'] . " Preview on " . date("Y-m-d H:i:s"), '../');
  ?>
    <table class="table table-striped" id="table-1">
      <thead>
        <tr>
          <th>#</th>
          <th>Parent User</th>
          <th>Receiver User</th>
          <th>Message Count</th>
          <th>Details</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
      <?
          $replace_txt = '{
            "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
          }';
          // To Get Api URL
          $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL =>$api_url . '/list/message_credit_list',
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
          $response = curl_exec($curl);
      site_log_generate("Message Credit List Page : " . $uname . " Execute the service [$replace_txt,$bearer_token] on " . date("Y-m-d H:i:s"), '../');
      curl_close($curl);
      $sms = json_decode($response, false);
      site_log_generate("Message Credit List Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');
// To get the one by one data
      $indicatori = 0;
      if ($sms->num_of_rows > 0) {
        for ($indicator = 0; $indicator < $sms->num_of_rows; $indicator++) {
          $indicatori++;
          $entry_date = date('d-m-Y h:i:s A', strtotime($sms->report[$indicator]->message_credit_log_entdate));
          ?>
          <tr>
            <td><?= $indicatori ?></td>
            <td><?= $sms->report[$indicator]->parntname ?></td>
            <td><?= $sms->report[$indicator]->usrname ?></td>
            <td><?= $sms->report[$indicator]->provided_message_count ?></td>
            <td><?= $sms->report[$indicator]->message_comments ?></td>
            <td><?= $entry_date ?></td>
          </tr>
        <?
        }
      }
      ?>
      </tbody>
    </table>

    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/dataTables.searchPanes.min.js"></script>
    <script src="assets/js/dataTables.select.min.js"></script>
    <script src="assets/js/jszip.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/buttons.html5.min.js"></script>
    <script src="assets/js/buttons.colVis.min.js"></script>
<!-- filter using -->
    <script>
    $('#table-1').DataTable( {
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
  <?
}
// message_credit_list Page message_credit_list - End

// manage_users_list Page manage_users_list - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "manage_users_list") {
  site_log_generate("Manage Users List Page : User : " . $_SESSION['yjwatsp_user_name'] . " Preview on " . date("Y-m-d H:i:s"), '../');
  ?>
    <table class="table table-striped" id="table-1">
      <thead>
        <tr>
          <th>#</th>
          <th>Parent</th>
          <th>User</th>
          <th>Login</th>
          <th>User Title</th>
          <th>Contact Details</th>
          <th>Status</th>
          <? /* if($_SESSION['yjwatsp_user_master_id'] == 1) { ?>
           <th>Action</th>
           <? } */?>
        </tr>
      </thead>
      <tbody>
      <?
        $replace_txt = '{
          "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
        }';
        // To Get Api URL
        $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL =>$api_url . '/list/manage_users',
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
        $response = curl_exec($curl);
      site_log_generate("Manage Users List Page : " . $uname . " Execute the service [$replace_txt,$bearer_token] on " . date("Y-m-d H:i:s"), '../');
      curl_close($curl);
      $sms = json_decode($response, false);
      site_log_generate("Manage Users List Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');
// To get the one by one data
      $indicatori = 0;
      if ($sms->num_of_rows > 0) {
        for ($indicator = 0; $indicator < $sms->num_of_rows; $indicator++) {
          $indicatori++;
          $entry_date = date('d-m-Y h:i:s A', strtotime($sms->report[$indicator]->usr_mgt_entry_date));
          ?>
          <tr>
            <td><?= $indicatori ?></td>
            <td><?= $sms->report[$indicator]->parent_name ?></td>
            <td><?= $sms->report[$indicator]->user_name ?></td>
            <td><?= $sms->report[$indicator]->login_id ?></td>
            <td><?= $sms->report[$indicator]->user_title ?></td>
            <td>Mobile : <?= $sms->report[$indicator]->user_mobile ?><br>Email : <?= $sms->report[$indicator]->user_email ?></td>
            <td>
              <? if ($sms->report[$indicator]->usr_mgt_status == 'Y') { ?><div class="badge badge-success">Active</div><? } elseif ($sms->report[$indicator]->usr_mgt_status == 'N') { ?><div class="badge badge-danger">Inactive</div><? } ?>
              <br><?= $entry_date ?>
            </td>
            <? /* if($_SESSION['yjwatsp_user_master_id'] == 1) { ?>
              <td>
              <a href="user_repository?action=viewrep&usr=<?=$sms->report[$indicator]->user_id?>">View Repository</a>
              </td>
              <? } */?>
          </tr>
        <?
        }
      }
      ?>
      </tbody>
    </table>

    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/dataTables.searchPanes.min.js"></script>
    <script src="assets/js/dataTables.select.min.js"></script>
    <script src="assets/js/jszip.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/buttons.html5.min.js"></script>
    <script src="assets/js/buttons.colVis.min.js"></script>
<!-- filter  using-->
    <script>
    $('#table-1').DataTable( {
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
    } );
    </script>
  <?
}
// manage_users_list Page manage_users_list - End

// messenger_responses Page messenger_responses - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "messenger_responses") {
  site_log_generate("Messenger Response List Page : User : " . $_SESSION['yjwatsp_user_name'] . " Preview on " . date("Y-m-d H:i:s"), '../');
  ?>
    <table class="table table-striped text-center" id="table-1">
      <thead>
        <tr class="text-center">
          <th>#</th>
          <th>Username</th>
          <th>Sender</th>
          <th>Receiver</th>
          <th>Reference ID</th>
          <th>Message Type</th>
          <th>Status</th>
          <th>Entry Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?
           $replace_txt = '{
            "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
          }';
          // To Get Api URL
          $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL =>$api_url . '/report/messenger_response_list',
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
          $response = curl_exec($curl);
        curl_close($curl);
// echo  $response;
        $sms = json_decode($response, false);
        site_log_generate("Messenger Response List Page : User : " . $_SESSION['yjwatsp_user_name'] . " executed the Query reponse ($response) on " . date("Y-m-d H:i:s"));
// To get the one by one data
        $indicatori = 0;
	if ($sms->response_status == 200) {
          for ($indicator = 0; $indicator < count($sms->report); $indicator++) {
            $indicatori++;
            $entry_date = date('d-m-Y h:i:s A', strtotime($sms->report[$indicator]->message_rec_date));
            $tr_bg_clr = "";
            $td_text_clr = " font-weight: bold;";
            $stat_view = 'Read ';
            if ($sms->report[$indicator]->message_is_read == 'N') {
              $tr_bg_clr = "background-color: #5bd4672b";
              $td_text_clr = "color: #00391b; font-weight: bold;";
              $stat_view = 'Unread ';
            }
            ?>
            <tr style="<?= $tr_bg_clr ?>">
              <td>
                <?= $indicatori ?>
              </td>
              <td><?= $sms->report[$indicator]->user_name ?></td>
              <td><?= $sms->report[$indicator]->message_from ?></td>
              <td><?= $sms->report[$indicator]->message_to ?></td>
              <td class="text-left">
                <? echo $string = (strlen($sms->report[$indicator]->message_resp_id) > 23) ? substr($sms->report[$indicator]->message_resp_id, 0, 20) . '...' : $sms->report[$indicator]->message_resp_id; ?>
              </td>
              <td><?= strtoupper($sms->report[$indicator]->message_type) ?></td>
              <td>
                <? if ($sms->report[$indicator]->message_status == 'Y') { ?><a href="#!"
                      class="btn btn-outline-success btn-disabled">Active</a>
                <? } elseif ($sms->report[$indicator]->message_status == 'N') { ?><a href="#!"
                      class="btn btn-outline-danger btn-disabled">Inactive</a>
                <? } ?>
              </td>
              <td>
                <?= $entry_date ?>
              </td>
              <td><a href="#!" style="<?= $td_text_clr ?>"
                  onclick="func_view_response('<?= $sms->report[$indicator]->message_id ?>', '<?= $sms->report[$indicator]->message_from ?>', '<?= $sms->report[$indicator]->message_to ?>')"><?= $stat_view ?>View</a>
              </td>
            </tr>
          <?
          }
        }
        ?>
      </tbody>
    </table>

    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/dataTables.searchPanes.min.js"></script>
    <script src="assets/js/dataTables.select.min.js"></script>
    <script src="assets/js/jszip.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/buttons.html5.min.js"></script>
    <script src="assets/js/buttons.colVis.min.js"></script>
<!-- filter using -->
    <script>
    $('#table-1').DataTable( {
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
    } );
    </script>
  <?
}
// messenger_responses Page messenger_responses - End

// business_summary_report Page business_summary_report - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "business_summary_report") {
  site_log_generate("Business Summary Report Page : User : " . $_SESSION['yjwatsp_user_name'] . " Preview on " . date("Y-m-d H:i:s"), '../');
  ?>
    
    <table class="table table-striped" id="table-1">
      <thead>
        <tr>
          <th>#</th>
          <th>Date</th>
          <th>User</th>
          <th>Department</th>
          <th>Credits</th>
          <th>Total Pushed</th>
          <th>Success</th>
          <th>Delivered</th>
          <th>Read</th>
          <th>Failed</th>
          <th>In processing</th>
        </tr>
      </thead>
      <tbody> 
        <?
        $srch1 = $_REQUEST['srch1'];
        $srch_1 = $_REQUEST['srch_1'];

        if ($_REQUEST['dates']) {
          $date = $_REQUEST['dates'];
        } else {
          $date = date('m/d/Y') . "-" . date('m/d/Y'); // 01/28/2023 - 02/27/2023 
        }

        $td = explode('-', $date);
        $thismonth_startdate = date("Y/m/d", strtotime($td[0]));
        $thismonth_today = date("Y/m/d", strtotime($td[1]));
       
        $replace_txt = '{
          "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '",';

        if ($srch1) {
          $replace_txt .= '"filter_user" : "' . $srch1 . '",';
        }

        if ($srch_1) {
          $replace_txt .= '"filter_department" : "' . $srch_1 . '",';
        }
    
        if ($date) {         
          $replace_txt .= '"filter_date" : "' . $thismonth_startdate . ' - ' . $thismonth_today . '",';
        }
        if ($campaign_name_filter) {         
          $replace_txt .= '"campaign_name" : "' .$campaign_name_filter.'",';
        }
        
        $replace_txt = rtrim($replace_txt, ",");
        $replace_txt .= '}';

    if($campaign_name_filter ){  
      // To Get Api URL
      $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL =>$api_url . '/report/report_campaign_name',
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
      }
      else{
        // To Get Api URL
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL =>$api_url . '/report/summary_report',
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
      }
        site_log_generate("Business Summary Report Page : " . $uname . " Execute the service [$replace_txt,$bearer_token] on " . date("Y-m-d H:i:s"), '../');
        $response = curl_exec($curl);
        // echo $response;summary
        curl_close($curl);
        $sms = json_decode($response, false);
        site_log_generate("Business Summary Report Page  : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');
// To get the one by one data
        $indicatori = 0;
        if ($sms->report) {
         for ($indicator = 0; $indicator < count($sms->report); $indicator++) {
            $indicatori++; ?>
            
              <?php
            $entry_date = date('d-m-Y', strtotime($sms->report[$indicator]->entry_date));
            $user_id = $sms->report[$indicator]->user_id;
            $user_name = $sms->report[$indicator]->user_name;
            $user_master_id = $sms->report[$indicator]->user_master_id;
            $user_type = $sms->report[$indicator]->user_type;
            $total_msg = $sms->report[$indicator]->total_msg;
            $credits = $sms->report[$indicator]->available_messages;
            $total_success = $sms->report[$indicator]->total_success;
            $total_delivered = $sms->report[$indicator]->total_delivered;
            $total_read = $sms->report[$indicator]->total_read;
            $total_failed = $sms->report[$indicator]->total_failed;
            $total_waiting = $sms->report[$indicator]->total_waiting;
            $total_invalid = $sms->report[$indicator]->total_invalid;           
            $campaign_name = $sms->report[$indicator]->campaign_name;

            if ($user_id != '') {
              $increment++;
              ?>                       
          <tr style="text-align: center !important">
                <td>
                  <?= $increment ?>
                </td>
                <td>
                  <?= $entry_date ?>
                </td>
                <td>
                  <?= $user_name ?>
                </td>
                <td>
                  <?= $user_type ?>
                </td>
                <td>
                  <?= $credits ?>
                </td>
                <td>
                  <?= $total_msg ?>
                </td>
                <td>
                  <?= $total_success ?>
                </td>
                <td>
                  <?= $total_delivered ?>
                </td>
                <td>
                  <?= $total_read ?>
                </td>
                <td>
                  <?= $total_failed ?>
                </td>
                <td>
                  <?= $total_waiting ?>
                </td>
              </tr>
              
            <?
            }
          }
        }
        ?>  
             
      </tbody>
    </table>	

    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/dataTables.searchPanes.min.js"></script>
    <script src="assets/js/dataTables.select.min.js"></script>
    <script src="assets/js/jszip.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/buttons.html5.min.js"></script>
    <script src="assets/js/buttons.colVis.min.js"></script>
<!-- filter using -->
    <script>
    $('#table-1').DataTable( {
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
    } );
    </script>
  <?

}
// business_summary_report Page business_summary_report - End  

// Get Campaign Name  Drop Down- start  
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "camapign_name") {

  site_log_generate("Get Campaign Name Using business_summary_report Page : User : " . $_SESSION['yjwatsp_user_name'] . " Preview on " . date("Y-m-d H:i:s"), '../');
  
  if ($_REQUEST['dates']) {
 
    $date = $_REQUEST['dates'];
  } else {

    $date = date('m/d/Y') . "-" . date('m/d/Y'); // 01/28/2023 - 02/27/2023 
  }
// echo  $date ."***";
  $td = explode('-', $date);
  $thismonth_startdate = date("Y/m/d", strtotime($td[0]));
  $thismonth_today = date("Y/m/d", strtotime($td[1]));

  $replace_txt = '{
    "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '",';
  if ($date) {         
    $replace_txt .= '"filter_date" : "' . $thismonth_startdate . ' - ' . $thismonth_today . '",';
  }
 
  $replace_txt = rtrim($replace_txt, ",");
  $replace_txt .= '}';
  // To Get Api URL
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL =>$api_url . '/report/filter_campaign_name',
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
  site_log_generate("Get Campaign Name Using business_summary_report Page : " . $uname . " Execute the service [$replace_txt,$bearer_token] on " . date("Y-m-d H:i:s"), '../');
  $response = curl_exec($curl);
  // echo $response;
  curl_close($curl);
  $sms = json_decode($response, false);
  site_log_generate("Get Campaign Name Using business_summary_report Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');
// To get the one by one data
  $indicatori = 0;
  if ($sms->response_code == 1) {
   for ($indicator = 0; $indicator < $sms->num_of_rows; $indicator++) {
      $indicatori++;?> 
        <?php
      $campaign_name .= $sms->report[$indicator]->campaign_name."$";
      $compose_whatsapp_id .= $sms->report[$indicator]->compose_whatsapp_id."&";
   }
   $replace_campaign_name = rtrim($campaign_name, "$");
   $replace_compose_whatsapp_id = rtrim($compose_whatsapp_id, "&");
   echo "Choose Campaign Name $".$replace_campaign_name;
}
else{
 echo "Choose Campaign Name";
}
}
// Get Campaign Name  Drop Down- End  


// business_details_report Page business_details_report - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "business_details_report") {
  site_log_generate("Business Details Report Page : User : " . $_SESSION['yjwatsp_user_name'] . " Preview on " . date("Y-m-d H:i:s"), '../');
  ?>
    <table class="table table-striped text-center" id="table-1">
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Campaign</th>
          <th>Count</th>
          <th>Mobile No</th>
          <th>Status</th>
          <th>Delivery Status</th>
          <th>Read Status</th>
        </tr>
      </thead>
      <tbody>
      <?
      $srch1 = $_REQUEST['srch1'];
      $srch_1 = $_REQUEST['srch_1'];
      $srch_status = $_REQUEST['srch_status'];
      /* if($_REQUEST['dates']) {
      $date = $_REQUEST['dates'];
      } else {
      $date = date('m/d/Y')."-".date('m/d/Y'); // 01/28/2023 - 02/27/2023 
      } */

      $td = explode('-', $date);
      $thismonth_startdate = date("Y/m/d", strtotime($td[0]));
      $thismonth_today = date("Y/m/d", strtotime($td[1]));
      $replace_txt = '{
        "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '",';

      if ($srch1) {
        $replace_txt .= '"filter_user" : "' . $srch1 . '",';
      }
      if ($srch_1) {
        $replace_txt .= '"filter_department" : "' . $srch_1 . '",';
      } 
      if ($srch_status) {
        switch ($srch_status) {
          case 'SENT': 
            $replace_txt .= '"status_filter" : "' . $srch_status . '",';
          break;
          case 'YET TO SENT':
            $replace_txt .= '"status_filter" : "' . $srch_status . '",';
            break;
          case 'FAILED':
            $replace_txt .= '"status_filter" : "' . $srch_status . '",';
            break;
          case 'INVALID':
            $replace_txt .= '"status_filter" : "' . $srch_status . '",';
            break;

          case 'DELIVERED':
            $replace_txt .= '"delivery_filter" : "' . $srch_status . '",';
            break;
          case 'NOT DELIVERED':
            $replace_txt .= '"delivery_filter" : "' . $srch_status . '",';
            break;

          case 'READ':
            $replace_txt .= '"read_filter" : "' . $srch_status . '",';
            break;
          case 'NOT READ':
            $replace_txt .= '"read_filter" : "' . $srch_status . '",';
            break;
        }
      }
      if ($date) {
        $replace_txt .= '"filter_date" : "' . $thismonth_startdate . ' - ' . $thismonth_today . '",';
      }

      $replace_txt = rtrim($replace_txt, ",");
      $replace_txt .= '}';
// To Get Api URL
      $curl = curl_init();  
      curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url . '/report/detailed_report',
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
      site_log_generate("Business Details Report Page : " . $uname . " Execute the service [$replace_txt,$bearer_token] on " . date("Y-m-d H:i:s"), '../');
      $response = curl_exec($curl);
      curl_close($curl);
      // echo $response;
      $sms = json_decode($response, false);
      site_log_generate("Business Details Report Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');
// To get the one by one data
      $increment = 0;
      if ($sms->num_of_rows > 0) {
        for ($indicator = 0; $indicator < $sms->num_of_rows; $indicator++) {
          $increment++;
          $compose_whatsapp_id = $sms->report[$indicator]->compose_whatsapp_id;
          $user_id = $sms->report[$indicator]->user_id;
          $user_name = $sms->report[$indicator]->user_name;
          $campaign_name = $sms->report[$indicator]->campaign_name;
          $whatsapp_content = $sms->report[$indicator]->whatsapp_content;

          $message_type = $sms->report[$indicator]->message_type;
          $total_mobileno_count = $sms->report[$indicator]->total_mobileno_count;
          $content_char_count = $sms->report[$indicator]->content_char_count;
          $content_message_count = $sms->report[$indicator]->content_message_count;
          $mobile_no = $sms->report[$indicator]->country_code . $sms->report[$indicator]->mobile_no;

          $sender = $sms->report[$indicator]->sender;
          $send_date = date('d-m-Y h:i:s A', strtotime($sms->report[$indicator]->comwtap_entry_date));
          if ($sms->report[$indicator]->response_date != '') {
            $response_date = date('d-m-Y h:i:s A', strtotime($sms->report[$indicator]->response_date));
          } else {
            $response_date = '';
          }

          if ($sms->report[$indicator]->delivery_date != '') {
            $delivery_date = date('d-m-Y h:i:s A', strtotime($sms->report[$indicator]->delivery_date));
          } else {
            $delivery_date = '';
          }

          if ($sms->report[$indicator]->read_date != '') {
            $read_date = date('d-m-Y h:i:s A', strtotime($sms->report[$indicator]->read_date));
          } else {
            $read_date = '';
          }

          $response_status = $sms->report[$indicator]->response_status;
          $response_message = $sms->report[$indicator]->response_message;

          $response_id = $sms->report[$indicator]->response_id;
          $delivery_status = $sms->report[$indicator]->delivery_status;
          $read_status = $sms->report[$indicator]->read_status;

          $disp_stat = '';
          switch ($response_status) {
            case 'S':
              $disp_stat = '<div class="badge badge-success">SENT</div>';
              break;
            case 'F':
              $disp_stat = '<div class="badge badge-danger">FAILED</div>';
              break;
            case 'I':
              $disp_stat = '<div class="badge badge-warning">INVALID</div>';
              break;

            default:
              $disp_stat = '<div class="badge badge-info">YET TO SENT</div>';
              break;
          }

          $disp_stat1 = '';
          switch ($delivery_status) {
            case 'Y':
              $disp_stat1 = '<div class="badge badge-success">DELIVERED</div>';
              break;

            default:
              $disp_stat1 = '<div class="badge badge-danger">NOT DELIVERED</div>';
              break;
          }

          $disp_stat2 = '';
          switch ($read_status) {
            case 'Y':
              $disp_stat2 = '<div class="badge badge-success">READ</div>';
              break;

            default:
              $disp_stat2 = '<div class="badge badge-danger">NOT READ</div>';
              break;
          }
          ?>
            <tr>
                <td><?= $increment ?></td>
                <td><?= $user_name ?></td>
                <td><?= $campaign_name ?></td>
                <td>Total Mobile No : <?= $total_mobileno_count ?><? /* <br>Total Messages : <?= $content_message_count */ ?></td>
                <td class="text-left" style='width: 180px !important;'>
                  <div><div style='float: left'>Sender : </div><div style='float: right; width:100px; margin-right: 15px;'><a href="#!" class="btn btn-outline-primary btn-disabled" style='width: 140px;'><?= $sender ?></a></div></div>
                  <div style='clear: both;'><div style='float: left'>Receiver : </div><div style='float: right;  width:100px; margin-right: 15px;'><a href="#!" class="btn btn-outline-success btn-disabled" style='width: 140px;'><?= $mobile_no ?></a></div></div>
                </td>
                <td><?= $response_date . "<br>" . $disp_stat ?></td>
                <td><?= $delivery_date . "<br>" . $disp_stat1 ?></td>
                <td><?= $read_date . "<br>" . $disp_stat2 ?></td>
            </tr>
          <?
        }
      }
      ?>
      </tbody>
    </table>

    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/dataTables.searchPanes.min.js"></script>
    <script src="assets/js/dataTables.select.min.js"></script>
    <script src="assets/js/jszip.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/buttons.html5.min.js"></script>
    <script src="assets/js/buttons.colVis.min.js"></script>
<!-- filter using -->
    <script>
    $('#table-1').DataTable( {
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
    } );
    </script>
  <?
}
// business_details_report Page business_details_report - End

// manage_whatsappno_list Page manage_whatsappno_list - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "manage_whatsappno_list") {
  site_log_generate("Manage Whatsappno List Page : User : " . $_SESSION['yjwatsp_user_name'] . " Preview on " . date("Y-m-d H:i:s"), '../');
  ?>
    <table class="table table-striped text-center" id="table-1">
      <thead>
        <tr class="text-center">
          <th>#</th>
          <th>User</th>
          <th>Mobile No</th>
          <th>Status</th>
          <th>Entry Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?
      $replace_txt = '{
      "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
    }';
    // To Get Api URL
    $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url . '/list/manage_whatsappno_list',
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
      site_log_generate("Manage Whatsappno List Page : " . $uname . " Execute the service [$replace_txt,$bearer_token] on " . date("Y-m-d H:i:s"), '../');
      $response = curl_exec($curl);
      curl_close($curl);
      $sms = json_decode($response, false);
      site_log_generate("Manage Whatsappno List Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');
// To get the one by one data
      $indicatori = 0;
      if ($sms->num_of_rows > 0) {
        for ($indicator = 0; $indicator < $sms->num_of_rows; $indicator++) {
          $indicatori++;
          $entry_date = date('d-m-Y h:i:s A', strtotime($sms->report[$indicator]->whatspp_config_entdate));
          ?>
          <tr>
            <td><?= $indicatori ?></td>
            <td><?= strtoupper($sms->report[$indicator]->user_name) ?></td>
            <td><?= $sms->report[$indicator]->country_code . $sms->report[$indicator]->mobile_no ?></td>
            <td>
              <? if ($sms->report[$indicator]->whatspp_config_status == 'Y') { ?><a href="#!" class="btn btn-outline-success btn-disabled">Active</a><? } elseif ($sms->report[$indicator]->whatspp_config_status == 'D') { ?><a href="#!" class="btn btn-outline-danger btn-disabled">Deleted</a><? } elseif ($sms->report[$indicator]->whatspp_config_status == 'B') { ?><a href="#!" class="btn btn-outline-dark btn-disabled">Blocked</a><? } elseif ($sms->report[$indicator]->whatspp_config_status == 'N') { ?><a href="#!" class="btn btn-outline-danger btn-disabled">Inactive</a><? } elseif ($sms->report[$indicator]->whatspp_config_status == 'M') { ?><a href="#!" class="btn btn-outline-danger btn-disabled">Mobile No Mismatch</a><? } elseif ($sms->report[$indicator]->whatspp_config_status == 'I') { ?><a href="#!" class="btn btn-outline-warning btn-disabled">Invalid</a><? } elseif ($sms->report[$indicator]->whatspp_config_status == 'P') { ?><a href="#!" class="btn btn-outline-info btn-disabled">Processing</a><? } elseif ($sms->report[$indicator]->whatspp_config_status == 'R') { ?><a href="#!" class="btn btn-outline-danger btn-disabled">Rejected</a><? } elseif ($sms->report[$indicator]->whatspp_config_status == 'X') { ?><a href="#!" class="btn btn-outline-primary btn-disabled">Need Rescan</a><? } elseif ($sms->report[$indicator]->whatspp_config_status == 'L') { ?><a href="#!" class="btn btn-outline-info btn-disabled">Linked</a><? } elseif ($sms->report[$indicator]->whatspp_config_status == 'U') { ?><a href="#!" class="btn btn-outline-warning btn-disabled">Unlinked</a><? } ?>
            </td>
            <td><?= $entry_date ?></td>
            <td id='id_approved_lineno_<?= $indicatori ?>'>
              <? if ($sms->report[$indicator]->whatspp_config_status == 'D' or $sms->report[$indicator]->whatspp_config_status == 'N' or $sms->report[$indicator]->whatspp_config_status == 'M' or $sms->report[$indicator]->whatspp_config_status == 'I' or $sms->report[$indicator]->whatspp_config_status == 'X') { ?>
                  <a href="manage_whatsapp_no?mob=<?= $sms->report[$indicator]->mobile_no ?>" class="btn btn-success">Scan</a>
              <? } else { ?>
                  <a href="#!" class="btn btn-outline-light btn-disabled" style="cursor: not-allowed;">Scan</a>
              <? } ?>
              <? if ($sms->report[$indicator]->whatspp_config_status != 'D') { ?>
                  <button type="button" title="Delete Sender ID" onclick="remove_senderid('<?= $sms->report[$indicator]->whatspp_config_id ?>', 'D', '<?= $indicatori ?>')" class="btn btn-icon btn-danger" style="padding: 0.3rem 0.41rem !important;">Delete</button>
              <? } else { ?>
                  <a href="#!" class="btn btn-outline-light btn-disabled" style="padding: 0.3rem 0.41rem !important;cursor: not-allowed;">Delete</a>
              <? } ?>
            </td>
          </tr>
        <?
        }
      }
      ?>
      </tbody>
    </table>

    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/dataTables.searchPanes.min.js"></script>
    <script src="assets/js/dataTables.select.min.js"></script>
    <script src="assets/js/jszip.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/buttons.html5.min.js"></script>
    <script src="assets/js/buttons.colVis.min.js"></script>
<!-- filter using -->
    <script>
    $('#table-1').DataTable( {
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
    } );
    </script>
  <?
}
// manage_whatsappno_list Page manage_whatsappno_list - End

// approve_whatsapp_no Page approve_whatsapp_no - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "approve_whatsapp_no") {
  site_log_generate("Manage Whatsappno List Page : User : " . $_SESSION['yjwatsp_user_name'] . " Preview on " . date("Y-m-d H:i:s"), '../');
  ?>
    <table class="table table-striped text-center" id="table-1">
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Mobile No</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?
        $replace_txt = '{
        "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
      }';
      // To Get Api URL
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url . '/list/approve_whatsapp_no',
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
        site_log_generate("Manage Whatsappno List Page : " . $uname . " Execute the service [$replace_txt,$bearer_token] on " . date("Y-m-d H:i:s"), '../');
        $response = curl_exec($curl);
        curl_close($curl);
        $sms = json_decode($response, false);
        site_log_generate("Manage Whatsappno List Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');
// To get the one by one data
        $indicatori = 0;
        if ($sms->num_of_rows > 0) {
          for ($indicator = 0; $indicator < $sms->num_of_rows; $indicator++) {
            $indicatori++;
            ?>
            <tr>
              <td>
                <?= $indicatori ?>
              </td>
              <td><?= $sms->report[$indicator]->user_name ?></td>
              <td><?= $sms->report[$indicator]->country_code . $sms->report[$indicator]->mobile_no ?></td>
              <td>
                <?
                switch ($sms->report[$indicator]->whatspp_config_status) {
                  case 'N':
                    ?><a href="#!" class="btn btn-outline-primary btn-disabled">New</a>
                        <?
                        break;

                  case 'L':
                    ?><a href="#!" class="btn btn-outline-info btn-disabled">Whatsapp Linked</a>
                        <?
                        break;
                  case 'U':
                    ?><a href="#!" class="btn btn-outline-warning btn-disabled">Whatsapp Unlinked</a>
                        <?
                        break;
                  case 'X':
                    ?><a href="#!" class="btn btn-outline-primary btn-disabled">Rescan</a>
                        <?
                        break;

                  case 'Y':
                    ?><a href="#!" class="btn btn-outline-success btn-disabled">Super Admin Approved</a>
                        <?
                        break;
                  case 'R':
                    ?><a href="#!" class="btn btn-outline-danger btn-disabled">Super Admin Rejected</a>
                        <?
                        break;

                  default:
                    ?><a href="#!" class="btn btn-outline-dark btn-disabled">Invalid</a>
                        <?
                        break;
                } ?>
              </td>
              <td id='id_approved_lineno_<?= $indicatori ?>'>
                <div class="btn-group mb-3" role="group" aria-label="Basic example">
                  <button type="button" title="Approve"
                    onclick="change_status('<?= $sms->report[$indicator]->whatspp_config_id ?>', 'Y', '<?= $indicatori ?>')"
                    class="btn btn-icon btn-success"><i class="fas fa-check"></i></button>
                  <button type="button" title="Reject"
                    onclick="change_status('<?= $sms->report[$indicator]->whatspp_config_id ?>', 'N', '<?= $indicatori ?>')"
                    class="btn btn-icon btn-danger"><i class="fas fa-times"></i></button>
                </div>
              </td>
            </tr>
          <?
          }
        }
        ?>
      </tbody>
    </table>

    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/dataTables.searchPanes.min.js"></script>
    <script src="assets/js/dataTables.select.min.js"></script>
    <script src="assets/js/jszip.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/buttons.html5.min.js"></script>
    <script src="assets/js/buttons.colVis.min.js"></script>
<!-- filter using -->
    <script>
    $('#table-1').DataTable( {
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
    } );
    </script>
  <?
}
// approve_whatsapp_no Page approve_whatsapp_no - End

// whatsapp_list Page whatsapp_list - Start
if ($_SERVER['REQUEST_METHOD'] == "POST" and $call_function == "whatsapp_list") {
  site_log_generate("Manage Whatsappno List Page : User : " . $_SESSION['yjwatsp_user_name'] . " Preview on " . date("Y-m-d H:i:s"), '../');
  ?>
    <table class="table table-striped" id="table-1">
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Campaign</th>
          <th>Message Content</th>
          <th>Count</th>
          <th>Mobile No</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
      <?
      $replace_txt = '{
      "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
    }';
    // To Get Api URL
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url . '/list/whatsapp_list',
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
      site_log_generate("Manage Whatsappno List Page : " . $uname . " Execute the service [$replace_txt,$bearer_token] on " . date("Y-m-d H:i:s"), '../');
      $response = curl_exec($curl);
      curl_close($curl);
      $sms = json_decode($response, false);
      site_log_generate("Manage Whatsappno List Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');
// To get the one by one data
      $increment = 0;
      if ($sms->num_of_rows > 0) {
        for ($indicator = 0; $indicator < $sms->num_of_rows; $indicator++) {
          $increment++;
          $compose_whatsapp_id = $sms->report[$indicator]->compose_whatsapp_id;
          $user_id = $sms->report[$indicator]->user_id;
          $user_name = $sms->report[$indicator]->user_name;
          $campaign_name = $sms->report[$indicator]->campaign_name;
          $whatsapp_content = $sms->report[$indicator]->whatsapp_content;

          $message_type = $sms->report[$indicator]->message_type;
          $total_mobileno_count = $sms->report[$indicator]->total_mobileno_count;
          $content_char_count = $sms->report[$indicator]->content_char_count;
          $content_message_count = $sms->report[$indicator]->content_message_count;
          $mobile_no = $sms->report[$indicator]->country_code . $sms->report[$indicator]->mobile_no;

          $sender = $sms->report[$indicator]->sender;
          $send_date = date('d-m-Y h:i:s A', strtotime($sms->report[$indicator]->comwtap_entry_date));
          if ($sms->report[$indicator]->response_date != '') {
            $response_date = date('d-m-Y h:i:s A', strtotime($sms->report[$indicator]->response_date));
          } else {
            $response_date = '';
          }
          $response_status = $sms->report[$indicator]->response_status;
          $response_message = $sms->report[$indicator]->response_message;

          $response_id = $sms->report[$indicator]->response_id;
          $delivery_status = $sms->report[$indicator]->delivery_status;
          $read_status = $sms->report[$indicator]->read_status;

          $disp_stat = '';
          switch ($response_status) {
            case 'S':
              $disp_stat = '<div class="badge badge-success">SENT</div>';
              break;
            case 'F':
              $disp_stat = '<div class="badge badge-danger">FAILED</div>';
              break;
            case 'I':
              $disp_stat = '<div class="badge badge-warning">INVALID</div>';
              break;

            default:
              $disp_stat = '<div class="badge badge-info">YET TO SEND</div>';
              break;
          }
          ?>
          <tr>
              <td><?= $increment ?></td>
              <td><?= $user_name ?></td>
              <td><?= $campaign_name ?></td>
              <td><?= $message_type ?> : <?= $whatsapp_content ?></td>
              <td>Total Mobile No : <?= $total_mobileno_count ?><br>Total Messages : <?= $content_message_count ?></td>
              <td>Sender : <a href="#!" class="btn btn-outline-primary btn-disabled"><?= $sender ?></a><br>Receiver : <a href="#!" class="btn btn-outline-success btn-disabled"><?= $mobile_no ?></a></td>
              <td><?= $response_date . "<br>" . $disp_stat ?></td>
          </tr>
        <?
        }
      }
      ?>
      </tbody>
    </table>

    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/dataTables.searchPanes.min.js"></script>
    <script src="assets/js/dataTables.select.min.js"></script>
    <script src="assets/js/jszip.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/buttons.html5.min.js"></script>
    <script src="assets/js/buttons.colVis.min.js"></script>
<!-- filter using -->
    <script>
    $('#table-1').DataTable( {
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
    } );
    </script>
  <?
}
// whatsapp_list Page whatsapp_list - End

// Finally Close all Opened Mysql DB Connection
$conn->close();

// Output header with HTML Response
header('Content-type: text/html');
echo $result_value;
