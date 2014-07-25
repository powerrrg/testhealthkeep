<?php

require_once(ENGINE_PATH.'class/error.class.php');

class Config{

    private $_pdo;
	private $_dbname;
	private $_dbuser;
	private $_dbpass;
	
	function __construct($op=0,$name=DB_HEALTHKEEP_NAME,$user=DB_HEALTHKEEP_USER,$pass=DB_HEALTHKEEP_PW)
    {
    		if(defined("USER_DB_NAME") && defined("USER_DB_USER") && defined("USER_DB_PASS") && $op==0)
		{
		    $this->_dbname=USER_DB_NAME;
		    $this->_dbuser=USER_DB_USER;
		    $this->_dbpass=USER_DB_PASS;
    		}else{
		    $this->_dbname=$name;
		    $this->_dbuser=$user;
		    $this->_dbpass=$pass;
            }
            $this->_pdo = $this->pdo();
	}
		
	public function pdo()
        {
            try
            {
                return new PDO("mysql:host=".DB_HEALTHKEEP_HOST.";dbname=".$this->_dbname,
                $this->_dbuser,$this->_dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
                                                  PDO::ATTR_EMULATE_PREPARES => false, 
                                                  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            }
            catch(PDOException $err)
            {
                $myError = new Error();
                $myError->exceptionHandling($err);
            }
            catch (Exception $err)
            {
                $myError = new Error();
                $myError->exceptionHandling($err);
            }
	}
        
	public function query($sql,$params=array(),$modo="")
        {   
            try 
            {     
                if($modo == "")
                {
                    $query = $this->_pdo->prepare($sql);

                    if(preg_match("/^select/", strtolower($sql))){
                            $query->execute($params);
                            $res = $query->fetchAll(PDO::FETCH_ASSOC);
                        $res["result"]=count($res);
                            return $res;
                    }else{
                            return $query->execute($params);    
                    }
                }
                    elseif ($modo == "exec") {
                        return $this->_pdo->exec($sql);
                    }
                    
            }
            catch(PDOException $err)
            {
                $myError = new Error();
                $myError->exceptionHandling($err);
            }
            catch (Exception $err)
            {
                $myError = new Error();
                $myError->exceptionHandling($err);
            }
	}
	
	public function name($array,$param0=true){
		if($param0){
			if($array[0]["type_profile"]==1){
				return $array[0]["username_profile"];
			}else{
				return $array[0]["name_profile"];
			}
		}else{
			if($array["type_profile"]==1){
				return $array["username_profile"];
			}else{
				return $array["name_profile"];
			}
		}
	}
	
	public function endEmailText(){
		return "<br /><br />-----------------------------------------------------------<br /><br />You received this message because your <a href=\"".WEB_URL."\">HealthKeep</a> account is set to allow it.<br />If you wish to unsubscribe from this type of email please visit ".WEB_URL."account/notifications";
	}
	
	public function formatPhoneNumber($number){
		return "(".substr($number, 0, 3).") ".substr($number, 3, 3)."-".substr($number,6);
	}
        
    public function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])){   //check ip from share internet
          return $_SERVER['HTTP_CLIENT_IP'];
        }else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){   //to check ip is pass from proxy
          return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
          return $_SERVER['REMOTE_ADDR'];
        }
    }
    
    public function escapeOddChars($string){
    	$string=str_replace('‘', '\'', $string);
	    $string=str_replace('’', '\'', $string);
	    return $string;
    }
    
    public function br2nl($string)
	{
	    RETURN PREG_REPLACE('#<br\s*?/?>#i', "", $string);
	} 
    
    public function processPostText($text){
	    $text=strip_tags($text);
    	$text=$this->haveLink($text);
    	$text=nl2br(trim($text));
    	/*$text=preg_replace("/(<br\s*\/?>\s*)+/", "<br/><br/>", $text);*/
    	return $text;
    }
    
    public function haveLink($text){
	    $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		
		if(preg_match($reg_exUrl, $text, $url)) {
			$parsed=parse_url($url[0]);
		    //return preg_replace($reg_exUrl, "<a href=\"".$url[0]."\" rel=\"nofollow\" target=\"_blank\">".$parsed["host"]."</a> ", $text);
		    //as requested by Lyle, the URL displayed should be the full URL and not just the domain
		    return preg_replace($reg_exUrl, "<a href=\"".$url[0]."\" rel=\"nofollow\" target=\"_blank\">".$url[0]."</a> ", $text);
		
		} else {
		
		       return $text;
		
		}
    }
    
    public function ago($time){
		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");
		
		$now = time();
		
		   $difference     = $now - $time;
		   $tense         = "ago";
		
		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		   $difference /= $lengths[$j];
		}
		
		$difference = round($difference);
		
		if($difference<1){
			return "just now";
		}
		
		if($difference != 1) {
		   $periods[$j].= "s";
		}
		
		return "$difference $periods[$j] ago ";
	}
    
    public function safeUri($string){

             $string = trim($string);

             if ( ctype_digit($string) ) {
               return $string;
             }
             else {
               // replace accented chars
               $accents = '/&([A-Za-z]{1,2})(grave|acute|circ|cedil|uml|lig|tilde);/';
               $string_encoded = htmlentities($string,ENT_NOQUOTES,'UTF-8');

               $string = preg_replace($accents,'$1',$string_encoded);

               // clean out the rest
               $replace = array('([\40])','([^a-zA-Z0-9-])','(-{2,})','/[^[:print:]]+/');
               $with = array('-','','-','');
               $string = preg_replace($replace,$with,$string);
             }
                    $string = ltrim($string, "-");
                    $string = rtrim($string, "-");
                    $string = strtolower($string);
            return urlencode($string);
    }
    
    public function getPagingQuery($sql,$page, $itemPerPage = 10)
	{
		if ($page > 0) {
			$page = $page;
		} else {
			$page = 1;
		}
		
		// start fetching from this row number
		$offset = ($page - 1) * $itemPerPage;
		
		return $sql . " LIMIT $offset, $itemPerPage";
	}
    
    public function pagination($total,$actual,$url,$symbol,$num=15)
    {
		$actual = (int)$actual;
		if($actual == 0)
		{
		    $actual = 1;
		}
		$start = (($actual-1)*$num);
		$end = $num;
	
		$total_pages = ceil($total/$num);
		$html = "";
		
		if($total_pages > 1)
		{
		
			if($actual==1){
				$html.='<button disabled class="btn">first</button>';
			}else{
				$html.='<a href="'.$url.'" class="btn">first</a>';
			}
			
			if($actual>5){
			    $z=$actual-4;
		    }else{
			    $z=1;
		    }
		    $quantos=1;
		    for ($i=$z;$total_pages>=$i && $quantos<=10;$i++)
		    {
		    if($i==$actual){
		    
		    	$html.= ' | <button disabled class="btn">'.$i."</button>";
		    
			}else if($i==1)
			{
			    $html.= ' | <a href="'.$url.'" class="btn">'.$i.'</a>';
			}
			else
			{
			    $html.= ' | <a href="'.$url.$symbol.'page='.$i.'" class="btn">'.$i.'</a>';
			}
			$quantos++;
		    }
		    
		    if($actual==$total_pages){
				$html.='<button disabled class="btn">last</button>';
			}else{
				$html.=' | <a href="'.$url.$symbol.'page='.$total_pages.'" class="btn">last</a>';
			}
		    
		    //$html = ltrim($html," |");
		}

		return array("start"=>$start,"end"=>$end,"total_pages"=>$total_pages,"html"=>$html);
    }
    
    public function uploadFile($inputName,$uploadDir){
    
		$filename = $_FILES[$inputName];
		$filePath = '';
		
		if (trim($filename['tmp_name']) != '') {
			$ext = substr(strrchr($filename['name'], "."), 1);
			$ext = strtolower($ext);
			$filePath = md5(rand() * time()) . ".".$ext;
			
			if(!move_uploaded_file($filename['tmp_name'], $uploadDir.$filePath)) {
			    $filePath = '';
			}
			
		}
		
		return array('file' => $filePath);
	}
    
    /*
	Create a thumbnail of $srcFile and save it to $destFile.
	The thumbnail will be $width pixels.
	*/
	private function createThumbnail($srcFile, $destFile, $width, $quality = 90)
	{
		$thumbnail = '';
					
		//HACKED TO ALLOW REMOTE URL UPLOAD
		/*if (file_exists($srcFile)  && isset($destFile))
		{*/
			$size        = getimagesize($srcFile);
			$w           = number_format($width, 0, ',', '');
			$h           = number_format(($size[1] / $size[0]) * $width, 0, ',', '');

			$thumbnail =  $this->copyImage($srcFile, $destFile, $w, $h, $quality);
		//}
		
		// return the thumbnail file name on sucess or blank on fail
		return basename($thumbnail);
	}
	
	/*
		Copy an image to a destination file. The destination
		image size will be $w X $h pixels
	*/
	private function copyImage($srcFile, $destFile, $w, $h, $quality = 90)
	{
	    $tmpSrc     = pathinfo(strtolower($srcFile));
	    $tmpDest    = pathinfo(strtolower($destFile));
	    $size       = getimagesize($srcFile);
	
	    if ($tmpDest['extension'] == "gif" || $tmpDest['extension'] == "jpg" || $tmpDest['extension'] == "jpeg")
	    {
	       //$destFile  = substr_replace($destFile, 'jpg', -3);
	       $dest      = imagecreatetruecolor($w, $h);
	       imageantialias($dest, TRUE);
	    } elseif ($tmpDest['extension'] == "png") {
	       $dest = imagecreatetruecolor($w, $h);
	       imageantialias($dest, TRUE);
	    } else {
	      return false;
	    }
	    
	    if($size[2]==1 || $size[2]==3){
	    	imagecolortransparent($dest, imagecolorallocatealpha($dest, 0, 0, 0, 127));
	    	imagealphablending($dest, false);
	    	imagesavealpha($dest, true);
	    }
	    
	    switch($size[2])
	    {
	       case 1:       //GIF
	           $src = imagecreatefromgif($srcFile);
	           break;
	       case 2:       //JPEG
	           $src = imagecreatefromjpeg($srcFile);
	           break;
	       case 3:       //PNG
	           $src = imagecreatefrompng($srcFile);
	           break;
	       default:
	           return false;
	           break;
	    }
	
	    imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);
	
	    switch($size[2])
	    {
	       case 1:
	       	   imagegif($dest,$destFile,$quality);
	           break;
	       case 2:
	           imagejpeg($dest,$destFile,$quality);
	           break;
	       case 3:
	           imagepng($dest,$destFile);
	    }
	    return $destFile;
	
	}
	
	public function uploadImage($inputName, $uploadDir, $orgsize=960, $medsize=320, $tbsize=100)
	{
		$image = $_FILES[$inputName];
		$imagePath = '';
		
		// if a file is given
		if (trim($image['tmp_name']) != '') {
			$ext = substr(strrchr($image['name'], "."), 1); //$extensions[$image['type']];
	
			// generate a random new file name to avoid name conflict
			$ext = strtolower($ext);
			
			$imagePath = md5(rand() * time()) . ".".$ext;
			//$imagePath = md5(rand() * time()) . ".jpg";
			
			list($width, $height, $type, $attr) = getimagesize($image['tmp_name']); 

			if (($image["type"] == "image/jpeg" || $image["type"] == "image/pjpeg" || $image["type"] == "image/gif" || $image["type"] == "image/x-png" || $image["type"] == "image/png") && ($image["size"] < 2097152)){
			
			// make sure the image width does not exceed the
			// maximum allowed width
			if ($width < $orgsize) {
				$orgsize=$width;
			}
			$result    = $this->createThumbnail($image['tmp_name'], $uploadDir . "org/" . $imagePath, $orgsize);

			if(!$result){
			$imagePath = '';
			}else{
			$imagePath = $result;
				if ($width < $medsize) {
				$medsize=$width;
				}
				$result    = $this->createThumbnail($uploadDir . "org/" . $imagePath, $uploadDir . "med/" . $imagePath, $medsize);
				if (!$result) {
				// the product cannot be upload / resized
				unlink($uploadDir . "org/" . $imagePath);
				$imagePath = '';
				}else{
					if ($width < $tbsize) {
					$tbsize=$width;
					}
					
					if($width==$height){
					$result = $this->createThumbnail($uploadDir . "med/" . $imagePath, $uploadDir . "tb/" . $imagePath, $tbsize);
					}else{
					$result = $this->square_crop($uploadDir . "med/" . $imagePath, $uploadDir . "tb/" . $imagePath, $tbsize); 
					}
					if (!$result) {
					// the product cannot be upload / resized
					unlink($uploadDir . "org/" . $imagePath);
					unlink($uploadDir . "med/" . $imagePath);
					$imagePath = '';
					}
				}
			}
			//echo "ok";
			}else{
			//echo "erro";
			$imagePath='';
			}
			
		}
	
		
		return array('image' => $imagePath);
	}
	private function square_crop($src_image, $dest_image, $thumb_size = 75, $jpg_quality = 90) {
	
	    // Get dimensions of existing image
	    $image = getimagesize($src_image);
	
	    // Check for valid dimensions
	    if( $image[0] <= 0 || $image[1] <= 0 ) return false;
	
	    // Determine format from MIME-Type
	    $image['format'] = strtolower(preg_replace('/^.*?\//', '', $image['mime']));
	
	    // Import image
	    if($image['format']=="png"){
		    $image_data = imagecreatefrompng($src_image);
	    }else if($image['format']=="gif"){
		    $image_data = imagecreatefromgif($src_image);
	    }else{
			$image_data = imagecreatefromjpeg($src_image);
		}
	    
	    // Verify import
	    if( $image_data == false ) {return false;};
	
	    // Calculate measurements
	    if( $image[0] > $image[1] ) {
	        // For landscape images
	        $x_offset = ($image[0] - $image[1]) / 2;
	        $y_offset = 0;
	        $square_size = $image[0] - ($x_offset * 2);
	    } else {
	        // For portrait and square images
	        $x_offset = 0;
	        $y_offset = ($image[1] - $image[0]) / 2;
	        $square_size = $image[1] - ($y_offset * 2);
	    }
	
	    // Resize and crop
	    $canvas = imagecreatetruecolor($thumb_size, $thumb_size);
	    
	    if($image['format']=="png" || $image['format']=="gif"){
	    	imagecolortransparent($canvas, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
	    	imagealphablending($canvas, false);
	    	imagesavealpha($canvas, true);
    	}
    	
	    if( imagecopyresampled(
	        $canvas,
	        $image_data,
	        0,
	        0,
	        $x_offset,
	        $y_offset,
	        $thumb_size,
	        $thumb_size,
	        $square_size,
	        $square_size
	    )) {
	    	
		    
		    if($image['format']=="png"){
			    return imagepng($canvas, $dest_image, 0);
		    }else if($image['format']=="gif"){
			    return imagegif($canvas, $dest_image);
		    }else{
				return imagejpeg($canvas, $dest_image, $jpg_quality);
			}
		
	
	    } else {
	        return false;
	    }
	
	}
	
	public function uploadImageURL($url, $uploadDir, $orgsize=960, $medsize=320, $tbsize=100)
	{
		$image = $url;
		$imagePath = '';
		
		// if a file is given
		if (trim($image) != '') {
			$ext = substr(strrchr($image, "."), 1); //$extensions[$image['type']];
			// generate a random new file name to avoid name conflict
			$ext = strtolower($ext);
			$imagePath = md5(rand() * time()) . ".jpg";
			
			if(!@getimagesize($image)){
				return array('image' => '');
			}
			
			list($width, $height, $type, $attr) = getimagesize($image); 
			//print_r($image);exit;
			if (1==1/*($image["type"] == "image/jpeg" || $image["type"] == "image/pjpeg" || $image["type"] == "image/gif" || $image["type"] == "image/x-png") && ($image["size"] < 1000000)*/){
			
			// make sure the image width does not exceed the
			// maximum allowed width
			if ($width < $orgsize) {
				$orgsize=$width;
			}
	
			$result    = $this->createThumbnail($image, $uploadDir . "org/" . $imagePath, $orgsize);

			if(!$result){
			$imagePath = '';
			}else{
				//$imagePath = $result;

				$imagePath = $result;
				if ($width < $medsize) {
				$medsize=$width;
				}
				$result    = $this->createThumbnail($uploadDir . "org/" . $imagePath, $uploadDir . "med/" . $imagePath, $medsize);
				if (!$result) {
				// the product cannot be upload / resized
				unlink($uploadDir . "org/" . $imagePath);
				$imagePath = '';
				}else{
					if ($width < $tbsize) {
					$tbsize=$width;
					}
					if($width==$height){
					$result = $this->createThumbnail($uploadDir . "org/" . $imagePath, $uploadDir . "tb/" . $imagePath, $tbsize);
					}else{
					$result = $this->square_crop($uploadDir . "org/" . $imagePath, $uploadDir . "tb/" . $imagePath, $tbsize); 
					}
					if (!$result) {
					// the product cannot be upload / resized
					unlink($uploadDir . "org/" . $imagePath);
					unlink($uploadDir . "med/" . $imagePath);
					$imagePath = '';
					}
				}
				
			}
			//echo "ok";
			}else{
			//echo "erro";
			$imagePath='';
			}
			
		}
	
		
		return array('image' => $imagePath);
	}
}