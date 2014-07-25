<?php
require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

require_once(ENGINE_PATH.'class/doctor.class.php');
$doctorClass=new Doctor();

require_once(ENGINE_PATH."html/inc/common/usStates.php");

if(isset($_GET["l3"])){

	if($_GET["l2"]=="taxonomy"){
		$res=$doctorClass->getTaxonomyByCode($_GET["l3"]);
		
		if(!$res["result"]){
			go404();
		}
		
		$headingTitle="<span class=\"colorLighterBlue\">".$res[0]["name_taxonomy"]."</span> <span class=\"colorGray\">Doctors</span>";
		
		$pageTitle="Find a Doctor ".$res[0]["name_taxonomy"]." Doctors - HealthKeep";
		$pageDescr="Full list of medical doctors specialized in ".$res[0]["name_taxonomy"].". HealthKeep has simple way for you to find a doctor.";
		
		$resDoc=$profileClass->getAllDoctorsWithTaxonomy($_GET["l3"]);
		$onlyTaxo=$res[0]["name_taxonomy"];
		$ajaxUrl=WEB_URL."act/ajax/doctors/taxonomy.php";
		$onload.="endlessScroll('$ajaxUrl',$('#postHolder'),'".$_GET["l3"]."');";
	}else{
		go404();
	}
	
}else if(isset($_GET["l2"])){
	
	$pieces=explode('_',$_GET["l2"]);
	if(isset($pieces[1])){
		$cityState=strtoupper($pieces[0]);
		if(!isset($usStates[$cityState])){
			go404();
		}
		$onlyCity=str_replace("-", " ", strtolower($pieces[1]));
		$resDoc=$profileClass->getAllDoctorsFromCity($cityState,$onlyCity);
		
		$headingTitle="<span class=\"colorLighterBlue\">".$usStates[$cityState]."</span>, ".ucwords($onlyCity)." <span class=\"colorGray\">Doctors</span>";
		
		$pageTitle="Find a Doctor from ".$usStates[$cityState].", ".ucwords($onlyCity)." - HealthKeep";
		$pageDescr="Full list of medical doctors from ".$usStates[$cityState].", ".ucwords($onlyCity).". HealthKeep has simple way for you to find a doctor.";
	}else{
		$onlyState=strtoupper($_GET["l2"]);
		if(!isset($usStates[$onlyState])){
			go404();
		}
		$headingTitle="<span class=\"colorLighterBlue\">".$usStates[$onlyState]."</span> <span class=\"colorGray\">Doctors</span>";
		$pageTitle="Find a Doctor from ".$usStates[$onlyState]." - HealthKeep";
		$pageDescr="Full list of medical doctors from ".$usStates[$onlyState].". HealthKeep has simple way for you to find a doctor.";
		$resDoc=$profileClass->getAllDoctorsFromState($onlyState);
	}
	$ajaxUrl=WEB_URL."act/ajax/doctors/location.php";
	$onload.="endlessScroll('$ajaxUrl',$('#postHolder'),'".$_GET["l2"]."');";
}else{
	$headingTitle="Doctors";
	$pageTitle="Find a Doctor - HealthKeep";
	$pageDescr="A simple way to search and find a medical doctor. HealthKeep has a full list of doctors in the United States.";
	$resDoc=$profileClass->getAllDoctors();
	$ajaxUrl=WEB_URL."act/ajax/doctors/all.php";
	$onload.="endlessScroll('$ajaxUrl',$('#postHolder'));";
}
	
