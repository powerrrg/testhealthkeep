<form class="iPost iWrap clearfix" enctype="multipart/form-data" id="mainPost" method="post" action="<?php echo WEB_URL; ?>act/post/savePostNew.php">
	<?php
	if(isset($resTopic)){
		$ctaText="Share your health experience with ".$resTopic[0]["name_topic"];
		?>
		<input type="hidden" value="<?php echo $resTopic[0]["id_topic"]; ?>" name="forceTopic" />
		<?php
	}else{
		if(isset($resProfile) && $resProfile[0]["id_profile"]==USER_ID){
			$ctaText="Share what's happening with your health now";
		}else{
			$ctaText="Share your health experience";
		}
	}
	?>
	<textarea placeholder="<?php echo $ctaText; ?>" name="txtPost" id="txtPost"></textarea>					
	<input type="submit" id="feedCTASubmit2" disabled class="btn btn-red submitBtn" value="share your experience" />
		
		<div id="iPostBtns" style="float:right;">
			
			<span id="imageChooseUploadHolder">
			<img id="imageChooseUpload" src="<?php echo WEB_URL; ?>inc/img/v1/inc/camera.png" style="width:20px;height:20px;margin-right:15px;cursor:pointer;" title="add a photo" />
			</span>
			<div class="fileupload fileupload-new avatarImgBtns clearfix" data-provides="fileupload" style="display:none;padding:0;float:right;">
				<span class="btn-file" style="display:none"><span class="fileupload-new"></span>
				<input type="file" style="display:none" name="avatarFile" id="avatarFile" /></span>
				<div class="fileupload-preview thumbnail" style="width: 30px; height: 30px;float:right;margin-top:-10px;margin-right:20px;"></div>
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
$onload.="$('#txtPost').autogrow({'minHeight':'65'});";
$onload.="$('.submitBtn').prop('disabled', false);$('input[placeholder],textarea[placeholder]').placeholder();";

$onload.="
$('#mainPost').submit(function(){
	if($('#txtPost').val().length<5){
		alert('You need to type a message to be able to post!');
		$('#txtPost').focus();
		return false;
	}else{
		$(this).slideUp(function(){
			$(this).parent().html('<p style=\"text-align:center;\">submitting...</p>');
		});
		
		return true;
	}
});
";
?>