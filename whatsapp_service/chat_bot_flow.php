<?php
session_start();//start session
error_reporting(0);// The error reporting function
include_once "api/configuration.php";// Include configuration.php
extract($_REQUEST);
if ($_SESSION["yjwatsp_user_id"] == "") { ?>
    <script>window.location = "index";</script>
  <?php exit();
}

$site_page_name = pathinfo($_SERVER["PHP_SELF"], PATHINFO_FILENAME);
site_log_generate(
  " Page : User : " .
  $_SESSION["yjwatsp_user_name"] .
  " access the page on " .
  date("Y-m-d H:i:s")
);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>New Whatsapp Bot :: <?= $site_title ?></title>
  <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <!-- General CSS Files -->
  <script src="assets/draggable_line/plain-draggable.min.js"></script>

  <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- CSS Libraries -->


  <!-- <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
  <!-- arrow lines using -->
  <script src="assets/leader_line/leader-line.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <!-- style include in css -->
  <style>
    textarea {
      resize: none;
    }
    /* .section-body{
        background-image: url('assets/img/bg.jpg');
        width:100%;
        height:1000px;
       
    } */
    .container1{
        position:relative; 
        margin:30px;    
  top:10px;
  display: inline-block;
  width: 300px;
   max-height: 700px;
  border: 3px solid #73AD21;
  background-color:white;
    }

.circle_plus:hover {
    cursor: pointer;
     transform: scale(-1.1); 
  box-shadow: 2px 2px 2px grey, -2px -2px 2px grey;
}
.center{
  /* left:50px; */
  text-align: center;  
}
.button {
    position: absolute;
    top: 50%;
}
.container-box{
  width: 350px;
  max-height:700px;
  display: inline-block;
  border: 3px solid #73AD21;
  background-color:white;
}
.container-list_box{
  width: 350px;
  max-height:700px;
  display: inline-block;
  /* height: 250px; */
  border: 3px solid #73AD21;
  background-color:white;
}

