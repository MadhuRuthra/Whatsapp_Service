<?php
/*
Authendicated users details getting to navigate this call function page.
This page is used to give the API request to get the API response.
Api response is used to getting all data from the database.

Version : 1.0
Author : Madhubala (YJ0009)
Date : 01-Jul-2023
*/

session_start(); //start session
error_reporting(E_ALL); // The error reporting function
include_once "../api/configuration.php"; // Include configuration.php
extract($_REQUEST); // Extract the request
$bearer_token = "Authorization: " . $_SESSION["yjwatsp_bearer_token"] . ""; // To get bearertoken
$current_date = date("Y-m-d H:i:s"); // To get currentdate function

// Index Page Signin - Start
if ($_SERVER["REQUEST_METHOD"] == "POST" and $call_function == "signin") {
    // Get data
    $uname = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["txt_username"])
                ? $conn->real_escape_string($_REQUEST["txt_username"])
                : ""
        )
    );
    $password = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["txt_password"])
                ? $conn->real_escape_string($_REQUEST["txt_password"])
                : ""
        )
    );
    $upass = md5($password); // For authenticating digital signatures
    $ip_address = $_SERVER["REMOTE_ADDR"]; // To Get IP address
    site_log_generate("Index Page : Username => " .$uname . " trying to login on " .date("Y-m-d H:i:s"),"../"
);
// To Send the request  API
    $replace_txt =
        '{
    "txt_username" : "' .
        $uname .
        '",
    "txt_password" : "' .
        $password .
        '"
  }';
    // It will call "p_login" API to verify, can we allow to login the already existing user for access the details
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $api_url . "/login/p_login",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $replace_txt,
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    ]);
     // Send the data into API and execute  
    // Log file generate
    site_log_generate(
        "Index Page : " .
            $uname .
            " Execute the service [$replace_txt] on " .
            date("Y-m-d H:i:s"),
        "../"
    );
    $response = curl_exec($curl);
    curl_close($curl);
     // After got response decode the JSON result
    $state1 = json_decode($response, false); 
     // Log file generate
    site_log_generate(
        "Index Page : " .
            $uname .
            " get the Service response [$response] on " .
            date("Y-m-d H:i:s"),
        "../"
    );
// To get the API response one by one data
    if ($state1->response_code == 1) {
        for (
            $indicator = 0;
            $indicator < count($state1->response_result);
            $indicator++
        ) {
            $_SESSION["yjwatsp_parent_id"] =
                $state1->response_result[$indicator]->parent_id;
            $_SESSION["yjwatsp_user_id"] =
                $state1->response_result[$indicator]->user_id;
            $_SESSION["yjwatsp_user_master_id"] =
                $state1->response_result[$indicator]->user_master_id;
            $_SESSION["yjwatsp_user_name"] =
                $state1->response_result[$indicator]->user_name;
            $_SESSION["yjwatsp_api_key"] =
                $state1->response_result[$indicator]->api_key;
            $_SESSION["yjwatsp_user_permission"] =
                $state1->response_result[$indicator]->user_permission;
            $_SESSION["yjwatsp_bearer_token"] =
                $state1->response_result[$indicator]->bearer_token;
            $_SESSION["yjwatsp_login_id"] =
                $state1->response_result[$indicator]->login_id;
            $_SESSION["yjwatsp_user_email"] =
                $state1->response_result[$indicator]->user_email;
            $_SESSION["yjwatsp_user_mobile"] =
                $state1->response_result[$indicator]->user_mobile;
            $_SESSION["yjwatsp_price_per_sms"] =
                $state1->response_result[$indicator]->price_per_sms;
            $_SESSION["yjwatsp_netoptid"] =
                $state1->response_result[$indicator]->network_operators_id;
            $_SESSION["yjwatsp_usraprstat"] =
                $state1->response_result[$indicator]->user_approval_status;
            $_SESSION["yjwatsp_login_time"] = $state1->login_time;
        }
 // To log file generate
        site_log_generate(
            "Index Page : " .
                $uname .
                " logged in success on " .
                date("Y-m-d H:i:s"),
            "../"
        );
        $json = ["status" => 1, "info" => $result];
    } else { // otherwise it willbe execute 
        // Log file generate
        site_log_generate(
            "Index Page : " .
                $uname .
                " logged in failed [$state1->response_msg] on " .
                date("Y-m-d H:i:s"),
            "../"
        );
        $json = ["status" => 0, "msg" => $state1->response_msg];
    }
}
// Index Page Signin - End

