<?php
    define('YUNGSCF', true);
    require_once('sc_include.php');
    $rname = 'YungAncestor';
    
    $tmp = doGet("https://socialclub.rockstargames.com/member/YungAncestor/");
    if(!$tmp){
        $errortext = 'request failed(1).';
        toExit();
    }
    debugprint($tmp);
    
    if(!strpos($tmp[2], RID_SELF)){
        $errortext = 'not login';
        $tmp = doPost('https://signin.rockstargames.com/api/connect/check/socialclub', 
            '{"fingerprint":"{\"fp\":{\"user_agent\":\"e6244ef379b377ed3fcac7d252ad9ba9\",\"language\":\"en-US\",\"pixel_ratio\":1.25,\"timezone_offset\":-480,\"session_storage\":1,\"local_storage\":1,\"indexed_db\":1,\"open_database\":1,\"cpu_class\":\"unknown\",\"navigator_platform\":\"Win32\",\"do_not_track\":\"1\",\"regular_plugins\":\"5ca1a49d3b10aee42dd81dd25ee4b1f1\",\"canvas\":\"c47b34d2b98fef4d3a2f8eba8500b9ce\",\"webgl\":\"62949d72373f2826959821a873c0e956\",\"adblock\":false,\"has_lied_os\":false,\"touch_support\":\"0;false;false\",\"device_name\":\"Chrome on Windows\",\"js_fonts\":\"771805ee36027aeaa63c4a4b354eb92a\"}}","returnUrl":"/Blocker/AuthCheck"}', 
            Array('content-type: application/json', 
                'Origin: https://signin.rockstargames.com', 
                'Sec-Fetch-Mode: cors', 
                'Sec-Fetch-Site: same-origin', 
                'x-requested-with: XMLHttpRequest'));
        if(!$tmp){
            $errortext = 'request failed while refresh login credentials(1).';
            toExit();
        }
        debugprint($tmp);
        $next_url=json_decode($tmp[2], true);
        
        if(!isset($next_url['redirectUrl'])){
            $errortext = 'redirectUrl not found. reason:'.$tmp[2];
            toExit();
        }
        $tmp = doGet($next_url['redirectUrl']);
        if(!$tmp){
            $errortext = 'request failed while refresh login credentials(2).';
            toExit();
        }
        debugprint($tmp);
        if(!strpos($tmp[2], 'authRockstarId')){
            $errortext = 'refresh login credentials failed!';
            toExit();
        }
    }
    
    if(!$bearer = getAuthorization()){
        $errortext = 'BearerToken not found.';
        toExit();
    }
    // Find RID!
    $tmp = doGet("https://scapi.rockstargames.com/profile/getprofile?nickname={$rname}&maxFriends=3", 
        Array(
        "Authorization: Bearer {$bearer}",
        'Origin: https://socialclub.rockstargames.com',
        'Referer: https://socialclub.rockstargames.com/',
        'X-Requested-With: XMLHttpRequest'
    ));
    debugprint($tmp);
    if(!$tmp){
        $errortext = 'getprofile failed.';
        toExit();
    }
    $ridresult = json_decode($tmp[2], true);
    if(!isset($ridresult['accounts'])) {
        $errortext = 'rockstarAccount not found. reason:'.$tmp[2];
        toExit();
    }
    $errortext = "Name:{$ridresult['accounts'][0]['rockstarAccount']['name']}<br>RID:{$ridresult['accounts'][0]['rockstarAccount']['rockstarId']}";
    toExit();
    
    
    
    
    
