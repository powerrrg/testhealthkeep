<?php
foreach($xml->channel->item as $item){
	$title=$item->title;
	$link=$item->guid;
	$description=$item->description;
	$pubDate=date("Y-m-d H:i:s",strtotime($item->pubDate));
	
	$externalClass->processNewsPost($title,$link,$description,$pubDate,$id_profile);

}