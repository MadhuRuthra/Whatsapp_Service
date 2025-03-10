<?php
/*
Authendicated users to use this site menu page for all main pages.
This page is used to give the menu details for all main pages.
It is used to some main activities are used.

Version : 1.0
Author : Madhubala (YJ0009)
Date : 01-Jul-2023
*/

// To Send the request API 
$replace_txt = '{
  "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
}';
// Add bearer token
$bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
  // It will call "approve_payment" API to verify, can we can we allow to view the message credit list
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL =>$api_url . '/list/approve_payment',
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
$response = curl_exec($curl);
site_log_generate("Approve Payment Page : " . $uname . " Execute the service [$replace_txt,$bearer_token] on " . date("Y-m-d H:i:s"), '../');
curl_close($curl);
// After got response decode the JSON result
$sms = json_decode($response, false);
$waiting_approval = $sms->num_of_rows ? $sms->num_of_rows : 0;
?>
<!-- sidebar menu -->
<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="dashboard"><img src="assets/img/cm-logo.png" style="height:100%" /></a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="dashboard"><img src="assets/img/cm.png" style="height:100%" /></a>
    </div>
    <ul class="sidebar-menu">
      <li <? if ($site_page_name == 'dashboard') { ?>class="dropdown active" <? } else { ?>class="dropdown" <? } ?>>
        <a href="dashboard" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
      </li>

      <li <? if ($site_page_name == 'manage_store' or $site_page_name == 'approve_whatsapp_no' or $site_page_name == 'purchase_message_credit' or $site_page_name == 'purchase_message_list' or $site_page_name == 'manage_store_list' or $site_page_name == 'manage_whatsappno_list' or $site_page_name == 'manage_whatsapp_no' or $site_page_name == 'whatsapp_no_api_list' or $site_page_name == 'whatsapp_no_api' or $site_page_name == 'approve_whatsapp_no_api' or $site_page_name == 'template_list' or $site_page_name == 'create_template' or $site_page_name == 'approve_template') { ?>class="dropdown active" <? } else { ?>class="dropdown" <? } ?>>
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
          <span>Manage</span></a>
        <ul class="dropdown-menu">

          <? /*if ($_SESSION['yjwatsp_user_permission'] == 1 or $_SESSION['yjwatsp_user_permission'] == 3) { ?>
            <? if ($_SESSION['yjwatsp_user_permission'] == 1) { ?>
              <li class="menu-header">Scan</li>
            <? } ?>
            <li <? if ($site_page_name == 'manage_whatsapp_no' or $site_page_name == 'manage_whatsappno_list') { ?>class="active" <? } ?>><a class="nav-link" href="manage_whatsappno_list">Sender ID</a></li>
            <? if ($_SESSION['yjwatsp_user_master_id'] != 4) { ?>
              <li <? if ($site_page_name == 'approve_whatsapp_no') { ?>class="active" <? } ?>><a class="nav-link"
                  href="approve_whatsapp_no">Approve Sender ID</a></li>
            <? } ?>
          <? } */?>

          <? if ($_SESSION['yjwatsp_user_permission'] == 1 or $_SESSION['yjwatsp_user_permission'] == 2) { ?>
            <? if ($_SESSION['yjwatsp_user_permission'] == 1) { ?>
              <!-- <li class="menu-header">OTP</li> -->
            <? } ?>
            <li <? if ($site_page_name == 'whatsapp_no_api' or $site_page_name == 'whatsapp_no_api_list') { ?>class="active" <? } ?>><a class="nav-link" href="whatsapp_no_api_list">Sender ID</a></li>
            <? if ($_SESSION['yjwatsp_user_master_id'] == 1) { ?>
              <li <? if ($site_page_name == 'approve_whatsapp_no_api') { ?>class="active" <? } ?>><a class="nav-link"
                  href="approve_whatsapp_no_api">Approve Sender ID</a></li>
            <? } ?>
            <li <? if ($site_page_name == 'template_list' or $site_page_name == 'create_template') { ?>class="active" <? } ?>>
              <a class="nav-link" href="template_list">Template List</a></li>
          <? } ?>

          <? if ($_SESSION['yjwatsp_user_master_id'] != 1) { ?>              
	          <li <? if ($site_page_name == 'purchase_message_credit') { ?>class="active" <? } ?>><a class="nav-link" href="purchase_message_credit">Purchase Message Credit</a></li>
        	  <li <? if ($site_page_name == 'purchase_message_list') { ?>class="active" <? } ?>><a class="nav-link" href="purchase_message_list">Payment History</a></li>	
	  <? } ?>
        </ul>
      </li>

      <li <? if ($site_page_name == 'compose_whatsapp' or $site_page_name == 'whatsapp_list' or $site_page_name == 'compose_template_whatsapp' or $site_page_name == 'template_whatsapp_list' or $site_page_name == 'messenger_responses') { ?>class="dropdown active" <? } else { ?>class="dropdown" <? } ?>>
        <a href="#" class="nav-link has-dropdown"><i class="fab fa-whatsapp"></i> <span>Campaign</span></a>
        <ul class="dropdown-menu">
          <?/* if ($_SESSION['yjwatsp_user_permission'] == 1 or $_SESSION['yjwatsp_user_permission'] == 3) { ?>
            <? if ($_SESSION['yjwatsp_user_permission'] == 1) { ?>
              <li class="menu-header">Scan</li>
            <? } ?>
            <li <? if ($site_page_name == 'compose_whatsapp') { ?>class="active" <? } ?>><a class="nav-link"
                href="compose_whatsapp">Compose</a></li>
            <li <? if ($site_page_name == 'whatsapp_list') { ?>class="active" <? } ?>><a class="nav-link"
                href="whatsapp_list">Whatsapp List</a></li>
          <? } */?>

          <? if ($_SESSION['yjwatsp_user_permission'] == 1 or $_SESSION['yjwatsp_user_permission'] == 2) { ?>
            <? if ($_SESSION['yjwatsp_user_permission'] == 1) { ?>
              <!-- <li class="menu-header">OTP</li> -->
            <? } ?>
            <li <? if ($site_page_name == 'compose_template_whatsapp') { ?>class="active" <? } ?>><a class="nav-link"
                href="compose_template_whatsapp">Compose</a></li>
            <li <? if ($site_page_name == 'template_whatsapp_list') { ?>class="active" <? } ?>><a class="nav-link"
                href="template_whatsapp_list">Whatsapp List</a></li>
            <li <? if ($site_page_name == 'messenger_responses') { ?>class="active" <? } ?>><a class="nav-link"
                href="messenger_responses">Messenger Response</a></li>
          <? } ?>
        </ul>
      </li>

      <? if ($_SESSION['yjwatsp_user_master_id'] == 1 or $_SESSION['yjwatsp_user_master_id'] == 2 or $_SESSION['yjwatsp_user_master_id'] == 3) { ?>
        <? if ($_SESSION['yjwatsp_user_master_id'] == 1 or $_SESSION['yjwatsp_user_master_id'] == 2) { ?>
          <li <? if ($site_page_name == 'message_credit_list' or $site_page_name == 'request_demo_list' or $site_page_name == 'activation_payment_list' or $site_page_name == 'message_credit' or $site_page_name == 'approve_payment' or $site_page_name == 'manage_users_list' or $site_page_name == 'manage_users' or $site_page_name == 'user_repository' or $site_page_name == 'view_onboarding') { ?>class="dropdown active" <? } else { ?>class="dropdown" <? } ?>>
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-crown"></i>
              <span style="margin-left: 10px;">Admin </span><? if($waiting_approval > 0) { ?><span class="badge badge-success" style="width: auto; min-width:30px; font-weight: bold; margin-left: 10px; margin-right: 20px; line-height: 20px;"><?=$waiting_approval?></span><? } ?></a>
            <ul class="dropdown-menu">
              <li <? if ($site_page_name == 'approve_payment' or $site_page_name == 'message_credit') { ?>class="active" <? } ?>><a class="nav-link" href="approve_payment">Approve Payment <? if($waiting_approval > 0) { ?><span class="badge badge-success" style="width: auto; min-width:30px; margin-left: 10px; line-height: 20px; font-weight: bold;"><?=$waiting_approval?></span><? } ?></a></li>

              <? /* <li <? if ($site_page_name == 'message_credit') { ?>class="active" <? } ?>><a class="nav-link"
                  href="message_credit">Add Message Credit</a></li> */ ?>
              <li <? if ($site_page_name == 'message_credit_list') { ?>class="active" <? } ?>><a class="nav-link"
                  href="message_credit_list">Message Credit List</a></li>

              <? if ($_SESSION['yjwatsp_user_master_id'] == 1) { ?>
                <li <? if ($site_page_name == 'manage_users_list' or $site_page_name == 'manage_users' or $site_page_name == 'user_repository' or $site_page_name == 'view_onboarding') { ?>class="active" <? } ?>><a class="nav-link"
                    href="manage_users_list">Manage Users</a></li>
		<li <? if ($site_page_name == 'activation_payment_list') { ?>class="active" <? } ?>><a class="nav-link"
                    href="activation_payment_list">Activation Payment List</a></li>
		<li <? if ($site_page_name == 'request_demo_list') { ?>class="active" <? } ?>><a class="nav-link"
                    href="request_demo_list">Demo Request List</a></li>
              <? } ?>
            </ul>
          </li>
        <? } ?>

        <li <? if ($site_page_name == 'summary_report' or $site_page_name == 'details_report' or $site_page_name == 'business_summaries_report' or $site_page_name == 'business_details_report') { ?>class="dropdown active" <? } else { ?>class="dropdown" <? } ?>>
          <a href="#" class="nav-link has-dropdown"><i class="fas fa-chart-bar"></i> <span>Reports</span></a>
          <ul class="dropdown-menu">
            <?/* if ($_SESSION['yjwatsp_user_permission'] == 1 or $_SESSION['yjwatsp_user_permission'] == 3) { ?>
              <? if ($_SESSION['yjwatsp_user_permission'] == 1) { ?>
                <li class="menu-header">Scan</li>
              <? } ?>
              <li <? if ($site_page_name == 'summary_report') { ?>class="active" <? } ?>><a class="nav-link"
                  href="summary_report">Summary Report</a></li>
              <li <? if ($site_page_name == 'details_report') { ?>class="active" <? } ?>><a class="nav-link"
                  href="details_report">Details Report</a></li>
            <? }*/ ?>

            <? if ($_SESSION['yjwatsp_user_permission'] == 1 or $_SESSION['yjwatsp_user_permission'] == 2) { ?>
              <? if ($_SESSION['yjwatsp_user_permission'] == 1) { ?>
                <!-- <li class="menu-header">OTP</li> -->
              <? } ?>
              <li <? if ($site_page_name == 'business_summaries_report') { ?>class="active" <? } ?>><a class="nav-link"
                  href="business_summaries_report">Summary Report</a></li>
              <li <? if ($site_page_name == 'business_details_report') { ?>class="active" <? } ?>><a class="nav-link"
                  href="business_details_report">Detailed Report</a></li>
            <? } ?>
          </ul>
        </li>
      <? } ?>
    </ul>

  </aside>
</div>