$designV1=1;
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
		<div id="iFeed" class="iBoard  clearfix">
			<div id="iFeedContent">
				<div id="postDetail">
					<div class="iHeading clearfix marginBottom20">
						<h3 class="feedHeading"><?php echo $headingTitle; ?></h3>
					</div>
					<?php
					if(!isset($_GET["l2"])){
					?>
					<div id="mapHolder" style="width:100%;margin:0 auto;">
					<div id="map" style="width: 100%;min-height:200px;"></div>
					</div>
					<?php
					$needInteractiveMap=1;
					$jsfunctions.="
					var mapW=0;
					function resizeMap(){
						mapW=$('#postHolder').innerWidth();
						if(mapW<400){
							mapW=mapW-20;
						}else{
							mapW=mapW-60;
						}
						$('#map').css('width',mapW);
						$('#mapHolder').css('width',mapW);
						mapW=(mapW/10)*7;
						$('#map').css('height',mapW);
						
					}
					";
					$onload.="resizeMap();
					$(window).resize(function() {
						if($('#postHolder').innerWidth()<$('#map').innerWidth()){
							$('#map').slideUp();
						}
					});
					";
					$onload.="$('#map').usmap({
					    'stateStyles': {
					      fill: '#025', 
					      'stroke-width': 1,
					      'stroke' : '#fff',
					      'fill' : '#1A3967'
					    },
					    'stateHoverStyles': {
					      fill: '#2c5fb2'
					    },
					    
					    'click' : function(event, data) {
					    	location.href='".WEB_URL."doctors/'+data.name;
					    }
					  });";
					
					}
					?>
					<div class="searchResult clearfix" id="docSearch" style="position:relative;">
						<input type="text" id="sname" class="inputSdoc" placeholder="Name" />
						<input type="text" id="sstate" class="inputSdoc" value="<?php if(isset($onlyState)){ echo $usStates[$onlyState] ; }else if(isset($cityState)){ echo $usStates[$cityState]; } ?>" placeholder="State" />
						<input type="text" id="scity" class="inputSdoc" value="<?php if(isset($onlyCity)){ echo ucwords($onlyCity) ; }?>" placeholder="City" />
						<input type="text" id="staxo" class="inputSdoc" value="<?php if(isset($onlyTaxo)){ echo $onlyTaxo ; }?>" placeholder="Speciality" />
					</div>
					<div id="iSLoader" class="center margin10" style="display:none;">
						<img src="<?php echo WEB_URL; ?>inc/img/v1/inc/ajax-loader-bar.gif" alt="ajax loader" />
					</div>
					<?php
						//endlessScroll(,$('#postHolder'),'');
						$jsfunctions.="
						function doSearch(){
							ENDurl='".WEB_URL."act/ajax/doctors/search.php';
							ENDele=$('#postHolder');
							ENDtval=$('#sname').val()+'*_*'+$('#sstate').val()+'*_*'+$('#scity').val()+'*_*'+$('#staxo').val();
							
							$('#iSLoader').show();
							$.ajax({
							type: 'POST',
							url: '".WEB_URL."act/ajax/doctors/search.php',
							data: { name: $('#sname').val(),state: $('#sstate').val(),city: $('#scity').val(),taxo: $('#staxo').val(),p:'1' },
							success: function(data) {
								$('#iSLoader').hide();
								$('#postHolder').html(data);
							}
						});
						}
						";
						$jsfunctions.="
						var searchString='';
						var delay = (function(){
						  var timer = 0;
						  return function(callback, ms){
						    clearTimeout (timer);
						    timer = setTimeout(callback, ms);
						  };
						})();";
						$onload.="
						$('.inputSdoc').keyup(function() {
						    delay(function(){
						     	doSearch();
						    }, 1000 );
						});
						";
						
						?>
					<div id="postHolder">
						<?php
							if($resDoc["result"]){
								require_once(ENGINE_PATH."html/doctors/list.php");
							}
						?>
					</div>
					<?php
					require_once(ENGINE_PATH."html/inc/endless.php");
					?>
				</div>
			</div>
			<?php
			$needPanelMenu=1;
			$onload.="var jPM = $.jPanelMenu({
			menu: '#jPMHolder',
			trigger: '#goPanelMenu'
			});
			jPM.on();
			";
			//ATT:I changed the file jquery.jpanelmenu.min.js added $('#goPanelMenu').hide(); and $('#goPanelMenu').show(); to beforeOpen and beforeClose
			?>
			<div id="goPanelMenu">menu</div>
			<div id="iFeedSidebar">
			<div id="jPMHolder">
				<div class="iHeading clearfix marginBottom20">
					<h3 class="feedHeading" style="font-size:14px"><span class="colorLighterBlue">Doctors</span> by <span class="colorGray">State</span></h3>
				</div>
				<div class="iBoard3">
					<div class="iDashboardHolder clearfix iDashboardLinks">
						<?php
						
						foreach($usStates as $key=>$value){
							echo '<a href="'.WEB_URL.'doctors/'.strtolower($key).'">'.$value.'</a>';
						}
						?>
					</div>
				</div>
			</div>
			</div>	
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');