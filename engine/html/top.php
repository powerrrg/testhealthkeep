<div id="dBar">
	<div class="iHold clearfix">
		<?php
		if(USER_ID!=0){
			$logoLink=WEB_URL."home/";
		}else{
			$logoLink=WEB_URL;
		}
		?>
		<div id="mobileMenuBtn"><a href="#" onclick="$('#topMenu').toggle();"><img src="<?php echo WEB_URL; ?>inc/img/v1/inc/menu.gif" alt="Mobile Toggle Menu" /></a></div>
		<?php
		if(isset($active) && $active=="homepage"){
		?>
		<a id="dLogo" href="<?php echo $logoLink; ?>">
			<img src="<?php echo WEB_URL; ?>inc/img/v1/logo/HealthKeep.png" alt="HealthKeep" />
		</a>
		<?php
		}else{
		?>
		<a id="dLogo40" href="<?php echo $logoLink; ?>"><img src="<?php echo WEB_URL; ?>inc/img/v1/logo/healthkeep_h40.png" alt="HealthKeep" /></a>
		<?php
		}
		?>
		<div id="topMenu">
		
		<?php
		if(USER_ID!=0){
		?>
		
		<?php
		if(isset($active) && $active=="homepage"){
		?>
		<ul id="dNav">
		<?php
		}else{
		?>
		<ul id="dNav" class="dNavSmall">
		<?php
		}
		/*
		?>
		
			<li<?php if(isset($active) && $active=="home"){ echo ' class="active"'; } ?>><a href="<?php echo WEB_URL; ?>home">Home</a></li>

			<li class="dNavDivider">|</li>
					*/	?>
			<?php
			if(USER_TYPE==9){
			?>
			<li<?php if(isset($active) && $active=="backoffice"){ echo ' class="active"'; } ?>><a href="<?php echo WEB_URL; ?>ges">Back Office</a></li>
			<li class="dNavDivider">|</li>
			<?php
			}
			/*if(PROFILE_TYPE==1){
			?>
			<li<?php if(isset($active) && $active=="timeline"){ echo ' class="active"'; } ?>><a href="<?php echo WEB_URL; ?>timeline">Timeline</a></li>
			<li class="dNavDivider">|</li>
			<?php
			}*/
			?>
			<li<?php if(isset($active) && $active=="myProfile"){ echo ' class="active"'; } ?>><a href="<?php echo WEB_URL.USER_NAME; ?>">Profile</a></li>
			<li class="dNavDivider">|</li>
			<li<?php if(isset($active) && $active=="feed"){ echo ' class="active"'; } ?>><a href="<?php echo WEB_URL; ?>feed">Feed</a></li>
			<li class="dNavDivider">|</li>
			<li<?php if(isset($active) && $active=="messages"){ echo ' class="active"'; } ?>><span id="inboxHolder"><a href="<?php echo WEB_URL; ?>msg" id="inboxTxt">Inbox</a> <span id="inboxCount"><?php echo PROFILE_MSGS; ?></span></span></li>
		</ul>
		<?php
		}else{
		?>
			<?php
			if(isset($active) && $active=="homepage"){
			?>
				<ul id="dNav">
			<?php
			}else{
			?>
				<ul id="dNav" class="dNavSmall">
			<?php
			}
			?>
				<li<?php if(isset($active) && $active=="about"){ echo ' class="active"'; } ?>><a href="<?php echo WEB_URL; ?>about">About us</a></li>
				<li class="dNavDivider">|</li>
				<li<?php if(isset($active) && $active=="contact"){ echo ' class="active"'; } ?>><a href="<?php echo WEB_URL; ?>contact">Contact</a></li>
			</ul>
		<?php
		}
		
		if(!isset($active) || (isset($active) && $active!="homepage")){
		
		if(isset($_GET["l1"]) && isset($_GET["l2"]) && $_GET["l1"]=="q"){
			$qvalue=urldecode($_GET["l2"]);
		}else{
			$qvalue="";
		}
		?>
		<div id="dBarSearch" <?php if(isset($active) && $active=="homepage"){ echo 'class="marginTop9"'; } ?>>
			<form method="get" action="<?php echo WEB_URL ;?>q.php" id="topSearch">
			<input type="text" name="q" id="q" maxlength="100" value="<?php echo $qvalue; ?>" autocomplete="off" />
			</form>
		</div>
		<?php
		$needTypeAhead=1;
		$jsfunctions.="
		$(function () {
			$('#q').typeahead({
		        ajax: { url: '".WEB_URL."act/ajax/search/typeahead.php', triggerLength: 1 }, 
		        itemSelected: displayResult
		    });

		});
		function displayResult(item, val, text) {
			if(val==0){
				location.href='".WEB_URL."q.php?q='+$('#q').val();
			}else{
				location.href='".WEB_URL."q.php?id='+val;
			}
		}
		";
		$onload.="$('#topSearch').submit(function(){
			if($('#q').val().length<3){
			 alert('You can only search words with 3 characters or more!');
			 return false;
			}
		});";
		
		}
		if(USER_ID!=0){
			if(isset($active) && $active=="homepage"){
			?>
			<div id="dBarLoginBtn" class="btn-group">
			<?php
			}else{
			?>
			<div id="dBarLoginBtn" class="btn-group marginY0">
			<?php
			}
			?>
				<?php
				if(USER_IMAGE==""){
					$userImageUrl=$imagePath=WEB_URL."inc/img/empty-avatar.png";
				}else{
					$userImageUrl=WEB_URL."img/profile/tb/".USER_IMAGE;
				}
				?>
				<a class="dropdown-toggle" id="topAccountBtn" style="background-image:url('<?php echo $userImageUrl; ?>');" data-toggle="dropdown">
					
					
					<span></span>
				</a>
	
				<ul class="dropdown-menu pull-right">
					<li><a href="<?php echo WEB_URL; ?>account/details">Account Details</a></li>
					<?php
					if(PROFILE_TYPE==1){
					?>
					<li><a href="<?php echo WEB_URL; ?>account/health">Health Details</a></li>
					<?php
					}
					?>
					<li><a href="<?php echo WEB_URL; ?>account/notifications">Email Settings</a></li>
					<li><a href="<?php echo WEB_URL; ?>act/login.php?logout">Logout</a></li>
				</ul>
			</div>
			<?php
			$jsTopFormIsSet=false;
		}else{
			?>
				
			<?php
			if(isset($active) && $active=="homepage"){
			?>
			<div id="dBarLoginBtn" class="btn-group">
			<?php
			}else{
			?>
			<div id="dBarLoginBtn" class="btn-group marginY0">
			<?php
			}

				if(!isset($hideLogin)){
				?>
				<a class="btn btn-red dropdown-toggle" data-toggle="dropdown">Login</a>
				<?php
					$token=sha1(microtime(true).mt_rand(10000,90000));
				    $_SESSION["token"]=$token;
				    
				    $goTo=$_SERVER["REQUEST_URI"];
				    if(isset($_GET["go"])){
				    	$goTo="?go=".ltrim($_GET["go"],"/");
				    }else if($goTo=="" || $goTo=="/"){
					    $goTo="";
				    }else{
					    $goTo="?go=".ltrim($goTo,"/");
				    }
					?>
					<div class="dropdown-menu pull-right">
					<form id="dBarForm" method="post" class="clearfix" action="<?php echo WEB_URL; ?>act/login.php<?php echo $goTo; ?>">
						<input type="text" maxlength="150" class="txtInputs" placeholder="Email" name="email" id="email" />
						<label class="formError" id="emailFormError"></label>
						<input type="password" maxlength="20" class="txtInputs" placeholder="Password" name="password" id="password" />
						<label class="formError" id="passwordFormError"></label>
						<div class="colorGray" style="font-size:12px;"><input type="checkbox" name="remember" /> Remember Me</div>
						<input type="text" name="hpot" class="hpot" value="" />
						<input type="hidden" name="token" value="<?php echo $token; ?>" />
						<a href="<?php echo WEB_URL; ?>forgot.php" id="forgotTopBar">Forgot Password</a>
						<input type="submit" value="Log In" disabled class="btn submitBtn btn-blue" />
						
					</form>
					</div>
					<?php
					$onload.="$('#dBarForm').bind('click', function (e) { e.stopPropagation() });";
					$jsTopFormIsSet=true;
					$onload.="
						$('.submitBtn').prop('disabled', false);
						$('input[placeholder],textarea[placeholder]').placeholder();
						$('#dBarForm').submit(function(){
							if(!isValidEmailAddress($('#email').val())){
								$('#emailFormError').show();
								$('#emailFormError').html('Invalid email address');
								$('#email').focus();
								return false;
							}else if($('#password').val().length<5){
								$('#passwordFormError').show();
								$('#passwordFormError').html('Invalid Password');
								$('#password').focus();
								return false;
							}else{
								return true;
							}
						});
						$('#email').keyup(function() {
					    	$('#emailFormError').hide();
					    });
					    $('#password').keyup(function() {
					    	$('#passwordFormError').hide();
					    });
					";
				}
				?>
			</div>
			<?php
		}
		?>
		</div>
	</div>
</div>