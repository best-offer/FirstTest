<?php

date_default_timezone_set("UTC");
session_start();
ob_start();

error_reporting(E_ALL);
ini_set('display_error', 1);

if (!extension_loaded('soap')) {
 echo '<div id="test-mode" class="vertical-text" style="position: fixed;left: 0;top: 0;width: 100%;height: 100%;background: black;text-transform: none;line-height: 30px;padding-top:300px;text-align: center;color:white;font-size:20px;font-weight:bold;z-index: 9999;">SOAP CLIENT IS MISSING<br />CONTACT TECHNICAL SUPPORT</div>';
 die();
}

if(!isset($_SESSION['REFERER']) && !isset($_POST['form']) && isset($_SERVER['HTTP_REFERER'])){
    $_SESSION['REFERER'] = $_SERVER['HTTP_REFERER'];
}

include("form_include/config_php/config.php");
include("form_include/config_php/ip.php");
require_once "form_include/config_php/Mobile_Detect.php";
$detect = new Mobile_Detect;

$ini_array = parse_ini_file("settings.ini", true);
if(empty($ini_array))
{
 echo '<div id="test-mode" class="vertical-text" style="position: fixed;left: 0;top: 0;width: 100%;height: 100%;background: black;text-transform: none;line-height: 30px;padding-top:300px;text-align: center;color:white;font-size:20px;font-weight:bold;z-index: 9999;">A settings.ini parse error has occured<br />Please repair the file and try again</div>';
 die();
}
foreach ($ini_array['FORM_SETTINGS'] as $type => $fieldsData) {
    if ($fieldsData['fieldtype'] == 'settings'){
        $captcha = @$fieldsData['captcha'];
        $multistep = @$fieldsData['multistep'];
		$uselanguageplaceholder = @$fieldsData['uselanguageplaceholder'];
        $api = @$fieldsData['API'];
        $api_type = @$fieldsData['api_type'];
        $captcha_html = @$fieldsData['captcha_html'];
		 if (@$fieldsData['next'] != NULL) {$next = @$fieldsData['next']; } else { $next =  "Next"; };
		  if (@$fieldsData['previous'] != NULL) {$previous = @$fieldsData['previous']; } else { $previous = "Previous"; };
		// if (@$fieldsData['previous']) $previous = @$fieldsData['previous'];
        if (@$fieldsData['captcha_description']) $captcha_description = @$fieldsData['captcha_description'];
        if (@$fieldsData['submit_succes']) $submit_succes = @$fieldsData['submit_succes'];
        if (@$fieldsData['submit_button']) $submit_button = @$fieldsData['submit_button'];
    continue;}
}

if(empty($result)){
	$result['Language']="EN";
}
if(isset($_GET['lang'])){
	$result['Language'] = $_GET['lang'];
}



$language = $result['Language'];
$leadstestmode = @$result['LeadsTestMode'];
$sendingID = @$result['SendingID'];
$SendLeadsToEmail = @$result['SendLeadsToEmail'];
$ID = @$result['ID'];
$_SESSION['id'] = $ID;
$ip = getIP();
///////////////////////////////////////////////////////////////////////////////////////////


use PFBC\Element;
use PFBC\Form;


include("form_include/PFBC/Form.php");

$form = new Form("form-elements");
$form->configure(array(
        "prevent" => array("bootstrap", "jQuery"),
        "class" => "form"
	 
));
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<head>
<link rel="stylesheet" type="text/css" href="form_include/style_css/style.css">
<link rel="stylesheet" href="form_include/style_css/style.php">
<link rel="stylesheet" href="form_include/style_css/styleradio.php">
<link rel="stylesheet" type="text/css" href="form_include/style_css/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>

<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>
 <script type="text/javascript">

   </script>
<?php
if($multistep == true){
echo '<script src="form_include/js/jquery-ui.min.js"></script>';
echo' <script src="form_include/js/jquery.validate.js"></script>';
echo '<script src="form_include/js/scripts.js"></script>';
}
?>
<script type="text/javascript">
    var js_variable = <?php echo json_encode(html_entity_decode($language_error)); ?>;
    var js_variable_email = <?php echo json_encode(html_entity_decode($email_error)); ?>;
