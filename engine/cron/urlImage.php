<?php
$iamcron=1;
require_once('../starter/config.php');

require_once(ENGINE_PATH.'cron/functions.php');

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();
$resPost = $postClass->getPostWithLinkNotProcessed();

if($resPost["result"]){
	
	$postClass->markPostWithLinkProcessed($resPost[0]["id_post"]);
	$link=$resPost[0]["link_post"];
	
	$res=get_web_page($link);
	if($res["http_code"]=="200"){
		$doc = new DOMDocument();
		@$doc->loadHTML($res["content"]);
		$xpath = new DOMXPath($doc);
		$query = '//*/meta[starts-with(@property, \'og:\')]';
		$metas = $xpath->query($query);
		foreach ($metas as $meta) {
		    $property = $meta->getAttribute('property');
		    $content = $meta->getAttribute('content');
		    $rmetas[$property] = $content;
		}
		
		if(isset($rmetas["og:image"])){
			$imgPath=PUBLIC_HTML_PATH."img/post/";
		    $image=$configClass->uploadImageURL($rmetas["og:image"], $imgPath);
		    if($image["image"]!=""){
				$postClass->saveImage($image["image"],$resPost[0]["id_post"]);
		    }
		}


	}
	
	
}