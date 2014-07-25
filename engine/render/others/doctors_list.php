<?php


	$headingTitle="Find a Doctor";
	$pageTitle="Find a Doctor - HealthKeep";
	$pageDescr="A simple way to search and find a medical doctor. HealthKeep has a full list of doctors in the United States.";
	//$ajaxUrl=WEB_URL."act/ajax/doctors/all.php";
	//$onload.="endlessScroll('$ajaxUrl',$('#postHolder'));";


$active="homepage";
require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup class="iWrap clearfix" style="padding:20px;">
		
		<h2 class="" style="padding:30px;color:#678DCC;font-size:30px;"><?php echo $headingTitle; ?></h2>
		<div class="iDoctorSearch clearfix" style="background:#DFE9F6;padding:15px; margin:30px 0 100px 0;">
			<form method="get" id="docForm" action="<?php echo WEB_URL; ?>searchd.php">
				<p style="margin-bottom:10px;">Search for a doctor by name:</p>
				<p><input type="text" name="lastname" id="lastname" style="width:150px;" placeholder="Last Name" />
				<input type="text" name="firstname" id="firstname" style="width:150px;" placeholder="First Name" />
				</p>
				<p style="margin:20px 0 10px 0;">Search for a doctor by speciality:</p>
				<p style="margin:10px 0 10px 0;"><select name="spec" id="spec" style="width:330px;">
					<option value="">Choose a speciality</option>
					<?php
					require_once(ENGINE_PATH."class/doctor.class.php");
					$doctorClass=new Doctor();
					$resTax=$doctorClass->getAllNewTaxonomy();
					foreach($resTax as $key=>$value){
						if(is_int($key)){
						?>
						<option value="<?php echo urlencode($value["name_taxo"]); ?>"><?php echo $value["name_taxo"]; ?></option>
						<?php
						}
					}
					?>
				</select></p>
				<p>Within: <select id="miles" name="miles" style="width:70px;">
					<option value="10">10</option>
					<option value="25">25</option>
					<option value="50">50</option>
					<option value="75">75</option>
					<option value="100">100</option>
				</select>
				miles of 
					<input type="text" name="ccode" id="ccode" class="typeahead" style="width:130px;" autocomplete="off" placeholder="Zip Code" /></p>
				<p style="margin-top:20px;"><input type="submit" id="doctorBtn" class="btn btn-red" value="search" /></p>
				<div id="spin" style="text-align:center;display:none;"><img src="<?php echo WEB_URL; ?>inc/img/hook-spinner.gif" /></div>

				<?php
				$needGudbergur=1;
			/*	$jsfunctions.="$('#ccode').typeahead({
					    source: function (query, process) {
					        return $.get('".WEB_URL."act/ajax/autoCompleteCcode.php?q='+$('#ccode').val()+'&miles='+$('#miles').val(), function (data) {
					            return process(data.options);
					        });
					    }
					});";*/
					
					
				$jsfunctions.="
				$('#ccode').typeahead({
				source: function(typeahead, query){ 
				$.ajax({
				url: '".WEB_URL."act/ajax/autoCompleteCcode.php?miles='+$('#miles').val(),
				type: 'POST',
				data: 'q='+query ,
				dataType: 'JSON',
				async: false,
				success: function(data){
				typeahead.process(data);
				}
				})
				},
				property: 'name',
				items:11
				
				});
				";
				$onload.="
					
					$('#docForm').submit(function(){
						$('#doctorBtn').hide();
						$('#spin').show();
					});
				";
				?>
			</form>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');