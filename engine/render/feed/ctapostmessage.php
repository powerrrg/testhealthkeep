<form class="iPost clearfix" enctype="multipart/form-data" id="msgPost" style="display:none;margin:90px 0 30px 0;clear:both;" method="post" action="<?php echo WEB_URL; ?>act/post/savePostNew.php">
	<?php
	$ctaText="Share a post with ".$resProfile[0]["username_profile"];
	?>
	<textarea placeholder="<?php echo $ctaText; ?>" style="width:100%;height:100px;" name="txtPost" id="txtMsgPost"></textarea>		
	<input type="hidden" name="asMessage" value="<?php echo $resProfile[0]["id_profile"]; ?>" />
	<input type="submit" style="" disabled class="btn btn-red submitBtn" value="submit" />

		<div id="iPostBtns" style="float:left;">
		<span id="imageChooseUploadHolder">
		<img id="imageChooseUpload" src="<?php echo WEB_URL; ?>inc/img/v1/inc/camera.png" style="width:20px;height:20px;margin-right:15px;cursor:pointer;" title="add a photo" />
		</span>
		<div class="fileupload fileupload-new avatarImgBtns clearfix" data-provides="fileupload" style="display:none;padding:0;float:right;">
			<span class="btn-file" style="display:none"><span class="fileupload-new"></span>
			<input type="file" style="display:none" name="avatarFile" id="avatarFile" /></span>
			<div class="fileupload-preview thumbnail" style="width: 30px; height: 30px;float:right;margin-top:-10px;"></div>
		</div>
		<?php
		$onload.="$('#imageChooseUpload').click(function(){ $('#avatarFile').click(); });";
		$needFupload=1;
		$onload.="$('#avatarFile').bind('change', function() {
			$('.fileupload-new').hide();
			if(this.files[0]!=undefined && this.files[0].size>2097152){
				alert('The Image cannot have more than 2 MB in size');
				$('.fileupload').fileupload('clear');
		  	}else if(this.files[0]!=undefined){
		  		var val = $(this).val();
		  		var val = val.substring(val.lastIndexOf('.') + 1).toLowerCase();
		  		if(val!='gif' && val!='jpg' && val!='jpeg' && val!='png'){
			  		alert('That is not a valid image file!');
		  			$('.fileupload').fileupload('clear');		            
		  		}else{
		  			$('#imageChooseUpload').hide();
		  			$('.fileupload-new').show();
		  		}
		  	}
		});";
		?>
		</div>

		
	
</form>
<?php		
if(!isset($needAutoGrow)){
	$needAutoGrow=1;
}	
$onload.="$('#txtMsgPost').autogrow({'minHeight':'65'});";
$onload.="$('.submitBtn').prop('disabled', false);$('input[placeholder],textarea[placeholder]').placeholder();";

$onload.="
$('#msgPost').submit(function(){
	if($('#txtMsgPost').val().length<5){
		alert('You need to type a message to be able to post!');
		$('#txtMsgPost').focus();
		return false;
	}else{
		$(this).slideUp(function(){
			$('#msgPostSubNotice').slideDown();
		});
		
		return true;
	}
});
";
?>