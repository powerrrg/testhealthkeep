<?php
onlyLogged();

$pageTitle="Messages - HealthKeep";
$pageDescr="All your notifications and messages";

$designV1=1;
require_once(ENGINE_PATH.'html/header.php');
$active="messages";
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold">
		<div id="iMessages" class="iBoard clearfix">
			<div class="iHeading iFull margin10auto padding15">
				<h2 class="colorBlue margin0">Notifications and Messages</h2>
			</div>
			<div id="iMessagesHolder" class="iFull iBoard2 margin20auto" style="padding:5px 15px;">
				<?php
				require_once(ENGINE_PATH.'class/config.class.php');
				$configClass=new Config();
				require_once(ENGINE_PATH.'class/topic.class.php');
				$topicClass=new Topic();
				require_once(ENGINE_PATH.'class/message.class.php');
				$messageClass=new Message();
				$resMsg=$messageClass->getInbox();
				if(!$resMsg["result"]){
					?>
					<div class="alert alert-info center" style="margin:50px 0;">There are no notifications or messages</div>
					<?php
				}else{
					$onload.="
						$('.iMsgItemUnRead a').click(function(e){
							e.stopImmediatePropagation();
							var myId=$(this).closest('div').attr('id');
							var url = $(this).attr('href');
							$.ajax({
							  type: 'POST',
							  url: '".WEB_URL."act/ajax/msg/markRead.php',
							  data: { id: myId }
							}).done(function( msg ) {
							  location.href=url;
							});
						});
						$('.iMsgItem').hover(function(){
							$(this).children('.iMsgItemDelete').show();
						},function(){
							$(this).children('.iMsgItemDelete').hide();
						});
						$('.iMsgItemDelete').click(function(){
							var myId=$(this).parent().attr('id');
							if(confirm('Are you sure you want to delete this message?')){
								$('#'+myId).slideUp(function() {
									if($('#iMessagesHolder').children(':visible').length == 0){
										$('#iMessagesHolder').prepend('<div id=\"emptyMsg\" class=\"alert alert-info center\" style=\"margin:50px 0;display:none;\">There are no notifications or messages</div>');
										$('#emptyMsg').slideDown();
									}
    							});
								$.ajax({
								  type: 'POST',
								  url: '".WEB_URL."act/ajax/msg/delete.php',
								  data: { id: myId }
								}).done(function( msg ) {
								  if(msg!='ok'){
								  	alert('There was an error and you message was not delete. Please try again later');
								  	$('#'+myId).slideDown();
								  }else{
								  	$('#inboxCount').load('".WEB_URL."act/ajax/msg/updateCount.php');
								  }
								});
							}
						});
					";
					
					foreach($resMsg as $key=>$value){
						if(is_int($key)){
							if($value["image_profile"]==""){
								$imagePath=WEB_URL."inc/img/empty-avatar.png";
								$imageAlt="No Image Avatar";
							}else{
								$imagePath=WEB_URL."img/profile/tb/".$value["image_profile"];
								$imageAlt=$configClass->name($value,false);
							}
							if($value["read_msg"]==1){
								$classRead="iMsgItemRead";
							}else{
								$classRead="iMsgItemUnRead";
							}
							?>
							<div id="message_<?php echo $value["id_msg"]; ?>" class="iMsgItem clearfix <?php echo $classRead; ?>">
								<a href="<?php echo WEB_URL.$value["username_profile"]; ?>">
								<img src="<?php echo $imagePath; ?>" alt="<?php echo $imageAlt; ?>" />
								</a>
								<p>
								<?php 
								if($value["type_msg"]=='likecomment'){
									echo $messageClass->likecommentText($value["username_profile"],$configClass->name($value,false),$value["msg_msg"]);
								}else if($value["type_msg"]=='likepost'){
									echo $messageClass->likepostText($value["username_profile"],$configClass->name($value,false),$value["msg_msg"]);
								}else if($value["type_msg"]=='comment'){
									echo $messageClass->commentText($value["username_profile"],$configClass->name($value,false),$value["msg_msg"]);
								}else if($value["type_msg"]=='newpost'){
									echo $messageClass->newpostText($value["username_profile"],$configClass->name($value,false),$value["msg_msg"]);
								}else if($value["type_msg"]=='follower'){
									echo $messageClass->followText($value["username_profile"],$configClass->name($value,false));
								}else if($value["type_msg"]=='message'){
									echo $value["msg_msg"]; 
								}else if($value["type_msg"]=='newuser'){
									$resTopic=$topicClass->getById($value["topic_msg"]);
									if($resTopic["result"]){
										$topicArray=array("name"=>$resTopic[0]["name_topic"],"link"=>WEB_URL.$topicClass->pathSingular($resTopic[0]["type_topic"])."/".$resTopic[0]["url_topic"]);
									}else{
										$topicArray=array("name"=>"topic","link"=>WEB_URL);
									}
									echo $messageClass->newuserText($value["username_profile"],$configClass->name($value,false),$value["msg_msg"],$topicArray);
								}
								?>
								</p>
								<span class="iMsgItemDelete">
								X
								</span>
							</div>
							<?php
						}
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');