<?php
$iamcron=1;
require_once('../starter/config.php');

require_once(ENGINE_PATH."class/post.class.php");
$postClass=new Post();

$resPost=$postClass->getNextPostToAutoTweet();

if(!$resPost["result"]){
	echo "no";
	exit;
}

if($resPost[0]["id_topic_pr"]==34015){
	$what="diabetes";
}else{
	$what="fibro";
}

$keys=array(
	"fibro"=>array(
		"ckey"=>"vuBz8wrdVmTHYYgVa43hQ",
		"csecret"=>"H5fgIyiujAumtRSrv02r6eUYukCwYVdG9aF7iHo1A",
		"atoken"=>"1570844742-fE9yw8G22p2svpUWnduiGzc15RbfmqhXgjMrYxE",
		"asecret"=>"LJeI2T09aM6xD9ccsUNLCtZqoJCPPrnXHAmKf08Ij0",
		"id"=>112,
		"taccount"=>"fibromyalgia_hk"
	),
	"diabetes"=>array(
		"ckey"=>"fdlKgcyEh48qH0LPAUp6Cw",
		"csecret"=>"NAF55sGJRslsJeI2HclHkt286KnGCI7A2kYCL848t0",
		"atoken"=>"1570996987-LBuoPXjmXQnv23I6GMNQl5DS31LYOxyJp8kkiFG",
		"asecret"=>"ae0EQopMV2uD0GfqHTNqQDlEAtqLClTrc1948yK0E",
		"id"=>34015,
		"taccount"=>"diabetes_hk"
	)
);

$consumerKey    = $keys[$what]["ckey"];
$consumerSecret = $keys[$what]["csecret"];
$oAuthToken     = $keys[$what]["atoken"];
$oAuthSecret    = $keys[$what]["asecret"];

require_once(ENGINE_PATH.'class/twitter/twitteroauth.php');

$tweet = new TwitterOAuth($consumerKey, $consumerSecret, $oAuthToken, $oAuthSecret);

$title="";
if($resPost[0]["title_post"]!=""){
	$title=$resPost[0]["title_post"];
}else{
	$title=strip_tags($resPost[0]["text_post"]);
}
if(strlen($title)>120){
$title=substr($title, 0,120)."...";
}
$link="https://www.healthkeep.com/post/".$resPost[0]["id_post"]."?utm_source=twitter&utm_medium=post&utm_campaign=".$keys[$what]["taccount"];

/* returns the shortened url */
function get_bitly_short_url($url,$login,$appkey,$format='txt') {
	$connectURL = 'http://api.bit.ly/v3/shorten?login='.$login.'&apiKey='.$appkey.'&uri='.urlencode($url).'&format='.$format;
	return curl_get_result($connectURL);
}

/* returns a result form url */
function curl_get_result($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

/* get the short url */
$short_url = get_bitly_short_url($link,'twitterhk','R_330d595e03a6bef19bc91ef4200b8165');

$title.=" ".$short_url;

$res=$tweet->post('statuses/update', array('status' => $title));
