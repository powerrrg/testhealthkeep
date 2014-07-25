<?php
foreach($xml->channel->item as $item){
	$title=$item->title;
	$link=$item->link;
	$description=trim(strip_tags($item->description));
	$pubDate=date("Y-m-d H:i:s",strtotime($item->pubDate));
	
	if($title!="Advertisement:"){
		$externalClass->processNewsPost($title,$link,$description,$pubDate,$id_profile);
	}

}