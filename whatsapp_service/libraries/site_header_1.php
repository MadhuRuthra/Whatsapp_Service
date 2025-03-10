<?php
$replace_txt = '{
  "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '",
  "login_time" : "' . $_SESSION['yjwatsp_login_time'] . '"
}';
$bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].''; 
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $api_url . '/list/login_time',
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
   $bearer_token,
  'Content-Type: application/json'
  ),
)
);
site_log_generate("Header Page : " . $_SESSION['yjwatsp_user_name'] . " Execute the service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
$response = curl_exec($curl);
curl_close($curl);
$header = json_decode($response, false);
site_log_generate("Header Page : " . $_SESSION['yjwatsp_user_name'] . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');

if ($header->num_of_rows > 0) {
  for ($indicator = 0; $indicator < $header->num_of_rows; $indicator++) {
    if ($header->report[$indicator]->user_log_status == 'O') { ?>
      <script>window.location = "logout";</script>
      <?php exit();
    }
  }
}
  if($header->response_status == 403){?>
    <script>window.location = "logout";</script>
    <?php exit();
  }

$replace_txt = '{
  "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
}';
$bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].''; 
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $api_url . '/list/available_credits',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_POSTFIELDS => $replace_txt,
  CURLOPT_HTTPHEADER => array(
     $bearer_token,
    'Content-Type: application/json'
  ),
)
);
site_log_generate("Header Page : " . $_SESSION['yjwatsp_user_name'] . " Execute the service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
$response = curl_exec($curl);
curl_close($curl);

$header = json_decode($response, false);
site_log_generate("Header Page : " . $_SESSION['yjwatsp_user_name'] . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');

if ($header->num_of_rows > 0) {
  for ($indicator = 0; $indicator < $header->num_of_rows; $indicator++) {
    $total_messages = $header->report[$indicator]->total_messages;
    $available_messages = $header->report[$indicator]->available_messages;
    $_SESSION['yjwatsp_available_messages'] = $header->report[$indicator]->available_messages;
  }
}
?>
<style>
span.autoShowHide {
  white-space: nowrap; 
  width: 200px; 
  overflow: hidden;
  text-overflow: ellipsis;
}

span.autoShowHide:hover {
  white-space: normal; 
  overflow: visible;
  width: 300px; 
}
</style>
<nav class="navbar navbar-expand-lg main-navbar">
  <form class="form-inline mr-auto">
    <ul class="navbar-nav mr-3">
      <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    </ul>
  </form>

  <div class="search-element">
	<div><? /* <span class="badge badge-secondary autoShowHide" style="color:#FFF; font-size:18px; font-weight: bold; text-align: right;">APK Key : <span title="Click here to Copy the API Key" onclick="copyContent()" id='id_apikey_copy'><?= ($_SESSION['yjwatsp_api_key']) ?></span></span>&nbsp;*/ ?><span class="badge badge-secondary" style="color:#FFF; font-size:18px; font-weight: bold; text-align: right;">Available Credits : <?= $available_messages ? indian_money_format($available_messages) : 0 ?></span></div>
  </div>

  <ul class="navbar-nav navbar-right">
    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
        <img alt="image" src="assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
        <div class="d-sm-none d-lg-inline-block">Hi,
          <?= strtoupper($_SESSION['yjwatsp_user_name']) ?>
        </div>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="change_password" class="dropdown-item has-icon">
          <i class="fas fa-bolt"></i> Change Password
        </a>
        <div class="dropdown-divider"></div>
        <a href="logout" class="dropdown-item has-icon text-danger">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </div>
    </li>
  </ul>
</nav>

<script>
  let text = document.getElementById('id_apikey_copy').innerHTML;
  const copyContent = async () => {
    try {
      await navigator.clipboard.writeText(text);
      console.log('Content copied to clipboard');
    } catch (err) {
      console.error('Failed to copy: ', err);
    }
  }
</script>
