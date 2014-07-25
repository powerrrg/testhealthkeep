<?php
if($resNext["result"]){

	$link = 'http://api.sharedcount.com/?url='.$resNext[0]["link_post"];
	$res=get_web_page($link,30);
	if($res["http_code"]=="200"){

		$numres=json_decode($res["content"]);
	
		if(isset($numres->StumbleUpon) && isset($numres->Reddit) && isset($numres->Facebook->total_count) && isset($numres->Delicious) && isset($numres->GooglePlusOne) && isset($numres->Twitter) && isset($numres->Diggs) && isset($numres->LinkedIn)){
	
		$stumble=(int)$numres->StumbleUpon;
		$reddit=(int)$numres->Reddit;
		$fb_total_count=(int)$numres->Facebook->total_count;
		$delicious=(int)$numres->Delicious;
		$gplus=(int)$numres->GooglePlusOne;
		$quantostweets=(int)$numres->Twitter;
		$digg=(int)$numres->Diggs;
		$pint=(int)$numres->Pinterest;
		$lin=(int)$numres->LinkedIn;
	
		$res=$postClass->updateRank($resNext[0]["id_post"],$resNext[0]["tsdate"],$resNext[0]["thumb_up_post"],$stumble,$reddit,$fb_total_count,$delicious,$gplus,$quantostweets,$digg,$lin,$pint);
			
		}else{
			echo "sharedcount no values<br />";
		echo 'http://api.sharedcount.com/?url='.$link;
		print_r($res);
		}
	/*}else if(isset($_GET["debug"])){
		echo "sharedcount failed<br />";
		echo 'http://api.sharedcount.com/?url='.$link;
	}*/
	}else{
		echo "sharedcount failed<br />";
		echo 'http://api.sharedcount.com/?url='.$link;
		print_r($res);
	}


}