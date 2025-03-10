<?
/*
This page is used to authendicate the user.
Every valid user can login here to access their
role based services.

Version : 1.0
Author : Madhubala (YJ0009)
Date : 01-Jul-2023
*/
session_start(); // To start session
error_reporting(0);// The error reporting function
// Include configuration.php
include_once('api/configuration.php');

// If Session available user try to access this page, then it will redirect to Logout page
if($_SESSION['yjwatsp_user_id'] != ""){ ?>
  <script>window.location = "logout";</script>
<?php exit();
}
site_log_generate("Index Page : Unknown User : '".$_SESSION['yjwatsp_user_id']."' access this page on ".date("Y-m-d H:i:s"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login - <?=$site_title?></title>
  <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">

  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="assets/modules/bootstrap-social/bootstrap-social.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <style>
    .progress {
      height: 0.3rem !important;
    }
    .tab_signup{
width:100%;
    }
    .label{
      top:5px;
    }
    select option {
  text-transform: capitalize;
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
<body>
<div class="theme-loader"></div>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
             <!-- Signin - Start -->
             <div class="offset-sm-0 col-md-6 offset-md-3" id="tab_signin" style="display:block">
            <div class="login-brand">
              <img src="assets/img/cm-logo.png" alt="logo" style="width: 100%">
            </div>
            <div class="card card-success">
             <div class="card-body" >
                <form class="md-float-material form-material" action="#" name="frm_login" id='frm_login'  method="post">
                  <div>
                    <div class="row m-b-20">
                      <div class="col-md-12">
                        <h3 class="text-center"><i class="icofont icofont-sign-in"></i> Sign In</h3>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                        Login ID
                      </div>
                      <div class="col-8" >
                        <input type="text" name="txt_username" id="txt_username" class="form-control" value=""
                          maxlength="100" tabindex="1" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title="Login ID" placeholder="Login ID"   pattern="[a-zA-Z0-9 -_]+" onkeypress="return clsAlphaNoOnly(event)" onpaste="return false;">
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-4">
                        Login Password
                      </div>
                      <div class="col-8">
                        <div class="input-group" title="Visible Password">
                          <input type="password" name="txt_password" id='txt_password' class="form-control" value=""
                            maxlength="100" tabindex="2" required="" data-toggle="tooltip" data-placement="top" title=""
                            data-original-title="Login Password" placeholder="Login Password">
				              <div class="input-group-prepend">
                          		<div class="input-group-text" onclick="password_visible1()" id="id_signup_display_visiblitity"><i class="fas fa-eye-slash"></i></div>
                        	</div>
                        </div>
                      </div>
                    </div>
                    <div class="row m-t-30">
                      <div class="col-md-12">
                        <span class="error_display" id='id_error_display_signin'></span>
                         <!-- To Display Error message -->
                      </div>
                      <div class="col-md-4"></div>
                    </div>

                    <div class="row  mt-4">
                      <div class="col-md-4"></div>
                      <div class="col-md-4">
                        <input type="hidden" class="form-control" name='call_function' id='call_function'
                          value='signin' /> 
                        <input type="hidden" class="form-control" name='hid_sendurl' id='hid_sendurl'
                          value='<? $server_http_referer = $site_url . "dashboard";  echo $server_http_referer; ?>' />
                        <input type="submit" name="submit" id="submit" tabindex="3" value="Sign in"
                          class="btn btn-success btn-md btn-block waves-effect waves-light text-center m-b-20"> 

                          
                          <!-- Sign In Button -->
                      </div>
                      <div class="col-md-4"></div>
                    </div>
                    <div class="row m-t-1">
                      <div class="col-md-6 text-left"><a class="nav-link" data-toggle="tab" href="#tab_signup" onclick="func_open_tab_signin()" role="tab"> New Users : Sign up</a></div>
                     
                    </div>

                  </div>
                </form>
              </div></div></div></div>
              <!-- Signin End -->

              <!-- Signup -->
              <div class="offset-sm-0"  id="tab_signup" style="display:none;">
              <div class="login-brand" >
              <img src="assets/img/cm-logo.png" alt="logo" style="width: 50%">
            </div>
           
              <div class="card card-success">
             
              <div class="card-body tab_signup" >
                <form class="needs-validation" novalidate  action="#" name="frm_signup" id='frm_signup' enctype="multipart/form-data" method="post">
                  <div>
                    <div class="row m-b-20">
                      <div class="col-md-12">
                        <h3 class="text-center"><i class="icofont icofont-sign-in"></i> Sign Up</h3>
                        <h5 class="text-center"><i class="icofont icofont-sign-in"></i>Basic Information </h5>
                      </div>
                    </div>                 
                    <div class="row mt-2">
                      <div class="col-6 label">
                        Client Name<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                        <input type="text" name="clientname_txt" id="clientname_txt" class="form-control" value=""
                          maxlength="50" tabindex="1" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title="Client Name" placeholder="Client Name" 
                           pattern="[a-zA-Z0-9 -_]+" onkeypress="return clsAlphaNoOnly(event)" onpaste="return false;"
                         >
                      </div>
                      </div>                 
                    <div class="row mt-2">
                      <div class="col-6 label">
                        Contact Person<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                        <input type="text" name="contact_person_txt" id="contact_person_txt" class="form-control" value=""
                          maxlength="50" tabindex="2" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title=" Contact Person" placeholder="Contact Person" 
                            pattern="[a-zA-Z0-9 -_]+" onkeypress="return clsAlphaNoOnly(event)" onpaste="return false;"
                          >
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Designation<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                        <input type="text" name="designation_txt" id="designation_txt" class="form-control" value=""
                          maxlength="20" tabindex="3" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title="Contact Person Designation" placeholder="Contact Person Designation" 
                           pattern="[a-zA-Z0-9 -_]+" onkeypress="return clsAlphaNoOnly(event)" onpaste="return false;"
                           >
                      </div>
                      </div>                 
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Contact No<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                        <input type="text" name="mobile_no_txt" id="mobile_no_txt" class="form-control" value=""
                          maxlength="10" tabindex="4" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title="Contact Person Contact No" placeholder="Contact Person Contact No" 
onkeypress="return (event.charCode !=8 && event.charCode ==0 ||  (event.charCode >= 48 && event.charCode <= 57))" >
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Contact Email ID<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                        <input type="text" name="email_id_contact" id="email_id_contact" class="form-control" value=""
                          maxlength="100" tabindex="5" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title="Contact Person Email ID" placeholder="Contact Person Email ID" 
                           >
                      </div>
                      </div>                 
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Billing Address & GST<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                        <input type="text" name="bill_address" id="bill_address" class="form-control" value=""
                          maxlength="300" tabindex="6" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title="Billing Address & GST" placeholder="Billing Address & GST" 
                            pattern="^[a-zA-Z0-9/\s,.-]*$" onkeypress="return address_validation(event)"
                          >
                      </div>
                    </div>
                    
                    </br></br><h5 class="text-center"><i class="icofont icofont-sign-in"></i>Company Information </h5></br>

                    <div class="row mt-2">
                      <div class="col-6 label">
                      Name Of The Business<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                        <input type="text" name="business_name" id="business_name" class="form-control" value=""
                          maxlength="50" tabindex="7" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title="Company Name" placeholder="Company Name" 
                           pattern="[a-zA-Z0-9 -_]+" onkeypress="return clsAlphaNoOnly(event)" onpaste="return false;"
                          >
                      </div>
                      </div>                 
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Company Website Details<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                      <textarea type="text" name="cpy_website_details" id="cpy_website_details" class="form-control" value=""
                          maxlength="100" tabindex="8" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title="Company Website Details" placeholder="Company Website Details" 
                           pattern="[a-zA-Z0-9 -_]+" onkeypress="return single_quote_validation(event)" 
                          ></textarea>
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Parents Company Name<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                        <input type="text" name="parent_company_name" id="parent_company_name" class="form-control" value=""
                          maxlength="50" tabindex="9" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title="Parents Company Name" placeholder="Parents Company Name" 
                           pattern="[a-zA-Z0-9 -_]+" onkeypress="return clsAlphaNoOnly(event)" onpaste="return false;" 
                          >
                      </div>
                      </div>                 
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Company Display Name (To be configured)<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                      <textarea type="text" name="cpy_display_name" id="cpy_display_name" class="form-control" value=""
                          maxlength="50" tabindex="10" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title="Group Company Name" placeholder="Group Company Name" 
                         pattern="[a-zA-Z0-9 -_]+" onkeypress="return clsAlphaNoOnly(event)" onpaste="return false;"
                          ></textarea>
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-6 label">
                     Description Of The Business<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                      <textarea type="text" name="desp_business_txt" id="desp_business_txt" class="form-control" value=""
                          maxlength="200" tabindex="11" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title="Description Of The Business" placeholder="Description Of The Business"
                          ></textarea>
                      </div>
                      </div>                 
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Registered Address Of The Business<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                      <textarea type="text" name="reg_add_business" id="reg_add_business" class="form-control" value=""
                          maxlength="200" tabindex="12" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title="Registered Address Of The Business" placeholder=" Registered Address Of The Business" 
                            pattern="^[a-zA-Z0-9/\s,.-]*$" onkeypress="return address_validation(event)"
                          ></textarea>
                      </div>
                    </div>

                    </br></br><h5 class="text-center"><i class="icofont icofont-sign-in"></i>Communication Information </h5></br>
                    
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Email ID<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                        <input type="text" name="email_id_txt" id="email_id_txt" class="form-control" value=""
                          maxlength="128" tabindex="13" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title=" Official Email ID" placeholder=" Email ID" 
                           >
                      </div>
                      </div>                 
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Contact No<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                        <input type="text" name="contact_no_cmpy" id="contact_no_cmpy" class="form-control" value=""
                          maxlength="10" tabindex="14" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title="Business Contact No" placeholder="Contact No" onkeypress="return (event.charCode !=8 && event.charCode ==0 ||  (event.charCode >= 48 && event.charCode <= 57))" >
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Profile/ Display Picture Of The Business<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                        <input type="file" name="profile_display_pic" id="profile_display_pic" class="form-control" value=""
                          maxlength="100" tabindex="15" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title=" Profile/ Display Picture Of The Business - Allowed only jpeg, png images. Maximum 5 MB Size allowed" placeholder="Profile/ Display Picture Of The Business" accept="image/png, image/jpeg" 
                         >
                      </div>
                      </div>                 
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Select business category<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                      <select name="slt_service_category"  required="" id='slt_service_category' class="form-control"
                            data-toggle="tooltip" data-placement="top" title=""
                            data-original-title="Select Service Category" tabindex="16">
                            <?   // To Show the Service Category list
                            $replace_txt = '{
                              "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
                            }';
                            $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                              CURLOPT_URL => $api_url.'/list/service_category_list',
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
                            site_log_generate("Manage Sender ID Page : " . $uname . " Execute the service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
                            $response = curl_exec($curl);
                            curl_close($curl);
 // After got response decode the JSON result
                            $state1 = json_decode($response, false);
                            site_log_generate("Manage Sender ID Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');
                            $i1 = 0;
// Based on the JSON response, list in the option button
                            if ($state1->num_of_rows > 0) {
  // Looping the indicator is less than the count of report.if the condition is true to continue the process and to get the report details.if the condition are false to stop the process and to send the no available data
                              for ($indicator = 0; $indicator < count($state1->report); $indicator++) {
                                $message_category_id = $state1->report[$indicator]->message_category_id;
                                $message_category_title = $state1->report[$indicator]->message_category_title;
                                $i1++; ?>
                                <option value="<?= $message_category_id ?>" <? if ($i1 == 1) { ?> selected <? } ?>>
                                  <?= $message_category_title ?></option>
                              <?
                              }
                            }
                            ?>
                          </select>
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Sender ID<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                        <input type="text" name="sender_id_txt" id="sender_id_txt" class="form-control" value=""
                          maxlength="10" tabindex="17" autofocus="" required="" data-toggle="tooltip"  onkeypress="return (event.charCode !=8 && event.charCode ==0 ||  (event.charCode >= 48 && event.charCode <= 57))"
                          data-placement="top" title="" data-original-title="Recommend fresh no or never used whats app" placeholder="Sender ID" 
                            >
                      </div>
                      </div>                 
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Sender ID 1 (Communication)<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                      <textarea type="text" name="sender_id_txt_1_txt" id="sender_id_txt_1_txt" class="form-control" value=""
                          maxlength="300" tabindex="18" autofocus="" required="" data-toggle="tooltip" onkeypress="return single_quote_validation(event)"
                          data-placement="top" title="" data-original-title="Message script / template. This message will send from this sender id. Maximum 300 characters." placeholder="Sender ID 1" 
                          ></textarea>
                      </div>
                    </div>   
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Sender ID 2 (Communication)<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                      <textarea type="text" name="sender_id_txt_2_txt" id="sender_id_txt_2_txt" class="form-control" value=""
                          maxlength="300" tabindex="19" autofocus="" required="" data-toggle="tooltip" onkeypress="return single_quote_validation(event)"
                          data-placement="top" title="" data-original-title="Message script / template. This message will send from this sender id. Maximum 300 characters." placeholder="Sender ID 2" 
                            ></textarea>
                      </div>
                      </div>                 
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Type of message<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                      <select class="form-select form-control"  required="" aria-label="Default select example" tabindex="20" id="type_of_message" name="type_of_message">
                      <option value="transactional">Transactional</option>
                      <option value="promotional">Promotional </option>
                      <option value="both">Both</option>
                          </select>
                      </div>
                    </div>       
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Opt In Process<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                      <select class="form-select form-control" required="" aria-label="Default select example" tabindex="21" id="otp_in_process" name="otp_in_process">
                      <option value="sms">SMS</option>
                      <option value="obd">OBD</option>
                      <option value="website_others">Website & Others</option>
                          </select>
                      </div>
                      </div>                 
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Enquiry/ Approval page<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                        <input type="text" name="enquiry_approve_txt" id="enquiry_approve_txt" class="form-control" value=""
                          maxlength="100" tabindex="22" autofocus="" required="" data-toggle="tooltip" onkeypress="return single_quote(event)"
                          data-placement="top" title="" data-original-title="Enquiry/ Approval page" placeholder="Enquiry/ Approval page" 
                           >
                      </div>
                    </div>  
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Privacy & Terms of service page<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                        <input type="text" name="privacy_terms_txt" id="privacy_terms_txt" class="form-control" value=""
                          maxlength="100" tabindex="23" autofocus="" required="" data-toggle="tooltip" onkeypress="return single_quote(event)"
                          data-placement="top" title="" data-original-title="Privacy & Terms of service page" placeholder="Privacy & Terms of service page" 
                          >
                      </div>
                      </div>                 
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Terms & Condition page<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                        <input type="text" name="terms_condition_txt" id="terms_condition_txt" class="form-control" value=""
                          maxlength="100" tabindex="24" autofocus="" required="" data-toggle="tooltip" onkeypress="return single_quote(event)"
                          data-placement="top" title="" data-original-title="Official T&C page" placeholder="Terms & Condition page" 
                              >
                      </div>
                    </div>  
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Proof of document<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                      <select class="form-select form-control" required="" aria-label="Default select example" tabindex="25" id="proof_document_slt" name="proof_document_slt">
                      <option value="business_registration_license">Business registration or License document</option>
                      <option value="utility_bill">Utility bill (Mobile, Electricity, Bank statement)</option>
                      <option value="change_business_name">Change of Business name</option>
                       <option value="incorporation_certificate">Certification of incorporation</option>
                        <option value="tax_document">Tax document</option>
                          </select>
                      </div>
                      </div>                 
                    <div class="row mt-2">
                      <div class="col-6 label">
                      Upload PDF<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                      <input type="file" name="proof_document" id="proof_document" class="form-control" value=""
                          maxlength="100" tabindex="26" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title=" Proof of document - Allowed only PDF files"  accept="application/pdf" placeholder=" Proof of document (Upload PDF)" >
                      </div>
                    </div> 
                     <div class="row mt-2">
                      <div class="col-6 label">
                      Expected volumes for day<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                        <input type="text" name="expected_volumes_day" id="expected_volumes_day" class="form-control" value=""
                          maxlength="5" tabindex="27" autofocus="" required="" data-toggle="tooltip"
                          data-placement="top" title="" data-original-title="Expected volumes for day" placeholder="50000 Per Day"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"
                            <? if ($_REQUEST['mob'] != '') { ?> readonly <? } ?>
>
                      </div></div>

                     <?/* <div class="col-6 label">
                      Parent user<label
                            style="color:#FF0000">*</label>
                      </div>
                      <div class="col-6">
                      <select name="slt_super_admin" id='slt_super_admin' class="form-control mb-2" data-toggle="tooltip" data-placement="top" title="" tabindex="28" data-original-title="Select Super Admin" name="slt_super_admin">
                            <? // To get the Super Admin user from API
                            $replace_txt = '{
                                "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
                              }'; // Add User ID
                            $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].''; // Add Bearer Token
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                              CURLOPT_URL => $api_url. '/list/display_super_admin',
                              CURLOPT_RETURNTRANSFER => true,
                              CURLOPT_ENCODING => '',
                              CURLOPT_MAXREDIRS => 10,
                              CURLOPT_TIMEOUT => 0,
                              CURLOPT_FOLLOWLOCATION => true,
                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                              CURLOPT_CUSTOMREQUEST => 'GET',
                              CURLOPT_POSTFIELDS =>$replace_txt,
                              CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json'
                              ),
                            ));

			    // Send the data into API and execute
                            site_log_generate("Manage Users Page : " . $_SESSION['yjwatsp_user_name'] . " Execute the service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
                            $response = curl_exec($curl);
                            curl_close($curl);

			    // After got response decode the JSON result
                            $header = json_decode($response, false);
                            site_log_generate("Manage Users Page : " . $_SESSION['yjwatsp_user_name'] . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');

			    // To Dispay the response data into option button
                            for ($indicator = 0; $indicator < $header->num_of_rows; $indicator++) {
 // Looping the indicator is less than the num_of_rows.if the condition is true to continue the process and to get the details.if the condition are false to stop the process
                              if ($indicator == 0) {
                                $firstusr = $header->report[$indicator]->user_id;
                              }
                              echo '<option value="' . $header->report[$indicator]->user_id . '~~' . $header->report[$indicator]->user_short_name . '">' . strtoupper($header->report[$indicator]->user_name) . '</option>';
                            }
                            ?>
                          </select>
                      </div>
                    </div>*/?>
                   
                    <div class="row mt-2">
                      <div class="col-6">
                        <input type="checkbox" name="chk_terms" id="chk_terms" value="" tabindex="29">
                        <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                        <span class="text-inverse" style="color:#FF0000 !important">I read and accept <a href="javascript:void(0)" style="color:#FF0000 !important"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Terms & Conditions." class="alert-ajax btn-outline-info">Terms &amp; Conditions.</a></span>
                      </div></div>
                    </div>

                    <div class="row m-t-30">
                      <div class="col-md-12" style="text-align:center;">
                      <span class="error_display text-center" id='id_error_display_onboarding'></span>&nbsp;
                    
                      </div>
                      <div class="col-md-4"></div>
                    </div>

                    <div class="row  m-t-30">
                    <div class="col-md-12" style="text-align:center">
                  
                        <input type="submit" name="submit_signup" id="submit_signup" style="width:150px;margin-left:auto;margin-right:auto" tabindex="30" value="Sign Up Now"
                          class="btn btn-success btn-md btn-block waves-effect waves-light text-center ">
                      </div>
                      <div class="col-md-4"></div>
                    </div>

                    <div class="row m-t-1">
                      <div class="col-md-6 text-left"><a class="nav-link" data-toggle="tab" href="#tab_signin" onclick="func_open_tab_signup()" role="tab">Sign In</a></div>
                      <!-- <div class="col-md-6 text-right"><a class="nav-link" data-toggle="tab"
                          href="#tab_forgotpwd" onclick="func_open_tab('forgotpwd')" role="tab">Forgot
                          Password?</a></div> -->
                    </div>

                  </div>
                </form>
              </div></div>
                          </div></div>
              <!-- Signup -->

              <!-- Forget Password -->
             <?/* <div class="offset-sm-0 col-md-6 offset-md-3"  id="tab_forgotpwd" style="display: none;" >
              <div class="login-brand">
              <img src="assets/img/cm-logo.png" alt="logo" style="width: 100%">
              </div>
              <div class="card card-success">
              <div class="card-body">
                <form class="md-float-material form-material" action="#" name="frm_resetpwd" id='frm_resetpwd' method="post">
                  <div>
                    <div class="row m-b-20">
                      <div class="col-md-12">
                        <h3 class="text-center"><i class="icofont icofont-sign-in"></i> Recover Password</h3>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                        Email ID
                      </div>
                      <div class="col-8">
                        <input type="email" name="txt_user_email_fp" id='txt_user_email_fp' class="form-control" value="" maxlength="100" tabindex="1" required="" data-toggle="tooltip" data-placement="top" title="" data-original-title="Email ID" placeholder="Email ID">
                      </div>
                    </div>

                    <!-- <div class="row m-t-30">
                      <div class="col-md-12">
                        <span class="error_display" id='id_error_display_signin'></span>
                      </div>
                      <div class="col-md-4"></div>
                    </div> -->

                    <div class="row  mt-4">
                      <div class="col-md-4"></div>
                      <div class="col-md-4">
                        <span class="error_display" id='id_error_display_resetpwd'></span>
												<input type="hidden" class="form-control" name='call_function' id='call_function' value='resetpwd' />
												<input type="submit" name="submit_resetpwd" id="submit_resetpwd" tabindex="2" value="Reset Password" class="btn btn-success btn-md btn-block waves-effect text-center m-b-20">
                      </div>
                      <div class="col-md-4"></div>
                    </div>

                    <div class="row m-t-1">
                     
                      <div class="col-md-6 text-right"><a class="nav-link" data-toggle="tab" href="#tab_signin" onclick="func_open_tab('signin')" role="tab">Sign In</a></div>
                    </div>

                  </div>
                </form>
              </div>*/?>
              <!-- Forget Password -->

            </div>
            <div class="simple-footer">
              Copyright &copy; <?=$site_title?> - <?=date("Y")?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Modal content-->
  <div class="modal fade" id="default-Modal" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
						<div class="modal-content">
								<div class="modal-header">
										<h4 class="modal-title">Terms & Conditions</h4>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
										</button>
								</div>
								<div class="modal-body" id="id_modal_display">
										<h5>Welcome</h5>
										<p>Waiting for load Data..</p>
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

  <script>


  // start function document
  $(function () {
      $('.theme-loader').fadeOut("slow");
      init();
    });

    $("option").each(function() {
    var $this = $(this);
    $this.text($this.text().charAt(0).toUpperCase() + $this.text().slice(1));
});
    $(".alert-ajax").click(function(){
				$("#id_modal_display").load("uploads/imports/terms.htm",function(){
						$('#default-Modal').modal({show:true});
				});
		});

    function func_open_tab_signin(){
      $('#tab_signup').css("display", "block");
        $('#tab_signin').css("display", "none");
      // login clear
      $('#txt_username').val("");
      $('#txt_password').val("");
    }
    function func_open_tab_signup(){
      $('#tab_signin').css("display", "block");
        $('#tab_signup').css("display", "none");
     // signup clear 
  $('#clientname_txt').val('');
              $('#contact_person_txt').val('');
              $('#designation_txt').val('');
              $('#mobile_no_txt').val('');
              $('#email_id_contact').val('');
              $('#bill_address').val('');
              $('#business_name').val('');
              $('#cpy_website_details').val('');
              $('#parent_company_name').val('');
              $('#cpy_display_name').val('');
              $('#desp_business_txt').val('');
              $('#reg_add_business').val('');
              $('#email_id_txt').val('');
              $('#contact_no_cmpy').val('');
              $('#profile_display_pic').val('');
              // $('#slt_service_category').val('');
              $('#sender_id_txt').val('');
              $('#sender_id_txt_1_txt').val('');
              $('#sender_id_txt_2_txt').val('');
              // $('#type_of_message').val('');
              // $('#otp_in_process').val('');
              $('#enquiry_approve_txt').val('');
              $('#privacy_terms_txt').val('');
              $('#terms_condition_txt').val('');
              // $('#proof_document_slt').val('');
              $('#proof_document').val('');
              $('#expected_volumes_day').val('');
    }
    function password_visible1() {
		var x = document.getElementById("txt_password");
		if (x.type === "password") {
			x.type = "text";
			$('#id_signup_display_visiblitity').html('<i class="fas fa-eye"></i>');
		} else {
			x.type = "password";
			$('#id_signup_display_visiblitity').html('<i class="fas fa-eye-slash"></i>');
		}
	}
      // Sign in submit Button function Start
      $("#submit").click(function (e) {
      $("#id_error_display_signin").html("");
      var uname = $('#txt_username').val();
      var password = $('#txt_password').val();
      var flag = true;

      /********validate all our form fields***********/
      /* Name field validation  */
      if (uname == "") {
        $('#txt_username').css('border-color', 'red');
        flag = false;
        e.preventDefault();
      }
      /* password field validation  */
      if (password == "") {
        $('#txt_password').css('border-color', 'red');
        flag = false;
        e.preventDefault();
      } else {
      }
      /********Validation end here ****/

      /* If all are ok then we send ajax request to call_functions.php *******/
      if (flag) {
        var data_serialize = $("#frm_login").serialize();
        $.ajax({
          type: 'post',
          url: "ajax/call_functions.php",
          dataType: 'json',
          data: data_serialize,
          beforeSend: function () { // Before Send to Ajax
            $('#submit').attr('disabled', true);
            $('#load_page').show();
          },
          complete: function () { // After complete the Ajax
            $('#submit').attr('disabled', false);
            $('#load_page').hide();
          },
          success: function (response) { // Success
            if (response.status == '0') { // Failure Response
              $('#txt_password').val('');
              $('#submit').attr('disabled', false);
              $("#id_error_display_signin").html(response.msg);
            } else if (response.status == 1) { // Success Response
              $('#submit').attr('disabled', false);
              var hid_sendurl = $("#hid_sendurl").val();
              window.location = hid_sendurl;
            }
          },
          error: function (response, status, error) { // If any error occurs
            $('#txt_password').val('');
            $('#submit').attr('disabled', false);
            $("#id_error_display_signin").html(response.msg);
          }
        });
      }
    });
    // Sign in submit Button function End
    /******************Sign up validation*****************************/
    
    const upload_limit = 5; //Maximum 2 MB
    // File type validation
    $("#profile_display_pic").change(function () {
      // xls, xlsx, csv, txt
      var file = this.files[0];
      var fileType = file.type;
      var match = ['image/jpeg', 'image/jpg', 'image/png'];
      if (!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]) || (fileType == match[4]) || (fileType == match[5]))) {
        $("#id_error_display_signup").html('Sorry, only PNG, JPG files are allowed to upload.');
        $("#profile_display_pic").val('');
        return false;
        e.preventDefault();
      }
      const size = this.files[0].size / 1024 / 1024;
      if (size < upload_limit) { return true; }
      else {
        $("#id_error_display_signup").html('Maximum File size allowed - ' + upload_limit + ' MB. Kindly reduce and choose below ' + upload_limit + ' MB');
        $("#profile_display_pic").val('');
        return false;
        e.preventDefault();
      }
    });
    $("#proof_document").change(function () {
      // xls, xlsx, csv, txt
      var file = this.files[0];
      var fileType = file.type;
      var match = ['application/pdf'];
      if (!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]) || (fileType == match[4]) || (fileType == match[5]))) {
        $("#id_error_display_signup").html('Sorry,Only PDF files are allowed to be uploaded..');
        $("#proof_document").val('');

        return false;
        e.preventDefault();
      }
    });

    document.body.addEventListener("click", function (evt) {
      $("#id_error_display_signup").html("");
 $("#id_error_display_onboarding").html("");
    })
