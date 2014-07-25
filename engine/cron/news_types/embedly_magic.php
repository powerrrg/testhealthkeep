<?php
if(!isset($profileClass)){
	require_once(ENGINE_PATH."class/profile.class.php");
	$profileClass=new Profile();
}
foreach($xml->channel->item as $item){

	$title=$item->title;
	$link=$item->link;
	
	$res=$postClass->getByLinkPost($link);

	if(!$res["result"]){
		
		$enlink=urlencode($link);
		
		$url='http://api.embed.ly/1/extract?key=13b0d45923a04126aa9489924b6d602d&url='.$enlink.'&maxwidth=560&maxheight=600&autoplay=false';
	
		$ctx=stream_context_create(array('http'=>
		    array(
		        'timeout' => 10 // 10 seconds
		    )
		));
	
		$embedly=file_get_contents($url,false,$ctx);
		$resEmb=json_decode($embedly);
		if(!$resEmb){
			break;
		}else{
			if(!isset($pubDate) || $pubDate==""){
				$pubDate=date("Y-m-d H:i:s",(time()-$resEmb->cache_age));
			}
			/*
			EMBEDLY BRINGS DATA THAT AT TIMES HAS ESTRA VALUE THAN OUR AWAY BUT SO I DON'T LOSE MORE TIME WITH THIS, I WILL JUST IGNORE IT!
			
			$video="";
			if(isset($resEmb->media) && isset($resEmb->media->html)){
				$video=$resEmb->media->html;
			}
			$res=$postClass->addNewsPost($resEmb->title,$resEmb->url,$resEmb->content,$pubDate,$id_profile,$video);
			
			if(isset($resEmb->images) && isset($resEmb->images[0]->url)){
				$imgPath=PUBLIC_HTML_PATH."img/post/";
			    $image=$configClass->uploadImageURL($resEmb->images[0]->url, $imgPath);
			    if($image["image"]!=""){
					$postClass->saveImage($image["image"],$res[0]["id_post"]);
				}
			}
			
			*/
			
			if(isset($resEmb->related) && count($resEmb->related)>1){
				foreach($resEmb->related as $key=>$value){
					$resSourceUrl=parse_url($value->url);
					if(!$resSourceUrl["host"]){
						break;
					}
					
					$resHave=$postClass->doesNewsHaveTopic($value->title,$value->description);
					if($resHave){
						$resSource=$profileClass->lookForNewsSource($resSourceUrl["host"]);
						if(!$resSource["result"]){
							$resAdd=$userClass->addNewProAuto($resSourceUrl["host"],$resSourceUrl["host"]."@healthkeep.org",$resSourceUrl["host"]."st4ff","1111111111","news");
							$resSource=$profileClass->lookForNewsSource($resSourceUrl["host"]);
							if(!$resSource["result"]){
								break;
							}	
						}
					

						if(!is_null($value->thumbnail_url)){
							$tempTB=$value->thumbnail_url;
						}else{
							$tempTB="";
						}
						$externalClass->processNewsPostAuto($value->title,$value->url,$value->description,$pubDate,$resSource[0]["id_profile"],$tempTB);
					}
				}
			}
		}
		
	}
	
}