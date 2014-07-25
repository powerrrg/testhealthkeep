<?php

onlyLogged();

if(USER_TYPE!=9){
	go404();	
}

if(!isset($_POST["URL"])){
	
	go404();
}

$url=$_POST["URL"];

require_once(ENGINE_PATH.'cron/functions.php');

require_once(ENGINE_PATH.'class/external.class.php');
$externalClass=new External();

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

require_once(ENGINE_PATH."class/post.class.php");
$postClass=new Post();

function file_get_contents_curl($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

$html = file_get_contents_curl($url);

//parsing begins here:
$doc = new DOMDocument();
@$doc->loadHTML($html);
$nodes = $doc->getElementsByTagName('title');

//get and display what you need:
$title = $nodes->item(0)->nodeValue;

$metas = $doc->getElementsByTagName('meta');

for ($i = 0; $i < $metas->length; $i++)
{
    $meta = $metas->item($i);
    if(strtolower($meta->getAttribute('name')) == 'description')
        $description = $meta->getAttribute('content');

}

$xpath = new DOMXPath($doc);
$query = '//*/meta[starts-with(@property, \'og:\')]';
$metas = $xpath->query($query);
foreach ($metas as $meta) {
    $property = $meta->getAttribute('property');
    $content = $meta->getAttribute('content');
    $rmetas[$property] = $content;
}

if(isset($rmetas["og:image"])){
	
    $image=$rmetas["og:image"];
    
}else{
	$image="";
}

$pageTitle="Back Office";
$pageDescr="Back Office";

$designV1=1;
$active="backoffice";
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>

<div id="main">
	<div class="iHold clearfix">
		<div class="iBoard">
			<div class="iHeading iFull margin10auto padding15">
				<h1 class="colorRed margin10 center">Add Post</h1>
			</div>
			<div class="iFull iBoard2 margin20auto">
				<form method="post" action="<?php echo WEB_URL; ?>ges/addpost/confirm" enctype="multipart/form-data">
					<?php echo $url; ?>
					<input type="hidden" name="url" value="<?php echo $url; ?>" /><br /><br />
					<input type="text" name="title" placeholder="Title" style="width:90%;" value="<?php echo $title; ?>" /><br /><br />
					<textarea name="description" placeholder="Description" style="width:90%;height:200px;"><?php echo $description; ?></textarea><br /><br />
					<?php
					$style="";
					if($image!=""){
						echo '<div id="imageHolder" style="margin-bottom:10px;"><img src="'.$image.'" style="max-width:90%;max-height:200px;" /></div><a href="#" style="color:red;" onclick="$(\'#extImage\').val(\'\');$(\'#imageHolder\').hide();$(this).hide();$(\'#uploadImageHolder\').show();">remove this image</a><input type="hidden" name="extImage" id="extImage" value="'.$image.'" /><br /><br />';
						$style="display:none;";
					}else{
						echo '<input type="hidden" name="extImage" id="extImage" value="" />';
					}
					echo '<div style="margin-bottom:30px;'.$style.'" id="uploadImageHolder"><input type="file" name="uploadimage" /></div>';
					?>
					<input type="submit" />
				</form>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');