// Manage Users Page signup - Start
if ($_SERVER["REQUEST_METHOD"] == "POST" and $call_function == "signup") {
    // Get data
    $user_type = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["slt_user_type"]) ? $_REQUEST["slt_user_type"] : ""
        )
    );
    $user_name = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["txt_loginid"]) ? $_REQUEST["txt_loginid"] : ""
        )
    );
    $user_email = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["txt_user_email"])
                ? $_REQUEST["txt_user_email"]
                : ""
        )
    );
    $user_mobile = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["txt_user_mobile"])
                ? $_REQUEST["txt_user_mobile"]
                : ""
        )
    );

    $slt_super_admin = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["slt_super_admin"])
                ? $_REQUEST["slt_super_admin"]
                : ""
        )
    );
    $slt_dept_admin = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["slt_dept_admin"])
                ? $_REQUEST["slt_dept_admin"]
                : ""
        )
    );

    $loginid = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["txt_loginid"]) ? $_REQUEST["txt_loginid"] : ""
        )
    );
    $txt_login_shortname = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["txt_login_shortname"])
                ? $_REQUEST["txt_login_shortname"]
                : ""
        )
    );
    // Sign user - default password for all user
    $user_password = "Password@123";
    $confirm_password = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["txt_confirm_password"])
                ? $_REQUEST["txt_confirm_password"]
                : ""
        )
    );
    // Default User permission
    $user_permission = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["user_permission"])
                ? $_REQUEST["user_permission"]
                : "3"
        )
    );
     // Log file generate
    site_log_generate(
        "Manage Users Page : " .
            $loginid .
            " trying to create a new account in our site on " .
            date("Y-m-d H:i:s"),
        "../"
    );
    $user_short_name = $txt_login_shortname;
// To Send the request API
    $replace_txt =
    '{
        "user_type" : "' .$user_type .'",
        "user_name" : "' .$user_name .'",
        "user_email" : "' .$user_email .'",
        "user_mobile" : "' .$user_mobile .'",
        "slt_super_admin" : "'. $slt_super_admin .'",
        "slt_dept_admin" : "' .$slt_dept_admin .'",
        "login_shortname" : "' .$user_short_name .'",
        "user_password" : "' .$user_password .'",
       "user_permission" : "' .$user_permission .'"
  }';
 // It will call "signup" API to verify, can we allow to  the Add the new user for signup option 
    $bearer_token = "Authorization: " . $_SESSION["yjwatsp_bearer_token"] . "";
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $api_url . "/login/signup",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $replace_txt,
        CURLOPT_HTTPHEADER => [$bearer_token, "Content-Type: application/json"],
    ]);
   // Send the data into API and execute 
    site_log_generate(
        "Manage Users Page : " .
            $_SESSION["yjwatsp_user_name"] .
            " Execute the service [$replace_txt] on " .
            date("Y-m-d H:i:s"),
        "../"
    );
    $response = curl_exec($curl);
    curl_close($curl);
  // After got response decode the JSON result
    $header = json_decode($response, false); 
     // To Generate the log file
    site_log_generate(
        "Manage Users Page : " .
            $_SESSION["yjwatsp_user_name"] .
            " get the Service response [$response] on " .
            date("Y-m-d H:i:s"),
        "../"
    );

    if ($header->num_of_rows > 0) { // If the response is success to execute this condition
        site_log_generate(
            "Manage Users Page : " .
                $user_name .
                " account created successfully on " .
                date("Y-m-availd H:i:s"),
            "../"
        );
        $json = ["status" => 1, "msg" => "New User created. Kindly login!!"];
    } else if ($header->response_status == 204){
        site_log_generate(
            "Manage Users Page : " .
                $user_name .
                " account creation Failed [$header->response_msg] on " .
                date("Y-m-d H:i:s"),
            "../"
        );
        $json = ["status" => 2, "msg" => $header->response_msg];
    } else { // otherwise it willbe execute
        site_log_generate(
            "Manage Users Page : " .
                $user_name .
                " account creation Failed [$header->response_msg] on " .
                date("Y-m-d H:i:s"),
            "../"
        );
        $json = ["status" => 0, "msg" => $header->response_msg];
    }
}
// Manage Users Page signup - End

