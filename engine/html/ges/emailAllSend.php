<?php

onlyLogged();

if(USER_TYPE!=9){
	go404();	
}

if(!isset($_POST["from"]) || !isset($_POST["subject"]) || !isset($_POST["message"])){
	go404();	
}

$from=$_POST["from"];
$subject=$_POST["subject"];
$message=nl2br($_POST["message"]);

$pageTitle="Back Office";
$pageDescr="Back Office";

$designV1=1;
$active="backoffice";
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
		<div class="iBoard">
		<div class="iHeading iFull margin10auto padding15">
		<h1 class="colorRed margin10 center">Email all users</h1>
		</div>
			<h3 class="iFull margin20auto colorBlue">Preview</h3>
			<div class="iFull iBoard2 margin20auto">
				<div>
					<p style="margin-top:0;"><b>From:</b> <?php echo $from; ?></p>
					<p><b>Subject:</b> <?php echo $subject; ?></p>
					<p><b>Message:</b><br />
						<?php echo $message; ?>
					</p>
				</div>
			</div>
			<?php
			$res=$userClass->getAll();
			$total=count($res)-1;
			$myarr=array();
			foreach($res as $key=>$value){
				if(is_int($key)){
					if($value["type_profile"]==1){
						$myarr[]=array("name"=>$value["username_profile"],"email"=>$value["email_user"]);
					}else{
						$myarr[]=array("name"=>$value["name_profile"],"email"=>$value["email_user"]);
					}
				}
			}
			$js_array = json_encode($myarr);
			$jsfunctions.="var javascript_array = ". $js_array . ";\n";
			$jsfunctions.="
				var i = 0;
				var length = javascript_array.length,element = null;
				function myLoop () {           
				   setTimeout(function () {    
				      element = javascript_array[i];
				      var date = new Date;
						var seconds = date.getSeconds();
						var minutes = date.getMinutes();
						var hour = date.getHours();
						var nowtime= hour+':'+minutes+':'+seconds;
						$.ajax({
							type: 'POST',
							url: '".WEB_URL."act/ges/emailAll.php',
							data: { from: '".$from."', name: element['name'], email: element['email'], subject: '".addslashes($subject)."', message: '".addslashes($message)."' }
							}).done(function( msg ) {
							$('#result').prepend(nowtime+' - '+element['name']+' - '+element['email']+' - '+msg+'<br />');      
								i++;                     
							if (i < length) {            
					        	myLoop();             
					        }else{
					        	$('#preloader').hide();
					      		$('#result').prepend('FINISHED!!!<br />---------------------------------------<br />');
					      	} 
						});
						                      
				   }, 1000)
				}
			";
			$onload.="
				$('#goall').click(function(){
					$('#result').html('---------------------------------------<br />STARTED!!!<br />DO NOT CLOSE THE BROWSER WINDOW OR TURN OFF THE COMPUTER!!!<br />');
					$('#preloader').show();
					myLoop();   
				});
			";
			?>
			<div id="preloader" style="display:none;margin:20px;text-align:center;">
				<img src="<?php echo WEB_URL; ?>inc/img/v1/inc/ajax-loader-bar.gif" />
			</div>
			<div id="result" class="iFull iBoard2 margin20auto">
				<p>This message will be sent to <b><?php echo $total; ?></b> users</p>
				<p>Are you sure you want to proceed</p>
				<p><button id="goall" class="btn btn-large btn-red">Send</button></p>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');