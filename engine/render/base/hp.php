<?php
$jsfunctions.="mixpanel.track('HomePage V2');";
$ogImage=WEB_URL."inc/img/v2/logo/HealthKeep.png";

$token=sha1(microtime(true).mt_rand(10000,90000));
$_SESSION["token"]=$token;


require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup id="homeCTA">
		<div class="iWrap">
			<h1>Health Experience Network</h1>
			<h2>See what others say about their meds, conditions, and symptoms.  Add your own experiences</h2>
			<form id="homeRegister" method="post" class="clearfix" action="<?php echo WEB_URL; ?>act/registerNewDesign.php">
				<input type="email" id="hpSingleInput" name="email" placeholder="Enter email to register. It will never be made public or shared." />
				<input type="hidden" name="username" value="user<?php echo time(); ?>" />
				<input type="hidden" name="password" value="<?php echo substr($token, 0,6); ?>" />
				<input type="hidden" name="gender" value="m" />
				<input type="text" name="hpot" class="hpot" value="" />
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				<div class="clearfix">
					<span id="homeDocRegLink"><a href="<?php echo WEB_URL; ?>pro_register.php" style="font-weight:normal">Doctors register here</a></span>
					<input type="submit" id="proFormBtn" disabled class="btn submitBtn btn-red" value="Sign Up" />
				</div>
			</form>
			<?php
			$onload.="$('#hpSingleInput').focus();";

			$onload.="$('.submitBtn').prop('disabled', false);
					$('input[placeholder]').placeholder();";

			$jsfunctions.="
			function testEmail(){
				if(isValidEmailAddress($('#hpSingleInput').val())){
					return true;
				}else{
					alert('Invalid email!');
					return false;
				}
			}";
			$onload.="
			$('#homeRegister').submit(function(){
				return testEmail();
			});
			";
			$_SESSION["mx_signup"]=1;
			/*
			?>
			<form id="homeRegister" class="clearfix" method="get" action="<?php echo WEB_URL ;?>q.php">
				<input type="text" name="q" id="hpSingleInput" maxlength="100" style="height: 40px !important;width: 100%;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;" placeholder="search topics, news, and experiences" value="" />
				<div class="clearfix">
					<span id="homeDocRegLink"><a href="<?php echo WEB_URL; ?>pro_register.php" style="font-weight:normal">Doctors register here</a></span>
					<input type="submit" id="proFormBtn" disabled class="btn submitBtn btn-red" value="Search" />
				</div>
			</form>
			<?php
			$onload.="$('#hpSingleInput').focus();";

			$onload.="$('.submitBtn').prop('disabled', false);
					$('input[placeholder]').placeholder();";
			
			$onload.="$('#homeRegister').submit(function(){
				if($('#hpSingleInput').val().length<3){
				 alert('You can only search words with 3 characters or more!');
				 return false;
				}
			});";
			*/
			?>
			<h3>HealthKeep is totally anonymous.  Your privacy is safe with us.</h3>
			<div id="homeScroll">
				Scroll down for more<br />
				<img src="<?php echo WEB_URL ;?>inc/img/v2/base/scroll_arrow.png" alt="scroll down arrow" />
			</div>
			<?php
			$needScrollTo=1;
			$onload.="
			$('#homeScroll').click(function(){
				$.scrollTo($('#homeContent'),800);
			});
			";
			
			?>
		</div>
	</hgroup>
	<hgroup id="homeContent">
	<div id="homeWorksHolder" class="iWrap">
		<h4>How it works</h4>
		<img src="<?php echo WEB_URL; ?>inc/img/v2/base/left_arrow.png" id="homeWorks_left" />
		<div id="homeWorks_1" class="homeWorks clearfix">
			<div>
				<h6>1. Share a health experience.</h6>
				<p>Share a brief health experience that you or a loved one is going through, or went through in the past. It can be about a symptom, diagnosis, medication or ask a question.</p>
			</div>
			<img src="<?php echo WEB_URL; ?>inc/img/v2/base/home_1.png" />	
		</div>
		<div id="homeWorks_2" class="homeWorks clearfix">
			<div>
				<h6>2. Connect with people who have a similar health experience.</h6>
				<p>We will determine what your experience is about and will share it with others who have similar experiences so they can help and give you feedback.</p>
			</div>
			<img src="<?php echo WEB_URL; ?>inc/img/v2/base/home_2.png" />	
		</div>
		<div id="homeWorks_3" class="homeWorks clearfix">
			<div>
				<h6>3. Get personalized experiences and health news in a custom feed.</h6>
				<p>You specify what you want to read about.<br />
				You choose the medication, symptoms, conditions, procedures and doctors you want to ‘follow’ and we will filter the information for you.</p>
			</div>
			<img src="<?php echo WEB_URL; ?>inc/img/v2/base/home_3.png" />	
		</div>
		<div id="homeWorks_4" class="homeWorks clearfix">
			<div>
				<h6>4. The more you share, the more you learn.</h6>
				<p>The more you open yourself up to the community, the more it will help you and educate you. Share your experiences with no restrictions.