// Sign up submit Button function Start
$(document).on("submit", "form#frm_signup", function (e) {
// $("#submit_signup").click(function (e) {
      $("#id_error_display_signup").html("");
      e.preventDefault();
       //get input field values 
       var clientname_txt = $('#clientname_txt').val();
      var contact_person_txt = $('#contact_person_txt').val();
      var designation_txt = $('#designation_txt').val();
      var mobile_no_txt = $('#mobile_no_txt').val();
      var email_id_contact = $('#email_id_contact').val();
      var bill_address = $('#bill_address').val();
      var business_name = $('#business_name').val();
      var cpy_website_details = $('#cpy_website_details').val();
      var parent_company_name = $('#parent_company_name').val();
      var cpy_display_name = $('#cpy_display_name').val();
      var desp_business_txt = $('#desp_business_txt').val();
      var reg_add_business = $('#reg_add_business').val();
      var email_id_txt = $('#email_id_txt').val();
      var contact_no_cmpy = $('#contact_no_cmpy').val();
      
      var profile_display_pic = $('#profile_display_pic').val();
      var slt_service_category = $('#slt_service_category').val();
      var sender_id_txt = $('#sender_id_txt').val();
      var sender_id_txt_1_txt = $('#sender_id_txt_1_txt').val();
      var sender_id_txt_2_txt = $('#sender_id_txt_2_txt').val();
      var type_of_message = $('#type_of_message').val();
      var otp_in_process = $('#otp_in_process').val();
      var enquiry_approve_txt = $('#enquiry_approve_txt').val();
      var privacy_terms_txt = $('#privacy_terms_txt').val();
      var terms_condition_txt = $('#terms_condition_txt').val();
      var proof_document_slt = $('#proof_document_slt').val();
      var proof_document = $('#proof_document').val();
      var expected_volumes_day = $('#expected_volumes_day').val();
       var flag = true;

      /********validate all our form fields***********/
      if (clientname_txt == "") {
        $('#clientname_txt').css('border-color', 'red');
        flag = false;
      }
      if (contact_person_txt == "") {
        $('#contact_person_txt').css('border-color', 'red');
        flag = false;
      }
      if (designation_txt == "") {
        $('#designation_txt').css('border-color', 'red');
        flag = false;
      }
      if (bill_address == "") {
        $('#bill_address').css('border-color', 'red');
        flag = false;
      }
      if (email_id_contact == "") {
        $('#email_id_contact').css('border-color', 'red');
        flag = false;
      }

      if (business_name == "") {
        $('#business_name').css('border-color', 'red');
        flag = false;
      }
      if (cpy_website_details == "") {
        $('#cpy_website_details').css('border-color', 'red');
        flag = false;
      }
      if (parent_company_name == "") {
        $('#parent_company_name').css('border-color', 'red');
        flag = false;
      }
      if (cpy_display_name == "") {
        $('#cpy_display_name').css('border-color', 'red');
        flag = false;
      }
      if (desp_business_txt == "") {
        $('#desp_business_txt').css('border-color', 'red');
        flag = false;
      }

      if (reg_add_business == "") {
        $('#reg_add_business').css('border-color', 'red');
        flag = false;
      }
      if (email_id_txt == "") {
        $('#email_id_txt').css('border-color', 'red');
        flag = false;
      }
      if (contact_no_cmpy == "") {
        $('#contact_no_cmpy').css('border-color', 'red');
        flag = false;
      }
      if (profile_display_pic == "") {
        $('#profile_display_pic').css('border-color', 'red');
        flag = false;
      }
      if (slt_service_category == "") {
        $('#slt_service_category').css('border-color', 'red');
        flag = false;
      }

      if (sender_id_txt == "") {
        $('#sender_id_txt').css('border-color', 'red');
        flag = false;
      }
      if (sender_id_txt_1_txt == "") {
        $('#sender_id_txt_1_txt').css('border-color', 'red');
        flag = false;
      }
      if (sender_id_txt_2_txt == "") {
        $('#sender_id_txt_2_txt').css('border-color', 'red');
        flag = false;
      }
      if (type_of_message == "") {
        $('#type_of_message').css('border-color', 'red');
        flag = false;
      }
      if (otp_in_process == "") {
        $('#otp_in_process').css('border-color', 'red');
        flag = false;
      }

      if (enquiry_approve_txt == "") {
        $('#enquiry_approve_txt').css('border-color', 'red');
        flag = false;
      }
      if (privacy_terms_txt == "") {
        $('#privacy_terms_txt').css('border-color', 'red');
        flag = false;
      }
      if (terms_condition_txt == "") {
        $('#terms_condition_txt').css('border-color', 'red');
        flag = false;
      }
      if (proof_document_slt == "") {
        $('#proof_document_slt').css('border-color', 'red');
        flag = false;
      }
      if (proof_document == "") {
        $('#proof_document').css('border-color', 'red');
        flag = false;
      }

      if (expected_volumes_day == "") {
        $('#expected_volumes_day').css('border-color', 'red');
        flag = false;
      }

      if (mobile_no_txt == "") {
        $('#mobile_no_txt').css('border-color', 'red');
        flag = false;
      }
      var mobile_no_txt = document.getElementById('mobile_no_txt').value;
      if (mobile_no_txt.length != 10) {
        $("#id_error_display_onboarding").html("Please enter a valid mobile number");
        flag = false;
        document.getElementById('mobile_no_txt').focus();
      }
      if (!(mobile_no_txt.charAt(0) == "9" || mobile_no_txt.charAt(0) == "8" || mobile_no_txt.charAt(0) == "6" || mobile_no_txt.charAt(0) == "7")) {
        $("#id_error_display_onboarding").html("Please enter a valid mobile number");
        document.getElementById('mobile_no_txt').focus();
        flag = false;
      }
      /************************************/
      if (contact_no_cmpy == "") {
        $('#contact_no_cmpy').css('border-color', 'red');
        flag = false;
      }
      var contact_no_cmpy = document.getElementById('contact_no_cmpy').value;
      if (contact_no_cmpy.length != 10) {
       document.getElementById('contact_no_cmpy').focus();
        $("#id_error_display_onboarding").html("Please enter a valid mobile number");
        flag = false;
      }
      if (!(contact_no_cmpy.charAt(0) == "9" || contact_no_cmpy.charAt(0) == "8" || contact_no_cmpy.charAt(0) == "6" || contact_no_cmpy.charAt(0) == "7")) {
        $("#id_error_display_onboarding").html("Please enter a valid mobile number");
        document.getElementById('contact_no_cmpy').focus();
        flag = false;
      }
      /*************File Validation******************/
      const upload_limit = 5; //Maximum 2 MB
      // File type validation
      $("#profile_display_pic").change(function() {
        // xls, xlsx, csv, txt
        var file = this.files[0];
        var fileType = file.type;
        var match = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]) || (fileType == match[4]) || (fileType == match[5]))) {
          $("#id_error_display_onboarding").html('Sorry, only PNG, JPG files are allowed to upload.');
          $("#profile_display_pic").val('');
          return false;
        }
        const size = this.files[0].size / 1024 / 1024;
        if (size < upload_limit) {
          return true;
        } else {
          $("#id_error_display_onboamadhu@gmail.comrding").html('Maximum File size allowed - ' + upload_limit + ' MB. Kindly reduce and choose below ' + upload_limit + ' MB');
          $("#profile_display_pic").val('');
          return false;
        }
      });
      
      $("#proof_document").change(function() {
        // xls, xlsx, csv, txt
        var file = this.files[0];
        var fileType = file.type;
        var match = ['application/pdf'];
        if (!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]) || (fileType == match[4]) || (fileType == match[5]))) {
          $("#id_error_display_onboarding").html('Sorry,Only PDF files are allowed to be uploaded..');
          $("#proof_document").val('');
          return false;
        }
      });

  var sender_id_txt = document.getElementById('sender_id_txt').value;
      if (sender_id_txt.length != 10) {
        $("#id_error_display_onboarding").html("Please enter a valid mobile number");
        flag = false;
        document.getElementById('sender_id_txt').focus();
      }
      if (!(sender_id_txt.charAt(0) == "9" || sender_id_txt.charAt(0) == "8" || sender_id_txt.charAt(0) == "6" || sender_id_txt.charAt(0) == "7")) {
        $("#id_error_display_onboarding").html("Please enter a valid mobile number");
        document.getElementById('sender_id_txt').focus();
        flag = false;
      }

      var email_id_contact = $('#email_id_contact').val();
      /* Email field validation  */
      var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
      if (filter.test(email_id_contact)) {
        // flag = true;
      } else {
          document.getElementById('email_id_contact').focus();
        $("#id_error_display_onboarding").html("Email is invalid");
        flag = false;
        e.preventDefault();
      }
      var email_id_txt = $('#email_id_txt').val();
      /* Email field validation  */
      var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
      if (filter.test(email_id_txt)) {
        // flag = true;
      } else {
        document.getElementById('email_id_txt').focus();
        $("#id_error_display_onboarding").html("Email is invalid");
        flag = false;
        e.preventDefault();
      }
      if($("#chk_terms").prop('checked') == true){
        
          }else{
            $("#id_error_display_onboarding").html("Please select the terms & conditions");
            flag = false;
        e.preventDefault();
          }

      /********Validation end here ****/

      /* If all are ok then we send ajax request to call_functions.php *******/
      if (flag) {
        var data_serialize = $("#frm_signup").serialize();
        var fd = new FormData(this);
        var profilePicInput = $('#profile_display_pic')[0];
    if (profilePicInput && profilePicInput.files.length > 0) {
      fd.append('profile_display_pic', profilePicInput.files[0]);
    }

    // Make sure the element with ID 'document_proof' exists in the DOM
    var documentProofInput = $('#document_proof')[0];
    if (documentProofInput && documentProofInput.files.length > 0) {
      fd.append('document_proof', documentProofInput.files[0]);
    }
        $.ajax({
          type: 'post',
          url: "ajax/call_functions.php?temp_call_function=onboarding_signup",
          dataType: 'json',
          data: fd,
          contentType: false,
          processData: false,
          beforeSend: function () { // Before Send to Ajax
            $('#submit').attr('disabled', true);
             $('.theme-loader').show();
            $("#id_error_display_onboarding").html("");
          },
          complete: function () { // After complete the Ajax
            $('#submit').attr('disabled', true);
             $('.theme-loader').hide();
          },
          success: function (response) { // Success
 //alert(response.status);
            if (response.status == 0) { // Failure Response
              $('#submit').attr('disabled', false);
              $("#id_error_display_onboarding").html(response.msg);
               } else if (response.status == 1) { // Success Response
              $('#submit').attr('disabled', false);
              $("#id_error_display_onboarding").html(response.msg);
              $('#clientname_txt').val('');
              $('#contact_person_txt').val('');
              $('#designation_txt').val('');
              $('#mobile_no_txt').val('');
              $('#email_id_contact').val('');
              $('#bill_address').val('');
              $('#business_name').val('');
              $('#cpy_website_details').val('');
              $('#parent_company_name').val('');
              $('#cpy_display_name').val('');
              $('#desp_business_txt').val('');
              $('#reg_add_business').val('');
              $('#email_id_txt').val('');
              $('#contact_no_cmpy').val('');
              $('#profile_display_pic').val('');
              $('#slt_service_category').val('');
              $('#sender_id_txt').val('');
              $('#sender_id_txt_1_txt').val('');
              $('#sender_id_txt_2_txt').val('');
              $('#type_of_message').val('');
              $('#otp_in_process').val('');
              $('#enquiry_approve_txt').val('');
              $('#privacy_terms_txt').val('');
              $('#terms_condition_txt').val('');
              $('#proof_document_slt').val('');
              $('#proof_document').val('');
              $('#expected_volumes_day').val('');
              setInterval(function() {
              window.location = 'index';
              }, 2000);
            }else if(response.status == 2){
//alert(response.msg);
              $('#submit').attr('disabled', true);
              $("#id_error_display_onboarding").html(response.msg);
            }
          },
          error: function (response, status, error) { // If any error occurs
            $('#txt_password').val('');
            $('#submit').attr('disabled', false);
            $("#id_error_display_onboarding").html(response.msg);
          }
        });
      }
    });
    // Sign in submit Button function End
    
  
    function address_validation(e) {
  // Accept only alphanumeric characters, spaces, '/', ',', '.', and '-'
  var key = e.key;
  var allowedCharacters = /^[a-zA-Z0-9/\s,.-]$/;
  if (allowedCharacters.test(key) || key === " ") {
    return true;
  } 
  return false;
}


    function clsAlphaNoOnly(e) { // Accept only alpha numerics, no special characters 
	var key = e.keyCode;
	if ((key >= 65 && key <= 90) || (key >= 97 && key <= 122) || (key >= 48 && key <= 57) || (key == 32) || (key == 95)) {
		return true;
	}
	return false;
}
  
