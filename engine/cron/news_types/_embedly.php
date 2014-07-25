<?php
foreach($xml->channel->item as $item){
	$title=$item->title;
	$link=$item->link;
	$res=$postClass->getByLinkPost($link);
	print_r($res);
	if(!$res["result"]){

		$description=trim(strip_tags($item->description));
		$pubDate=date("Y-m-d H:i:s",strtotime($item->pubDate));
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
		}
		//addNewsPost($title,$link,$description,$pubDate,$id_profile){
		if($pubDate==""){
		$pubDate=date("Y-m-d H:i:s",(time()-$resEmb->cache_age));
		}
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
		if(isset($resEmb->related) && count($resEmb->related)>1){
			//addNewsPostHKSource($title,$description,$id_profile,$video=''){
			$res2=$postClass->addNewsPostHKSource($resEmb->description,$resEmb->content,'721528',$video);
			
			if(isset($resEmb->images) && isset($resEmb->images[0]->url) && isset($image["image"]) && $image["image"]!=""){
				
				$imgPath=PUBLIC_HTML_PATH."img/post/";
				copy($imgPath."tb/". $image["image"], $imgPath."tb/main_". $image["image"]);
				copy($imgPath."med/". $image["image"], $imgPath."med/main_". $image["image"]);
				copy($imgPath."org/". $image["image"], $imgPath."org/main_". $image["image"]);
				$postClass->saveImage("/main_".$image["image"],$res2[0]["id_post"]);
			}
			
			$postClass->linkStories($res2[0]["id_post"],$res[0]["id_post"]);
			foreach($resEmb->related as $key=>$value){
				//$postClass->addNewTemp($value["score"],$value["description"],$value["title"],$value["url"],$value["thumbnail_url"],$res2[0]["id_post"]);
				if(!is_null($value->thumbnail_url)){
					$tempTB=$value->thumbnail_url;
				}else{
					$tempTB="";
				}
				$postClass->addNewTemp($value->score,$value->description,$value->title,$value->url,$tempTB,$res2[0]["id_post"]);
			}
		}
	
	}else{
		$resLy=false;
	}

}
$resEmb=true;