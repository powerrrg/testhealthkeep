<?php
onlyLogged();

$pageTitle="Messages - HealthKeep";
$pageDescr="All your notifications and messages";

$active="messages";

require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div class="iFull iText">
			<div style="float:right;"><button id="delAll" class="btn btn-small btn-red">delete all</button></div>
			<?php
			$onload.="
			$('#delAll').click(function(){
				if(confirm('Are you sure you want to delete all messages and notifications?')){
					window.location.href='".WEB_URL."act/del_all_msg.php';
				}
			});
			";
			?>
			<h2 class="iFullHeading">Notifications and Messages</h2>
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
								}else if($value["type_msg"]=='post4user'){
									echo $messageClass->post4userText($value["username_profile"],$configClass->name($value,false),$value["msg_msg"]);
								}else if($value["type_msg"]=='comcom'){
									echo $messageClass->comcomText($value["username_profile"],$configClass->name($value,false),$value["msg_msg"]);
								}else if($value["type_msg"]=='newpost'){
									if(!is_null($value["topic_msg"])){
										echo $messageClass->newpostText($value["username_profile"],$configClass->name($value,false),$value["msg_msg"],$value["topic_msg"]);
									}else{
										echo $messageClass->newpostText($value["username_profile"],$configClass->name($value,false),$value["msg_msg"]);
									}
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
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');