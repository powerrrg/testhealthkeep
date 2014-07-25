<?php
require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

$resProfile=$profileClass->getByUsername(urlencode($_GET["l1"]));

if(!$resProfile["result"]){
	go404();
}


if(USER_ID==$resProfile[0]["id_profile"]){
	//top bar active
	$active="myProfile";
	$resUser=$userClass->getById($resProfile[0]["id_profile"]);
	
	if(!$resUser["result"]){
		go404();
	}
}

if($resProfile[0]["image_profile"]!=""){
	$ogImage=WEB_URL."img/profile/med/".$resProfile[0]["image_profile"];
}

if(!isset($_GET["l2"])){
	go404();
}else if($_GET["l2"]=="followers"){
	$weare="followers";
	$list=$profileClass->listFollowers($resProfile[0]["id_profile"]);
}else if($_GET["l2"]=="following"){
	$weare="following";
	$list=$profileClass->listFollowing($resProfile[0]["id_profile"]);
}else{
	go404();
}

if(!$list["result"]){
	go404();
}

$pageTitle=$configClass->name($resProfile)." $weare - HealthKeep";
$pageDescr="List of the users $weare ".$configClass->name($resProfile);
require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div class="iFull">
			<h2 class="iFullHeading"><?php 
				echo "<a href=\"".WEB_URL.$resProfile[0]["username_profile"]."\">".$configClass->name($resProfile)."</a>";
				echo " <span  style=\"color:#999;\">".$weare."</span>"; 
			?></h2>
		
			<div id="holdMeet" class="clearfix">
			<?php
			require_once(ENGINE_PATH."html/inc/common/usStates.php");
			$res=$list;
			require_once(ENGINE_PATH.'class/location.class.php');
			$locationClass=new Location();
			/*foreach($list as $key=>$value){
				if(is_int($key)){
				if($value["image_profile"]==""){
					$imagePath=WEB_URL."inc/img/empty-avatar.png";
					$imageAlt="No Image Avatar";
				}else{
					$imagePath=WEB_URL."img/profile/tb/".$value["image_profile"];
					$imageAlt=$configClass->name($value, false);
				}
				?>
					<a href="<?php echo WEB_URL.$value["username_profile"];?>"  class="clearfix">
						<img src="<?php echo $imagePath; ?>" alt="<?php echo $imageAlt; ?>" />
						<div>
							<h4><?php echo $configClass->name($value, false); ?></h4>
							<p><?php 
							if($value["country_profile"]!='US' && $value["country_profile"]!=''){
								echo $value["short_name"];
							}else if($value["state"]!='' && isset($usStates[$value["state"]])){
								echo $usStates[$value["state"]];
							}else if($value["type_profile"]==2){
								if(isset($usStates[$value["state_doctor"]])){
									echo $usStates[$value["state_doctor"]];
								}
							}
							echo "<br />";
							if($value["job_profile"]!=''){
								echo $value["job_profile"];
							}else if($value["type_profile"]==2 && $value["name_taxonomy"]!=''){
								echo $value["name_taxonomy"];
							}
							?></p>
						</div>
					</a>
				<?php
				}
			}*/
			require_once(ENGINE_PATH."render/others/meet_list.php");
			?>
			</div>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');