<br /><br />
HealthKeep is absolutely anonymous.</p>
			</div>
			<img src="<?php echo WEB_URL; ?>inc/img/v2/base/home_4.png" />	
		</div>
		<img src="<?php echo WEB_URL; ?>inc/img/v2/base/right_arrow.png" id="homeWorks_right" />
		<?php
		$jsfunctions.="
		var works_active=1;
		function homeWorksArrows(){
			if($(window).width()<1000){
				$('.homeWorks').hide();
				$('#homeWorks_1').show();
				
			}
		}
		";
		$onload.="
		homeWorksArrows();
		$(window).resize(function() {
			homeWorksArrows();
  		});
  		$('#homeWorks_right').click(function(){
  			$('#homeWorks_'+works_active).hide();
  			if(works_active>=4){
  				works_active=1;
  			}else{
  				works_active++;
  			}
  			$('#homeWorks_'+works_active).show();
  		});
  		$('#homeWorks_left').click(function(){
  			$('#homeWorks_'+works_active).hide();
  			if(works_active<=1){
  				works_active=4;
  			}else{
  				works_active--;
  			}
  			$('#homeWorks_'+works_active).show();
  		});
		";
		?>
	</div>
	</hgroup>
	<hr class="onlyMobile" />
	<hgroup id="homeContentVideo" class="clearfix">
		<div id="homeContentVideoHolder" class="iWrap onlyDesktop">
		<div id="homeContentVideoText">
			<h6><a href="http://mashable.com/2013/03/26/healthkeep/" target="_blank">Mashable</a></h6>
			<p>
				"HealthKeep helps users understand their own health by connecting them to relevant information based on their provided history and similar users."
			</p>
			<h6><a href="http://edition.cnn.com/2013/09/17/health/gallery/personal-healthcare-apps/index.html?hpt=he_c1" target="_blank">CNN</a></h6>
			<p>"Top 10 health app"</p>
			<h6><a href="http://www.forbes.com/sites/johnnosta/2013/06/20/what-used-to-be-rules-for-patients-is-now-patients-rule/" target="_blank">Forbes</a></h6>
			<p>
				"HealthKeep’s goal is to help people better understand and become more engaged in their health through social connections with others like them. It humanizes the cold impersonal complex health information found via search engines and traditional online health sites."
			</p>
		</div>
		<iframe src="https://player.vimeo.com/video/62584284?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
		
	</hgroup>
	<hgroup id="homeContentSignUp" class="onlyMobile">
		<div id="homeContentSignUpHolder" class="iWrap">
			<span id="homeContentSignUpMiddle" class="btn btn-red">Sign Up</span>
		</div>
	</hgroup>
	<?php
	$onload.="
	$('#homeContentSignUpMiddle').click(function(){
		$.scrollTo($('#homeCTA'),800);
	});
	";
	?>
	<hgroup id="homeContentExperiences">
		<div id="homeContentExperiencesHolder" class="iWrap clearfix">
			<h5>Health Experiences</h5>
			<img src="<?php echo WEB_URL; ?>inc/img/v2/base/left_arrow.png" id="homeExp_left" />
			<img src="<?php echo WEB_URL; ?>inc/img/v2/base/left_arrow_big.png" id="homeExp_left_big" />
			<div id="homeExp_block_1" class="homeExp_block clearfix">
				<div id="homeExp_1" class="homeExp clearfix homeExpLeft">
					<img src="<?php echo WEB_URL; ?>inc/img/avatar/man7.jpg" alt="jbrook" />
					<div>
						<h6><a href="https://www.healthkeep.com/jbrook">jbrook</a></h6>
						<p>A few months ago my wife woke up and noticed blood in her urine. We became very afraid especially after looking in up on Google and reading about bladder cancer.  She went to her general doctor who sent her to a urologist. She had an ultrasound, a CAT scan and a cystoscopy.  Finally they found nothing wrong and the blood went away.</p>
					</div>
				</div>
				<div id="homeExp_2" class="homeExp clearfix homeExpRight">
					<img src="<?php echo WEB_URL; ?>inc/img/avatar/woman5.jpg" alt="maryp" />
					<div>
						<h6><a href="https://www.healthkeep.com/maryp">maryp</a></h6>
						<p>I have been having headaches for a long time and I never mentioned it to a doctor. Finally after I got health insurance i went to the doctor. She ordered an MRI of my brain and said everything was ok.  Then she told me I had migraine headache and gave me a medicine called Imitrex. It works great for me.
	</p>
					</div>
				</div>
			</div>
			<div id="homeExp_block_2" class="homeExp_block clearfix">
				<div id="homeExp_3" class="homeExp clearfix homeExpLeft">
					<img src="<?php echo WEB_URL; ?>inc/img/avatar/woman3.jpg" alt="theone" />
					<div>
						<h6><a href="https://www.healthkeep.com/theone">theone</a></h6>
						<p>My doctor said I have high cholesterol. My total cholesterol is 250 and my LDL is 185.  So he said I need to take a medicine called Lipitor.  I am afraid of side effects.  I have read that some people get problems with their muscles and get pain from it.</p>
					</div>
				</div>
				<div id="homeExp_4" class="homeExp clearfix homeExpRight">
					<img src="<?php echo WEB_URL; ?>inc/img/avatar/man1.jpg" alt="davidsancious" />
					<div>
						<h6><a href="https://www.healthkeep.com/davidsancious">davidsancious</a></h6>
						<p>My asthmatic symptoms have been relevant to my life and health since I was a child. The good thing is that the symptoms have improved after I reached adulthood, most likely due to certain lifestyle changes such as having a better diet and living space. My worst asthma was during my days of living in a small and unhealthy apartment.</p>
					</div>
				</div>
			</div>
			<img src="<?php echo WEB_URL; ?>inc/img/v2/base/right_arrow.png" id="homeExp_right" />
			<img src="<?php echo WEB_URL; ?>inc/img/v2/base/right_arrow_big.png" id="homeExp_right_big" />
		</div>
		<?php
		$jsfunctions.="
		var expt_active=1;
		function homeExpArrows(){
			if($(window).width()<1000){
				$('.homeExp').hide();
				$('#homeExp_1').show();
			}else{
				$('.homeExp_block').hide();
				$('#homeExp_block_1').show();
				
			}
		}
		";
		$onload.="
		homeExpArrows();
		$(window).resize(function() {
			homeExpArrows();
  		});
  		$('#homeExp_right').click(function(){
  			$('.homeExp_block').show();
  			$('.homeExp').hide();
  			$('#homeExp_'+expt_active).hide();
  			if(expt_active>=4){
  				expt_active=1;
  			}else{
  				expt_active++;
  			}
  			$('#homeExp_'+expt_active).show();
  		});
  		$('#homeExp_left').click(function(){
	  		$('.homeExp_block').show();
	  		$('.homeExp').hide();
  			$('#homeExp_'+expt_active).hide();
  			if(expt_active<=1){
  				expt_active=4;
  			}else{
  				expt_active--;
  			}
  			$('#homeExp_'+expt_active).show();
  		});
  		$('#homeExp_right_big').click(function(){
  			$('.homeExp').show();
  			$('.homeExp_block').hide();
  			$('#homeExp_block_'+expt_active).hide();
  			if(expt_active>=2){
  				expt_active=1;
  			}else{
  				expt_active++;
  			}
  			$('#homeExp_block_'+expt_active).show();
  		});
  		$('#homeExp_left_big').click(function(){
  			$('.homeExp').show();
  			$('.homeExp_block').hide();
  			if(expt_active<=1){
  				expt_active=2;
  			}else{
  				expt_active--;
  			}
  			$('#homeExp_block_'+expt_active).show();
  		});
		";
		?>
	</hgroup>
	<hgroup id="homeAsSeen">
		<div id="homeAsSeenContent" class="iWrap">
			<h5>As seen in:</h5>
			<div id="homeAsSeenContentHolder">
				<a href="http://edition.cnn.com/2013/09/17/health/gallery/personal-healthcare-apps/index.html?hpt=he_c1" target="_blank"><img src="<?php echo WEB_URL; ?>inc/img/v1/hp/extLogos/cnn-logo.png" id="cnnLogo" alt="CNN Logo" /></a>
				<a href="http://mashable.com/2013/03/26/healthkeep/" target="_blank"><img src="<?php echo WEB_URL; ?>inc/img/v1/hp/extLogos/mashable-logo1.png" id="mashLogo" alt="Mashable Logo" /></a>
				<a href="http://techcrunch.com/2013/04/16/healthkeep-launches-an-anonymous-social-network-to-let-you-share-and-track-health-information/" target="_blank"><img src="<?php echo WEB_URL; ?>inc/img/v1/hp/extLogos/techcrunch-logo1.png"  id="tcLogo" alt="TechCrunch Logo" /></a>
				<a href="http://www.forbes.com/sites/johnnosta/2013/06/20/what-used-to-be-rules-for-patients-is-now-patients-rule/" target="_blank"><img src="<?php echo WEB_URL; ?>inc/img/v1/hp/extLogos/forbes-logo1.png" id="forbesLogo" alt="Forbes Logo" /></a>
				<a href="http://venturebeat.com/2013/04/12/want-to-share-your-aches-and-pains-try-healthkeep-an-anonymous-social-network-exclusive/" target="_blank"><img src="<?php echo WEB_URL; ?>inc/img/v1/hp/extLogos/venture-beat-logo1.png" id="vbLogo" alt="Venture Beat Logo" /></a>
			</div>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');