// Manage Users Page display_login_id - Start
if (
    $_SERVER["REQUEST_METHOD"] == "POST" and
    $tmpl_call_function == "display_login_id"
) {
    site_log_generate(
        "Manage Users - Generate Login ID Page : User : " .
            $_SESSION["yjwatsp_user_name"] .
            " access the page on " .
            date("Y-m-d H:i:s"),
        "../"
    );
    // Get data
    $slt_user_type = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["slt_user_type"])
                ? $conn->real_escape_string($_REQUEST["slt_user_type"])
                : ""
        )
    );
    $slt_super_admin = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["slt_super_admin"])
                ? $conn->real_escape_string($_REQUEST["slt_super_admin"])
                : ""
        )
    );
    $slt_dept_admin = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["slt_dept_admin"])
                ? $conn->real_escape_string($_REQUEST["slt_dept_admin"])
                : ""
        )
    );
    $bearer_token = "Authorization: " . $_SESSION["yjwatsp_bearer_token"] . "";
    if ($slt_user_type == 2) {
        // To Send the request API For Super Admin
        $replace_txt =
            '{
      "user_id" : "' .
            $_SESSION["yjwatsp_user_id"] .
            '",
      "user_type" : "' .
            $slt_user_type .
            '"
    }';
    } elseif ($slt_user_type == 3) {
        // To Send the request API For Dept Admin
        $replace_txt =
            '{  
      "user_id" : "' .
            $_SESSION["yjwatsp_user_id"] .
            '",
      "user_type" : "' .
            $slt_user_type .
            '",
      "super_admin" : "' .
            $slt_super_admin .
            '"
    }';
    } elseif ($slt_user_type == 4) {
        // To Send the request API For Agent
        $replace_txt =
            '{
      "user_id" : "' .
            $_SESSION["yjwatsp_user_id"] .
            '",
      "user_type" : "' .
            $slt_user_type .
            '",
      "super_admin" : "' .
            $slt_super_admin .
            '",
      "dept_admin" : "' .
            $slt_dept_admin .
            '"
    }';
    }
    // Add bearer token
    $bearer_token = "Authorization: " . $_SESSION["yjwatsp_bearer_token"] . "";
// It will call "username_generate" API to verify, can we Auto generate the Add the New Username,Login Id option
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $api_url . "/list/username_generate",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => $replace_txt,
        CURLOPT_HTTPHEADER => [$bearer_token, "Content-Type: application/json"],
    ]);
     // Send the data into API and execute 
    site_log_generate(
        "Manage Users - Generate Login ID Page : " .
            $_SESSION["yjwatsp_user_name"] .
            " Execute the service [$replace_txt] on " .
            date("Y-m-d H:i:s"),
        "../"
    );
    $response = curl_exec($curl);
    curl_close($curl);
      // After got response decode the JSON result
    $header = json_decode($response, false);
    site_log_generate(
        "Manage Users - Generate Login ID Page : " .
            $_SESSION["yjwatsp_user_name"] .
            " get the Service response [$response] on " .
            date("Y-m-d H:i:s"),
        "../"
    );

    if ($header->num_of_rows > 0) { // If the response is success to execute this condition
        for ($indicator = 0; $indicator < $header->num_of_rows; $indicator++) {
            $replace_login_id = $header->report;
        }
        $json = ["status" => 1, "msg" => $replace_login_id];
    }  else if($header->response_status == 204){
        site_log_generate("Manage Whatsappno List Page  : " . $user_name . "get the Service response [$header->response_msg] on " . date("Y-m-d H:i:s"), '../');
        $json = array("status" => 0, "msg" => $header->response_msg);
      }
      else { //Otherwise it will be execute
        $json = ["status" => 0, "msg" => $header->response_msg];
    }
}
// Manage Users Page display_login_id - End

