<?php
onlyLogged();

$pageTitle="Notifications - HealthKeep";
$pageDescr="Set what notifications you would like us to email you.";

$designV1=1;
require_once(ENGINE_PATH.'html/header.php');
$active="account";
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold">
		<div id="iMessages" class="iBoard clearfix">
			<div class="iHeading iFull margin10auto padding15">
				<h2 class="colorBlue margin0">Notifications</h2>
			</div>
			<div id="iMessagesHolder" class="iFull iBoard2 margin20auto" style="padding:5px 15px;">
				<p style="border-bottom:1px solid #ccc;padding:0 10px 10px 10px;margin-bottom:0;font-weight:bold">You want to be notified via email when:</p>
				<?php
				require_once(ENGINE_PATH.'class/message.class.php');
				$messageClass=new Message();
				$resNot=$messageClass->getAllEmailSettings();
				$notArray=array();
				foreach($resNot as $key=>$value){
					if(is_int($key)){
						$notArray[$value["type_not"]]=false;
					}
				}
				?>
				<div class="padding10">
				<img src="<?php echo WEB_URL; ?>inc/img/v1/inc/ajax-loader.gif" style="float:right;margin:15px 10px 0 0;display:none;" id="preloader" />
				<p><input type="checkbox" name="likepost" id="likepost" <?php if(!isset($notArray["likepost"])){ echo "checked";} ?> class="notCheckBox" /> Someone likes your post</p>
				<p><input type="checkbox" name="likecomment" id="likecomment" <?php if(!isset($notArray["likecomment"])){ echo "checked";} ?> class="notCheckBox" /> Someone likes your comment</p>
				<p><input type="checkbox" name="comment" id="comment" <?php if(!isset($notArray["comment"])){ echo "checked";} ?> class="notCheckBox" /> Someone comments your post</p>
				<p><input type="checkbox" name="message" id="message" <?php if(!isset($notArray["message"])){ echo "checked";} ?> class="notCheckBox" /> Someone sends you a direct message</p>
				<p><input type="checkbox" name="follower" id="follower" <?php if(!isset($notArray["follower"])){ echo "checked";} ?> class="notCheckBox" /> Someone follows you</p>
				<?php /*<p><input type="checkbox" name="newuser" id="newuser" <?php if(!isset($notArray["newuser"])){ echo "checked";} ?> class="notCheckBox" /> A new user joins a community you follow</p> */ ?>
				<p><input type="checkbox" name="newpost" id="newpost" <?php if(!isset($notArray["newpost"])){ echo "checked";} ?> class="notCheckBox" /> Someone posts about a topic you follow</p>
				<p><input type="checkbox" name="newsletter" id="newsletter" <?php if(!isset($notArray["newsletter"])){ echo "checked";} ?> class="notCheckBox" /> HealthKeep newsletter</p>
				</div>
				<?php
				$onload.="$('.notCheckBox').change(function(){
					$('#preloader').show();
					$('.notCheckBox').attr('disabled', true);
					var sList = '';
					$('.notCheckBox').each(function () {
						if(this.checked==0){
					    sList += '_' + $(this).attr('id');
					    }
					});
					$.ajax({
					  type: 'POST',
					  url: '".WEB_URL."act/ajax/msg/updateNotifications.php',
					  data: { nots: sList }
					}).done(function( msg ) {
					  $('.notCheckBox').removeAttr('disabled');
					  $('#preloader').hide();
					  if(msg!='ok'){
					  	alert('We could not save your setting, please try again later'+msg);
					  }
					});
				});";
				?>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');