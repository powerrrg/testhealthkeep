<?php
echo "<pre>";
foreach($xml->channel->item as $item){
	$title=$item->title;
	$link=$item->guid;
	$description=trim(strip_tags($item->description));
	$pubDate=date("Y-m-d H:i:s",strtotime($item->pubDate));
	
	echo $title."<br />";
	echo $link."<br />";
	echo $description."<br />";
	echo $pubDate."<br />";
}
print_r($xml);