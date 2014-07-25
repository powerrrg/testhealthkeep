<?php

onlyLogged();

if(USER_TYPE!=9){
	go404();	
}

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();



$pageTitle="Back Office";
$pageDescr="Back Office";



$designV1=1;
$active="backoffice";
$needTinyMCE=1;
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');

if(isset($_POST["title"]) && strlen($_POST["title"])>3){
	$description="";
	$title=trim($_POST["title"]);
	if(isset($_POST["description"])){
		$description=$title;
	}
	$resList=$postClass->deletePostsWith($title, $description);
}else if(isset($_POST["sourceNews"])){
	$id=(int)$_POST["sourceNews"];
	$postClass->goBlackList($id);
}

?>
<div id="main">
	<div class="iHold clearfix">
		<div class="iBoard">
			<div class="iHeading iFull margin10auto padding15">
				<h1 class="colorRed margin10 center">Black List</h1>
			</div>
			<div>
				<form action="" method="post" id="blist" style="width:550px;margin:20px auto;" class="clearfix">
					<input type="text" maxlength="250" name="title" id="title" style="width:550px;" placeholder="Delete all posts with the word" / ><br /><br />
					Include the ones that mention the word(s) in the description? <input type="checkbox" name="description" value="true" /><br /><br />
					
					<input type="submit" value="Delete Posts" class="btn btn-red" />
					
				</form>
				<?php
				$onload.="
					$('#blist').submit(function(){
						if($('#title').val().length<4){
							alert('It needs more than 3 characters');
							return false;
						}else{
							return true;
						}				
					});
				";				
				?>
			</div>
			<hr style="margin:50px 0;" />
			<div class="iHeading iFull margin10auto padding15">
				<h1 class="colorRed margin10 center">Delete News Source</h1>
			</div>
			<div style="text-align:center;">
				<form method="post">
					<select name="sourceNews">
						<?php
						$res=$profileClass->getByType('4');
						foreach($res as $key=>$value){
							if(is_int($key)){
							?>
								<option value="<?php echo $value["id_profile"]; ?>"><?php echo $value["name_profile"]; ?></option>
							<?php
							}
						}
						?>
					</select><br />
					<input type="submit" value="Delete Posts From This Source" class="btn btn-red" />
				</form>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');