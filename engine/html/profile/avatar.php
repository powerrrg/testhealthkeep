<?php
onlyLogged();

$pageTitle="Avatar - HealthKeep";
$pageDescr="Change your avatar";

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$resProfile=$profileClass->getById(USER_ID);

if(!$resProfile["result"]){
	go404();
}


$designV1=1;
require_once(ENGINE_PATH.'html/header.php');
$active="account";
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold">
		<div id="iMessages" class="iBoard clearfix">
			<div class="iHeading iFull margin10auto padding15">
				<h2 class="colorBlue margin0">Change your avatar</h2>
			</div>
			<div id="iMavatarHolder" class="iFull iBoard2 margin20auto" style="padding:5px 15px;">
				<form id="iMavatarForm" enctype="multipart/form-data" method="post" action="<?php echo WEB_URL; ?>act/profile/avatar.php">
						<ul id="imageChoose2" class="clearfix">
							<?php 
							if($resProfile[0]["gender_profile"]=="f"){
								$gender="woman";
							}else{
								$gender="man";
							}
							?>
							<h3 class="colorBlue">Choose an avatar</h3>
							<li class="imageChooseHolder active">
								<input type="radio" checked name="theimage" value="1" />
								<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>1.jpg" id="avatarImg1" />
							</li>
							<li class="imageChooseHolder">
								<input type="radio" name="theimage" value="2" />
								<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>2.jpg" id="avatarImg2" />
							</li>
							<li class="imageChooseHolder">
								<input type="radio" name="theimage" value="3" />
								<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>3.jpg" id="avatarImg3" />
							</li>
							<li class="imageChooseHolder">
								<input type="radio" name="theimage" value="4" />
								<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>4.jpg" id="avatarImg4" />
							</li>
							<li class="imageChooseHolder">
								<input type="radio" name="theimage" value="5" />
								<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>5.jpg" id="avatarImg5" />
							</li>
							<li class="imageChooseHolder">
								<input type="radio" name="theimage" value="6" />
								<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>6.jpg" id="avatarImg6" />
							</li>
							<li class="imageChooseHolder">
								<input type="radio" name="theimage" value="7" />
								<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>7.jpg" id="avatarImg7" />
							</li>
							<li class="imageChooseHolder">
								<input type="radio" name="theimage" value="8" />
								<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>8.jpg" id="avatarImg8" />
							</li>
							<li class="imageChooseHolder">
								<input type="radio" name="theimage" value="9" />
								<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>9.jpg" id="avatarImg9" />
							</li>
							<li class="imageChooseHolder">
								<input type="radio" name="theimage" value="10" />
								<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>10.jpg" id="avatarImg10" />
							</li>
							<li class="imageChooseHolder">
								<input type="radio" name="theimage" value="11" />
								<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>11.jpg" id="avatarImg11" />
							</li>
							<li class="imageChooseHolder">
								<input type="radio" name="theimage" value="12" />
								<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>12.jpg" id="avatarImg12" />
							</li>
							<h3 class="colorBlue" style="clear:both;padding-bottom:0;float:left;margin: 10px 15px 0 0;">Or upload your own image</h3>
							<li id="imageChooseUpload" class="imageChooseHolder">
								<input type="radio" name="theimage" value="99" />
								<img src="<?php echo WEB_URL; ?>inc/img/v1/inc/plus100.png" style="border:1px solid #d8d9da;width:40px;height:40px;" />
							  
								
								<?php
								$onload.="$('#imageChooseUpload').click(function(){ $('#avatarFile').click(); });";
								$needFupload=1;
								$onload.="$('#avatarFile').bind('change', function() {
									$('.fileupload-new').hide();
									$('#subImg').hide();
									if(this.files[0]!=undefined && this.files[0].size>2097152){
										alert('The Image cannot have more than 2 MB in size');
										$('.fileupload').fileupload('clear');
										$('.fileupload-new').show();
								  	}else if(this.files[0]!=undefined){
								  		var val = $(this).val();
								  		var val = val.substring(val.lastIndexOf('.') + 1).toLowerCase();
								  		if(val!='gif' && val!='jpg' && val!='jpeg' && val!='png'){
									  		alert('That is not a valid image file!');
								  			$('.fileupload').fileupload('clear');	
								  			$('.fileupload-new').show();		            
								  		}
								  	}
								});";
										?>
								</li>
								<div class="fileupload fileupload-new avatarImgBtns clearfix" data-provides="fileupload" style="display:none;">
									<span class="btn-file" style="display:inline-block"><span class="fileupload-new"></span>
									<input type="file" name="avatarFile" id="avatarFile" /></span>
								</div>
								  <?php
								  $onload.="
									$('.imageChooseHolder').click(function(){
										var inChild=$(this).find('input');
										if(inChild.prop('checked')){
											inChild.prop('checked', false);
										}else{
											inChild.prop('checked', true);
										}
										$('.imageChooseHolder').removeClass('active');
										$('input[name=theimage]:checked', '#iMavatarForm').parent().addClass('active');
										
									});
								  ";
								  ?>
						</ul>
						<div class="center margin20">
						<a href="<?php echo WEB_URL.$resProfile[0]["username_profile"]; ?>" class="colorGray">cancel</a> <button id="saveMyAvatar" type="button" class="btn btn-blue">save</button>
						<?php
						$onload.="
						$('#saveMyAvatar').click(function(){
							if(($('input[name=theimage]:checked', '#iMavatarForm').val()==99 && $('#avatarFile').val()=='') || 
							($('input[name=theimage]:checked', '#iMavatarForm').val()==undefined && $('#avatarFile').val()=='') || 
							($('input[name=theimage]:checked', '#iMavatarForm').val()<1 && $('#avatarFile').val()=='') || 
							($('input[name=theimage]:checked', '#iMavatarForm').val()>12 && $('#avatarFile').val()=='')){
								alert('You need to choose an avatar or upload your own');
							}else{
								$('#iMavatarForm').submit();
							}
						
						});
						";
						?>
						</form>
						</div>
				
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');