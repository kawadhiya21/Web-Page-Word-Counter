<?php

$url = "http://www.google.com"; //enter the web address here

    
    $fileHeaders = @get_headers($url);
    if( $fileHeaders[0] == "HTTP/1.1 200 OK" || $fileHeaders[0] == "HTTP/1.0 200 OK")
    {
            $content = strip_html_tags(file_url_contents($url));
    }

function file_url_contents($url){
    $crl = curl_init();
    $timeout = 30;
    curl_setopt ($crl, CURLOPT_URL,$url);
    curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
    $ret = curl_exec($crl);
    curl_close($crl);
    return $ret;
} 


function strip_html_tags($str){
    $str = preg_replace('/(<|>)\1{2}/is', '', $str);
    $str = preg_replace(
        array(// Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            ),
        "", //replace above with nothing
        $str );
    $str = replaceWhitespace($str);
    $str = strip_tags($str);
    return $str;
} 

function replaceWhitespace($str) {
    $result = $str;
    foreach (array(
    "  ", " \t",  " \r",  " \n",
    "\t\t", "\t ", "\t\r", "\t\n",
    "\r\r", "\r ", "\r\t", "\r\n",
    "\n\n", "\n ", "\n\t", "\n\r",
    ) as $replacement) {
    $result = str_replace($replacement, $replacement[0], $result);
    }
    return $str !== $result ? replaceWhitespace($result) : $result;
}

$content = filter_var($content , FILTER_SANITIZE_STRING);
$content = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $content);
$wordcol = explode(' ', $content);

$j=0;
$countword=0;

foreach($wordcol as $word)
	{
		if($word != "**")
		{
		for ($i=0 ; $i<count($wordcol) ; $i++)
			{
			 if($word == $wordcol[$i])
				{
				$countword++;
				$wordcol[$i] = "**";
				}		
			}
		$final[$j]['word'] = $word;
		$final[$j]['count'] = $countword;
		$countword=0;	
		$j++;
		}
	}

foreach($final as $fin)
	{
	if($fin['count']!=0)
	{
	echo $fin['word']." --> ".$fin['count']."</br>";
	}
	}
?>

