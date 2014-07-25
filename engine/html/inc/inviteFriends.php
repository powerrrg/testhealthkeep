<div id="iHoldInvite">
<input type="text" id="inviteYourName" class="input100" maxlength="200" placeholder="Your name" />
<input type="email" id="inviteFriendEmail1" class="input100" maxlength="200" placeholder="Your friends email" />
<input type="email" id="inviteFriendEmail2" class="input100" style="display:none;" maxlength="200" placeholder="Your friends email" />
<input type="email" id="inviteFriendEmail3" class="input100" style="display:none;" maxlength="200" placeholder="Your friends email" />
<input type="email" id="inviteFriendEmail4" class="input100" style="display:none;" maxlength="200" placeholder="Your friends email" />
<input type="email" id="inviteFriendEmail5" class="input100" style="display:none;" maxlength="200" placeholder="Your friends email" />
<div id="inviteAnother" style="margin-top:-10px;margin-bottom:10px;text-align:center">
<a href="#" onclick="return inviteAnother();" style="color:#666;font-size:11px;">add another friends email</a></div>
<input type="button" id="inviteBtn" value="send" class="btn btn-blue" style="width:100%;" />
</div>
<div id="iHoldInviteSending">
	<img src="<?php echo WEB_URL; ?>inc/img/v1/inc/ajax-loader.gif"iHoldInviteSending alt="sending" />
</div>
<?php
if(!$jsTopFormIsSet){
	$onload.="$('input[placeholder]').placeholder();";
}
$jsfunctions.="
var inviteNumEmail=1;
function inviteAnother(){
	inviteNumEmail++;
	if(inviteNumEmail>4){
		$('#inviteAnother').hide();
	}
	$('#inviteFriendEmail'+inviteNumEmail).show();
	return false;
}
";
$onload.="$('#inviteBtn').click(function(){
	if($('#inviteYourName').val()==''){
		alert('Please insert your name');
		$('#inviteYourName').focus();
	}else if(!isValidEmailAddress($('#inviteFriendEmail1').val())){
		alert('Your friends email is not valid!');
		$('#inviteFriendEmail1').focus();
	}else if($('#inviteFriendEmail2').val()!='' && !isValidEmailAddress($('#inviteFriendEmail2').val())){
		alert('Your friends email is not valid!');
		$('#inviteFriendEmail2').focus();
	}else if($('#inviteFriendEmail3').val()!='' && !isValidEmailAddress($('#inviteFriendEmail3').val())){
		alert('Your friends email is not valid!');
		$('#inviteFriendEmail3').focus();
	}else if($('#inviteFriendEmail4').val()!='' && !isValidEmailAddress($('#inviteFriendEmail4').val())){
		alert('Your friends email is not valid!');
		$('#inviteFriendEmail4').focus();
	}else if($('#inviteFriendEmail5').val()!='' && !isValidEmailAddress($('#inviteFriendEmail5').val())){
		alert('Your friends email is not valid!');
		$('#inviteFriendEmail5').focus();
	}else{
		$('#iHoldInvite').hide();
		$('#iHoldInviteSending').show();
		var emailArray=$('#inviteFriendEmail1').val();
		if($('#inviteFriendEmail2').val()!=''){
			emailArray=emailArray+','+$('#inviteFriendEmail2').val();
		}
		if($('#inviteFriendEmail3').val()!=''){
			emailArray=emailArray+','+$('#inviteFriendEmail3').val();
		}
		if($('#inviteFriendEmail4').val()!=''){
			emailArray=emailArray+','+$('#inviteFriendEmail4').val();
		}
		if($('#inviteFriendEmail5').val()!=''){
			emailArray=emailArray+','+$('#inviteFriendEmail5').val();
		}
		$.ajax({
			type: 'POST',
			url: '".WEB_URL."act/ajax/inviteFriends.php',
			data: { yourName: $('#inviteYourName').val(), yourFriend: emailArray },
			success: function(data) {
				if(data=='ok'){
					$('#iHoldInviteSending').html('Your invite was successfully sent!<br /><br />Thank you');
				}else{
					$('#iHoldInviteSending').html('There was an error when we attempted to send your invite.'+data);
				}
			}
		});
	}
});";
?>