<?php

if(!isset($_POST["name"]) || !isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["phone"]) || !isset($_POST["regType"]) || !isset($_POST["hpot"]) || !isset($_POST["token"]) || !isset($_SESSION["token"])){
	go404();
}

if($_SESSION["token"]!=$_POST["token"] || $_POST["hpot"]!=""){
	go404();
}

$name=trim($_POST["name"]);
$email=trim($_POST["email"]);
$password=$_POST["password"];
$phone=trim($_POST["phone"]);
$type=$_POST["regType"];

$_SESSION["token"]="";