</script>
</head>
<body>
<?php 
if ($leadstestmode === true){ echo $testMode; } 
elseif($leadstestmode === false) { }
else{echo $Mode;}
?>
    <div class="container">
        
        <div class="row">
            
            <div class="col-md-12">
            
           
            
                <div class="col-md-8">
                    <img src="img.png" class="img-responsive img-adjust" alt="Img">
                </div>
                <div class="col-md-4 formcolor">
                    
                    
                            <div class="gap"></div>
                            <?php
                            if($captcha == true){
                                if(empty($_POST)){
                                    $form->addElement(new Element\Hidden("form", "form-elements", "T_General"));
                                    if($multistep == true){
                                        $form->addElement(new Element\HTML('<div class="gap" style="margin-top:60px">  '));
                                        $form->addElement(new Element\HTML('</div>'));
                                        $form->addElement(new Element\HTML('<div class="progress">'));
                                        $form->addElement(new Element\HTML('<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>'));
                                        $form->addElement(new Element\HTML('</div>'));
                                    }
                                    include ("form_include/config_php/option.php");
                                    if($multistep == true){
											?>
										<script language="Javascript">
$(document).on("keypress", "form", function(event) { 
    return event.keyCode != 13;
});
</script>
<?php
                                        $form->addElement(new Element\HTML('<div id="captcha_container">'));
                                        $form->addElement(new Element\HTML(html_entity_decode($captcha_description)));
                                        $form->addElement(new Element\HTML('<div class="form-inline">'));
                                        $form->addElement(new Element\captcha_form(""));
                                        $form->addElement(new Element\Input("", "captcha", array(
                                            "id" => "captcha-code",
                                            "class" => "action captcha"
                                        ), "T_General"));
                                        $form->addElement(new Element\HTML('</div>'));
                                        $form->addElement(new Element\HTML('</div>'));
                                        $form->addElement(new Element\HTML('<div class="captcha-style showw action">'));
                                        $form->addElement(new Element\HTML($captcha_html));
                                        $form->addElement(new Element\HTML('</div>'));
										$form->addElement(new Element\Button($next, "", array("type" => "button", "class" => "action next btn")));
										$form->addElement(new Element\HTML('<div class="submitmessage control-group" >'.$submitMessage.'</div>'));
                                        $form->addElement(new Element\Button(html_entity_decode($submit_button), "", array("class" => "action submitt")));
                                        $form->addElement(new Element\Button($previous, "", array("type" => "button", "class" => "action back btn btn-danger", "style"=>"width: 100% !important")));
                                        $form->addElement(new Element\HTML('<div class="gap" style="margin-bottom:20px">'));
                                        $form->addElement(new Element\HTML('<div class="gap" style="margin-bottom:20px">'));
                                    }else{
                                        $form->addElement(new Element\HTML('<div id="captcha_container">'));
                                        $form->addElement(new Element\HTML(html_entity_decode($captcha_description)));
                                        $form->addElement(new Element\HTML('<div class="form-inline">'));
                                        $form->addElement(new Element\captcha_form(""));
                                        $form->addElement(new Element\Input("", "captcha", array(
                                            "id" => "captcha-code",
                                        ), "T_General"));
                                        $form->addElement(new Element\HTML('</div>'));
                                        $form->addElement(new Element\HTML('</div>'));
                                        $form->addElement(new Element\HTML('<div class="captcha-style showw action">'));
                                        $form->addElement(new Element\HTML($captcha_html));
                                        $form->addElement(new Element\HTML('</div>'));
										$form->addElement(new Element\HTML('<div class="submitmessage control-group" >'.$submitMessage.'</div>'));										
                                        $form->addElement(new Element\Button(html_entity_decode($submit_button), "", array("class" => "action submitt")));
                                        $form->addElement(new Element\HTML('<div class="gap" style="margin-bottom:20px">'));

                                    }
                                    
                                    $form->render();
                                }
                                if(isset($_POST["form"])) {
                                    if (Form::isValid($_POST["form"], false)){
                                        $answer = md5(strtoupper($_POST['captcha']));
                                        if ($answer == $_SESSION['captcha_code']){
                                            if ($api == true){
                                                if($api_type == "MVF"){
                                                    include("form_include/config_php/API-MFV.php");
                                                }else if ($api_type == "TriGlobal") {
													 include("form_include/config_php/API-TRIGLOBAL.php");
													}else if ($api_type == "Lidito") {
													 include("form_include/config_php/api-lidito.php");
													}
                                            }
                                            include("form_include/config_php/insert_lead.php"); 
                                        }else{
                                            PFBC\Form::setError($_POST['form'], html_entity_decode($captcha_error));
                                            header("Location: " . $_SERVER['REQUEST_URI']);
                                    }
                                 } 
                                $form = new Form("succes");
                                $form->configure(array(
                                    "prevent" => array("bootstrap")
                                ));
                                $form->addElement(new Element\HTML('<div class="gap" style="margin-top:200px">'));
                                $form->addElement(new Element\HTML('</div>'));
                                if ($result_InsertLead == "0"){
                                    $form->addElement(new Element\HTML('<p class="text-center style" style="color:#FFF; font-size: 19px; font-weight: bold;">'.html_entity_decode($InsertLeadDuplicate).'</p>'));
                                }else{
                                   $form->addElement(new Element\HTML('<div class="succes">'. html_entity_decode($submit_succes) . '</div>'));
									
									
                                }
                                    $form->render();
                                 exit;
                                }
                            }
                            else {
                            if (empty($_POST)){
				$form->addElement(new Element\Hidden("form", "form-elements", "T_General"));
                                if($multistep == true){
                                    $form->addElement(new Element\HTML('<div class="gap" style="margin-top:60px">'));
                                    $form->addElement(new Element\HTML('</div>'));
                                    $form->addElement(new Element\HTML('<div class="progress">'));
                                    $form->addElement(new Element\HTML('<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>'));
                                    $form->addElement(new Element\HTML('</div>'));
                                }
                                include ("form_include/config_php/option.php");
                                
                                if($multistep == true){
										?>
										<script language="Javascript">
$(document).on("keypress", "form", function(event) { 
    return event.keyCode != 13;
});
</script>
<?php
                                    $form->addElement(new Element\Button($next , "", array("type" => "button", "class" => "action next btn")));
									$form->addElement(new Element\HTML('<div class="submitmessage control-group" >'.$submitMessage.'</div>'));
									$form->addElement(new Element\Button(html_entity_decode($submit_button), "", array("class" => "action submitt")));
                                    $form->addElement(new Element\Button($previous, "", array("type" => "button", "class" => "action back btn btn-danger", "style"=>"width: 100% !important")));
                                 
                                    $form->addElement(new Element\HTML('<div class="gap" style="margin-bottom:20px">'));
                                }else{
									$form->addElement(new Element\HTML('<div class="submitmessage control-group" >'.$submitMessage.'</div>'));
                                    $form->addElement(new Element\Button(html_entity_decode($submit_button), "", array("class" => "action submitt")));
									
                                    $form->addElement(new Element\HTML('<div class="gap" style="margin-bottom:20px">'));
                                }
				$form->render();
				}
							
				if(isset($_POST['form']) && !empty($_POST['form'])) {
                                    if ($api == true){
                                        if($api_type == "MVF"){
                                            include("form_include/config_php/API-MFV.php");
                                        } else if ($api_type == "TriGlobal") {
													 include("form_include/config_php/API-TRIGLOBAL.php");
													}
													else if ($api_type == "Lidito") {
													 include("form_include/config_php/api-lidito.php");
													}
                                    }
                                    include("form_include/config_php/insert_lead.php");
                                
                                    $form = new Form("succes");
                                    $form->configure(array(
                                        "prevent" => array("bootstrap")
                                    ));
                                    $form->addElement(new Element\HTML('<div class="gap" style="margin-top:200px">'));
                                    $form->addElement(new Element\HTML('</div>'));
                                    if ($result_InsertLead == "0"){
                                        $form->addElement(new Element\HTML('<p class="text-center style" style="color:#FFF; font-size: 19px; font-weight: bold;">'.html_entity_decode($InsertLeadDuplicate).'</p>'));
                                    }else{
                                      $form->addElement(new Element\HTML('<div class="succes">'. html_entity_decode($submit_succes) . '</div>'));
										
                                    }
                                    $form->render();
                                    exit;
                                }
                            }
                            
                            ?>
                    </div>
                </div>
            
            </div>
            
        </div>
        <div id="footer" style="text-align: center;width: 100%;position: fixed;left: 0px;right: 0px;bottom: 0px;background-color: #c7c7c7;padding: 3px;"><?php echo $copyrightText; ?></div>
    </div>
  
