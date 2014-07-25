<?php
require_once(ENGINE_PATH.'class/doctor.class.php');
$doctorClass=new Doctor();

if(isset($_GET["l3"])){
	
	if($_GET["l2"]=="group"){
		$res=$doctorClass->getTaxonomyGroupByCode($_GET["l3"]);
		
		if(!$res["result"]){
			go404();
		}
	
		$pageTitle=$res[0]["group_taxonomy"]." Doctors - HealthKeep";
		$pageDescr=$res[0]["group_taxonomy"]." - Full list of doctors.";
	}else if($_GET["l2"]=="taxonomy"){
	
		$res=$doctorClass->getTaxonomyByCode($_GET["l3"]);
		
		if(!$res["result"]){
			go404();
		}
	
		$pageTitle=$res[0]["name_taxonomy"]." Doctors - HealthKeep";
		$pageDescr=$res[0]["name_taxonomy"]." - Full list of doctors.";
		
	}else{
		go404();
	}

}else{
	$pageTitle="Doctors - HealthKeep";
	$pageDescr="Full list of doctors.";
}

require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/bar.php');

?>
<div id="main" class="iHold clearfix">
	<div class="iRounded iBoard clearfix">
		<?php
		if(isset($_GET["l3"])){
			if($_GET["l2"]=="group"){
			?>
				<h3 class="marginbottom30"><?php echo $res[0]["group_taxonomy"]; ?></h3>
				<ul class="marginbottom30 fullList">
				<?php	
				$res=$doctorClass->getAllFromTaxonomyGroup($_GET["l3"]);
				
				foreach($res as $key=>$value){
					if(is_int($key)){
						echo "<li><a href=\"".WEB_URL."doctors/taxonomy/".$value["code_taxonomy"]."\">".$value["name_taxonomy"]."</a></li>";
					}
				}
				
				?>
				</ul>
			<?php
			}else if($_GET["l2"]=="taxonomy"){
			?>
				<h3 class="marginbottom30"><?php echo $res[0]["name_taxonomy"]; ?></h3>
				<ul class="marginbottom30">
				<?php	
				$res=$doctorClass->getAllFromTaxonomy($_GET["l3"]);
				
				foreach($res as $key=>$value){
					if(is_int($key)){
						$name="";
						if($value["name_prefix_doctor"]!=""){
							$name.=$value["name_prefix_doctor"]." ";
						}
						$name.=ucfirst(strtolower($value["first_name_doctor"]));
						if($value["middle_name_doctor"]!=""){
							$name.=" ".ucfirst(strtolower($value["middle_name_doctor"]));
						}
						$name.=" ".ucfirst(strtolower($value["last_name_doctor"]));
						if($value["name_suffix_doctor"]!=""){
							$name.=" ".$value["name_suffix_doctor"];
						}
						$name.=", ".$value["credential_doctor"];
						echo "<li><a href=\"".WEB_URL.$value["url_doctor"]."\">".$name."</a> - ".$value["state_doctor"]." - ".ucfirst(strtolower($value["city_doctor"]))."</li>";
					}
				}
				
				?>
				</ul>
			<?php
				
			}
			
		}else{
		?>
		<h3 class="marginbottom30">Doctors</h3>
		<ul class="marginbottom30 fullList">
		<?php	
		$res=$doctorClass->getAllTaxonomyGroups();
		
		foreach($res as $key=>$value){
			if(is_int($key)){
				echo "<li><a href=\"".WEB_URL."doctors/group/".$value["group_code_taxonomy"]."\">".$value["group_taxonomy"]."</a></li>";
			}
		}
		
		?>
		</ul>
		<?php
		}
		?>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');