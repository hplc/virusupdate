<?php

$url_content = "http://free.grisoft.com/doc/24/us/frt/0";

$tmp = getPage($url_content);
// echo $tmp;

preg_match_all("/\/softw\/70free\/update\/[0-9a-z]+\.bin/", $tmp, $links);
// print_r($links);

$links = $links[0];

foreach ($links as $link) {
	$link = "http://free.grisoft.com" . $link . "\n";
	$content .= $link; 
}

$fd = fopen("targets.url", "w+");
fputs($fd, $content);
fclose($fd);


function getPage($url, $cookie_file = "cookie.txt") {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); 
        curl_setopt($ch, CURLOPT_PROXY, "192.168.1.8:8080");
	$tmp = curl_exec($ch);
	curl_close($ch);
	return $tmp;
	// return iconv("UTF-8", "GB18030", $tmp);
	// return iconv("UTF-8", "GBK", $tmp);
}

function postPage($posturl, $postfields, $cookie_file = "cookie.txt") {
	$ch =curl_init();
	curl_setopt($ch, CURLOPT_URL, $posturl);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); 
	$tmp = curl_exec($ch);
	curl_close($ch);
	return $tmp;
}

function cut_str($string, $sublen, $start = 0, $code = 'UTF-8') {
	if($code == 'UTF-8') {
		$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
		preg_match_all($pa, $string, $t_string);

		if(count($t_string[0]) - $start > $sublen) 
			return join('', array_slice($t_string[0], $start, $sublen))."...";
		return join('', array_slice($t_string[0], $start, $sublen));
	}
	else {
		$start = $start*2;
		$sublen = $sublen*2;
		$strlen = strlen($string);
		$tmpstr = '';
		for($i=0; $i<$strlen; $i++) {
			if($i>=$start && $i<($start+$sublen)) {
				if(ord(substr($string, $i, 1))>129) 
					$tmpstr.= substr($string, $i, 2);
				else 
					$tmpstr.= substr($string, $i, 1);
			}
			if(ord(substr($string, $i, 1))>129) 
				$i++;
		}
		if(strlen($tmpstr)<$strlen ) 
			$tmpstr.= "...";
		return $tmpstr;
	}
}

?>
