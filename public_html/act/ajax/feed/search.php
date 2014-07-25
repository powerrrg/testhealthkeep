<?php
require_once('../../../../engine/starter/config.php');

if(isset($_POST["p"]) && isset($_POST["t"])){

	$pageNum = (int)$_POST["p"];
	
	if($pageNum<2){
		go404();
	}
	
	$q=$_POST["t"];
	if(strlen($q)<2){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/search.class.php');
	$searchClass=new Search();
	
	if($_POST["x"]){
		$ofilter=$_POST["x"];
		if($ofilter!='topic' && $ofilter!='user' && $ofilter!='post' && $ofilter!='comment'){
			go404();
		}
		$resSearch = $searchClass->search($q,$pageNum,$ofilter);
	}else{
		$resSearch = $searchClass->search($q,$pageNum);
	}
	$hideFilters=1;
	require_once(ENGINE_PATH."html/list/search_list.php");

}else{
	go404();
}