.container-reply_box{
  width: 350px;
  /* height: 250px; */
  max-height:400px;
  display: inline-block;
  border: 3px solid #73AD21;
  background-color:white;
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
<!-- include sitemenu adding -->
      <? include("libraries/site_menu.php"); ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>New Whatsapp Bot</h1>
            <div class="col-sm"> 
           
                      </div>
            <div class="section-header-breadcrumb">           
              <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
              <div class="breadcrumb-item active"><a href="template_list">Template List</a></div>
              <div class="breadcrumb-item">New Whatsapp Bot</div>             
            </div>
            
          </div>
          <div class="section-body">
          
          <form class="needs-validation" novalidate="" id="frm_compose_whatsapp" name="frm_compose_whatsapp"
                    action="#" method="post" enctype="multipart/form-data">
            <!-- <div class= "container"> -->
            <span class="error_display" id='id_error_display' style="left:1000px;margin-left:0px; position:relative;"></span>
            <div class="row right_align" style="float:right;" >
            <!-- <div class = "col-sm-6"> -->
              <input type="button" onclick="myFunction_clear()" value="Clear" class="btn btn-success" id="clr_button" style="text-align: center; ">
                      <input type="submit" name="submit" id="submit" tabindex="26" value=" Save & Submit"
                        class="btn btn-success" style="text-align: center; ">

                        <input type="submit" name="show_submit" id="show_submit" tabindex="26" value=" Sender Id" onclick="showsender_id_checkbox()"
                        class="btn btn-success" style="text-align: center; ">
                      <!-- </div> -->
                        <!-- <div class = "col-sm-1"><span class="error_display" id='id_error_display' style="float:right;left:30px;margin-left:30px; position:relative;">hello</span></div> -->
                        
</div>
            <div class="row">
            
            <!-- <div class="col-12 col-md-12 col-lg-12"> -->
                <div class = "col-sm-4">
                     <div class="container1">
                    <div class="row"> 
    <div class="col-sm-6">
    <i class="fa fa-flag-checkered" style="font-size:40px;left:40px;margin-top:10px; position:relative; 
  top:10px; "></i> 
    </div>
    <div class="col-sm-6">
    <p><b>Starting Point</b></p>
    <i class='far fa-hand-point-down' style='font-size:48px;color:green;margin-left:20px;'></i>     
    </div>
   
      <div class="col-10" style="left:20px;float:right;">
    <label class="col-form-label"> Pattern Name <label style="color:#FF0000">*</label><span data-toggle="tooltip"
                            data-original-title=" Pattern Name allowed maximum 30 Characters.">[?]</span></label><input type="text" id="start_name" name="start_name" class="form-control"  required  tabindex="11"  placeholder="Start" maxlength="30" size="15">
                            <input type="text" id="restart_name" name="restart_name" class="form-control"  required  tabindex="11"  placeholder="Restart" maxlength="30" size="15">
                            <input type="text" id="invalid_name" name="invalid_name" class="form-control"  required  tabindex="11"  placeholder="Invalid" maxlength="30" size="15">
                            
                            <!-- dialouge -->
                            <div class="col- sm - 4 add_dio_box"><ul><br><a class="btn btn-warning mb-1" href="javascript:void(0)"  id="add_msg_url" onclick="preview_open_msg()" tabindex="5"  style="padding: 0.3rem 0rem !important; width:140px"><i class="far fa-comment-dots"></i>Send Messages</a><br><a class="btn btn-warning mb-1" href="javascript:void(0)"  tabindex="5" style="padding: 0.3rem 0rem !important;  width:140px" id="add_reply_url" onclick="preview_open_reply()"><i class="fas fa-reply-all"></i>Reply Buttons</a><br><a class="btn btn-warning mb-1" href="javascript:void(0)" tabindex="5"  id="add_list_url" style="padding: 0.3rem 0rem !important; width:140px" onclick="preview_open_list()"><i class="fa fa-list"></i>List Buttons</a></ul></div>
<!-- Message -->
                            <div class="row open_msg" style="bottom:10px;display:none;"><div class="container "><div class="row "><div class="col" ><i class="	far fa-comment-dots" style="font-size:25px; position:relative;left:20px; top:10px; "></i> <b style="font-size:25px; position:relative;left:20px; top:10px; " >Messages</b></div></div></br><div class="col- sm - 4" id="textarea"><textarea id ="txt_area_msg" class="form-control txt_count" name="txtarea_msg"   maxlength="1024" tabindex="11"  placeholder="Enter Body Content" rows="6" style="width: 100%; height: 150px !important;"></textarea> </div><div class="row" style="right: 0px;"><div class="col-sm"> <span id="current_text_value">0</span><span>/ 1024</span> ​<a href="#!" name="btn" type="button" id="btn_variable" tabindex="12" style="float:right;position:relative;" class="btn btn-success"> + Add variable</a></div></div> </div><div class="col- sm - 3">
                            <img src="assets/img/circle.png" width="30px" height="20px"  style="position:relative;float:right;bottom:120px; left:15px;"  class="circle_plus" onclick="image()"  >
                           </div></div>
                  <!-- Message end -->    
                   
                  <!-- Reply button -->
                  <div class=" open_reply"  style="display:none;" id="reply_button" ><div class="row "><div class="col" ><i class="fab fa-r-project" style="font-size:25px; position:relative; top:5px; "></i> <b style="font-size:20px;">Reply Buttons</b><button class="btn btn-outline-secondary " style="position:relative;  width:110px;  left:150px;" type="button" id="add_button">Add Button</button><div class="input-group child_div"><input type="text" class="form-control" placeholder="Reply Button" name="reply_button_st[]" id= "reply_button" aria-label="Reply Button"><img src="assets/img/circle.png" width="25px" height="20px"  onclick="image()" style=" position:relative;  float:right; left:10px; bottom:30px; top:10px;" class="circle_plus_1"  >
                </div><div class=" add_reply_wrapper"></div>
                  <textarea id ="txt_area_reply" class="form-control txt_count_rpy" name="txtarea_reply"   maxlength="1024" tabindex="11"  placeholder="Enter Body Content" rows="6" style="width: 100%; height: 150px !important;"></textarea><div class="row" style="right: 0px;"><div class="col-sm"> <span id="current_text_value_rp">0</span><span>/ 1024</span> ​<a href="#!" name="btn" type="button" id="btn_variable_rpy" tabindex="12" style="float:right;position:relative;" class="btn btn-success"> + Add variable</a></div></div></div>
                </div><div></div></div></br>
<!-- Reply button end -->
<!-- List Button -->
<div class="open_list" style="display:none;" id="list_button"><div class="row "><div class="col"><i class="fa fa-list" style="font-size:25px; position:relative;left:15px; top:5px; "></i> <b style="left:30px;position:relative;font-size:20px ">List Buttons</b></br><button class="btn btn-outline-secondary " style="position:relative;  width:100px;  left:140px;" type="button" id="add_list_btn">Add Button</button> <div class="input-group  child_div_list"><input type="text" class="form-control"  placeholder="Button Text" name=" button_txt_st" id= "list_button" aria-label="List Button"></div>
<div class="input-group"><input type="text" class="form-control"  placeholder="List Button" name="list_button_st[]" id= "button_txt" aria-label="Button Text"><img src="assets/img/circle.png" width="25px" height="20px"  class="circle_plus1"   onclick="image()" style=" position:relative; float:right; left:20px;top:10px;"  ></div> <div class=" add_list_wrapper "></div>
<textarea id ="txt_area_list" class="form-control txt_count_list" name="txtarea_list_st"  maxlength="1024" tabindex="11"  placeholder="Enter Body Content" rows="6" style="width: 100%; height: 150px !important;"></textarea><div class="row" style="right: 0px;"><div class="col-sm"> <span id="current_text_value_list">0</span><span>/ 1024</span> ​<a href="#!" name="btn" type="button" id="btn_variable_list" tabindex="12" style="float:right;position:relative;" class="btn btn-success"> + Add variable</a></div></div></br> </div> 
<!-- List Button end -->
</div>
</div>  </div>
</div>
  
</div></div> 


<div class="col-sm-5 "> 
       <div class="row  add_dialog">  


      
    </div>
  </div> 
  
</div>

<input type='hidden' name="hidden_max_value" id="hidden_max_value"
                            />
<input type='hidden' name="hidden_sender_id[]" id="hidden_sender_id"
                            />
</div>         
</form>
        </section>

      </div>
      <? include("libraries/site_footer.php"); ?>
    </div>
  </div>
    <!-- Modal content-->
    <div class="modal fade" id="default-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" >
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Choose SenderId</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form class="needs-validation" novalidate="" id="model_sender_id" name="model_sender_id"
                    action="#" method="post" enctype="multipart/form-data">
  <ul  aria-labelledby="dropdownMenu1"><?php
  $replace_txt = '{
      "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '",
      "status_filter" :"active"
    }';
    $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url . '/list/sender_id_list',
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
      // echo $response;
      curl_close($curl);
      $sms = json_decode($response, false);
      site_log_generate("Manage Whatsappno List Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');

      // print_r($sms); exit;
      $indicatori = 0;
      if ($sms->num_of_rows > 0) {
        for ($indicator = 0; $indicator < $sms->num_of_rows; $indicator++) {
          // $indicatori++;
         $mobile_no = $sms->sender_id[$indicator]->country_code . $sms->sender_id[$indicator]->mobile_no;
        //  echo  $mobile_no;
         ?>
        <li> 
      <input class="formcheckinput" type="checkbox" id="formcheckinput" value="<?= $mobile_no?>">
      <label class="formchecklabel" for="inlineCheckbox1"><?= $mobile_no?></label>
  </li>
      <?  }
      }?>
  </ul> </form>
  <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="getsender_id" data-dismiss="modal" >Close</button>
      </div>   
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
  <!-- <script src="script.js"></script> -->
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/custom.js"></script>

  <script src="assets/js/xlsx.core.min.js"></script>
  <script src="assets/js/xls.core.min.js"></script>
  <script>

        // FORM Clear value
        var sender_ids = [];
        var bot_array = [];  
        var z_value_start = [];
        var hidden_mx_val ;
  

        var s1 = '';
 var s2 = '';
function myFunction_clear() {
  document.getElementById("frm_compose_whatsapp").reset();
  window.location.reload();
}

function showsender_id_checkbox(){
  $("#id_modal_display :input").attr("disabled", true);
      $('#default-Modal').modal({ show: true });
      $('#default-Modal').show();
}

// function getsender_id(){
  
//   var checkedValue = document.querySelector('.formcheckinput:checked').value;
//   alert(checkedValue);
// }



function preview_open_msg(){
  $('.circle_plus').attr('id', 'addcirclebtn_0');
  console.log("*msg");
  $('.open_msg').css("display", "block");
  var textarea = document.getElementById('txt_area_msg');
var i = 1;
const btn = document.getElementById('btn_variable');
btn.addEventListener('click', function handleClick_1() {
  if (i <= 12) {
  textarea.value += '{{' + i++ + '}}';
  }
});
$(".txt_count").keyup(function(){
  $("#current_text_value").text($(this).val().length);
});
  $('.add_dio_box').css("display", "none");
}


function preview_open_reply(){
  $('.circle_plus_1').attr('id','addcirclebtn_0');
  console.log("*reply");
  $('.open_reply').css("display", "block");
  var textarea = document.getElementById('txt_area_reply');
var i = 1;
const btn = document.getElementById('btn_variable_rpy');
btn.addEventListener('click', function handleClick_1() {
  if (i <= 12) {
  textarea.value += '{{' + i++ + '}}';
  }
});
$(".txt_count_rpy").keyup(function(){
  $("#current_text_value_rp").text($(this).val().length);
});
var z = 1 ;
 const txt_reply =[];
 var check_reply_btn = document.getElementById('add_button');
 check_reply_btn.addEventListener('click', function add_rpy_btn() {
  var wrapper_dialog_1 = $('.add_reply_wrapper');
  if (z <= 2) { 
    txt_reply.push(z);   
    // console.log(txt_reply);
$(wrapper_dialog_1).append('<div class="input-group child_div"><input type="text" class="form-control" placeholder="Reply Button" name="reply_button_st[]" id= "reply_button_st" aria-label="Reply Button"><a href="#" class="deleteButton'+z+' btn btn-danger">remove</a><img src="assets/img/circle.png" width="25px" height="20px"  class="circle_plus'+z+'" id="addcirclebtn_0'+z+'" style=" position:relative; float:right; left:10px; bottom:30px; top:10px;"></div>');
z_value_start.push(z); 
// console.log(z_value_start);  
$(document).on("click", '#addcirclebtn_0'+z+'',function() {
  console.log("image()calling");
  console.log( x++ +"increment");
  image();
});

$(document).on("click", '.deleteButton'+z+'', function() {
    $(this).closest('.child_div').remove();
    var w = (z-- )-1;
    // console.log(w+"removez--"); 
    // console.log(txt_reply);
    const index = txt_reply.indexOf(w);
    // console.log(index);
    txt_reply.splice(index, 1);
    // console.log(txt_reply+"txt");
    let length = txt_reply.length;
    z = length;
    // console.log(z +"--> zvalue set ");
    // console.log("!@#$%^&*(");
  console.log(  z++ +"increment value" );
});
// console.log("!@#$%^&*(");
  console.log(  z++ +"increment value" );
  } 
})
  $('.add_dio_box').css("display", "none");
}

function preview_open_list(){
  $('.circle_plus1').attr('id', 'addcirclebtn_0');
  $('.open_list').css("display", "block");
  var textarea = document.getElementById('txt_area_list');
var i = 1;
const btn = document.getElementById('btn_variable_list');
btn.addEventListener('click', function handleClick_1() {
  if (i <= 12) {
  textarea.value += '{{' + i++ + '}}';
  }
});
$(".txt_count_list").keyup(function(){
  $("#current_text_value_list").text($(this).val().length);
});
var zz = 1 ;
 var txt_list =[];
var check_list_btn = document.getElementById('add_list_btn');
check_list_btn.addEventListener('click', function add_list() {
  var wrapper_dialog_1 = $('.add_list_wrapper');
  if (zz <= 9) {
    txt_list.push(zz);
$(wrapper_dialog_1).append('<div class="input-group mx-auto child_div_list"><input type="text" class="form-control"  placeholder="List Button" name="list_button_st[]" id= "list_button'+zz+'" aria-label="Reply Button"><a href="#" class="deleteButton_list'+zz+' btn btn-danger">remove</a><img src="assets/img/circle.png" width="25px" height="20px"  class="circle_plus_list'+zz+'"  id="addcirclebtn_0'+zz+'" style=" position:relative; float:right; bottom:30px;left:20px; top:10px;"></div>');
z_value_start.push(zz); 
// console.log(z_value_start); 
$(document).on("click", '#addcirclebtn_0'+zz+'', function() {
  console.log( x++ +"increment");
  image();
});
$(document).on("click", '.deleteButton_list'+zz+'', function() {
    $(this).closest('.child_div_list').remove();
    var w = (zz-- )-1;
    // console.log(w+"removez--"); 
    // console.log(txt_list);
    const index = txt_list.indexOf(w);
    // console.log(index);
    txt_list.splice(index, 1);
    // console.log(txt_list+"txt");
    let length = txt_list.length;
    zz  = length;
    // console.log(zz +"--> zvalue set ");
    // console.log("!@#$%^&*(");
  console.log(  zz++ +"increment value" );
});
// console.log("!@#$%^&*(");
  console.log(  zz++ +"increment value" );

}
});
  $('.add_dio_box').css("display", "none");
}


// console.log(z_value_start); 
var x = 1;
function  image(s_val,hidden_mx_val) {

  document.getElementById('hidden_max_value').value = x;
  var wrapper_dialog = $(".add_dialog");
  // console.log(x+"&&");
$(wrapper_dialog).append('<div class="col add_dio_'+x+'"><ul><br><a class="btn btn-warning mb-1" href="javascript:void(0)"  id="add_msg_url'+x+'" tabindex="5"  style="padding: 0.3rem 0rem !important; width:140px"><i class="far fa-comment-dots"></i>Send Messages</a><br><a class="btn btn-warning mb-1" href="javascript:void(0)"  tabindex="5" style="padding: 0.3rem 0rem !important;  width:140px" id="add_reply_url'+x+'"><i class="fas fa-reply-all"></i>Reply Buttons</a><br><a class="btn btn-warning mb-1" href="javascript:void(0)" tabindex="5"  id="add_list_url'+x+'" style="padding: 0.3rem 0rem !important; width:140px"><i class="fa fa-list"></i>List Buttons</a></ul></div> ');
hidden_mx_val = x;
document.getElementById('add_msg_url'+x+'').onclick = function() {add_msg_urlx()};
document.getElementById('add_reply_url'+x+'').onclick = function() {add_reply_urlx()};
document.getElementById('add_list_url'+x+'').onclick = function() {add_list_urlx()};

// auto_x_value = x + 1;
function add_msg_urlx(reply_id) {
  console.log(hidden_mx_val+"hidden_mx_valhidden_mx_valhidden_mx_val");
  bot_array.push("Message");
  console.log(bot_array);
  $('.add_dio_'+x+'').attr("style", "visibility:hidden");
$(wrapper_dialog).append('<div class="container container-box" id="line_end'+x+'"><div class="col" ><i class="	far fa-comment-dots" style="font-size:25px; position:relative;left:20px; top:10px; "></i> <b style="font-size:25px; position:relative;left:20px; top:10px; " >Messages</b></div><label class="col-form-label"> Message Name <label style="color:#FF0000">*</label><span data-toggle="tooltip"data-original-title=" Message Name allowed maximum 30 Characters.">[?]</span></label><input type="text" name="message_name_'+x+'" class="form-control" oninput="this.value = this.value.toLowerCase()" tabindex="11"  placeholder="Message Name" maxlength="30" size="4"></br><div class="col- sm - 4" id="textarea'+x+'"><textarea id ="textareamsg'+x+'" class="form-control txt_count'+x+'" name="textarea_msg_'+x+'" maxlength="1024" tabindex="11"  placeholder="Enter Body Content" rows="6" style="width: 100%; height: 150px !important;"></textarea> </div><div class="row" style="right: 0px;"><div class="col-sm"> <span id="current_text_value'+x+'">0</span><span>/ 1024</span> ​<a href="#!" name="btn" type="button" id="btn'+x+'" tabindex="12" style="float:right;position:relative;" class="btn btn-success"> + Add variable</a></div> </div></br> <img src="assets/img/circle.png" width="25px" height="20px"  style="bottom: 220px;position:relative;left:300px; cursor: default;"  class="circle_plus_'+x+'"   id="addcirclebtn_'+x+'" "></div>'); 

// console.log(z_value_start.length); 
var d = 0;
if(z_value_start.length > d){
    // console.log("if- -->msg");
  s1 = ''; s2 = '';
    console.log(z_value_start[d]+"zvalue");   
    console.log(z_value_start.length+"length");
    console.log('addcirclebtn_'+x+''+d+(x-1)+z_value_start.slice(-1));
s1 = document.getElementById('addcirclebtn_'+d+ z_value_start.pop());
s2 = document.getElementById('line_end'+x+'');
var myLine = new LeaderLine(s1,s2 ,{dash: {animation: true}}); 
myLine.setOptions({startSocket: 'right', endSocket: 'right'});
} 
        if(s_val){
// console.log(s_val+"sval*********************");
s1 = document.getElementById('addcirclebtn_'+(s_val)+'');
s2 = document.getElementById('line_end'+x+'');
var myLine = new LeaderLine((s1),s2 ,{dash: {animation: true}},
);
myLine.setOptions({startSocket: 'right', endSocket: 'right'});
        }
else{
console.log("else ---> msg");
// draggable = new PlainDraggable(document.getElementById('line'+(x-1)+''));
s1 = document.getElementById('addcirclebtn_'+(x-1)+'');
s2 = document.getElementById('line_end'+x+'');
var myLine = new LeaderLine((s1),s2 ,{dash: {animation: true}},
);
myLine.setOptions({startSocket: 'right', endSocket: 'right'});
}
// mouseHoverAnchor(startElement), endElement;
s1 = '';
 s2 ='';
    

//  draggable = new PlainDraggable(document.getElementById('line_end'+x+''));
var textarea = document.getElementById('textareamsg'+x+'');
var i = 1;
const btn = document.getElementById('btn'+x+'');
btn.addEventListener('click', function handleClick() {
  if (i <= 12) {
  textarea.value += '{{' + i++ + '}}';
  }
});
$(".txt_count"+x).keyup(function(){
  $("#current_text_value"+x).text($(this).val().length);
});

var check_plus = document.getElementById('addcirclebtn_'+x+'');
check_plus.addEventListener('click', function circle_plus(){
// document.getElementById('line'+x+'').onclick = function() {addplusx(this.id)};
// function addplusx() {
  var circle_id = this.id;
  alert(circle_id);
  var split_value = circle_id.split("_");
console.log(split_value[1]);
var s_val = split_value[1];
  // console.log("***");
  hidden_mx_val = x++ ;
  // document.getElementById('hidden_max_value').value = hidden_mx_val;
  alert(hidden_mx_val)
image(s_val,hidden_mx_val);
// console.log(x++);
// image(s_val);
})

}

 function add_reply_urlx(reply_id) {
  console.log(hidden_mx_val+"hidden_mx_valhidden_mx_valhidden_mx_val");
  // console.log(bot_array);
  bot_array.push("Replybutton_1");
  console.log(bot_array);
  $('.add_dio_'+x+'').attr("style", "visibility:hidden") ;
 $(wrapper_dialog).append('<div class="container container-reply_box" id="line_end'+x+'" ><div class="row "><div class="col" ><i class="fab fa-r-project" style="font-size:25px; position:relative; top:5px; "></i> <b>Reply Buttons</b><button class="btn btn-outline-secondary " style="position:relative;  width:110px;  left:42px;" type="button" id="addreplybt_'+x+'" >Add Button</button> </br>Offer Quick Response<label style="color:#FF0000">*</label><div class="input-group child_div"><input type="text" class="form-control" placeholder="Reply Name" name="reply_pattern_'+x+'" id= "reply_pattern'+x+'" aria-label="Reply Button"><input type="text" class="form-control" placeholder="Reply Button" name="'+x+'_reply_button_1" id= "reply_button" aria-label="Reply Button"><img src="assets/img/circle.png" width="25px" height="20px"  class="circle_plus_'+x+'" id="addcirclebtn_'+x+'" style=" position:relative; float:right; left:10px; bottom:30px; top:10px;" ></div><div class="add_reply'+x+'"></div><textarea id ="textareareply'+x+'" class="form-control txt_count_rpy'+x+'" name="textarea_reply'+x+'" maxlength="1024" tabindex="11"  placeholder="Enter Body Content" rows="6" style="width: 100%; height: 150px !important;"></textarea><div class="row" style="right: 0px;"><div class="col-sm"> <span id="current_text_value'+x+'">0</span><span>/ 1024</span> ​<a href="#!" name="btn" type="button" id="btn'+x+'" tabindex="12" style="float:right;position:relative;" class="btn btn-success"> + Add variable</a></div></div></br> '); 
//  console.log(z_value_start.length); 
var d = 0;

if(z_value_start.length > d){
  s1 = ''; s2 = '';
  console.log("if---->reply");
  console.log(z_value_start[d]+"zvalue");   
    console.log(z_value_start.length+"length");
    console.log('line'+d+(x-1)+z_value_start.slice(-1));
s1 = document.getElementById('addcirclebtn'+d+ z_value_start.pop());
s2 = document.getElementById('line_end'+x+'');
var myLine = new LeaderLine(s1,s2 ,{dash: {animation: true}}); 
myLine.setOptions({startSocket: 'right', endSocket: 'right'});
}   if(s_val){
// console.log(s_val+"sval*********************");
s1 = document.getElementById('addcirclebtn_'+(s_val)+'');
s2 = document.getElementById('line_end'+x+'');
var myLine = new LeaderLine((s1),s2 ,{dash: {animation: true}},
);
myLine.setOptions({startSocket: 'right', endSocket: 'right'});
        }
else{

// console.log("else ---> msg");
s1 = document.getElementById('addcirclebtn_'+(x-1)+'');
s2 = document.getElementById('line_end'+x+'');
var myLine = new LeaderLine((s1),s2 ,{dash: {animation: true}},
);
myLine.setOptions({startSocket: 'right', endSocket: 'right'});
}

var s1 = '';
var s2 ='';
 var textarea = document.getElementById('textareareply'+x+'');
var i = 1;
const btn = document.getElementById('btn'+x+'');
btn.addEventListener('click', function handleClick() {
  if (i <= 12) {
  textarea.value += '{{' + i++ + '}}';
  }
});
$(".txt_count_rpy"+x).keyup(function(){
  $("#current_text_value"+x).text($(this).val().length);
});
var check_plus = document.getElementById('addcirclebtn_'+x+'');
check_plus.addEventListener('click', function circle_plus(){
// document.getElementById('line'+x+'').onclick = function() {addplusx(this.id)};
// function addplusx() {
  var circle_id = this.id;
  alert(circle_id);
  var split_value = circle_id.split("_");
console.log(split_value[1]);
var s_val = split_value[1];
  console.log("image1");
  hidden_mx_val = x++ ;
  // document.getElementById('hidden_max_value').value = hidden_mx_val;
  alert(hidden_mx_val)
  // alert(hidden_mx_val)
image(s_val,hidden_mx_val);
  // console.log( x++ +"increment");
  // image(s_val);
});

var check_reply_btn = document.getElementById('addreplybt_'+x+'');
var z = 1 ;
 const txt_reply =[];
 check_reply_btn.addEventListener('click', function add_rpy_btn() { 
  // auto_x_value = x+1;
  var click_id = this.id;
  // alert(click_id);
 var split_value = click_id.split("_");
console.log(split_value[1]);
 x = split_value[1];

  var wrapper_dialog_1 = $('.add_reply'+x+'');
  if (z <= 2) {
console.log(z +"zpush");
    bot_array.splice((x-1),z, "Replybutton_"+(z+1));
    console.log(bot_array);
    txt_reply.push(z);
$(wrapper_dialog_1).append('<div class="input-group child_div"><input type="text" class="form-control" placeholder="Reply Button" name="'+x+'_reply_button_'+(z+1)+'" id= "reply_button_add'+z+'"aria-label="Reply Button"><a href="#" class="deleteButton'+x+''+z+' btn btn-danger">remove</a><img src="assets/img/circle.png" width="25px" height="20px"  class="circle_plus'+x+''+z+'"  id="addcirclebtn_'+x+''+z+'_'+x+'" style=" position:relative; float:right; left:10px; bottom:30px; top:10px;"></div>');

    $(document).on("click", '.circle_plus'+x+''+z+'', function() {
   
 var click_id = this.id;
  // alert(click_id);
 var split_value = click_id.split("_");
 var z_value_id = split_value[1];
var reply_id = split_value[2];
console.log(split_value[1]+"XVALUES")
var high_hidden_value = $('#hidden_max_value').val();
var high_hidden_value = +high_hidden_value+1;
console.log(high_hidden_value+"%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%");
  // console.log("image2");
  // // console.log( +x+1 +"incrementing");
  // hidden_mx_val = +x+1;
  // console.log(hidden_mx_val+"hidden_mx_valhidden_mx_valhidden_mx_val")
  // console.log(z_value_id+"zzzzzzzzzzzz");
  // console.log( auto_x_value+"CCCCCCCCCC" );
  // console.log(reply_id+"RRRRRRRRRRR");
  // image_1(auto_x_value,reply_id,z_value_id,high_hidden_value);
  image_1(reply_id,z_value_id,high_hidden_value);
});
$(document).on("click", '.deleteButton'+x+''+z+'', function() {
    $(this).closest('.child_div').remove();
    var w = (z-- )-1;
    // console.log(w+"removez--"); 
    // console.log(txt_reply);
    const index = txt_reply.indexOf(w);
    // console.log(index);
    txt_reply.splice(index, 1);
    if(w == '0'){
      bot_array.splice((x-1),z+1, "Replybutton_1");
    }else{
      bot_array.splice((x-1),z, "Replybutton_"+(w));
    }  
    // console.log(bot_array);
    // console.log(txt_reply+"txt");
    let length = txt_reply.length;
    z = length;
    // console.log(z +"--> zvalue set ");
    // console.log("!@#$%^&*(");
  console.log(  z++ +"increment value" );
});
// console.log("!@#$%^&*(");
  console.log(  z++ +"increment value" );
  } 
})
 }

 function add_list_urlx() {
  //  console.log(hidden_mx_val+"hidden_mx_valhidden_mx_valhidden_mx_val");
  bot_array.push("List_1");
console.log(bot_array);
$('.add_dio_'+x+'').attr("style", "visibility:hidden") ;
$(wrapper_dialog).append('<div class="container container-list_box" id="line_end'+x+'" ><div class="row "><div class="col" ><i class="fa fa-list" style="font-size:25px; position:relative; top:5px; "></i> <b style="left:30px;position:relative; ">List Buttons</b><button class="btn btn-outline-secondary " style="position:relative;  width:100px;  left:40px;" type="button" id="addlistbtn_'+x+'">Add Button</button> <label style="color:#FF0000; left:30px;position:relative;">Single Choice Menu*</label> <div class="input-group"><input type="text" class="form-control"  placeholder="List Name" name="list_pattern_'+x+'" id= "list_pattern" aria-label="Button Text"><input type="text" class="form-control"  placeholder="Button Text" name="button_txt_'+x+'" id= "button_txt" aria-label="Button Text"><div class="input-group child_div_list"><input type="text" class="form-control"  placeholder="List Button" name="'+x+'_list_button_1" id= "list_button'+x+'"aria-label="List Button"><img src="assets/img/circle.png" width="25px" height="20px"  class="circle_plus'+x+'" id="addcirclebtn_'+x+'" style=" position:relative; float:right; bottom:30px;left:10px;right:10px; top:10px;"></div></div><div class="add_list_'+x+'"></div><textarea id ="textarealist'+x+'" class="form-control txt_count_rpy'+x+'" name="textarea_list_'+x+'"  maxlength="1024" tabindex="11"  placeholder="Enter Body Content" rows="6" style="width: 100%; height: 150px !important;"></textarea><div class="row" style="right: 0px;"><div class="col-sm"> <span id="current_text_value'+x+'">0</span><span>/ 1024</span> ​<a href="#!" name="btn" type="button" id="btn'+x+'" tabindex="12" style="float:right;position:relative;" class="btn btn-success"> + Add variable</a></div></div></br> ');
// console.log(z_value_start.length); 
var d = 0;

if(z_value_start.length > d){
  s1 = ''; s2 = '';
  console.log("if---->reply");
  console.log(z_value_start[d]+"zvalue");   
    console.log(z_value_start.length+"length");
    console.log('line'+d+(x-1)+z_value_start.slice(-1));
s1 = document.getElementById('addcirclebtn_'+d+ z_value_start.pop());
s2 = document.getElementById('line_end'+x+'');
var myLine = new LeaderLine(s1,s2 ,{dash: {animation: true}}); 
myLine.setOptions({startSocket: 'right', endSocket: 'right'});
}   if(s_val){
// console.log(s_val+"sval*********************");
s1 = document.getElementById('addcirclebtn_'+(s_val)+'');
s2 = document.getElementById('line_end'+x+'');
var myLine = new LeaderLine((s1),s2 ,{dash: {animation: true}},
);
myLine.setOptions({startSocket: 'right', endSocket: 'right'});
        }
else{

console.log("else ---> msg");
s1 = document.getElementById('addcirclebtn_'+(x-1)+'');
s2 = document.getElementById('line_end'+x+'');
var myLine = new LeaderLine((s1),s2 ,{dash: {animation: true}},
);
myLine.setOptions({startSocket: 'right', endSocket: 'right'});
}

var s1 = '';
var s2 ='';
  var textarea = document.getElementById('textarealist'+x+'');
var i = 1;
const btn = document.getElementById('btn'+x+'');
btn.addEventListener('click', function handleClick() {
  if (i <= 12) {
  textarea.value += '{{' + i++ + '}}';
  }
});
$(".txt_count_rpy"+x).keyup(function(){
  $("#current_text_value"+x).text($(this).val().length);
});

var check_plus = document.getElementById('addcirclebtn_'+x+'');
check_plus.addEventListener('click', function circle_plus(){
  var circle_id = this.id;
  alert(circle_id);
  var split_value = circle_id.split("_");
console.log(split_value[1]);
var s_val = split_value[1];
  console.log("image1");
  // console.log(x++);
  // image(s_val);
  hidden_mx_val = x++ ;
  // document.getElementById('hidden_max_value').value = hidden_mx_val;
  alert(hidden_mx_val)
image(s_val,hidden_mx_val);
})

var z = 1 ;
 var txt_list =[];
var check_reply_btn = document.getElementById('addlistbtn_'+x+'');
check_reply_btn.addEventListener('click', function add_list() {
//   var click_id = this.id;
//   alert(click_id);
//  var split_value = click_id.split("_");
// console.log(split_value[1]);
//  x = split_value[1];
//  console.log("add_list_"+x  );

//  auto_x_value = x+1;
  var click_id = this.id;
  // alert(click_id);
 var split_value = click_id.split("_");
console.log(split_value[1]);
 x = split_value[1];
  var wrapper_dialog = $('.add_list_'+x+'');
  if (z <= 9) {
    console.log(z +"zpush");
    bot_array.splice((x-1),z, "List_"+(z+1));
    console.log(bot_array);
    txt_list.push(z);
    // console.log((z+1));
    // bot_array.splice((x-1),z, "List_"+(z+1));
    // console.log(bot_array);
$(wrapper_dialog).append('<div class="input-group child_div_list"><input type="text" class="form-control"  placeholder="List Button" name="'+x+'_list_button_'+(z+1)+'" id= "list_button'+z+'"aria-label="Reply Button"><a href="#" class="deleteButton'+x+''+z+' btn btn-danger">remove</a><img src="assets/img/circle.png" width="25px" height="20px"  class="circle_plus'+x+''+z+'"  id="addcirclebtn_'+z+'_'+x+'" style=" position:relative; float:right; bottom:30px;left:10px; top:10px;"></div>');

$(document).on("click", '.circle_plus'+x+''+z+'', function() {
    var click_id = this.id;
  // alert(click_id);
 var split_value = click_id.split("_");
 var z_value_id = split_value[1];
var reply_id = split_value[2];
console.log(split_value[1]+"XVALUES");
  console.log("image2");
  var high_hidden_value = $('#hidden_max_value').val();
var high_hidden_value = +high_hidden_value+1;
console.log(high_hidden_value+"%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%");
  // console.log( x+1 +"incrementing");
  // hidden_mx_val = +x+1;
  // console.log(hidden_mx_val+"hidden_mx_valhidden_mx_valhidden_mx_val")
  // console.log(z_value_id+"zzzzzzzzzzzz");
  // console.log( auto_x_value+"CCCCCCCCCC" );
  // console.log(reply_id+"RRRRRRRRRRR");
  // image_1(auto_x_value,reply_id,z_value_id);
  image_1(reply_id,z_value_id,high_hidden_value);
});
$(document).on("click", '.deleteButton'+x+''+z+'', function() {
    $(this).closest('.child_div').remove();
    var w = (z-- )-1;
    // console.log(w+"removez--"); 
    // console.log(txt_list);
    const index = txt_list.indexOf(w);
    // console.log(index);
    txt_list.splice(index, 1);
    if(w == '0'){
      bot_array.splice((x-1),z+1, "List_1");
    }else{
      bot_array.splice((x-1),z, "List_"+(w));
    }  
    // console.log(bot_array);
    // console.log(txt_list+"txt");
    let length = txt_list.length;
    z = length;
    // console.log(z +"--> zvalue set ");
    // console.log("!@#$%^&*(");
  console.log(  z++ +"increment value" );
});

// console.log("!@#$%^&*(");
  console.log(  z++ +"increment value" );

}
});
}

 function  image_1(reply_id,z_value_id,high_hidden_value) {
 document.getElementById('hidden_max_value').value = high_hidden_value;
 alert(high_hidden_value);
  var wrapper_dialog = $(".add_dialog");
  // var high_hidden_value = $('#hidden_max_value').val();
// var high_hidden_value = +high_hidden_value+1;
// console.log(high_hidden_value+"%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%");
  // if (x <= 10) {
    s = reply_id;
  //   if(z_value_id){  
  //     switch (z_value_id) {
  // case 1:
  //   x = +hidden_mx_val+1;
  //   alert(x);
  //   break;
  // case 2:
  //   x = +hidden_mx_val+1;
  //   alert(x);
  //   break;
  //   default:
  //   x = hidden_mx_val;
  //   }
  // }
    // console.log(reply_id+"RRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRR");
    // console.log(auto_x_value+"auto_x_value");
    // console.log(auto_x_value+"&&&&&&&&&&&&&&&&");
 x = high_hidden_value;
  // x = auto_x_value;
  
console.log(x+"xvalue----->image_1");
//     console.log(auto_x_value);
//   console.log(x+1"&&");
$(wrapper_dialog).append('<div class="col add_dio_'+x+'"><ul><br><a class="btn btn-warning mb-1" href="javascript:void(0)"  id="add_msg_url'+x+'" tabindex="5"  style="padding: 0.3rem 0rem !important; width:140px"><i class="far fa-comment-dots"></i>Send Messages</a><br><a class="btn btn-warning mb-1" href="javascript:void(0)"  tabindex="5" style="padding: 0.3rem 0rem !important;  width:140px" id="add_reply_url'+x+'"><i class="fas fa-reply-all"></i>Reply Buttons</a><br><a class="btn btn-warning mb-1" href="javascript:void(0)" tabindex="5"  id="add_list_url'+x+'" style="padding: 0.3rem 0rem !important; width:140px"><i class="fa fa-list"></i>List Buttons</a></ul></div> ');

  // console.log(hidden_mx_val+"hidden_mx_valhidden_mx_val");
document.getElementById('add_msg_url'+x+'').onclick = function() {add_msg_urlx()};
document.getElementById('add_reply_url'+x+'').onclick = function() {add_reply_urlx()};
document.getElementById('add_list_url'+x+'').onclick = function() {add_list_urlx()};



function add_msg_urlx() {
  bot_array.push("Message");
  // console.log(bot_array);
  $('.add_dio_'+x+'').attr("style", "visibility:hidden");
$(wrapper_dialog).append('<div class="container container-box" id="line_end'+x+'"><div class="col" ><i class="	far fa-comment-dots" style="font-size:25px; position:relative;left:20px; top:10px; "></i> <b style="font-size:25px; position:relative;left:20px; top:10px; " >Messages</b></div><label class="col-form-label"> Message Name <label style="color:#FF0000">*</label><span data-toggle="tooltip"data-original-title=" Message Name allowed maximum 30 Characters.">[?]</span></label><input type="text" name="message_name_'+x+'" class="form-control" oninput="this.value = this.value.toLowerCase()" tabindex="11"  placeholder="Message Name" maxlength="30" size="4"></br><div class="col- sm - 4" id="textarea'+x+'"><textarea id ="textareamsg'+x+'" class="form-control txt_count'+x+'" name="textarea_msg_'+x+'" maxlength="1024" tabindex="11"  placeholder="Enter Body Content" rows="6" style="width: 100%; height: 150px !important;"></textarea> </div><div class="row" style="right: 0px;"><div class="col-sm"> <span id="current_text_value'+x+'">0</span><span>/ 1024</span> ​<a href="#!" name="btn" type="button" id="btn'+x+'" tabindex="12" style="float:right;position:relative;" class="btn btn-success"> + Add variable</a></div> </div></br> <img src="assets/img/circle.png" width="25px" height="20px"  style="bottom: 220px;position:relative;left:300px; cursor: default;"  class="circle_plus_'+x+'"   id="addcirclebtn_'+x+'" "></div>'); 
console.log(z_value_start.length); 
var d = 0;
if(s_val){
// console.log(s_val+"sval*********************");
s1 = document.getElementById('addcirclebtn_'+(s_val)+'');
s2 = document.getElementById('line_end'+x+'');
var myLine = new LeaderLine((s1),s2 ,{dash: {animation: true}},
);
myLine.setOptions({startSocket: 'right', endSocket: 'right'});
        }else{
          var s = reply_id;
console.log("else ---> msg");
alert("********************");
// draggable = new PlainDraggable(document.getElementById('line'+(x-1)+''));
console.log('addcirclebtn_'+z_value_id+'_'+s+'');
console.log('line_end'+x+'');
s1 = document.getElementById('addcirclebtn_'+z_value_id+'_'+s+'');
s2 = document.getElementById('line_end'+x+'');
var myLine = new LeaderLine((s1),s2 ,{dash: {animation: true}},
);
myLine.setOptions({startSocket: 'right', endSocket: 'right'});
}
// mouseHoverAnchor(startElement), endElement;
s1 = '';
 s2 ='';

//  draggable = new PlainDraggable(document.getElementById('line_end'+x+''));
var textarea = document.getElementById('textareamsg'+x+'');
var i = 1;
const btn = document.getElementById('btn'+x+'');
btn.addEventListener('click', function handleClick() {
  if (i <= 12) {
  textarea.value += '{{' + i++ + '}}';
  }
});
$(".txt_count"+x).keyup(function(){
  $("#current_text_value"+x).text($(this).val().length);
});
var check_plus = document.getElementById('addcirclebtn_'+x+'');
check_plus.addEventListener('click', function circle_plus(){
// document.getElementById('line'+x+'').onclick = function() {addplusx(this.id)};
// function addplusx() {
  var circle_id = this.id;
  alert(circle_id);
  var split_value = circle_id.split("_");
console.log(split_value[1]);
var s_val = split_value[1];
  // console.log("***");
// console.log(x++);
hidden_mx_val = x++;
  // document.getElementById('hidden_max_value').value = hidden_mx_val;
  alert(hidden_mx_val)
image(s_val);
})
}
 function add_reply_urlx(reply_id) {
  // console.log(bot_array);
  bot_array.push("Replybutton_1");
  console.log(bot_array);
  $('.add_dio_'+x+'').attr("style", "visibility:hidden") ;
 $(wrapper_dialog).append('<div class="container container-reply_box" id="line_end'+x+'" ><div class="row "><div class="col" ><i class="fab fa-r-project" style="font-size:25px; position:relative; top:5px; "></i> <b>Reply Buttons</b><button class="btn btn-outline-secondary " style="position:relative;  width:110px;  left:42px;" type="button" id="addreplybt_'+x+'" >Add Button</button> </br>Offer Quick Response<label style="color:#FF0000">*</label><div class="input-group child_div"><input type="text" class="form-control" placeholder="Reply Name" name="reply_pattern_'+x+'" id= "reply_pattern'+x+'" aria-label="Reply Button"><input type="text" class="form-control" placeholder="Reply Button" name="'+x+'_reply_button_1" id= "reply_button" aria-label="Reply Button"><img src="assets/img/circle.png" width="25px" height="20px"  class="circle_plus_'+x+'" id="addcirclebtn_'+x+'" style=" position:relative; float:right; left:10px; bottom:30px; top:10px;" ></div><div class="add_reply'+x+'"></div><textarea id ="textareareply'+x+'" class="form-control txt_count_rpy'+x+'" name="textarea_reply'+x+'" maxlength="1024" tabindex="11"  placeholder="Enter Body Content" rows="6" style="width: 100%; height: 150px !important;"></textarea><div class="row" style="right: 0px;"><div class="col-sm"> <span id="current_text_value'+x+'">0</span><span>/ 1024</span> ​<a href="#!" name="btn" type="button" id="btn'+x+'" tabindex="12" style="float:right;position:relative;" class="btn btn-success"> + Add variable</a></div></div></br> '); 
//  console.log(z_value_start.length); 
var d = 0;
if(s_val){
// console.log(s_val+"sval*********************");
s1 = document.getElementById('addcirclebtn_'+(s_val)+'');
s2 = document.getElementById('line_end'+x+'');
var myLine = new LeaderLine((s1),s2 ,{dash: {animation: true}},
);
myLine.setOptions({startSocket: 'right', endSocket: 'right'});
        }else{
var s = reply_id;
console.log("else ---> msg");
// draggable = new PlainDraggable(document.getElementById('line'+(x-1)+''));
console.log('addcirclebtn_'+z_value_id+'_'+s+'');
console.log('line_end'+x+'');
s1 = document.getElementById('addcirclebtn_'+z_value_id+'_'+s+'');
s2 = document.getElementById('line_end'+x+'');
var myLine = new LeaderLine((s1),s2 ,{dash: {animation: true}},
);
myLine.setOptions({startSocket: 'right', endSocket: 'right'});
}
// mouseHoverAnchor(startElement), endElement;
s1 = '';
 s2 ='';


 var textarea = document.getElementById('textareareply'+x+'');
var i = 1;
const btn = document.getElementById('btn'+x+'');
btn.addEventListener('click', function handleClick() {
  if (i <= 12) {
  textarea.value += '{{' + i++ + '}}';
  }
});
$(".txt_count_rpy"+x).keyup(function(){
  $("#current_text_value"+x).text($(this).val().length);
});
var check_plus = document.getElementById('addcirclebtn_'+x+'');
check_plus.addEventListener('click', function circle_plus(){
// document.getElementById('line'+x+'').onclick = function() {addplusx(this.id)};
// function addplusx() {
  var circle_id = this.id;
  alert(circle_id);
  var split_value = circle_id.split("_");
console.log(split_value[1]);
var s_val = split_value[1];
  console.log("image1");
  hidden_mx_val = x++;

  alert(hidden_mx_val)
  // console.log( x++ +"increment");
  image(s_val,hidden_mx_val);
});

var check_reply_btn = document.getElementById('addreplybt_'+x+'');
var z = 1 ;
 const txt_reply =[];
 check_reply_btn.addEventListener('click', function add_rpy_btn() { 

//  console.log(hidden_mx_val+"%%%%%%%%%%%%%%%%%%%%%%%%%");
  // auto_x_value = x+1;
  var click_id = this.id;
  // alert(click_id);
 var split_value = click_id.split("_");
console.log(split_value[1]);
 x = split_value[1];

  var wrapper_dialog_1 = $('.add_reply'+x+'');
  if (z <= 2) {
console.log(z +"zpush");
    bot_array.splice((x-1),z, "Replybutton_"+(z+1));
    console.log(bot_array);
    txt_reply.push(z);
$(wrapper_dialog_1).append('<div class="input-group child_div"><input type="text" class="form-control" placeholder="Reply Button" name="'+x+'_reply_button_'+(z+1)+'" id= "reply_button_add'+z+'"aria-label="Reply Button"><a href="#" class="deleteButton'+x+''+z+' btn btn-danger">remove</a><img src="assets/img/circle.png" width="25px" height="20px"  class="circle_plus'+x+''+z+'"  id="addcirclebtn_'+x+''+z+'_'+x+'" style=" position:relative; float:right; left:10px; bottom:30px; top:10px;"></div>');

    $(document).on("click", '.circle_plus'+x+''+z+'', function() {
 var click_id = this.id;
  // alert(click_id);
 var split_value = click_id.split("_");
 var z_value_id = split_value[1];
var reply_id = split_value[2];
console.log(split_value[1]+"XVALUES")
var high_hidden_value = $('#hidden_max_value').val();
var high_hidden_value = +high_hidden_value+1;
console.log(high_hidden_value+"%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%");
  // console.log("image2");
  // console.log(x+1);
  // console.log(z_value_id+"zzzzzzzzzzzz");
  // console.log(auto_x_value+"CCCCCCCCCC" );
  // console.log(reply_id+"RRRRRRRRRRR");

  // image_1(auto_x_value,reply_id,z_value_id);
  image_1(reply_id,z_value_id,high_hidden_value);
});
$(document).on("click", '.deleteButton'+x+''+z+'', function() {
    $(this).closest('.child_div').remove();
    var w = (z-- )-1;
    console.log(w+"removez--"); 
    console.log(txt_reply);
    const index = txt_reply.indexOf(w);
    console.log(index);
    txt_reply.splice(index, 1);
    if(w == '0'){
      bot_array.splice((x-1),z+1, "Replybutton_1");
    }else{
      bot_array.splice((x-1),z, "Replybutton_"+(w));
    }  
    console.log(bot_array);
    console.log(txt_reply+"txt");
    let length = txt_reply.length;
    z = length;
    console.log(z +"--> zvalue set ");
    console.log("!@#$%^&*(");
  console.log(  z++ +"increment value" );
});
console.log("!@#$%^&*(");
  console.log(  z++ +"increment value" );
  } 
})
 }

 function add_list_urlx() {
  bot_array.push("List_1");
console.log(bot_array);
$('.add_dio_'+x+'').attr("style", "visibility:hidden") ;
$(wrapper_dialog).append('<div class="container container-list_box" id="line_end'+x+'" ><div class="row "><div class="col" ><i class="fa fa-list" style="font-size:25px; position:relative; top:5px; "></i> <b style="left:30px;position:relative; ">List Buttons</b><button class="btn btn-outline-secondary " style="position:relative;  width:100px;  left:40px;" type="button" id="addlistbtn_'+x+'">Add Button</button> <label style="color:#FF0000; left:30px;position:relative;">Single Choice Menu <label*</label> <div class="input-group"><input type="text" class="form-control"  placeholder="List Name" name="list_pattern_'+x+'" id= "list_pattern" aria-label="Button Text"><input type="text" class="form-control"  placeholder="Button Text" name="button_txt_'+x+'" id= "button_txt" aria-label="Button Text"><div class="input-group child_div_list"><input type="text" class="form-control"  placeholder="List Button" name="'+x+'_list_button_1" id= "list_button'+x+'"aria-label="List Button"><img src="assets/img/circle.png" width="25px" height="20px"  class="circle_plus'+x+'" id="addcirclebtn_'+x+'" style=" position:relative; float:right; bottom:30px;left:10px;right:10px; top:10px;"></div></div><div class="add_list_'+x+'"></div><textarea id ="textarealist'+x+'" class="form-control txt_count_rpy'+x+'" name="textarea_list_'+x+'"  maxlength="1024" tabindex="11"  placeholder="Enter Body Content" rows="6" style="width: 100%; height: 150px !important;"></textarea><div class="row" style="right: 0px;"><div class="col-sm"> <span id="current_text_value'+x+'">0</span><span>/ 1024</span> ​<a href="#!" name="btn" type="button" id="btn'+x+'" tabindex="12" style="float:right;position:relative;" class="btn btn-success"> + Add variable</a></div></div></br>  ');
console.log(z_value_start.length); 
var d = 0;

if(s_val){
// console.log(s_val+"sval*********************");
s1 = document.getElementById('addcirclebtn_'+(s_val)+'');
s2 = document.getElementById('line_end'+x+'');
var myLine = new LeaderLine((s1),s2 ,{dash: {animation: true}},
);
myLine.setOptions({startSocket: 'right', endSocket: 'right'});
        }else{
var s = reply_id;
alert(reply_id+"****");
console.log("else ---> msg");
// draggable = new PlainDraggable(document.getElementById('line'+(x-1)+''));
console.log('addcirclebtn_'+z_value_id+'_'+s+'');
console.log('line_end'+x+'');
s1 = document.getElementById('addcirclebtn_'+z_value_id+'_'+s+'');
s2 = document.getElementById('line_end'+x+'');
var myLine = new LeaderLine((s1),s2 ,{dash: {animation: true}},
);
myLine.setOptions({startSocket: 'right', endSocket: 'right'});
}

var s1 = '';
var s2 ='';
  var textarea = document.getElementById('textarealist'+x+'');
var i = 1;
const btn = document.getElementById('btn'+x+'');
btn.addEventListener('click', function handleClick() {
  if (i <= 12) {
  textarea.value += '{{' + i++ + '}}';
  }
});
$(".txt_count_rpy"+x).keyup(function(){
  $("#current_text_value"+x).text($(this).val().length);
});

var check_plus = document.getElementById('addcirclebtn_'+x+'');
check_plus.addEventListener('click', function circle_plus(){
// document.getElementById('line'+x+'').onclick = function() {addplusx(this.id)};
// function addplusx() {
  var circle_id = this.id;
  alert(circle_id);
  var split_value = circle_id.split("_");
console.log(split_value[1]);
var s_val = split_value[1];
  // console.log("image1");
  // console.log(x+1);
  hidden_mx_val = x++;
  // document.getElementById('hidden_max_value').value = hidden_mx_val;
  alert(hidden_mx_val)
  image(s_val);
})

var z = 1 ;
 var txt_list =[];
var check_reply_btn = document.getElementById('addlistbtn_'+x+'');
check_reply_btn.addEventListener('click', function add_list() {
//  auto_x_value = x +1;
  var click_id = this.id;
  // alert(click_id);
 var split_value = click_id.split("_");
console.log(split_value[1]);
 x = split_value[1];
  var wrapper_dialog = $('.add_list_'+x+'');
  if (z <= 9) {
    console.log(z +"zpush");
    bot_array.splice((x-1),z, "List_"+(z+1));
    console.log(bot_array);
    txt_list.push(z);
$(wrapper_dialog).append('<div class="input-group child_div_list"><input type="text" class="form-control"  placeholder="List Button" name="'+x+'_list_button_'+(z+1)+'" id= "list_button'+z+'"aria-label="Reply Button"><a href="#" class="deleteButton'+x+''+z+' btn btn-danger">remove</a><img src="assets/img/circle.png" width="25px" height="20px"  class="circle_plus'+x+''+z+'"  id="addcirclebtn_'+x+''+z+'_'+x+'" style=" position:relative; float:right; bottom:30px;left:10px; top:10px;"></div>');

$(document).on("click", '.circle_plus'+x+''+z+'', function() {
    var click_id = this.id;
  // alert(click_id);
 var split_value = click_id.split("_");
 var z_value_id = split_value[1];
var reply_id = split_value[2];
console.log(split_value[1]+"XVALUES")
var high_hidden_value = $('#hidden_max_value').val();
var high_hidden_value = +high_hidden_value+1;
console.log(high_hidden_value+"%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%");
  // console.log("image2");
  // console.log(x++);
  // console.log(z_value_id+"zzzzzzzzzzzz");
  // // console.log( auto_x_value+"CCCCCCCCCC" );
  // console.log(reply_id+"RRRRRRRRRRR");

  image_1(reply_id,z_value_id,high_hidden_value);
  // image_1(auto_x_value,reply_id,z_value_id);
});
$(document).on("click", '.deleteButton'+x+''+z+'', function() {
    $(this).closest('.child_div').remove();
    var w = (z-- )-1;high_hidden_value
    console.log(w+"removez--"); 
    console.log(txt_list);
    const index = txt_list.indexOf(w);
    console.log(index);
    txt_list.splice(index, 1);
    if(w == '0'){
      bot_array.splice((x-1),z+1, "List_1");
    }else{
      bot_array.splice((x-1),z, "List_"+(w));
    }  
    console.log(bot_array);
    console.log(txt_list+"txt");
    let length = txt_list.length;
    z = length;
    console.log(z +"--> zvalue set ");
    console.log("!@#$%^&*(");
  console.log(  z++ +"increment value" );

});

console.log("!@#$%^&*(");
  console.log(  z++ +"increment value" );

}
});
}
 }

i = 0;
   $('#getsender_id').click(function () {
      //  var sender_ids = [];
       $('.formcheckinput:checked').each(function () {
        sender_ids[i++] = $(this).val();
       });
       console.log(sender_ids);
   
      });

 $("#frm_compose_whatsapp").submit(function(e) {

  if (sender_ids.length > 0) {
    $("#id_error_display").html(" ");
    document.getElementById('hidden_sender_id').value = sender_ids;
    flag = true;
  }else{
    flag = false;
    $("#id_error_display").html("Select SenderId");
  }
//prevent Default functionality
e.preventDefault();
     
      var start_name = $('#start_name').val();

      var data_serialize = $("#frm_compose_whatsapp").serialize();
e.preventDefault();

// $("frm_compose_whatsapp").submit(function(e) {

if (flag) {

  var fd = new FormData(this);
  if (bot_array.length > 0) {
    fd.append('bot_array', bot_array);
  }
  
e.preventDefault();

        $.ajax({
          type: 'post',
          url: "ajax/whatsapp_call_functions.php?call_function=chat_bot",
          dataType: 'json',
          data: fd,
          contentType: false,
          processData: false,
          beforeSend: function () {
            $('#submit').attr('disabled', true);
            e.preventDefault();
          },
          complete: function () {
            e.preventDefault();
            $('#submit').attr('disabled', false);
          }, 
          // success: function (response) {
          //   if(response.status == '0' || response.status == 0) {
          //       $('#submit').attr('disabled', false);
          //       // $("#id_error_display_submit").html(response.msg);
          //   } else if(response.status == '2' || response.status == 2) {
          //       $('#submit').attr('disabled', false);
          //       // $("#id_error_display_submit").html(response.msg);
          //   } else if(response.status == 1 || response.status == '1') {
          //       $('#submit').attr('disabled', false);
          //       // $("#id_error_display_submit").html("Template Created Successfully..");
          //       //  window.location = 'template_list';
          //   }
          //   $('.theme-loader').hide();   
          // },
          // error: function (response, status, error) {
          //       // $('#submit').attr('disabled', false);
          //       // $("#id_error_display_submit").html("Template Created Successfully..");
          //       //  window.location = 'template_list';
          // }
        })
      }
    // })
  }
  );

  // }
 
};


    
  </script>


</body>


</html>
