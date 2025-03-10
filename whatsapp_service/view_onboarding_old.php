<?php
/*
Authendicated users only allow to view this Add Sender ID page.
This page is used to view the Add a New Sender ID.
It will send the form to API service and Save to Whatsapp Facebook
and get the response from them and store into our DB.

Version : 1.0
Author : Madhubala (YJ0009)
Date : 27-Jul-2023
*/

session_start(); // start session
error_reporting(0); // The error reporting function

include_once('api/configuration.php'); // Include configuration.php
extract($_REQUEST); // Extract the request

// If the Session is not available redirect to index page
if ($_SESSION['yjwatsp_user_id'] == "") { ?>
  <script>window.location = "index";</script>
  <?php exit();
}

if($_SESSION['yjwatsp_user_master_id'] != 1) { ?>
  <script>window.location = "dashboard";</script>
  <?php exit();
}

$site_page_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME); // Collect the Current page name
site_log_generate("View On Boarding Page : User : " . $_SESSION['yjwatsp_user_name'] . " access the page on " . date("Y-m-d H:i:s"));

// To Send the request  API
$replace_txt = '{
  "user_id" : "' . $_REQUEST["usr"] . '"
}';

// Add bearer token
$bearer_token = "Authorization: " . $_SESSION["yjwatsp_bearer_token"] . "";

// It will call "p_login" API to verify, can we allow to login the already existing user for access the details
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL =>  $api_url . '/list/view_onboarding',
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
// Log file generate
site_log_generate("View On Boarding Page : " . $uname . " Execute the service [$replace_txt, $bearer_token] on " . date("Y-m-d H:i:s") , "../");
$response = curl_exec($curl);
curl_close($curl);

// After got response decode the JSON result
$state1 = json_decode($response, false);

// Log file generate
site_log_generate("View On Boarding Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s") , "../");