</body>
 <!-- Javascript -->
<script src="form_include/js/language_validate.js"></script>
<script>
 var dateToday2 = new Date(); 
 $( "input[type=data]" ).datepicker({ dateFormat: 'yy-mm-dd',  numberOfMonths: 1,
        showButtonPanel: false,
        minDate: dateToday2 });
		
		
		
(function($) {
    $.fn.checkFileType = function(options) {
        var defaults = {
            disallowedExtensions: [],
            success: function() {},
            error: function() {}
        };
        options = $.extend(defaults, options);

        return this.each(function() {

            $(this).on('change', function() {
                var value = $(this).val(),
                    file = value.toLowerCase(),
                    extension = file.substring(file.lastIndexOf('.') + 1);

                if ($.inArray(extension, options.disallowedExtensions) != -1) {
                    options.error();
                    $(this).focus();
                } else {
                    options.success();

                }

            });

        });
    };

})(jQuery);

$(function() {
    $('.upload').checkFileType({
        disallowedExtensions: ['com', 'bat', 'exe', 'msi'],
        success: function() {
        
        },
        error: function() {
            alert('You cannot upload this file!');
			var input = $(".upload");    
input.replaceWith(input.val('').clone(true));
        }
    });

});
var uploadField = document.getElementsByClassName('upload')[0];

uploadField.onchange = function() {
    if(this.files[0].size > 12007200){
       alert("File is too big!");
	   var input = $(".upload");    
input.replaceWith(input.val('').clone(true));
    };
};

function do_nothing() {
    console.log("click prevented");
    return false;
    }

    $('html').delegate('form', 'submit', function(e) {
       $(e.target).find(':submit').click(do_nothing);
       setTimeout(function(){
       $(e.target).unbind('click', do_nothing);
       }, 10000);
    });


</script>

  