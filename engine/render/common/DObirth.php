<?php
if(isset($resProfile[0]["dob_profile"])){
	$dob=explode("-", $resProfile[0]["dob_profile"]);
}else{
	$dob=array(0=>"0000",1=>"00",2=>"00");
}
?>
<div id="DOBHolder">
<select name="month" id="month" style="width:120px;">
	<option value="0">Month</option>
	<option value="1"<?php if($dob[1]==1){echo " selected";} ?>>January</option>
	<option value="2"<?php if($dob[1]==2){echo " selected";} ?>>February</option>
	<option value="3"<?php if($dob[1]==3){echo " selected";} ?>>March</option>
	<option value="4"<?php if($dob[1]==4){echo " selected";} ?>>April</option>
	<option value="5"<?php if($dob[1]==5){echo " selected";} ?>>May</option>
	<option value="6"<?php if($dob[1]==6){echo " selected";} ?>>June</option>
	<option value="7"<?php if($dob[1]==7){echo " selected";} ?>>July</option>
	<option value="8"<?php if($dob[1]==8){echo " selected";} ?>>August</option>
	<option value="9"<?php if($dob[1]==9){echo " selected";} ?>>September</option>
	<option value="10"<?php if($dob[1]==10){echo " selected";} ?>>October</option>
	<option value="11"<?php if($dob[1]==11){echo " selected";} ?>>November</option>
	<option value="12"<?php if($dob[1]==12){echo " selected";} ?>>December</option>
</select>
<input type="text" name="day" id="day" style="width:30px;" class="numeric" placeholder="Day" <?php if($dob[2]!="00"){echo 'value="'.(int)$dob[2].'"';} ?> />
<select name="year" id="year" style="width:80px;">
	<?php
	if(isset($forceYearZero)){
		echo "<option value=\"0\" selected>Year</option>";
	}
	$currentYear=date('Y');
	while($currentYear>1910){
		echo "<option value=\"$currentYear\"";
		if(($dob[0]=="0000" && $currentYear=="1980" && !isset($forceYearZero)) || $dob[0]==$currentYear){
			echo " selected";	
		}
		echo ">$currentYear</option>";
		$currentYear--;
	}
	
	?>
</select>
</div>