// To get the API response one by one data and assign to Session
if ($state1->response_status == 200)
{
  // Looping the indicator is less than the count of response_result.if the condition is true to continue the process.if the condition are false to stop the process
  for ($indicator = 0;$indicator < count($state1->report);$indicator++)
  {
    $cont_person = $state1->report[$indicator]->cont_person;
    $cont_designation = $state1->report[$indicator]->cont_designation;
    $cont_mobile_no = $state1->report[$indicator]->cont_mobile_no;
    $cont_email = $state1->report[$indicator]->cont_email;
    $billing_address = $state1->report[$indicator]->billing_address;

    $company_name = $state1->report[$indicator]->company_name;
    $company_website = $state1->report[$indicator]->company_website;
    $parent_company_name = $state1->report[$indicator]->parent_company_name;
    $company_display_name = $state1->report[$indicator]->company_display_name;
    $description_business = $state1->report[$indicator]->description_business;

    $profile_image = $state1->report[$indicator]->profile_image;
    $business_category = $state1->report[$indicator]->business_category;
    $sender = $state1->report[$indicator]->sender;
    $sender_1 = $state1->report[$indicator]->sender_1;
    $sender_2 = $state1->report[$indicator]->sender_2;

    $message_type = $state1->report[$indicator]->message_type;
    $opt_process = $state1->report[$indicator]->opt_process;
    $enquiry_approval = $state1->report[$indicator]->enquiry_approval;
    $company_display_name = $state1->report[$indicator]->company_display_name;
    $privacy_terms = $state1->report[$indicator]->privacy_terms;

    $terms_conditions = $state1->report[$indicator]->terms_conditions;
    $document_proof = $state1->report[$indicator]->document_proof;
    $proof_doc_name = $state1->report[$indicator]->proof_doc_name;
    $volume_day_expected = $state1->report[$indicator]->volume_day_expected;

    $user_name = $state1->report[$indicator]->user_name;
    $user_email = $state1->report[$indicator]->user_email;
    $user_mobile = $state1->report[$indicator]->user_mobile;
    $user_address = $state1->report[$indicator]->user_address;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>View On Boarding :: <?= $site_title ?></title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">

<!-- style include in css -->
<style>
  .loader {
    width: 50;
    background-color: #ffffffcf;
  }

  .loader img {}
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
          <!-- Title and Breadcrumbs -->
          <div class="section-header">
            <h1>View On Boarding</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
              <div class="breadcrumb-item">View On Boarding</div>
            </div>
          </div>

          <!-- Form Entry Panel -->
          <div class="section-body">
            <div class="row">

              <div class="col-12 col-md-12 col-lg-12">
                <div class="card" style="padding: 10px;">
                  <form class="md-float-material form-material" action="#" name="frm_edit_onboarding" id='frm_edit_onboarding' class="needs-validation" novalidate="" enctype="multipart/form-data" method="post">
                    <div>
                      <div class="row m-b-20">
                        <div class="col-md-12">
                          <h5 class="text-center"><i class="icofont icofont-sign-in"></i>Basic information </h5>
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-2 label">
                          Client Name : 
                        </div>
                        <div class="col-4">
                          <?=$user_name?>
                        </div>
                        <div class="col-2 label">
                          Contact person : 
                        </div>
                        <div class="col-4">
                          <?=$cont_person?>
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-2 label">
                          Designation : 
                        </div>
                        <div class="col-4">
                          <?=$cont_designation?>
                        </div>
                        <div class="col-2 label">
                          Contact no : 
                        </div>
                        <div class="col-4">
                          <?=$cont_mobile_no?>
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-2 label">
                          Contact Email ID : 
                        </div>
                        <div class="col-4">
                          <?=$cont_email?>
                        </div>
                        <div class="col-2 label">
                          Billing Address & GST : 
                        </div>
                        <div class="col-4">
                          <?=$billing_address?>
                        </div>
                      </div>
                      
                      </br><h5 class="text-center"><i class="icofont icofont-sign-in"></i>Company information </h5></br>

                      <div class="row mt-2">
                        <div class="col-2 label">
                          Name of the business : 
                        </div>
                        <div class="col-4">
                          <?=$company_name?>
                        </div>
                        <div class="col-2 label">
                          Company website details : 
                        </div>
                        <div class="col-4">
                          <?=$company_website?>
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-2 label">
                          Parents company name : 
                        </div>
                        <div class="col-4">
                          <?=$parent_company_name?>
                        </div>
                        <div class="col-2 label">
                          Company display name (To be configured) : 
                        </div>
                        <div class="col-4">
                          <?=$company_display_name?>
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-2 label">
                          Description of the business : 
                        </div>
                        <div class="col-4">
                          <?=$description_business?>
                        </div>
                        <div class="col-2 label">
                          Registered Address of the business : 
                        </div>
                        <div class="col-4">
                          <?=$user_address?>
                        </div>
                      </div>

                      </br><h5 class="text-center"><i class="icofont icofont-sign-in"></i>Communication information </h5></br>

                      <div class="row mt-2">
                        <div class="col-2 label">
                          Email ID : 
                        </div>
                        <div class="col-4">
                          <?=$user_email?>
                        </div>
                        <div class="col-2 label">
                          Contact no : 
                        </div>
                        <div class="col-4">
                          <?=$user_mobile?>
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-2 label">
                          Profile/ Display picture of the business
                        </div>
                        <div class="col-4">
                          <? if($profile_image != '') { ?><img src="uploads/whatsapp_images/<?=$profile_image?>" style="width: 100px; height: 100px;"><? } ?>
                        </div>
                        <div class="col-2 label">
                          Select business category : 
                        </div>
                        <div class="col-4">
                            <?   // To Show the Service Category list
                            $replace_txt = '{
                                  "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
                                }';
                            $bearer_token = 'Authorization: ' . $_SESSION['yjwatsp_bearer_token'] . '';
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                              CURLOPT_URL => $api_url . '/list/service_category_list',
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
                            // Send the data into API and execute
                            site_log_generate("View On Boarding Page : " . $uname . " Execute the service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
                            $response = curl_exec($curl);
                            curl_close($curl);
                            // After got response decode the JSON result
                            $state1 = json_decode($response, false);
                            site_log_generate("View On Boarding Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');
                            $i1 = 0;
                            // Based on the JSON response, list in the option button
                            if ($state1->num_of_rows > 0) {
                              // Looping the indicator is less than the count of report.if the condition is true to continue the process and to get the report details.if the condition are false to stop the process and to send the no available data
                              for ($indicator = 0; $indicator < count($state1->report); $indicator++) {
                                $message_category_id = $state1->report[$indicator]->message_category_id;
                                $message_category_title = $state1->report[$indicator]->message_category_title;
                                $i1++; ?>
                                <? if ($business_category == $message_category_id) { ?><?= $message_category_title ?><? } ?>
                                <?
                              }
                            }
                            ?>
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-2 label">
                          Sender ID : 
                        </div>
                        <div class="col-4">
                          <?=$sender?>
                        </div>
                        <div class="col-2 label">
                          Sender ID- 1 (Communication) : 
                        </div>
                        <div class="col-4">
                          <?=$sender_1?>
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-2 label">
                          Sender ID 2 (Communication) : 
                        </div>
                        <div class="col-4">
                          <?=$sender_2?>
                        </div>
                        <div class="col-2 label">
                          Type of message : 
                        </div>
                        <div class="col-4">
                            <? if($message_type == 2) { ?> Promotional <? } 
                               elseif($message_type == 3) { ?> Both <? } 
                               else { ?> Transactional <? } ?>
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-2 label">
                          Opt-in process : 
                        </div>
                        <div class="col-4">
                          <?  if($opt_process == 2) { ?> SMS <? } 
                              elseif($opt_process == 3) { ?> Website & Others <? } 
                              else { ?> SMS <? } ?>
                        </div>
                        <div class="col-2 label">
                          Enquiry/ Approval page : 
                        </div>
                        <div class="col-4">
                          <?=$enquiry_approval?>
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-2 label">
                          Privacy & terms of service page : 
                        </div>
                        <div class="col-4">
                          <?=$privacy_terms?>
                        </div>
                        <div class="col-2 label">
                          Terms & Condition page : 
                        </div>
                        <div class="col-4">
                          <?=$terms_conditions?>
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-2 label">
                          Proof of document : 
                        </div>
                        <div class="col-4">
                          <?  if($document_proof == 2) { ?> Utility bill (Mobile, Electricity, Bank statement) <? } 
                              elseif($document_proof == 3) { ?> Change of Business name <? } 
                              elseif($document_proof == 4) { ?> Certification of incorporation <? } 
                              elseif($document_proof == 5) { ?> Tax document <? } 
                              else { ?> Business registration or License document <? } ?>
                        </div>
                        <div class="col-2 label">
                          Upload PDF : 
                        </div>
                        <div class="col-4">
                          <? if($proof_doc_name != '') { ?><a href="uploads/whatsapp_docs/<?=$proof_doc_name?>" download>Download Document</a><? } ?>
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-2 label">
                          Expected volumes for day : 
                        </div>
                        <div class="col-4">
                          <?=$volume_day_expected?>
                        </div>

                      </div>
                    </div>

                    <div class="row m-t-30">
                      <div class="col-md-12" style="text-align:center;">&nbsp;</div>
                    </div>

                  </div>
                </form>
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

  <!--Remove dublicates numbers -->
  <script>
    // start function document
    $(function () {
      $('#id_qrcode').fadeOut("slow");
    });
  </script>
</body>

</html>


