<?php
if(isset($allowFutureDate)){
$plus7 = date('l', strtotime('+7 day'));
$plus6 = date('l', strtotime('+6 day'));
$plus5 = date('l', strtotime('+5 day'));
$plus4 = date('l', strtotime('+4 day'));
$plus3 = date('l', strtotime('+3 day'));
$plus2 = date('l', strtotime('+2 day'));
$plus1 = date('l', strtotime('+1 day'));
}
$today = date( "l");
$yesterday = date('l', strtotime('-1 day'));
$minus2 = date('l', strtotime('-2 day'));
$minus3 = date('l', strtotime('-3 day'));
$minus4 = date('l', strtotime('-4 day'));
$minus5 = date('l', strtotime('-5 day'));
$minus6 = date('l', strtotime('-6 day'));
$minus7 = date('l', strtotime('-7 day'));
$jsfunctions.="var dateArr = new Array();
		dateArr[0]=new Array();
		dateArr[0]['day']=".date("j").";
		dateArr[0]['month']=".date("n").";
		dateArr[0]['year']=".date("Y").";
		dateArr[1]=new Array();
		dateArr[1]['day']=".date("j", strtotime('-1 day')).";
		dateArr[1]['month']=".date("n", strtotime('-1 day')).";
		dateArr[1]['year']=".date("Y", strtotime('-1 day')).";
		dateArr[2]=new Array();
		dateArr[2]['day']=".date("j", strtotime('-2 day')).";
		dateArr[2]['month']=".date("n", strtotime('-2 day')).";
		dateArr[2]['year']=".date("Y", strtotime('-2 day')).";
		dateArr[3]=new Array();
		dateArr[3]['day']=".date("j", strtotime('-3 day')).";
		dateArr[3]['month']=".date("n", strtotime('-3 day')).";
		dateArr[3]['year']=".date("Y", strtotime('-3 day')).";
		dateArr[4]=new Array();
		dateArr[4]['day']=".date("j", strtotime('-4 day')).";
		dateArr[4]['month']=".date("n", strtotime('-4 day')).";
		dateArr[4]['year']=".date("Y", strtotime('-4 day')).";
		dateArr[5]=new Array();
		dateArr[5]['day']=".date("j", strtotime('-5 day')).";
		dateArr[5]['month']=".date("n", strtotime('-5 day')).";
		dateArr[5]['year']=".date("Y", strtotime('-5 day')).";
		dateArr[6]=new Array();
		dateArr[6]['day']=".date("j", strtotime('-6 day')).";
		dateArr[6]['month']=".date("n", strtotime('-6 day')).";
		dateArr[6]['year']=".date("Y", strtotime('-6 day')).";
		dateArr[7]=new Array();
		dateArr[7]['day']=".date("j", strtotime('-7 day')).";
		dateArr[7]['month']=".date("n", strtotime('-7 day')).";
		dateArr[7]['year']=".date("Y", strtotime('-7 day')).";";
if(isset($allowFutureDate)){
	$jsfunctions.="
		dateArr[8]=new Array();
		dateArr[8]['day']=".date("j", strtotime('+1 day')).";
		dateArr[8]['month']=".date("n", strtotime('+1 day')).";
		dateArr[8]['year']=".date("Y", strtotime('+1 day')).";
		dateArr[9]=new Array();
		dateArr[9]['day']=".date("j", strtotime('+2 day')).";
		dateArr[9]['month']=".date("n", strtotime('+2 day')).";
		dateArr[9]['year']=".date("Y", strtotime('+2 day')).";
		dateArr[10]=new Array();
		dateArr[10]['day']=".date("j", strtotime('+3 day')).";
		dateArr[10]['month']=".date("n", strtotime('+3 day')).";
		dateArr[10]['year']=".date("Y", strtotime('+3 day')).";
		dateArr[11]=new Array();
		dateArr[11]['day']=".date("j", strtotime('+4 day')).";
		dateArr[11]['month']=".date("n", strtotime('+4 day')).";
		dateArr[11]['year']=".date("Y", strtotime('+4 day')).";
		dateArr[12]=new Array();
		dateArr[12]['day']=".date("j", strtotime('+5 day')).";
		dateArr[12]['month']=".date("n", strtotime('+5 day')).";
		dateArr[12]['year']=".date("Y", strtotime('+5 day')).";
		dateArr[13]=new Array();
		dateArr[13]['day']=".date("j", strtotime('+6 day')).";
		dateArr[13]['month']=".date("n", strtotime('+6 day')).";
		dateArr[13]['year']=".date("Y", strtotime('+6 day')).";
		dateArr[14]=new Array();
		dateArr[14]['day']=".date("j", strtotime('+7 day')).";
		dateArr[14]['month']=".date("n", strtotime('+7 day')).";
		dateArr[14]['year']=".date("Y", strtotime('+7 day')).";
		";
}
$onload.="
	$('#dayOfWeek').change(function(){
		
		if($(this).val()<8 || $(this).val()>8){
		
			$('#dateHolder').animate({
		    	opacity: 0.25
		    }, 100, function() {
		    	$('#dateHolder').animate({
		    		opacity:1
		    	}, 100);
		    });
			
			$('#month').val(dateArr[$(this).val()]['month']);
			$('#day').val(dateArr[$(this).val()]['day']);
			$('#year').val(dateArr[$(this).val()]['year']);
			
		}
	});
