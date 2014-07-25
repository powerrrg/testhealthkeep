<?php

onlyLogged();

if(USER_TYPE!=9){
	go404();	
}

$pageTitle="Back Office";
$pageDescr="Back Office";

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

$resTxt=$postClass->getTop5Week();

$designV1=1;
$active="backoffice";
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
		<div class="iBoard">
			<div class="iHeading iFull margin10auto padding15">
				<h1 class="colorRed margin10 center">Top 5 of the Week</h1>
				<textarea style="width:90%;min-height:400px;text-akign:left;"><?php
				$txt="";
				foreach($resTxt as $key=>$rvalue){
					if(is_int($key)){
					
					$descr="";
					if($rvalue["title_post"]!=""){
						$title=$rvalue["title_post"];
						$descr=substr(strip_tags($rvalue["text_post"]), 0,300)."...";
					}else{
						$title=substr(strip_tags($rvalue["text_post"]), 0,150);
						$title = substr($title, 0, strrpos($title, ' '))."...";
						$descr=substr(strip_tags($rvalue["text_post"]), 151,450)."...";
					}
										
					if($rvalue["image_post"]!=""){
						$image=WEB_URL."img/post/tb/".$rvalue["image_post"];
					}else{
						$image="";
					}
					$txt.="<h1 style=\"color:#666;padding-bottom:50px !important;\"><a href=\"".WEB_URL."post/".$rvalue["id_post"]."\" style=\"text-decoration:underline;color:#666;\">$title</a><br /><span style=\"color:blue;\">-</span> <a href=\"".WEB_URL.$rvalue["username_profile"]."\" style=\"color:blue;\">".$rvalue["username_profile"]."</a></h1>";
//					echo "<p>$descr</p>";
					}
				}
				echo $txt;
				?></textarea>
				<br /><br />
				<?php echo $txt; ?>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');