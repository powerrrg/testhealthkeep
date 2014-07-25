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
		<form method="post" id="addCondition" class="fullSizeForm" action="<?php echo WEB_URL; ?>act/udisease.php">
			
			<input type="text" name="disease" style="width:100%;" id="disease" placeholder="Condition" /><br />
			<input type="hidden" id="id_disease" name="id_disease" value="0" />
			<input type="text" style="width:70px;" maxlength="4" name="year" id="year" placeholder="Year" /><br />
			<?php
			$onload.="
			var yearArr = ['1910','1911','1912','1913', '1914', '1915', '1916', '1917', '1918', '1919', '1920', '1921', '1922', '1923', '1924', '1925', '1926', '1927', '1928', '1929', '1930', '1931', '1932', '1933', '1934', '1935', '1936', '1937', '1938', '1939', '1940', '1941', '1942', '1943', '1944', '1945', '1946', '1947', '1948', '1949', '1950', '1951', '1952', '1953', '1954', '1955', '1956', '1957', '1958', '1959', '1960', '1961', '1962', '1963', '1964', '1965', '1966', '1967', '1968', '1969', '1970', '1971', '1972', '1973', '1974', '1975', '1976', '1977', '1978', '1979', '1980', '1981', '1982', '1983', '1984', '1985', '1986', '1987', '1988', '1989', '1990', '1991', '1992', '1993', '1994', '1995', '1996', '1997', '1998', '1999', '2000', '2001', '2002', '2003', '2004', '2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013' ];
			$( '#year' ).autocomplete({
				source: yearArr,
				select: function(event,ui){
					
			    }
			});"
			?>
			<br />
			<input type="submit" value="save" class="btn" />
		</form>
	</div>
	<?php
	$onload.="
	$( '#disease' ).autocomplete({
		source: '".WEB_URL."act/ajax/disease.php',
		minLength: 3,
		select: function(event,ui){
			var selId=ui.item['id'];
			var selName=ui.item['value'];
			$('#id_disease').val(selId);	
	    }
	});	
	";
	$onload.="
		$('#addCondition').submit(function(){
			if($('#id_disease').val()==0){
				alert('You need to choose a valid condition');
				$('#disease').focus();
				return false;
			}else if($('#year').val()==''){
				alert('You need to insert a year');
				$('#year').focus();
				return false;
			}else if($.inArray($('#year').val(), yearArr)<1){
				alert('You need to insert a valid year');
				$('#year').focus();
				return false;
			}else{
				return true;
			}
		});
	";
    
	?>

	
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');