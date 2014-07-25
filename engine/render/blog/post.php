<?php
$pageTitle="Terms of Use - HealthKeep";
$pageDescr="Please read these Terms of Use carefully before using the Site.";

$active="homepage";

require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>

<article id="main">
	<hgroup class="iWrap clearfix">
		<div class="iFull iText">
			<?php
			echo "<h1 style=\"font-size:30px;line-height:40px;margin-bottom:30px;\">".$resPost[0]["title_blog"]."</h1>";
			if($resPost[0]["img_blog"]!=""){
				echo "<img src=\"".WEB_URL."img/blog/org/".$resPost[0]["img_blog"]."\" alt=\"".$resPost[0]["title_blog"]."\" style=\"width:100%;margin-bottom:10px;\" />";
			}
			echo $resPost[0]["text_blog"];
			?>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');