// Compose Whatsapp Page senderid_template - Start
if (
    $_SERVER["REQUEST_METHOD"] == "POST" and
    $tmpl_call_function == "senderid_template"
) {
    site_log_generate(
        "Compose Whatsapp - Validate Campaign Page : User : " .
            $_SESSION["yjwatsp_user_name"] .
            " access the page on " .
            date("Y-m-d H:i:s"),
        "../"
    );
    // Get data
    $slt_whatsapp_template = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["slt_whatsapp_template"])
                ? strtolower($_REQUEST["slt_whatsapp_template"])
                : ""
        )
    );
    $expl = explode("!", $slt_whatsapp_template);
      // To Send the request API Load Templates
    $load_templates =
        '{
      "template_id" : "' .
        $expl[3] .
        '"
  }';
    site_log_generate(
        "Compose Whatsapp - Validate Campaign Page : User : " .
            $_SESSION["yjwatsp_user_name"] .
            " executed the query ($load_templates) on " .
            date("Y-m-d H:i:s")
    );
    // Add bearer token
    $bearer_token = "Authorization: " . $_SESSION["yjwatsp_bearer_token"] . "";
 // It will call "p_get_template_numbers" API to verify, can we use the template details
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $api_url . "/template/p_get_template_numbers",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $load_templates,
        CURLOPT_HTTPHEADER => [$bearer_token, "Content-Type: application/json"],
    ]);
     // Send the data into API and execute 
    $response = curl_exec($curl);
    curl_close($curl);
      // After got response decode the JSON result
    $state1 = json_decode($response, false);
    //generate the log file
    site_log_generate(
        "Compose Whatsapp - Validate Campaign Page : User : " .
            $_SESSION["yjwatsp_user_name"] .
            " executed the query response ($response) on " .
            date("Y-m-d H:i:s")
    );

    $rsmsg .= '<table style="width: 100%;">';

    if ($state1->response_code == 1) { // If the response is success to execute this condition
        for ($indicator = 0; $indicator < count($state1->data); $indicator++) {
            $cntmonth =
                $state1->data[$indicator]->available_credit -
                $state1->data[$indicator]->sent_count;
            if ($cntmonth > 0) {
                if ($indicator % 2 == 0) {
                    $rsmsg .= "<tr>";
                }
                $rsmsg .=
                    '<td>
          <input type="checkbox" checked class="cls_checkbox" id="txt_whatsapp_mobno" name="txt_whatsapp_mobno[]" tabindex="1" autofocus value="' .
                    $state1->data[$indicator]->store_id .
                    "~~" .
                    $state1->data[$indicator]->whatspp_config_id .
                    "~~" .
                    $state1->data[$indicator]->country_code .
                    $state1->data[$indicator]->mobile_no .
                    "~~" .
                    $state1->data[$indicator]->bearer_token .
                    "~~" .
                    $whatsapp_tmplate_url .
                    $state1->data[$indicator]->whatsapp_business_acc_id .
                    "~~0~~" .
                    $whatsapp_tmplate_url .
                    $state1->data[$indicator]->phone_number_id .
                    '"> <label class="form-label"> ' .
                    $state1->data[$indicator]->country_code .
                    $state1->data[$indicator]->mobile_no .
                    " [Avl. Credits : <b>" .
                    $cntmonth .
                    '</b>]</label>
        </td>';

                if ($indicator % 2 == 1) {
                    $rsmsg .= "</tr>";
                }
            }
        }
    }  else if($state1->response_status == 204){
        site_log_generate("Compose Whatsapp - Validate Campaign Page  : " . $user_name . "get the Service response [$state1->response_status] on " . date("Y-m-d H:i:s"), '../');
        $json = array("status" => 2, "msg" => $state1->response_msg);
      }else {
        site_log_generate("Compose Whatsapp - Validate Campaign Page : " . $user_name . " get the Service response [$state1->response_msg] on  " . date("Y-m-d H:i:s"), '../');
        $json = array("status" => 0, "msg" => $state1->response_msg);
      }

    $rsmsg .= "</table>";
    $json = ["status" => 1, "msg" => $rsmsg];
}
// Compose Whatsapp Page senderid_template - End

