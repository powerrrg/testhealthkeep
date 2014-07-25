<?php

onlyLogged();

if(USER_TYPE!=9){
	go404();	
}

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
				<h1 class="colorRed margin10 center">Update Mailchimp Data</h1>
			</div>
			<div class="iFull iBoard2 margin20auto">
				<?php
			$res=$userClass->getAll(true);
			$total=count($res)-1;
			unset($res["result"]);
			
			$js_array = json_encode($res);

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
							url: '".WEB_URL."act/ges/updateMC.php',
							data: { email: element['email_user'], uname: element['username_profile'], name: element['name_profile'], id: element['id_user'], type: element['type_profile'] }
							}).done(function( msg ) {
							$('#result').prepend(msg+'<br />');      
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
				myLoop();
			";
			?>
			<div id="preloader" style="display:none;margin:20px;text-align:center;">
				<img src="<?php echo WEB_URL; ?>inc/img/v1/inc/ajax-loader-bar.gif" />
			</div>
			<div id="result" class="iBoard2 margin20auto">
				---------------------------------------<br />STARTED!!!<br />DO NOT CLOSE THE BROWSER WINDOW OR TURN OFF THE COMPUTER!!!<br />
			</div>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');