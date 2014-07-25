<?php
function feetInchesToCm($feet,$inches=0){
	return (($feet.".".$inches) * 0.3048)*100;
}
function cmToFeetInches($cm)
{
	$m = $cm/100;
	$valInFeet = $m*3.2808399;
	$valFeet = (int)$valInFeet;
	$valInches = round(($valInFeet-$valFeet)*12);
	$data = $valFeet."&prime;".$valInches."&Prime;";
	return $data;
}