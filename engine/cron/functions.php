<?php
function wikidefinition($s) {
    $url = "http://en.wikipedia.org/w/api.php?action=opensearch&search=".urlencode($s)."&format=xml&limit=1";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
    curl_setopt($ch, CURLOPT_POST, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_NOBODY, FALSE);
    curl_setopt($ch, CURLOPT_VERBOSE, FALSE);
    curl_setopt($ch, CURLOPT_REFERER, "");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; he; rv:1.9.2.8) Gecko/20100722 Firefox/3.6.8");
    $page = curl_exec($ch);
    $xml = simplexml_load_string($page);
    if((string)$xml->Section->Item->Description) {
        return array((string)$xml->Section->Item->Text, (string)$xml->Section->Item->Description, (string)$xml->Section->Item->Url);
    } else {
        return false;
    }
}

function get_web_page( $url, $conTimeout=10 ) 
{ 
	$userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';

    $options = array( 
        CURLOPT_RETURNTRANSFER => true,     // return web page 
        CURLOPT_HEADER         => false,    // return headers 
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects 
        CURLOPT_ENCODING       => "",       // handle all encodings 
        CURLOPT_USERAGENT      => $userAgent, // who am i 
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect 
        CURLOPT_CONNECTTIMEOUT => $conTimeout,      // timeout on connect 
        CURLOPT_TIMEOUT        => 30,      // timeout on response 
        CURLOPT_MAXREDIRS      => 5,       // stop after 5 redirects 
      	CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0

    ); 

    $ch      = curl_init( $url ); 
    curl_setopt_array( $ch, $options ); 
    $content = curl_exec( $ch ); 
    $err     = curl_errno( $ch ); 
    $errmsg  = curl_error( $ch ); 
    $header  = curl_getinfo( $ch ); 
    curl_close( $ch ); 

    $header['errno']   = $err; 
   	$header['errmsg']  = $errmsg; 
    $header['content'] = $content; 
    return $header; 
}