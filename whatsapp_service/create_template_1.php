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
  "Create Template Page : User : " .
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
  <title>Create Template :: <?= $site_title ?></title>
  <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- CSS Libraries -->

  <!-- Multi Option was selected -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
  <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet" />

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <!-- style include in css -->
  <style>
    
    textarea {
      resize: none;
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
          <div class="section-header">
            <h1>Create Template</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="dashboard">Dashboard</a></div>
              <div class="breadcrumb-item active"><a href="template_list">Template List</a></div>
              <div class="breadcrumb-item">Create Template</div>
            </div>
          </div>

          <div class="section-body">
            <div class="row">

              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <form class="needs-validation" novalidate="" id="frm_compose_whatsapp" name="frm_compose_whatsapp"
                    action="#" method="post" enctype="multipart/form-data">
                    <div class="card-body">

                      <div class="form-group mb-2 row" style='display: none;'>
                        <label class="col-sm-3 col-form-label">Choose Template Category <label
                            style="color:#FF0000">*</label><br>
                          <div><i class="fa fa-star checked"></i> New categories are available. <a href="#"
                              data-toggle="modal" data-target="#myModal"> Learn more about categories </a></div>
                        </label></br>
                        <div class="col-sm-7">
                          <div class="list-group" name="list_items()">
                            <div role="button" class="list-group-item list-group-item-action"><input
                                class="form-check-input" tabindex="1" type="radio" name="categories" id="MARKETING"
                                value="MARKETING" style="margin-left:2px;" checked />
                              <div style="margin-left:20px;"><i class="fas fa-bullhorn"></i> <b> Marketing </b><br> Send promotions or information about your products, services or business.</div>
                            </div>
                            <div role="button" class="list-group-item list-group-item-action"><input
                                class="form-check-input" tabindex="2" type="radio" name="categories" id="UTILITY"
                                value="UTILITY" style="margin-left:2px;" />
                              <div style="margin-left:20px;"><i class="fa fa-bell"></i><b> Utility </b><br> Send
                                messages about an existing order or account.</div>
                            </div>
                            <div role="button" class="list-group-item list-group-item-action"><input
                                class="form-check-input" tabindex="3" type="radio" name="categories" id="AUTHENTICATION"
                                value="AUTHENTICATION" style="margin-left:2px;" />
                              <div style="margin-left:20px;"><i class="fa fa-key"></i> <b> Authentication </b><br> Send
                                codes to verify a transaction or login.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group mb-2 row" style='display: none;'>
                        <label class="col-sm-3 col-form-label">Message Template Name <label
                            style="color:#FF0000">*</label> <span data-toggle="tooltip"
                            data-original-title="Template Name allowed maximum 512 Characters. Unique values only allowed">[?]</span></label>
                        <div class="col-sm-7">
                          <input type="text" name="txt_template_name" id='txt_template_name' class="form-control"
                            value="-" tabindex="1" autofocus required="" maxlength="30"
                            placeholder="Enter message template name..." data-toggle="tooltip" data-placement="top"
                            title="" onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false" pattern="[a-zA-Z0-9 -_]+" onkeypress="return clsAlphaNoOnly(event)" data-original-title="Enter message template name"
                            onkeyup="this.value = this.value.toLowerCase();">
                        </div>
                        <div class="col-sm-2">
                        </div>
                      </div>
                      <div class="form-group mb-2 row">
                        <label class="col-sm-3 col-form-label">Languages <label style="color:#FF0000">*</label> <span
                            data-toggle="tooltip"
                            data-original-title="Choose languages for your message template. You can delete or add more languages later.">[?]</span></label>
                        <div class="col-sm-7">
                          <select name="lang[]" id="lang" required class="form-control" tabindex="2">
                            <option value="">Choose Language</option>
                            <?
                            $replace_txt = '{
                              "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
                            }';
                            $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                              CURLOPT_URL => $api_url.'/list/master_language',
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
                      
                            site_log_generate("Create Template Page : " . $uname . " Execute the service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
                            $response = curl_exec($curl);
                            curl_close($curl);

                            $state1 = json_decode($response, false);
                            site_log_generate("Create Template Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');

                            if ($state1->num_of_rows > 0) {
                              for ($indicator = 0; $indicator < count($state1->report); $indicator++) {
                                $language_name = $state1->report[$indicator]->language_name;
                                $language_id = $state1->report[$indicator]->language_id;
                                $language_code = $state1->report[$indicator]->language_code;
                                ?>
                                    <option value="<?= $language_code . '-' . $language_id ?>"><?= $language_name ?></option>
                                <?php }
                            }
                            site_log_generate("Create Template Page : User : " . $_SESSION['yjwatsp_user_name'] . " executed the Query ($sql_dashboard1) on " . date("Y-m-d H:i:s"));
                            ?>
                          </select>

                          <script>
                            $(".chosen-select").chosen({
                              no_results_text: "Oops, nothing found!"
                            })
                          </script>
                        </div>
                      </div>

                      <div class="form-group mb-2 row">
                        <label class="col-sm-3 col-form-label">Header <span data-toggle="tooltip"
                            data-original-title="Add a title or choose which type of media you'll use for this header">[?]</span><span
                            style="margin-left:10px;"><b>Optional</b></span></label>
                        <div class="col-sm-7">
                          
                          <select id="select_id" name="header" class="form-control" tabindex="3">
                            <option value="None" type="radio"> None
                            </option>
                            <option value="TEXT"> Text
                            </option>
                            <option value="MEDIA"> Media
                            </option>
                          </select>
                          <br>
                          <div style="display: none; margin-left:4px; " id="text"
                            class="form-group mb-2 row">
                            <input type="text" name="txt_header_name" id='txt_header_name' class="form-control"
                              value="<?= $txt_header_name ?>" tabindex="4" maxlength="60"
                              placeholder="Enter header name..." data-toggle="tooltip" data-placement="top" title=""
                              data-original-title="Enter header Name">Characters : ​<span id="count1"></span>

                          </div> <div class="col-4" style="display: none;" id="header_variable_btn1"> ​<button name="btn1" onclick="myFunction()" type="button" id="btn1" tabindex="5"
                                    class="btn btn-success" > + Add variable</button></div></br>
                                    <div class="col-4" id='txt_header_variable'>  <input type="text" name="txt_header_variable" id='txt_header_variable_1' tabindex="6" class="form-control"
                                  value="<?= $txt_sample_name ?>" maxlength="60" placeholder="Header Variable"
                                  data-toggle="tooltip" data-placement="top" title=""
                                  data-original-title="Header Variable" style="display: none; width:200px; margin-top:0px;"></div>

                          <div class="row" id="image_category" style="display: none; "
                            name="image_category">
                            <div class="col-4" style="float: left;">
                              <div role="button"><label>Image</label><input class="form-check-input" type="radio"
                                  name="media_category"  tabindex="7" id="image1" value="image"
                                  style="margin-left:2px;" onclick=" media_category_img(this)"/>
                                <div style="margin-left:20px;"><i class="fa fa-image" style="font-size: 20px"></i></div>
                              </div>
                            </div>
                            <div class="col-4" style="float: left;">
                              <div role="button"><label>Video</label><input class="form-check-input" type="radio"
                                  name="media_category"  tabindex="8" id="image2" value="video"
                                  style="margin-left:2px;" onclick=" media_category_vid(this)"/>
                                <div style="margin-left:20px;"><i class="fa fa-play-circle" style="font-size: 20px"></i>
                                </div>
                              </div>
                            </div>
                            <div class="col-4" style="float: left;">
                              <div role="button"><label>Document</label><input class="form-check-input" type="radio"
                                  name="media_category" tabindex="9" id="image3" value="document"
                                  style="margin-left:2px;" onclick=" media_category_doc(this)"/>
                                <div style="margin-left:20px;"><i class="fa fa-file-text" style="font-size: 20px"></i>
                                </div>
                              </div>
                            </div>
<script>


  </script>
                            <div class="col-4 file_image_header" style="float: left; display:none;">
                              <input type="file" name="file_image_header" id="file_image_header" tabindex="10" />
                            </div>
                          </div>

                        </div>
                      </div>
                      
                      <div class="form-group mb-2 row">

                        <label class="col-sm-3 col-form-label">Body <label style="color:#FF0000">*</label> <span
                            data-toggle="tooltip"
                            data-original-title="Enter the text for your message in the language that you've selected.">[?]</span></label>
                        <div class="col-sm-7">
                          <div class="row">
                            <div class="col-8">

                              <!-- TEXT area alert -->
                              <textarea id="textarea" class="delete" name="textarea" required maxlength="1024" tabindex="11"
                                placeholder="Enter Body Content" rows="6"
                                style="width: 100%; height: 150px !important;"></textarea>
                              <div class="row" style="right: 0px;">
                                <div class="col-sm"> <span id="current_text_value">0</span><span id="maximum">/ 1024</span>
                                </div>
                                <div class="col-sm">​<a href='#!' name="btn" type="button" id="btn" tabindex="12"
                                class="btn btn-success"> + Add variable</a></div>
                              </div>
                              
                              <!-- TEXT area alert End -->
                            </div>
                            <div class="col container1" id="add_variables" style="display:none;"><label class="col-form-label">Variable Values <label style="color:#FF0000"> * </label> <span
                            data-toggle="tooltip"
                            data-original-title="Variables must not empty.Template Name allowed maximum 60 Characters.">[?]</span></label>
                            </div>
                          </div>

                          <div> </div>
                        </div>
                        <div class="container" style="display:none;width:1000px; " id="alert_variable"
                          style="border-color:red">
                          <div class="row"><span>
                              <ul style="list-style-type: disc; margin-left: 16px;">
                                <div>
                                  <li style="width:800px;">The body text contains variable parameters at the beginning or end. You need
                                    to either change this format or add a sample.</li>
                                    <li style="width:800px;">Variables  must not empty.</li>
                                  <li style="width:800px;">This template contains too many variable parameters relative to the message
                                    length. You need to decrease the number of variable parameters or increase the
                                    message length.</li>
                                  <li style="width:800px;">The body text contains variable parameters that are next to each other. You
                                    need to either change this format or add a sample.</li>
                                    <li style="width:800px;"> <a target="_blank"
                                href="https://developers.facebook.com/docs/whatsapp/message-templates/guidelines/">Learn
                                more about formatting in Message Template Guidelines</a></li>
                                </div>
                              </ul>
                            </span></div>
                        </div>
                      </div>
                      <div class="form-group mb-2 row">
                        <label class="col-sm-3 col-form-label">Footer <span data-toggle="tooltip"
                            data-original-title="Add a short line of text to the bottom of your message template. If you add the marketing opt-out button, the associated footer will be shown here by default.">[?]</span><span
                            style="margin-left:10px;"><b>Optional</b></span></label>
                        <div class="col-sm-7">

                          <div>
                            <input type="text" name="txt_footer_name" id='txt_footer_name' tabindex="13"
                              class="form-control" value="<?= $txt_footer_name ?>" maxlength="60"
                              placeholder="Enter Footer Name..." data-toggle="tooltip" data-placement="top" title=""
                              data-original-title="Enter Footer Name">Characters : ​<span id="count2"></span>
                          </div>
                          <br>
                        </div>
                      </div>

                      <div class="form-group mb-2 row">
                        <label class="col-sm-3 col-form-label">Buttons <span data-toggle="tooltip"
                            data-original-title="Create buttons that let customers respond to your message or take action.">[?]</span><span
                            style="margin-left:10px;"><b>Optional</b></span></label>
                        <div class="col-sm-7">

                          <div>

                            <select id="select_action" name="select_action" class="form-control" tabindex="14">
                              <option value="None" type="radio">None
                              </option>
                              <option value="CALLTOACTION"> Call To Action
                              </option>
                              <option value="QUICK_REPLY"> Quick Reply
                              </option>
                            </select>
                          </div>
                          <br>

                          <div class="container" style="display:none;" id="callaction">

                            <div class="row">
                              <div class="col">
                                <label for="lang1">Type of action</label><br>

                                <select id="select_action1" name="select_action1" class="form-control" tabindex="15">

                                  <option value="PHONE_NUMBER" > Call Phone Number
                                  </option>
                                  <option value="VISIT_URL"> Visit Website
                                  </option>
                                </select>
                              </div>
                              <div class="col">
                                <label for="lang1">Button text</label><br>
                                <input type="text" name="button_text[]" id='button_text' class="form-control"
                                  value="<?= $button_text ?>" tabindex="16" maxlength="25"
                                  placeholder="Enter button name..." data-toggle="tooltip" data-placement="top" title=""
                                  data-original-title="Enter button name" >
                              </div>

                              <div class="col">
                                <label for="lang1">Country</label><br>
                                <select id="country_code" name="country_code" class="form-control" tabindex="17">
                                  <?
                                  $replace_txt = '{
                                  
                                    "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
                                  }';
                                  $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
                                  $curl = curl_init();
                                  curl_setopt_array($curl, array(
                                    CURLOPT_URL => $api_url . '/list/country_list',
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
                                
                                  site_log_generate("Create Template Page : " . $uname . " Execute the service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
                                  $response = curl_exec($curl);
                                  curl_close($curl);

                                  $state1 = json_decode($response, false);
                                  site_log_generate("Create Template Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');

                                  if ($state1->num_of_rows > 0) {
                                    for ($indicator = 0; $indicator < count($state1->report); $indicator++) {
                                      $shortname = $state1->report[$indicator]->shortname;
                                      $phonecode = $state1->report[$indicator]->phonecode;
                                      ?>
                                      <option value="<?= "+" . $phonecode ?>" <? if ($shortname == 'IN') { echo "selected"; } ?>><?=$shortname . "+" . $phonecode ?></option>
                                    <?php }
                                  }
                                  site_log_generate("Create Template Page : User : " . $_SESSION['yjwatsp_user_name'] . " executed the Query ($sql_dashboard1) on " . date("Y-m-d H:i:s"));
                                  ?>
                                </select></label>

                              </div>
                              <div class="col">
                                <label for="lang1">Phone number</label><br>
                                <input type="text" name="button_txt_phone_no[]" id='button_txt_phone_no'
                                  class="form-control" value="<?= $button_text1 ?>" tabindex="18" maxlength="10"
                                  placeholder="Phone number" style="padding: 10px 5px !important;" data-toggle="tooltip"
                                  data-placement="top" title="" data-original-title="Phone number">
                              </div>
                            </div>

                            <div class="row add_phone_content"> </div>
                            
                            <div class="col"><a href='#!' name="add_phone_btn" type="button" id="add_phone_btn_btn" tabindex="19"
                                class="btn btn-success" style="margin-top:30px; " >+ Add Another Button</a></div>
                          </div>
                          
                          <div class="container" style="display:none;" id="calltoaction">
                            <div class="row">                            
                              <div class="col">
                                <label for="lang1">Button Text</label><br>
                                <input type="text" name="button_quickreply_text[]" id='button_quickreply_text'
                                  class="form-control" value="<?= $button_text2 ?>" tabindex="20" maxlength="25"
                                  placeholder="Enter Button Name 1" data-toggle="tooltip" data-placement="top" title=""
                                  data-original-title="Enter button name">
                              </div>
                              
                              <div class="col" >
                              ​<a href='#!' name="add_another_button" type="button" id="add_another_button" tabindex="21"
                                class="btn btn-success" style="margin-top:30px;" >+ Add Another Button</a>
                                  </div></div>
                           
                            <div class="row ">
                              <div class="col-md-6 add_button_textbox"> </div>
                            </div>
                          </div> 
                          <div class="container" style="display:none;" id="visit_website">
                            <div class="row">
                            <div class="col"><label for="lang1">Type of action</label><br><select id="select_action3" name="select_action3" class="form-control" tabindex="22"><option value="PHONE_NUMBER">Phone Number</option> <option value="VISIT_URL" > Visit Website</option> </select> </div>
                              <div class="col"><label for="select_action2" >URL Button Name</label><br>
                                <input type="text" name="button_url_text[]" id='button_url_text' class="form-control"
                                  value="<?= $website ?>" tabindex="23" maxlength="25" placeholder="Enter url name..."
                                  data-toggle="tooltip" data-placement="top" title=""
                                  data-original-title="Enter button name" >
                              </div>
                              <div class="col"><label for="select_action2">Type</label><br>
                                <select name="select_action2" id="select_action2" class="form-control">
                                  <option value="Static"> Static
                                  </option>
                                </select>
                              </div>
                              <div class="col"><label for="select_action2" >URL</label><br>
                                <input type="text" name="website_url[]" id='website_url' class="form-control"
                                  value="<?= $website ?>" tabindex="24" maxlength="2000" placeholder="Enter url..."
                                  data-toggle="tooltip" data-placement="top" title=""
                                  data-original-title="Enter url">
                              </div>
                            </div>
                            <div class="row add_url_content"> </div>

                            <div class="col"><a href='#!' name="add_url_btn_btn" type="button" id="add_url_btn_btn" tabindex="25"
                                class="btn btn-success" style="margin-top:30px; " >+ Add Another Button</a></div>
                          </div>
                          

                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <input type="hidden" class="form-control" name='tmp_qty_count' id='tmp_qty_count' value='1' />
                      <input type="hidden" class="form-control" name='temp_call_function' id='temp_call_function'
                        value='create_template' />
                      <input type="hidden" class="form-control" name='hid_sendurl' id='hid_sendurl'
                        value='<?= $server_http_referer ?>' />
                    </div>

                    <div class="card-footer text-center">
                    <span class="error_display" id='id_error_display_submit'></span>

                    <input type="button" onclick="myFunction_clear()" value="Clear" class="btn btn-success" id="clr_button">
                      <input type="submit" name="submit" id="submit" tabindex="26" value=" Save & Submit"
                        class="btn btn-success">
 <input type="button"  value="Preview Content"  onclick="preview_content()" data-toggle="modal" data-target="#previewModal" class="btn btn-success" id="pre_button" name= "pre_button">

                    </div>
                  </form>

        </section>

      </div>

      <!-- Modal content-->
      <!-- Modal -->
      <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Learn more about categories</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <dl class="last">


                <dt><span class="order">Choose a category that describes the text, media and buttons that you will
                    send.</span></dt><br>
              <div class="container">
                <div class="row">
                  <div class="col">
                    <img src="./assets/img/image1.jpg" width="200px" height="200px">
                  </div>
                  <div class="col">
                    <img src="./assets/img/image2.jpg" width="200px" height="200px">
                  </div>
                  <div class="col">
                    <img src="./assets/img/image3.jpg" width="200px" height="200px">
                  </div>

                  <div class="col">
                    <i class="fas fa-bullhorn"></i> <b>Marketing</b></br>Any message that is not utility or
                    authentication will be marketing.</br>Examples: welcome messages, newsletters, offers, coupons,
                    catalogues, new store hours.
                  </div>
                  <div class="col">
                    <i class="fa fa-bell"></i> <b>Utility</b></br>Updates about an order or account that a customer
                    has already created.</br>Examples: order confirmations, account updates, receipts, appointment
                    reminders, billing.
                  </div>
                  <div class="col">
                    <i class="fa fa-key"></i> <b>
                      Authentication</b></br>Codes to help customers verify their purchases or account
                    logins.</br>Examples: one-time password, account recovery code.
                  </div>

              </dl>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn-outline-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>

        </div>
      </div>


       <!-- Preview Data Modal content-->
      <!-- Modal content-->
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
  <!-- Preview Data Modal content End-->
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

  <script src="assets/js/xlsx.core.min.js"></script>
  <script src="assets/js/xls.core.min.js"></script>
  <script>
  
//Checkbox - Media - Start
  function media_category_img(e)
{
    $('.file_image_header').css("display", "block");   
    $('#file_image_header').prop('accept', 'image/png, image/gif, image/jpeg, image/jpg');
}

function media_category_vid(e)
{
    $('.file_image_header').css("display", "block");  
    $('#file_image_header').prop('accept', 'video/h263,video/m4v,video/mp4,video/mpeg,video/mpeg4,video/webm');
}
function media_category_doc(e)
{
    $('.file_image_header').css("display", "block"); 
    $('#file_image_header').prop('accept', 'text/plain,text/csv, .doc, .pdf,application/vnd.ms-excel');  
}
//Checkbox - Media - End

    $('#select_id').on('change', function () {
      var value = $(this).val();
      if (value == 'TEXT') {
 $('#txt_header_name').attr('required', 'required');
        $('#header_variable_btn1').css("display", "block");
        
        $('#text').css("display", "block");
        $('#image_category').css("display", "none");
      } 
      else if (value == 'MEDIA') {
        $('#header_variable_btn1').css("display", "none");
        $('#text').css("display", "none");
          $('#image_category').css("display", "block");
      } 
      else {
        $('#text').css("display", "none");
        $('#image_category').css("display", "none");
      }
    });

    $('#select_action').on('change', function () {
      var value = $(this).val();
      if (value == 'CALLTOACTION') {
        // alert(value+"1")
        document.getElementById('select_action3').value="PHONE_NUMBER";
        $('#calltoaction').css("display", "none");
$('#button_text').attr('required', 'required');
$('#button_txt_phone_no').attr('required', 'required');
        $('#callaction').css("display", "block");
      } 
      else if (value == 'QUICK_REPLY') {
        $('#button_quickreply_text').attr('required', 'required');
        $('#calltoaction').css("display", "block");
        $('#callaction').css("display", "none");
         $('#visit_website').css("display", "none");
      } else {
        document.getElementById('select_action3').value="VISIT_URL";
        $('#callaction').css("display", "none");
        $('#calltoaction').css("display", "none");
        $('.add_phone_content').css("display", "none");
        $('.add_url_content').css("display", "none");
        $('#visit_website').css("display", "none");
      }
    });

    $('#select_action3').on('change', function () {
      var value = $(this).val();
      if (value == 'PHONE_NUMBER') {
        // alert(value+"2")
           $('#button_url_text').removeAttr('required');
        $('#website_url').removeAttr('required');

        $('#visit_website').css("display", "none");
        document.getElementById('select_action1').value="PHONE_NUMBER";
        $('#callaction').css("display", "block");
      } 
      else if (value == 'VISIT_URL') {
        // alert(value+"1")
        document.getElementById('select_action1').value="VISIT_URL";
        $('#callaction').css("display", "none");
         $('#visit_website').css("display", "block");
      } else {
        $('#callaction').css("display", "none");
        $('#calltoaction').css("display", "none");
        $('#visit_website').css("display", "none");
      }
    });

    $('#select_action1').on('change', function () {
      var value = $(this).val();
      if (value == 'PHONE_NUMBER'){
      document.getElementById('select_action3').value="PHONE_NUMBER";
      $('#visit_website').css("display", "none");
        $('#callaction').css("display", "block");
      }
      if (value == 'VISIT_URL'){
      // alert(value+"2")
$('#button_text').removeAttr('required');
$('#button_txt_phone_no').removeAttr('required');
$('#button_url_text').attr('required', 'required');
$('#website_url').attr('required', 'required');

      document.getElementById('select_action3').value="VISIT_URL";
      $('#add_another_button').css("display", "block");
        $('#visit_website').css("display", "block");
      $('#callaction').css("display", "none");
 $('#calltoaction').css("display", "none");
      }
    });


    $(document).on("submit", "form#frm_compose_whatsapp", function (e) {
      e.preventDefault();
      
      if( !document.getElementById('button_url_text').value ) {
  $('#button_url_text').css('border-color', 'red');
      }
      if( !document.getElementById('website_url').value ) {
  $('#website_url').css('border-color', 'red');
      }
      if( !document.getElementById('button_text').value ) {
  $('#button_url_text').css('border-color', 'red');
      }
      if( !document.getElementById('button_txt_phone_no').value ) {
  $('#button_txt_phone_no').css('border-color', 'red');
      }


      var txt_template_name = $('#txt_template_name').val();
      var lang = $('#lang').val();
      var list_items = $("input[type='radio'][name='categories']:checked").val();
      var media_category = $("input[type='radio'][name='media_category']:checked").val();
     
    
      var textarea = $('#textarea').val();
      var mediafile = $('#mediafile').val();


      flag = true;

      e.preventDefault();

      if (flag) {
        var fd = new FormData(this);
        var files = $('#file_image_header')[0].files;

        if (files.length > 0) {
          fd.append('file', files[0]);
        }
        // e.preventDefault();
        $.ajax({
          type: 'post',
          url: "ajax/whatsapp_call_functions.php",
          dataType: 'json',
          data: fd,
          contentType: false,
          processData: false,
          beforeSend: function () {
            $('#submit').attr('disabled', true);
          },
          complete: function () {
            $('#submit').attr('disabled', false);
          }, 
          success: function (response) {
            if(response.status == '0' || response.status == 0) {
                $('#submit').attr('disabled', false);
                $("#id_error_display_submit").html(response.msg);
            } else if(response.status == '2' || response.status == 2) {
                $('#submit').attr('disabled', false);
                $("#id_error_display_submit").html(response.msg);
            } else if(response.status == 1 || response.status == '1') {
                $('#submit').attr('disabled', false);
                $("#id_error_display_submit").html(response.msg);
                window.location = 'template_list';
            }
            $('.theme-loader').hide();   
          },
          error: function (response, status, error) {
                $('#submit').attr('disabled', false);
                $("#id_error_display_submit").html(response.msg);
                window.location = 'template_list';
          }
        })
      }
    });



    // FORM Clear value
    
function myFunction_clear() {
  
    document.getElementById("frm_compose_whatsapp").reset();
    window.location.reload();
}

//HEADER VARIABLE ADD
var ii = 1;
txt_header_name.value += '';
    function myFunction() {
      txt_header_name.value += '{{'+ii+'}}'
  $('#txt_header_variable_1').attr('required', 'required');
  $("#txt_header_variable_1").css("display", "block");
  document.getElementById("btn1").disabled = true;
  
}
    
// FORM preview value
    
 function preview_content() {
	var data_serialize = $("#frm_compose_whatsapp").serialize();
  $.ajax({
          type: 'post',
          url: "ajax/preview_call_functions.php?preview_functions=preview_template",
          data: data_serialize,
          success: function (response){
          if (response.status == 0) {
            console.log(response.status);
            $("#id_modal_display").html('No Data Available!!');
          } else if(response.status == 1) {
            console.log("elseif");
            console.log(response.status);
            $("#id_modal_display").html(response.msg);
          }
          console.log(response.status);
          $('#default-Modal').modal({ show: true });
        },
        error: function (response, status, error) {
          console.log("error");
          $("#id_modal_display").html(response.status);
          $('#default-Modal').modal({ show: true });
        }
        })
}

//Add another Url button

var wrapper_url_button = $(".add_url_content");
const add_another_url_button = document.getElementById('add_url_btn_btn');
    add_another_url_button.addEventListener('click', function handleClick() {
  
$(wrapper_url_button).append('</br><div class="col"><label for="lang1">Type of action</label><br><select id="select_action4" name="select_action4" class="form-control" tabindex="27"><option value="PHONE_NUMBER"> Phone Number</option> </select> </div> <div class="col"><label for="lang1">Button text</label><br> <input type="text" name="button_text[]"  class="form-control" tabindex="28" maxlength="25" placeholder="Enter button name..." data-toggle="tooltip" data-placement="top" title="" data-original-title="Enter button name" required> </div>  <div class="col"> <label for="lang1">Country</label><br><select id="country_code" name="country_code" class="form-control" tabindex="29"><?
$replace_txt = '{ 
  "user_id" : "' . $_SESSION['yjwatsp_user_id'] . '"
                                    }';
                                    $bearer_token = 'Authorization: '.$_SESSION['yjwatsp_bearer_token'].'';  
                                    $curl = curl_init();
                                    curl_setopt_array($curl, array(
                                      CURLOPT_URL =>  $api_url .'/list/country_list',
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
site_log_generate("Create Template Page : " . $uname . " Execute the service [$replace_txt] on " . date("Y-m-d H:i:s"), '../');
// echo $response;
$response = curl_exec($curl);
$state = json_decode($response,true);
// print_r($state);
curl_close($curl);
site_log_generate("Create Template Page : " . $uname . " get the Service response [$response] on " . date("Y-m-d H:i:s"), '../');
if ($state1->num_of_rows > 0) {
  for ($indicator = 0; $indicator < $state1->num_of_rows; $indicator++) {
    $shortname = $state1->report[$indicator]->shortname;
    $phonecode = $state1->report[$indicator]->phonecode;
    ?><option value="<?= "+" . $phonecode ?>" <? if ($shortname == 'IN') {echo "selected";} ?>><?=$shortname . "+" . $phonecode ?></option><?php }
}
?></select></label></div><div class="col"><label for="lang1">Phone number</label><br><input type="text" name="button_txt_phone_no[]"class="form-control"  tabindex="30" maxlength="10"placeholder="Phone number" style="padding: 10px 5px !important;" data-toggle="tooltip"data-placement="top" title="" data-original-title="Phone number" required></div>');   
      document.getElementById('add_url_btn_btn').style.pointerEvents="none";
document.getElementById('add_url_btn_btn').style.cursor="default";
$("#add_url_btn_btn").css("display", "none");
    });

// another PhoneButton
var wrapper_button = $(".add_phone_content");
const add_another_phn_button = document.getElementById('add_phone_btn_btn');
    add_another_phn_button.addEventListener('click', function handleClick() {
  
$(wrapper_button).append('<br><div class="col"><label for="lang1">Type of action</label><br><select id="select_action5" name="select_action5" class="form-control" tabindex="31"><option value="VISIT_URL" selected> Visit Website</option> </select> </div><div class="col"><label for="select_action2" >URL Button Name</label><br><input type="text" name="button_url_text[]" id="button_url_text" class="form-control" tabindex="32" maxlength="25" placeholder="Enter url name..." data-toggle="tooltip" data-placement="top" title="" data-original-title="Enter button name" required> </div> <div class="col"><label for="select_action2">Type</label><br> <select name="select_action2" id="select_action2" class="form-control"><option value="Static"> Static </option></select></div><div class="col"><label for="select_action2" >URL</label><br><input type="text" name="website_url[]"  class="form-control" tabindex="33" maxlength="2000" placeholder="Enter url..." data-toggle="tooltip" data-placement="top" title=""data-original-title="Enter url" required></div>');   
      document.getElementById('add_phone_btn_btn').style.pointerEvents="none";
document.getElementById('add_phone_btn_btn').style.cursor="default";
$("#add_phone_btn_btn").css("display", "none");
    });

// ADD ANOTHER REPLY BUTTON
 var x = 2;
var wrapper1 = $(".add_button_textbox");
    const add_another_button = document.getElementById('add_another_button');
    add_another_button.addEventListener('click', function handleClick() {
      if (x < 4) {
$(wrapper1).append('<br><input type="text" name="button_quickreply_text[]" class="form-control" placeholder="Enter Button Name'+x+'" required/>');   
 x++;
      }
    });

//TEXT AREA COUNT
    $("#textarea").keyup(function(){
  $("#current_text_value").text($(this).val().length);
});

//variable create
    const textarea = document.getElementById('textarea');
    var i = 1;
var wrapper = $(".container1");

var t = textarea.value ;
var txt_field_array = [];

var text_field_length ;

textarea.addEventListener('keyup', updateResult);
    textarea.value += '';
    const btn = document.getElementById('btn');
    btn.addEventListener('click', function handleClick() {
    
      if (i <= 12) {
     
       $(wrapper).append('<input type="text" name="txt_sample[]" id="Variable'+i+'" class="form-control" placeholder="Variable' + i+ '" required/>'); 
      
  txt_field_array.push(i.toString());      
   textarea.value += '{{' + i++ + '}}';
        $('#alert_variable').css("display", "block");
        $("#add_variables").css("display", "block");
console.log(txt_field_array);
      }
    });
    
    function updateResult() {
      var variable_count  = [];
  
  var t = textarea.value ;
  var s = t.split("{{");

for(var j=1; j<s.length;j++){
    var s1 = s[j].split("}}");
  console.log(s1[0]); 
  variable_count.push(s1[0]); 
}
if(txt_field_array.length > variable_count.length ){
  console.log('********');
console.log('deleted');
console.log(JSON.stringify(txt_field_array)+ JSON.stringify(variable_count) );
// console.log(JSON.stringify(variable_count));
var res = txt_field_array.filter(function(obj) {
   return variable_count.indexOf(obj) == -1; })
console.log(res[0]+ "delected");
var item = res[0];

var index = txt_field_array.indexOf(item);
txt_field_array.splice(index, 1);

console.log(txt_field_array)
console.log(JSON.stringify(txt_field_array));
console.log(JSON.stringify(variable_count));
var element = document.getElementById("add_variables");
var child=document.getElementById('Variable'+res[0]+'');
element.removeChild(child);
console.log('Variable'+res[0]+'');
// i--;
} 
}

//Text Input count - HEADER
    var el_t = document.getElementById('txt_header_name');
    var length = el_t.getAttribute("maxlength");
    var el_c = document.getElementById('count1');
    el_c.innerHTML = length;
    el_t.onkeyup = function () {
      document.getElementById('count1').innerHTML = (length - this.value.length);
    };
  //Text Input count - Footer
    var el_t = document.getElementById('txt_footer_name');
    var length = el_t.getAttribute("maxlength");
    var el_c = document.getElementById('count2');
    el_c.innerHTML = length;
    el_t.onkeyup = function () {
      document.getElementById('count2').innerHTML = (length - this.value.length);
    };
// TEMplate Name - Space

$(function() {
        $('#txt_template_name').on('keypress', function(e) {
            if (e.which == 32){
                console.log('Space Detected');
                return false;
           }

        });
});
  </script>
</body>


</html>
