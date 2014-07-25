<?php
if(!isset($usStates)){
	require_once(ENGINE_PATH."html/inc/common/usStates.php");
}
foreach($resDoc as $key=>$value){
	if(is_int($key)){
		?>
		<div class="searchResult clearfix">
			<div class="searchResultImg">
				<?php
				if($value["image_profile"]!=""){
					$image=WEB_URL."img/profile/tb/".$value["image_profile"];
					$alt=$value["name_profile"];
				}else{
					$image=WEB_URL."inc/img/empty-avatar.png";
					$alt="Empty avatar";
				}
				?>
				<a href="<?php echo WEB_URL.$value["username_profile"]; ?>">
				<img src="<?php echo $image; ?>" alt="<?php echo $alt; ?>" />
				</a>
			</div>
			<div class="searchResultContent">
				<?php
				$hString="";
				if($value["state"]!=""){
					$state=$value["state"];
					$city=$value["city"];
				}else{
					$state=$value["state_doctor"];
					$city=$value["city_doctor"];
				}

				if(isset($usStates[$state])){
					$hString.="<a href=\"".WEB_URL."doctors/".strtolower($state)."\" >".$usStates[$state]."</a>";
					if($city!=""){
					$hString.=", <a href=\"".WEB_URL."doctors/".strtolower($state)."_".str_replace(" ", "-", strtolower($city))."\">".ucwords(strtolower($city))."</a>";
					}
				}else if($city!=""){
					$hString.=ucfirst(strtolower($city));
				}
				
				
				?>
				<h3><?php echo $hString; ?></h3>
				<p><a href="<?php echo WEB_URL.$value["username_profile"]; ?>" class="colorRed"><?php echo $value["name_profile"]; ?></a>
				<?php 
				if($value["name_taxonomy"]!=""){
					echo " - <a href=\"".WEB_URL."doctors/taxonomy/".$value["code_taxonomy"]."\" class=\"colorBlue size12\">".$value["name_taxonomy"]."</a>";
				}
				?>
				</p>
			</div>
		</div>
		<?php
	}
}