// TEMplate Name - Space
$(function() {
        $('#clientname_txt').on('keypress', function(e) {
            if (e.which == 32){
                console.log('Space Detected');
                return false;
           }
        });
});
$(function() {
        $('#contact_person_txt').on('keypress', function(e) {
            if (e.which == 32){
                console.log('Space Detected');
                return false;
           }
        });
});
  $(function() {
        $('#txt_username').on('keypress', function(e) {
            if (e.which == 32){
                console.log('Space Detected');
                return false;
           }
        });
});
function single_quote_validation(e) {
  // Accept only alphanumeric characters, spaces, '/', ',', '.', and '-'
  var key = e.key;
  var allowedCharacters = /^[a-zA-Z0-9\[\]\$\!\#\%\^\&\\(\)\-\+\=\-\;\:\,\.\?\@\_ ]$/;
  if (allowedCharacters.test(key) || key === " ") {
    return true;
  } 
  return false;
}

function single_quote(e) {
  // Accept only alphanumeric characters, spaces, '/', ',', '.', '-', and some special characters
  var key = e.key;
  var allowedCharacters = /^[a-zA-Z0-9\[\]\$\!\#\%\^\&\/\(\)\-\+\=\-\;\:\,\.\?\@\_\ ]$/;
  if (allowedCharacters.test(key) || key === " ") {
    return true;
  } 
  return false;
}
  </script>
</body>
</html>

