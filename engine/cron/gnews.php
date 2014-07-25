<?php
$iamcron=1;
require_once('../starter/config.php');

require_once(ENGINE_PATH.'cron/functions.php');

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();

require_once(ENGINE_PATH.'class/external.class.php');
$externalClass=new External();

$res = $topicClass->getTopicToUpdateGoogleNews();

if($res["result"]){
	//echo $res[0]["name_topic"]."<br />";
	$name=urlencode($res[0]["name_topic"]);
	
	$link="https://news.google.com/news/feeds?q=$name&output=rss";
	
	$res=get_web_page($link);
	if($res["http_code"]=="200"){
		$originContent=$res["content"];
		$xml=new SimpleXMLElement($originContent);
		
		require_once(ENGINE_PATH."class/post.class.php");
		$postClass=new Post();
		
		require_once(ENGINE_PATH.'class/profile.class.php');
		$profileClass=new Profile();
		
		foreach($xml->channel->item as $item){
			$title=$item->title;
			$url=$item->link;
			$description=$item->description;
			$descp=explode('<font size="-1">', $description);
			$pubDate=date("Y-m-d H:i:s",strtotime($item->pubDate));
			$pieces=explode("&url=http", $url);
			if(isset($descp[2])){
				if(isset($pieces[1]) || count($pieces)==2){
					$link="http".$pieces[1];
					
					$resSourceUrl=parse_url($link);
					if($resSourceUrl["host"]){
					
						$description=strip_tags(str_ireplace('and more&nbsp;&raquo;', '',$descp[2]));
						$resHave=$postClass->doesNewsHaveTopic($title,$description);
						if($resHave){
							$resSource=$profileClass->lookForNewsSource($resSourceUrl["host"]);
							if(!$resSource["result"]){
								$resAdd=$userClass->addNewProAuto($resSourceUrl["host"],$resSourceUrl["host"]."@healthkeep.org",$resSourceUrl["host"]."st4ff","1111111111","news");
								$resSource=$profileClass->lookForNewsSource($resSourceUrl["host"]);
								if(!$resSource["result"]){
									break;
								}	
							}
							
							$externalClass->processNewsPost($title,$link,$description,$pubDate,$resSource[0]["id_profile"]);
							//echo "<h1>$title</h1><p>$url</p><p>$link</p><p>$pubDate</p><p>$description</p><hr />";
						}else{
							//echo "<h1>Doesnt have word</h1>";
						}
	
					}else{
						//echo "<h1>Doesnt have</h1>";
					}
					
					
				}else{
					//echo "<h1>No host</h1>";
				}
			}else{
				//echo "<h1>No description</h1>";
			}
		}
	}
	
	
}