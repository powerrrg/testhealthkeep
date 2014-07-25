<?php

onlyLogged();

if(USER_TYPE!=9){
	go404();	
}

require_once(ENGINE_PATH.'class/blog.class.php');
$blogClass=new Blog();

if(isset($_POST["title"]) && isset($_POST["title"]) && isset($_POST["texto"])){

$blogClass->addNew();

header("Location:".WEB_URL."ges/blog");
exit;
}

$pageTitle="Back Office";
$pageDescr="Back Office";



$designV1=1;
$active="backoffice";
$needTinyMCE=1;
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
		<div class="iBoard">
			<div class="iHeading iFull margin10auto padding15">
				<h1 class="colorRed margin10 center">Blog</h1>
			</div>
			<div>
				<form action="" method="post" enctype="multipart/form-data" style="width:550px;margin:20px auto;">
					<input type="text" maxlength="250" name="title" id="title" style="width:550px;" placeholder="Title" / ><br /><br />
					<span style="color:#ccc;display:inline;"><?php echo WEB_URL; ?>blog/</span><input name="url" id="url"  style="margin-left:10px;width:420px;display:inline !important;" type="text" maxlength="250" placeholder="URL" /><br /><br />
					<span style="color:#ccc;display:inline;margin-right:20px;">Imagem</span><input type="file" name="imagem" /><br /><br />
					<textarea class="tinymce" name="texto"  style="width:500px;height:300px;" id="texto">
					
					</textarea><br />
					<input type="submit" value="save" class="btn btn-red" />
					
				</form>
				<?php
				$jsfunctions.="
				function convertToSlug(Text)
				{
				    return Text
				        .toLowerCase()
				        .replace(/ /g,'-')
				        .replace(/[^\w-]+/g,'')
				        ;
				}
				";
				$onload.="

				$('#title').keyup(function(){
					ourl=convertToSlug($('#title').val());
					$('#url').val(ourl);
				});
				";
				?>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');