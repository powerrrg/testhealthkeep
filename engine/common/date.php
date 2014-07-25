<?php
function age($birthday){
	return intval(substr(date('Ymd') - date('Ymd', strtotime($birthday)), 0, -4));
}