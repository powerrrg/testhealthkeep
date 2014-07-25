<h1 id="hpHeading" class="center"><?php echo $testHeading; ?></h1>
	<div class="clearfix">
		<?php require_once(ENGINE_PATH."html/hp/descrText.php"); ?>
		<div class="iBox" style="margin-bottom:85px;">
			<div class="iBoxHolder hpMainBox" style="position:relative;">
			<h3 class="center" style="margin-bottom:-10px;">What about your health do you want to explore?</h3>
			<a href="<?php echo WEB_URL; ?>pro_register.php" style="position:absolute;bottom:10px;right:10px;font-size:14px;text-decoration:underline" class="colorBlue bold">Doctors register here</a>
				<div id="hpSingleInputHolder">
					<form method="get" action="<?php echo WEB_URL ;?>q.php" id="hpSearch">
					<input type="text" name="q"  id="hpSingleInput" style="width:450px;" maxlength="100" placeholder="Enter a symptom, condition, medication, procedure or doctor" autocomplete="off" />
					<input type="submit" id="proFormBtn" disabled class="btn submitBtn btn-large btn-success" value="Go to the Feed" />
					</form>
				</div>
			</div>
		</div>
		<?php
		$onload.="$('#hpSearch').focus();";
		$onload.="$('#topSearch').submit(function(){
			if($('#hpSingleInput').val().length<3){
			 alert('You can only search words with 3 characters or more!');
			 return false;
			}
		});";
		$jsfunctions.="
		$(function () {
			$('#hpSingleInput').typeahead({
		        ajax: { url: '".WEB_URL."act/ajax/search/typeahead.php', triggerLength: 1 }, 
		        itemSelected: displayResult
		    });

		});
		function displayResult(item, val, text) {
			if(val==0){
				location.href='".WEB_URL."q.php?q='+$('#hpSingleInput').val();
			}else{
				location.href='".WEB_URL."q.php?id='+val;
			}
		}
		";
		?>
	</div>
</div>