// Change Password Page change_pwd - Start
if (
    $_SERVER["REQUEST_METHOD"] == "POST" and
    $pwd_call_function == "change_pwd"
) {
    site_log_generate(
        "Change Password Page : User : " .
            $_SESSION["yjwatsp_user_name"] .
            " access the page on " .
            date("Y-m-d H:i:s"),
        "../"
    );
    // Get data
    $ex_password = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["txt_ex_password"])
                ? $_REQUEST["txt_ex_password"]
                : ""
        )
    );
    $new_password = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["txt_new_password"])
                ? $_REQUEST["txt_new_password"]
                : ""
        )
    );
    $ex_pass = md5($ex_password); // For authenticating digital signatures
    $upass = md5($new_password); // For authenticating digital signatures
  // To Send the request API 
    $replace_txt =
        '{
    "user_id" : "' .
        $_SESSION["yjwatsp_user_id"] .
        '",
    "ex_password" : "' .
        $ex_pass .
        '",
    "new_password" : "' .
        $upass .
        '"
  }';
  // Add bearer token
    $bearer_token = "Authorization: " . $_SESSION["yjwatsp_bearer_token"] . "";
// To Get Api Response URL
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $api_url . "/list/change_password",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $replace_txt,
        CURLOPT_HTTPHEADER => [$bearer_token, "Content-Type: application/json"],
    ]);
     // Send the data into API and execute 
    site_log_generate(
        "Change Password Page : " .
            $_SESSION["yjwatsp_user_name"] .
            " Execute the service [$replace_txt] on " .
            date("Y-m-d H:i:s"),
        "../"
    );
    $response = curl_exec($curl);
    curl_close($curl);
  // After got response decode the JSON result
    $header = json_decode($response, false);
    site_log_generate(
        "Change Password Page : " .
            $_SESSION["yjwatsp_user_name"] .
            " get the Service response [$response] on " .
            date("Y-m-d H:i:s"),
        "../"
    );
// To get the response message
    $json = [
        "status" => $header->response_code,
        "msg" => $header->response_msg,
    ];
}
// Change Password Page change_pwd - End

// Message Credit Page find get_available_balance - Start
if (isset($_POST["get_available_balance"]) == "get_available_balance") {
      // Get data
    $txt_receiver_user = htmlspecialchars(
        strip_tags(
            isset($_POST["txt_receiver_user"])
                ? $conn->real_escape_string($_POST["txt_receiver_user"])
                : ""
        )
    );
    $expl = explode("~~", $txt_receiver_user); // explode function using
// To Send the request API 
    $replace_txt =
        '{
    "user_id" : "' .$_SESSION["yjwatsp_user_id"] .'",
    "select_user_id" : "' .$expl[0] .'"
  }';
   // Add bearer token
    $bearer_token = "Authorization: " . $_SESSION["yjwatsp_bearer_token"] . "";
     // It will call "available_credits" API to verify, can we view the available credits to the particular user
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $api_url . "/list/available_credits",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => $replace_txt,
        CURLOPT_HTTPHEADER => [$bearer_token, "Content-Type: application/json"],
    ]);
      // Send the data into API and execute
    site_log_generate(
        "Message Credit Page : " .
            $_SESSION["yjwatsp_user_name"] .
            " Execute the service [$replace_txt] on " .
            date("Y-m-d H:i:s"),
        "../"
    );
    $response = curl_exec($curl);
    curl_close($curl);
 // After got response decode the JSON result
    $header = json_decode($response, false);
    site_log_generate(
        "Message Credit Page : " .
            $_SESSION["yjwatsp_user_name"] .
            " get the Service response [$response] on " .
            date("Y-m-d H:i:s"),
        "../"
    );

    if ($header->num_of_rows > 0) { // If the response is success to execute this condition
        for ($indicator = 0; $indicator < $header->num_of_rows; $indicator++) {
            $stateData = $header->report[$indicator]->available_messages;
        }
        $json = ["status" => 1, "msg" => "Available Credits : " . $stateData];
    }   else if($header->response_status == 204){
        site_log_generate("Add Message Credit Page   : " . $user_name . "get the Service response [$header->response_status] on " . date("Y-m-d H:i:s"), '../');
        $json = array("status" => 2, "msg" => $header->response_msg);
      }else { // Otherwise It willbe execute
        site_log_generate("Add Message Credit Page  : " . $user_name . " get the Service response [$header->response_msg] on  " . date("Y-m-d H:i:s"), '../');
        $json = [
            "status" => 0,
            "msg" =>
                "Invalid Inputs. Kindly try again with the correct Inputs!",
        ];
    }
}
// Message Credit Page find get_available_balance - End

