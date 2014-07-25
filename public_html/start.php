<?php
require_once('../engine/starter/config.php');

onlyLogged();

$jsfunctions.="mixpanel.track('Start V2');";

$pageTitle="Set your account details - HealthKeep";
$pageDescr="HealthKeep is a fun and intuitive social health network. It helps you to understand, organize and share about your health.  You are automatically connected to others who share your health issues.  You can connect to your doctors, and you are empowered improve your health and the health of those you care for.";

$fbTracking=1;

require_once(ENGINE_PATH.'render/base/header.php');
$iamstarting=1;
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="mainBlue">
	<hgroup class="iWrap">
		<form id="startForm" action="<?php echo WEB_URL; ?>act/account/start.php" method="POST">
			
			<div class="addEventFormItem clearfix">
				<div id="usernameError" class="addEventFormError"></div>
				<input type="text" maxlength="50" placeholder="Choose Username, don't use your real name" name="username" id="username" value="" /> 
				<?php
				$onload.="$('#username').focus();";
				?>
			</div>
			<div class="addEventFormItem clearfix">
				<div id="passwordError" class="addEventFormError"></div>
				<input type="password" maxlength="50" placeholder="Choose Password" name="password" id="password" value="" /> 	
			</div>
			<div class="addEventFormItem clearfix">
				<select id="yourGender" name="gender">
					<option value="m" selected>Male</option>
					<option value="f">Female</option>
				</select>
			</div>
			<h3>Share an experience and join HealthKeep.</h3>
			<h4>HealthKeep is totally anonymous. You are safe with us.</h4>
			<div class="addEventFormItem clearfix">
				<div id="experienceError" class="addEventFormError"></div>
				<textarea id="experience" name="experience" placeholder="Share your health experience. It can be about you or a loved one. Be complete and detailed. Be sure to mention any diseases or symptoms involved."></textarea>
			</div>
			<div class="addEventFormButtons clearfix">
				<input type="submit" value="Share your experience" disabled class="btn btn-red submitBtn track"  data-category="registrationcompleted" data-action="submitstart" data-label="submitedstart" data-value="2" />
			</div>
			<?php
			if($config["branch"]=="prod"){
				$needFormTrack=1;
			}
			?>
			<p style="color:#fff;margin:40px 0;text-align:center;">Stumped? Don't worry! See examples of what others are posting:</p>
			<div style="position:relative;margin-bottom:50px;">
				<img src="<?php echo WEB_URL; ?>inc/img/v2/base/left_arrow.png" id="startExp_left" />
				<div id="startExp_1" class="clearfix startExp">
					<img src="<?php echo WEB_URL; ?>inc/img/avatar/man7.jpg" alt="samples51" />
					<div>
						<h6>samples51</h6>
						<p>I had terrible migraine this morning.. Is there anything i can do to stop having them?</p>
					</div>
				</div>
				<div id="startExp_2" class="clearfix startExp">
					<img src="<?php echo WEB_URL; ?>inc/img/avatar/woman7.jpg" alt="sunnygarden" />
					<div>
						<h6>sunnygarden</h6>
						<p>I had toe surgery (bunionectomy) 3 months ago and am still in a lot of pain. Today it's just throbbing standing on it and hard to put my shoes on. What kind of recovery process have you experienced? I want to go dancing in high heels!</p>
					</div>
				</div>
				<div id="startExp_3" class="clearfix startExp">
					<img src="<?php echo WEB_URL; ?>inc/img/avatar/man3.jpg" alt="momof5" />
					<div>
						<h6>momof5</h6>
						<p>Someone I know has breast cancer. Will she have to take radiation for sure?</p>
					</div>
				</div>
				<div id="startExp_4" class="clearfix startExp">
					<img src="<?php echo WEB_URL; ?>inc/img/avatar/woman3.jpg" alt="grandmabos" />
					<div>
						<h6>grandmabos</h6>
						<p>I became diabetic at age 55 type 1 with no warning.</p>
					</div>
				</div>
				<div id="startExp_5" class="clearfix startExp">
					<img src="<?php echo WEB_URL; ?>inc/img/avatar/man5.jpg" alt="masher" />
					<div>
						<h6>masher</h6>
						<p>Hi I have a 25 year old son who has proliferative retinopathy and maculopathy he is currently having laser, we would love to hear from anyone just for some support as we are pretty scared thank you</p>
					</div>
				</div>
				<div id="startExp_6" class="clearfix startExp">
					<img src="<?php echo WEB_URL; ?>inc/img/avatar/woman5.jpg" alt="ladyknight" />
					<div>
						<h6>ladyknight</h6>
						<p>I have brittle Type 1 diabetes and severe depression which feed off each other in a seemingly unending cycle. I now have multiple complications and I spend a lot of time unable to see a way off this cycle. My endo says we need to fix my mind/psyche first, my psychologist says we need to control my blood glucose first, in other words "go away and quit bugging us" if it wasn't for my father and grandchildren I would very happily grant them their wish. Has anybody beaten this combo, if so do you have any suggestions for me please</p>
					</div>
				</div>
				<div id="startExp_7" class="clearfix startExp">
					<img src="<?php echo WEB_URL; ?>inc/img/avatar/man9.jpg" alt="edkent" />
					<div>
						<h6>edkent</h6>
						<p>I plan on fully quitting and I'm doing it cold turkey. My friends have a great incentive for me. If I can stay smoke-free for three months, they're taking me out to dinner to a five star restaurant. Every time I cheat, they say they will change the restaurant until finally we're eating take out!</p>
					</div>
				</div>
				<div id="startExp_8" class="clearfix startExp">
					<img src="<?php echo WEB_URL; ?>inc/img/avatar/man10.jpg" alt="seafmw" />
					<div>
						<h6>seafmw</h6>
						<p>There was a period in my life where I could not eat food, or drink water for weeks at a time. My appetite was completely gone, and I had to force food down my throat. This continued until I learned that this was being caused by depression / nervous issue that I had been dealing with for years.</p>
					</div>
				</div>
				<div id="startExp_9" class="clearfix startExp">
					<img src="<?php echo WEB_URL; ?>inc/img/avatar/woman10.jpg" alt="ajax77" />
					<div>
						<h6>ajax77</h6>
						<p>My hands seem to fatigue easily when I grip/use a paintbrush. There can also be some slight pain. Is this of any concern or is it normal fatigue? The symptoms don't match up with carpal tunnel syndrome.</p>
					</div>
				</div>
				<div id="startExp_10" class="clearfix startExp">
					<img src="<?php echo WEB_URL; ?>inc/img/avatar/woman11.jpg" alt="lorir1" />
					<div>
						<h6>lorir1</h6>
						<p>Migraines have been a part of my life since the fourth grade. At 49, I am finally getting relief with Maxalt. If you take this at the first symptom (aura, for me) it will prevent MOST of the migraine from progressing. I was left with a, what I call, 'hangover' feeling. Believe me, this beats the pounding excruciating headache followed by nausea!</p>
					</div>
				</div>
				<img src="<?php echo WEB_URL; ?>inc/img/v2/base/right_arrow.png" id="startExp_right" />
			</div>
			<?php
			$jsfunctions.="
			var expt_active=1;
			";
			$dontPushFooter=1;
			$onload.="
	  		$('#startExp_right').click(function(){
	  			$('.startExp').hide();
	  			$('#startExp_'+expt_active).hide();
	  			if(expt_active>=10){
	  				expt_active=1;
	  			}else{
	  				expt_active++;
	  			}
	  			$('#startExp_'+expt_active).show();
	  		});
	  		$('#startExp_left').click(function(){
		  		$('.startExp').hide();
	  			$('#startExp_'+expt_active).hide();
	  			if(expt_active<=1){
	  				expt_active=10;
	  			}else{
	  				expt_active--;
	  			}
	  			$('#startExp_'+expt_active).show();
	  		});
			";
			$onload.="$('.submitBtn').prop('disabled', false);
					$('input[placeholder],textarea').placeholder();";
			$onload.="
				$('#username').keyup(function() {
					this.value = this.value.toLowerCase();
			        if (this.value.match(/[^a-zA-Z0-9\_\-]/g)) {
			            this.value = this.value.replace(/[^a-zA-Z0-9\_\-]/g, '');
			        }
			        testUsername();
			    });
				$('#username').change(function(){
					testUsername();
				});
			";
			$jsfunctions.="
			var usernameok=true;
			var usernamekeyup=false;
			function testUsername(){
				if($('#username').val().length>4){
					$.ajax({
						type: 'POST',
						cache: false,
						url: '".WEB_URL."act/ajax/username.php',
						data: { username: $('#username').val(), notme:'1' },
						success: function(data) {
							if(data=='ok'){
								usernameok=true;
								$('#usernameError').hide();
							}else if(data=='exists'){
								usernameok=false;
								$('#usernameError').show();
								$('#usernameError').html('Username already in use. Please, choose another.');
								if(!usernamekeyup){
									$('#username').keyup(function(){
										testUsername();
									});
									usernamekeyup=true;
								}
							}
						}
					});
				}
			}
			";
			$onload.="$('#startForm').submit(function(){
				if($('#username').val().length<5){
					$('#usernameError').show();
					$('#usernameError').html('Username needs to have more than 5 characters');
					$('html, body').animate({
					         scrollTop: $('#usernameError').offset().top
					     }, 200);
					$('#username').focus();
					return false;
				}else if(!usernameok){
					$('#usernameError').show();
					$('#usernameError').html('Invalid or duplicate username');
					$('html, body').animate({
					         scrollTop: $('#usernameError').offset().top
					     }, 200);
					$('#username').focus();
					return false;
				}else if($('#password').val().length<5){
					$('#passwordError').show();
					$('#passwordError').html('Password needs to have more than 5 characters');
					$('html, body').animate({
					         scrollTop: $('#passwordError').offset().top
					     }, 200);
					$('#password').focus();
					return false;
				}else if($('#experience').val().length<10){
						alert('You need to add a health experience, question or concern.');
						$('#experience').focus();
						return false;
				}else{
					$('.submitBtn').prop('disabled', true);
					return true;
	
				}
			});";
			$onload.="
			$('#password').change(function(){ passwordChecks(); });$('#password').keyup(function(){ passwordChecks(); });
			";
			$jsfunctions.="function passwordChecks(){
				if($('#password').val().length>=5){
					$('#passwordError').hide();
				}
			}";
			$onload.="
			$('#username').change(function(){ usernameChecks(); });$('#username').keyup(function(){ usernameChecks(); });
			";
			$jsfunctions.="function usernameChecks(){
				if($('#username').val().length>=5){
					$('#usernameError').hide();
				}
			}";
			?>
		</form>
	</hgroup>	
</article>
<?php
if(defined(USER_TYPE) && USER_TYPE<5){
	require_once(ENGINE_PATH."mx/signup.php");
	if(isset($_SESSION["mx_signup"]) && $_SESSION["mx_signup"]==2){
		$_SESSION["mx_signup"]=0;
	}
}
$justSignedUp=1;
require_once(ENGINE_PATH.'render/base/footer.php');