";
?>
<select name="dayOfWeek" id="dayOfWeek" style="width:190px;">
	<?php
	if(isset($allowFutureDate)){
	?>
	<option value="14">Next <?php echo $plus7; ?></option>
	<option value="13">Next <?php echo $plus6; ?></option>
	<option value="12">Next <?php echo $plus5; ?></option>
	<option value="11">Next <?php echo $plus4; ?></option>
	<option value="10">Next <?php echo $plus3; ?></option>
	<option value="9">Next <?php echo $plus2; ?></option>
	<option value="8">Tomorrow (<?php echo $plus1; ?>)</option>
	<?php
	}
	?>
	<option value="0" selected>Today (<?php echo $today; ?>)</option>
	<option value="1">Yesterday (<?php echo $yesterday; ?>)</option>
	<option value="2">Last <?php echo $minus2; ?></option>
	<option value="3">Last <?php echo $minus3; ?></option>
	<option value="4">Last <?php echo $minus4; ?></option>
	<option value="5">Last <?php echo $minus5; ?></option>
	<option value="6">Last <?php echo $minus6; ?></option>
	<option value="7">Last <?php echo $minus7; ?></option>
	<option value="8">Other</option>
</select>
<div id="dateHolder">
<select name="month" id="month" style="width:120px;">
	<?php
	$month = date( "n");
	?>
	<option value="1" <?php if($month==1){ echo "selected"; } ?>>January</option>
	<option value="2" <?php if($month==2){ echo "selected"; } ?>>February</option>
	<option value="3" <?php if($month==3){ echo "selected"; } ?>>March</option>
	<option value="4" <?php if($month==4){ echo "selected"; } ?>>April</option>
	<option value="5" <?php if($month==5){ echo "selected"; } ?>>May</option>
	<option value="6" <?php if($month==6){ echo "selected"; } ?>>June</option>
	<option value="7" <?php if($month==7){ echo "selected"; } ?>>July</option>
	<option value="8" <?php if($month==8){ echo "selected"; } ?>>August</option>
	<option value="9" <?php if($month==9){ echo "selected"; } ?>>September</option>
	<option value="10" <?php if($month==10){ echo "selected"; } ?>>October</option>
	<option value="11" <?php if($month==11){ echo "selected"; } ?>>November</option>
	<option value="12" <?php if($month==12){ echo "selected"; } ?>>December</option>
</select>
<input type="text" name="day" id="day" style="width:20px;" value="<?php echo date( "j"); ?>" />
<select name="year" id="year" style="width:80px;">
	<?php
	$currentYear=date('Y');
	$year=$currentYear;
	if(isset($allowFutureDate)){
		$currentYear=$currentYear+$allowFutureDate;
	}
	while($currentYear>1920){
		echo "<option value=\"$currentYear\"";
		if($year==$currentYear){
			echo " selected";	
		}
		echo ">$currentYear</option>";
		$currentYear--;
	}
	?>
</select>
</div>