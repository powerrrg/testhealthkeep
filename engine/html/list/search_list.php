<?php
if(!isset($hideFilters)){
?>
<div id="searchFilter">
	<a href="<?php echo WEB_URL.'q/'.$_GET["l2"]; ?>" <?php if(!isset($_GET["l3"])){ echo "class='searchActive'"; } ?>>All</a> | 
	<a href="<?php echo WEB_URL.'q/'.$_GET["l2"].'/'; ?>topic" <?php if(isset($_GET["l3"]) && $_GET["l3"]=="topic"){ echo "class='searchActive'"; } ?>>Topic</a> | 
	<a href="<?php echo WEB_URL.'q/'.$_GET["l2"].'/'; ?>user" <?php if(isset($_GET["l3"]) && $_GET["l3"]=="user"){ echo "class='searchActive'"; } ?>>User</a> | 
	<a href="<?php echo WEB_URL.'q/'.$_GET["l2"].'/'; ?>post" <?php if(isset($_GET["l3"]) && $_GET["l3"]=="post"){ echo "class='searchActive'"; } ?>>Post</a> | 
	<a href="<?php echo WEB_URL.'q/'.$_GET["l2"].'/'; ?>comment" <?php if(isset($_GET["l3"]) && $_GET["l3"]=="comment"){ echo "class='searchActive'"; } ?>>Comment</a>
</div>
<?php 
}
if($resSearch["result"]){

	foreach($resSearch as $key=>$value){
		if(is_int($key)){
		?>
			<div class="searchResult clearfix">
				<?php
				if($value["user_image_s"]=="" && $value["type_s"]!="topic"){
					$image=WEB_URL."inc/img/empty-avatar.png";
					$alt="Empty avatar";
				}else if($value["user_image_s"]!=""){
					$image=WEB_URL."img/profile/tb/".$value["user_image_s"];
					$alt=$value["user_name_s"];
				}else{
					$image=WEB_URL."inc/img/v1/inc/healthkeep_icon.png";
					$alt="HealthKeep Icon";
				}
				?>
				<div class="searchResultImg">
				<?php
				if($value["user_link_s"]!=""){
					echo '<a href="'.$value["user_link_s"].'">';	
				}
				?>
				<img src="<?php echo $image; ?>" alt="<?php echo $alt; ?>" />
				<?php
				if($value["user_link_s"]!=""){
					echo '</a>';	
				}
				?>
				</div>
				<div class="searchResultContent">
				<?php
				//echo $value["rank"]." - ";
				if($value["type_s"]=="user"){
					echo '<h3>'.$value["title_s"].'</h3>';
					echo '<p><a href="'.$value["user_link_s"].'" class="colorRed">'.$value["user_name_s"].'</a></p>';
				}else if($value["type_s"]=="post"){
					echo '<h3>Post by: ';
					echo '<a href="'.$value["user_link_s"].'">'.$value["user_name_s"].'</a>';
					echo '</h3><p>';
					if($value["title_s"]!=""){
						echo '<a href="'.$value["link_s"].'" class="colorRed">';
						echo $value["title_s"];
						echo '</a>';
						echo '<br />'.$value["snippet_s"];
					}else{
						echo $value["snippet_s"]."<br />";
						echo '<a href="'.$value["link_s"].'" class="colorRed">view</a>';
					}
					echo "</p>";
				}else if($value["type_s"]=="comment"){
					echo '<h3>Comment</h3><p><a href="'.$value["link_s"].'" class="colorRed">'.$value["snippet_s"].'</a></p>';
				}else if($value["type_s"]=="topic"){
					echo '<h3>Topic</h3><p><a href="'.$value["link_s"].'" class="colorRed">'.$value["title_s"].'</a></p>';
				}
				?>
				</div>
			</div>
			<?php
			}
		}
	}else if(!isset($pageNum)){
	?>
	<div class="alert alert-info center" style="margin:50px 0;">
		<h3>The search returned no results.<br /><br />
		Please try a different set of words or a health topic.</h3>
	</div>
	<?php
	}
	?>