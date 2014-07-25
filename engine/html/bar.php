<div id="dBar">
	<div class="iHold">
		<?php
		if(USER_ID!=0){
			$logoLink=WEB_URL."home/";
		}else{
			$logoLink=WEB_URL;
		}
		?>
		<a id="dLogo" href="<?php echo $logoLink; ?>"><img src="<?php echo WEB_URL; ?>inc/img/healthkeep_25.png" alt="HealthKeep" /></a>
		<div id="mobileMenuBtn"><a href="#" onclick="$('#topMenu').toggle();">&#9776;</a></div>
		<div id="topMenu">
		
		<?php
		if(USER_ID!=0){
		?>
		<div id="loggedTop">
			<button class="btn" onclick="location.href='<?php echo WEB_URL; ?>act/login.php?logout';">Logout</button>
		</div>
		<ul id="dNav">
			<li<?php if(isset($active) && $active=="home"){ echo ' class="active"'; } ?>><a href="<?php echo WEB_URL; ?>home">Home</a></li>
			<?php
			if(PROFILE_TYPE==1){
			?>
			<li<?php if(isset($active) && $active=="timeline"){ echo ' class="active"'; } ?>><a href="<?php echo WEB_URL; ?>timeline">Timeline</a></li>
			<?php
			}
			?>
			<li<?php if(isset($active) && $active=="myProfile"){ echo ' class="active"'; } ?>><a href="<?php echo WEB_URL.USER_NAME; ?>"><?php echo USER_NAME; ?></a></li>	
			<li<?php if(isset($active) && $active=="feed"){ echo ' class="active"'; } ?>><a href="<?php echo WEB_URL; ?>feed">Feed</a></li>
		</ul>
		<?php
		$jsTopFormIsSet=false;
		}else{
			if(!isset($hideLogin)){
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
				<form id="dBarForm" method="post" action="<?php echo WEB_URL; ?>act/login.php<?php echo $goTo; ?>">
					<input type="text" maxlength="150" class="txtInputs" placeholder="Email" name="email" id="email" />
					<input type="password" maxlength="20" class="txtInputs" placeholder="Password" name="password" id="password" />
					<input type="text" name="hpot" class="hpot" value="" />
					<input type="hidden" name="token" value="<?php echo $token; ?>" />
					<input type="submit" value="Log In" disabled class="btn submitBtn" />
				</form>
				<?php
				$jsTopFormIsSet=true;
				$onload.="
					$('.submitBtn').prop('disabled', false);
					$('input[placeholder]').placeholder();
					$('#dBarForm').submit(function(){
						if(!isValidEmailAddress($('#email').val())){
							alert('Invalid email address');
							$('#email').focus();
							return false;
						}else if($('#password').val().length<5){
							alert('Password needs to have more than 5 characters');
							$('#password').focus();
							return false;
						}else{
							return true;
						}
					});
				";
			}
		}
		?>
		</div>
	</div>
</div>