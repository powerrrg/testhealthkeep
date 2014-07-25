<?php
onlyLogged();

require_once(ENGINE_PATH."html/inc/common/typeArray.php");
$meaArray=typeArray('measurement');

if(isset($_GET["l2"])){
	
	if(!isset($meaArray["type"][$_GET["l2"]])){
		go404();	
	}else{
		$types=$_GET["l2"];
	}
}else{
	$types="void";
}

$pageTitle="Create your health graphs - HealthKeep";
$pageDescr="Create your health graphs";

$designV1=1;
require_once(ENGINE_PATH.'html/header.php');
$active="timeline";
require_once(ENGINE_PATH.'html/top.php');

require_once(ENGINE_PATH.'html/inc/common/typeArray.php');
$typeArray=typeArray("measurement");
?>
<div id="main">
	<div class="iHold">
		<div id="iGraphs" class="iBoard clearfix">
			<div class="iHeading marginBottom20">
					<h1 class="iHeadingText center colorGray">Create your health graphs</h1>
			</div>
			<div id="iHoldGraphs">
				<div id="iMgraph" class="clearfix">
					<div id="iMgraphHolder">
						<?php
						require_once(ENGINE_PATH.'class/timeline.class.php');
						$timelineClass=new Timeline();
						$resChartAble=$timelineClass->getMeasurementsChartAble();
						if($resChartAble["result"]){
						?>
						<div id="chart_div_holder">
							<div id="chart_div" style="width: 100%; height: 350px;">
								<img src="<?php echo WEB_URL; ?>inc/img/v1/inc/ajax-loader-bar.gif" alt="Preloader" class="chartPreloader" />
							</div>
	
							<div id="iMgraphDates" class="center">
								<a href="#" onclick="chartTime=1;return updateChart();">Last week</a> | 
								<a href="#" onclick="chartTime=2;return updateChart();">Last Month</a> | 
								<a href="#" onclick="chartTime=3;return updateChart();">Last year</a>
							</div>
						</div>
						<?php
						}
						?>
						<div id="iMgraphDemo" <?php if(!$resChartAble["result"]){echo 'style="display:block"';} ?>>
							<img src="<?php echo WEB_URL; ?>inc/img/v1/inc/demoChart.png" alt="Demo graph" />
						</div>
					</div>
					<div id="iMgraphOptions">
						<?php
						if($resChartAble["result"]){
							echo "<ul>";
							foreach($resChartAble as $key=>$value){
								if(is_int($key)){
									if($types=="void"){
										$types=$value["measurement_tm"];
									}
									?>
									<li class="meaOptionsHolder<?php if($types==$value["measurement_tm"]){echo ' active';} ?>" >
									<input type="checkbox" id="in<?php echo $key; ?>" class="measurmentOpt" value="<?php echo $value["measurement_tm"]; ?>" <?php if($types==$value["measurement_tm"]){echo 'checked';} ?>>
										<?php echo $meaArray["type"][$value["measurement_tm"]]["name"]; ?>
									</li>
									<?php
								}
							}	
							echo "</ul>";
							$onload.="
								$('.meaOptionsHolder').click(function(){
									var inChild=$(this).find('input');
									if(inChild.prop('checked')){
										inChild.prop('checked', false);
									}else{
										inChild.prop('checked', true);
									}
									updateSidebar();
								});
								updateSidebar();
							";
							$jsfunctions.="
								function updateSidebar(){
									var str='';
									$('.meaOptionsHolder').removeClass('active');
									$(':checkbox:checked').each(function() {
										if(str!=''){
										str +='_';
										}
									    str += $(this).val();
									    $(this).parent().addClass('active');
									});
									charVal=str;
									updateChart();
								}
							";
							
							$needGoogleCharts=1;
							$googleChartsOptions="'legend':'top'";
							$googleChartParams="id:'".USER_ID."',o1:'weight',time:'2'";
							$jsfunctions.="var chartTime=2;";
							if($types!="void"){
								$jsfunctions.="var charVal='".$types."';";
							}else{
								$jsfunctions.="var charVal='';";
							}
							$jsfunctions.="var chartUrl=chartUrl='".WEB_URL."act/ajax/graphs/get.php?id=".USER_ID."&o1='+charVal+'&time='+chartTime;";
							$jsfunctions.="function updateChart(){
								$('#chart_div').html('<img src=\"".WEB_URL."inc/img/v1/inc/ajax-loader-bar.gif\" alt=\"Preloader\" class=\"chartPreloader\" />');
								if(charVal==''){
									$('#iMgraphDemo').show();
									$('#chart_div_holder').hide();
								}else{
									$('#chart_div_holder').show();
									$('#iMgraphDemo').hide();
									chartUrl='".WEB_URL."act/ajax/graphs/get.php?id=".USER_ID."&o1='+charVal+'&time='+chartTime;
									drawChart();
								}
								return false;
							}";
							
						}else{
							?>
							<div class="alert alert-error center">
							You still did not add measurements.<br /><br />
							We can only create graphs after you have multiple values off a given measurement.
						</div>
							<?php
						}
						
						
						?>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');