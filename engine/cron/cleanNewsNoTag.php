<?php
$iamcron=1;
require_once('../starter/config.php');

require_once(ENGINE_PATH.'cron/functions.php');

	require_once(ENGINE_PATH."class/post.class.php");
	$postClass=new Post();
require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();
$sql="select * from post left join post_relation on id_post=id_post_pr where id_topic_pr is NULL group by id_post limit 100";
$res=$configClass->query($sql,array());

if($res["result"]){

	foreach($res as $key=>$value){
	
		if(is_int($key)){
			echo "<pre>";
			print_r($value)	;
			echo "<br /><br />";
			
			//misses to verify if post has about before delete it!!!!
			
			$postClass->forceDeletePost($value["id_post"]);	
	 	}
	
	}
}