// Manage Users Page find display_dept_admin - Start
if (
    $_SERVER["REQUEST_METHOD"] == "POST" and
    $tmpl_call_function == "display_dept_admin"
) {
     // Get data
    $slt_user_type = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["slt_user_type"])
                ? $conn->real_escape_string($_REQUEST["slt_user_type"])
                : ""
        )
    );
    $slt_super_admin = htmlspecialchars(
        strip_tags(
            isset($_REQUEST["slt_super_admin"])
                ? $conn->real_escape_string($_REQUEST["slt_super_admin"])
                : ""
        )
    );
    // To Send the request API 
    $replace_txt =
        '{
    "user_id" : "' .
        $_SESSION["yjwatsp_user_id"] .
        '",
    "super_admin" : "' .
        $slt_super_admin .
        '"
  }';
   // Add bearer token
    $bearer_token = "Authorization: " . $_SESSION["yjwatsp_bearer_token"] . "";
        // It will call "display_dept_admin" API to verify, can we display the department head admin
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $api_url . "/list/display_dept_admin",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => $replace_txt,
        CURLOPT_HTTPHEADER => [$bearer_token, "Content-Type: application/json"],
    ]);
// Send the data into API and execute  
    $response = curl_exec($curl);
    curl_close($curl);
    site_log_generate(
        "Manage Users Page : " .
            $_SESSION["yjwatsp_user_name"] .
            " Execute the service [$replace_txt] on " .
            date("Y-m-d H:i:s"),
        "../"
    );
    $response = curl_exec($curl);
    curl_close($curl);
      // After got response decode the JSON result
    $header = json_decode($response, false);
    site_log_generate(
        "Manage Users Page : " .
            $_SESSION["yjwatsp_user_name"] .
            " get the Service response [$response] on " .
            date("Y-m-d H:i:s"),
        "../"
    );

    if ($header->num_of_rows > 0) { // If the response is success to execute this condition
        for ($indicator = 0; $indicator < $header->num_of_rows; $indicator++) {
            $stateData .=
                '<option value="' .
                $header->report[$indicator]->user_id .
                "~~" .
                $header->report[$indicator]->user_short_name .
                '">' .
                $header->report[$indicator]->user_name .
                "</option>";
        }
        $json = ["status" => 1, "msg" => $stateData];
    } else if($header->response_status == 204){
        site_log_generate("Manage Users Page  : " . $user_name . "get the Service response [$header->response_status] on " . date("Y-m-d H:i:s"), '../');
        $json = array("status" => 2, "msg" => $header->response_msg);
      } else { // Otherwise It willbe execute
        $json = ["status" => 0, "msg" => "NO Data Available!"];
    }
}
// Manage Users Page find display_dept_admin - End

// Finally Close all Opened Mysql DB Connection
$conn->close();

// Output header with JSON Response
header("Content-type: application/json");
echo json_encode($json);

