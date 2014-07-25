<?php
require_once('../../engine/starter/config.php');

onlyLogged();

$pageTitle="Add a medical condition - HealthKeep";
$pageDescr="";

$needUI=1;

require_once(ENGINE_PATH.'html/header.php');
$active="home";
require_once(ENGINE_PATH.'html/bar.php');

?>
<div id="main" class="iHold clearfix">

	<div class="iRounded iBoard clearfix center">
		<h3 class="marginbottom30">Add a medical condition</h3>
		<form method="post" id="addCondition">
			<input type="text" style="width:70px;" name="year" id="year" placeholder="Year" />
			<?php
			$onload.="
			var yearArr = [
				'1910',
				'1911',
				'1912',
				'1913',
				'1914',
				'1915',
				'1916',
				'1917',
				'1918',
				'1919',
				'1920',
				'1921',
				'1922',
				'1923',
				'1924',
				'1925',
				'1926',
				'1927',
				'1928',
				'1929',
				'1930',
				'1931',
				'1932',
				'1933',
				'1934',
				'1935',
				'1936',
				'1937',
				'1938',
				'1939',
				'1940',
				'1941',
				'1942',
				'1943',
				'1944',
				'1945',
				'1946',
				'1947',
				'1948',
				'1949',
				'1950',
				'1951',
				'1952',
				'1953',
				'1954',
				'1955',
				'1956',
				'1957',
				'1958',
				'1959',
				'1960',
				'1961',
				'1962',
				'1963',
				'1964',
				'1965',
				'1966',
				'1967',
				'1968',
				'1969',
				'1970',
				'1971',
				'1972',
				'1973',
				'1974',
				'1975',
				'1976',
				'1977',
				'1978',
				'1979',
				'1980',
				'1981',
				'1982',
				'1983',
				'1984',
				'1985',
				'1986',
				'1987',
				'1988',
				'1989',
				'1990',
				'1991',
				'1992',
				'1993',
				'1994',
				'1995',
				'1996',
				'1997',
				'1998',
				'1999',
	            '2000',
	            '2001',
	            '2002',
	            '2003',
	            '2004',
	            '2005',
	            '2006',
	            '2007',
	            '2008',
	            '2009',
	            '2010',
	            '2011',
	            '2012',
	            '2013'
	        ];
			$( '#year' ).autocomplete({
				source: yearArr,
				select: function(event,ui){
					
			    }
			});"
			?>
			<select name="month" id="month" style="width:80px;">
				<option value="0">Month</option>
				<option value="1">January</option>
				<option value="2">February</option>
				<option value="3">March</option>
				<option value="4">April</option>
				<option value="5">May</option>
				<option value="6">June</option>
				<option value="7">July</option>
				<option value="8">August</option>
				<option value="9">September</option>
				<option value="10">October</option>
				<option value="11">November</option>
				<option value="12">December</option>
			</select>
			<select name="month" id="month" style="width:70px;">
				<option value="0">Day</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>
				<option value="31">31</option>
			</select><br />
			<input type="text" style="width:80px;" name="year2" id="year2" placeholder="Year" />&nbsp;/&nbsp;<input type="text" style="width:40px;" name="month2" id="month2" placeholder="Month" />&nbsp;/&nbsp;<input type="text" style="width:40px;" name="day2" id="day2" placeholder="Day" /><br />
			<?php
			$onload.="
			$( '#year2' ).autocomplete({
				source: yearArr,
				select: function(event,ui){
					
			    }
			});
			";
			?>
			<input type="text" name="datepicker" id="datepicker" placeholder="Date" /><br />
			<input type="text" name="disease" id="disease" placeholder="Disease" />
			
		</form>
	</div>
	<?php
	$onload.="
	$( '#disease' ).autocomplete({
		source: '".WEB_URL."act/ajax/disease.php',
		minLength: 2,
		select: function(event,ui){
			var selId=ui.item['id'];
	    }
	});
	$( '#datepicker' ).datepicker({
        changeMonth: true,
        changeYear: true,
        minDate: '-50y', maxDate: 0
    });		
	";

    
	?>

	
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');