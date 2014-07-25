<?php
function typeArray($type){
    
	if($type=="d" || $type=="dis"){
	    return array("singular"=>"Condition","plural"=>"Conditions","icon"=>"heart.png", "color"=>"lightBlue");
    }else if($type=="m" || $type=="med"){
    	return array("singular"=>"Medication","plural"=>"Medications","icon"=>"pill.png", "color"=>"blue");
    }else if($type=="p" || $type=="pro"){
    	return array("singular"=>"Procedure","plural"=>"Procedures","icon"=>"inbed.png", "color"=>"brown");
    }else if($type=="s" || $type=="sym"){
	    return array("singular"=>"Symptom","plural"=>"Symptoms","icon"=>"temperature.png", "color"=>"pink");
    }else if($type=="fel"){
	    return array("singular"=>"Overall Health","plural"=>"Overall Health","icon"=>"line_gray.png", "color"=>"gray");
    }else if($type=="res"){
	    return array("singular"=>"Test Result","plural"=>"Test Results","icon"=>"line_green.png", "color"=>"green");
    }else if($type=="doc"){
	    return array("singular"=>"Doctor Visit","plural"=>"Doctor Visits","icon"=>"line_red.png", "color"=>"red");
    }else if($type=="measurement" || $type=="mea"){
	    return array("singular"=>"Measurement","plural"=>"Measurements","icon"=>"line_purple.png","color"=>"purple","type"=>array(
	    	"diet"=>array("name"=>"Diet"),
	    	"weight"=>array("name"=>"Weight"),
	    	"bp"=>array("name"=>"Blood Pressure"),
	    	"sugar"=>array("name"=>"Blood Sugar"),
	    	"exercise"=>array("name"=>"Exercise")
	    ));
    }else{
	    return false;
    }
    
}