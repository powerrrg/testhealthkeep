<?php

onlyLogged();

if(USER_TYPE!=9){
	go404();	
}

$pageTitle="Back Office";
$pageDescr="Back Office";

require_once(ENGINE_PATH.'class/blog.class.php');
$blogClass=new Blog();

$designV1=1;
$active="backoffice";
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
				<div style="text-align:center;margin:30px 0;">
				<button onclick="location.href='<?php echo WEB_URL; ?>ges/blog/add'">Add new post</button>
				</div>
				<div>
				<?php

				$res=$blogClass->getAll();
				foreach($res as $key=>$value){
					if(is_int($key)){
					?>
					<div style="margin-bottom:10px;padding:10px;border-bottom:1px solid #ccc;">
					<a href="<?php echo WEB_URL."blog/".$value["url_blog"]; ?>"><?php echo $value["title_blog"]; ?></a> - <a href="#" style="color:red;" onclick="testdel('<?php echo $value["id_blog"]; ?>');">delete</a>
					</div>
					<?php
					}
				}
				$jsfunctions.="
				function testdel(id){
					if(confirm('Are you sure')){
						location.href='".WEB_URL."blog/delete/'+id;
					}
				}
				";
				?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');