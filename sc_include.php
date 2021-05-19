<?php
    if(!defined('YUNGSCF')) exit();
    require_once('sc_config.php');
    
    function toExit(){
        global $errortext;
        global $tmp;
        echo json_encode(Array('errmsg'=>$errortext, 'errno'=>$tmp[0]));
        exit();
        return false;
    }
    
    function getAuthorization(){
        $str = file_get_contents('cookie.txt');
        debugPrint($str);
        if(!preg_match("/BearerToken\s(.+?)\s/", $str, $result)) {
            return false;
        }
        debugPrint($result);
        return $result[1];
        
    }
    
    function debugPrint($myvar) {
        if(IS_DEBUG) print_r($myvar);
        return IS_DEBUG;
    }
    
    function doGet($url, $reqheader = null){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.79 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt'); 
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt'); 
        if($reqheader) curl_setopt($ch, CURLOPT_HTTPHEADER, $reqheader);
        $response = curl_exec($ch);
        $statuscode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        //spilt header and body
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        curl_close($ch);
        return Array($statuscode, $header, $body);
    }

    function doPost($url, $postdata, $reqheader = null){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.79 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt'); 
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt'); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        if($reqheader) curl_setopt($ch, CURLOPT_HTTPHEADER, $reqheader);
        $response = curl_exec($ch);
        $statuscode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        //spilt header and body
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        curl_close($ch);
        return Array($statuscode, $header, $body);
    }
    