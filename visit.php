<?php

function baiduVisit($bdId, $targetUrl, $referer = 'yantuz.cn', $visitTimes=1){
    $bdjs = 'https://hm.baidu.com/hm.js?';
    $bdgif = 'https://hm.baidu.com/hm.gif?';
    //$bdId = '65e1c6689693082cffb3b7e1f2d8027f';

    $arr=array(
        'cc'=> '0',
        'ck'=> '1',
        'cl'=>'24-bit',
        'ds'=> '1440x900',
        'vl'=> '747',
        #'ep'=> '6346,2301',#时间
        'et'=> '0', #3
        'fl'=> '29.0',
        'ja'=> '0',
        'ln'=> 'zh-cn',
        'lo'=> '0',
        'lt'=> time(),
        'rnd'=> rand(1000000000,2000000000), #random
        'si'=> $bdId,
        'v'=> '1.2.30',
        'lv'=> '3',
        'sn'=> '25573',#25581
        'su'=> $referer, #请求来源

        #'ct'=> '!!',
        #'tt'=> '页面标题'
    );
    //echo http_build_query($arr);

    // Create a stream
    $opts = array(
        'http' => array(
            'method'=>"GET",
            'header'=>"Accept-language: cn\r\n"."referer:$targetUrl",
            'timeout'=>3,
        )//,  
       // "ssl" => [
      //  "verify_peer"=>false,
      //  "verify_peer_name"=>false,
      //  ]
    );
    $context = stream_context_create($opts);
 
    $url1 = $bdjs.$bdId;
    //echo $url1.'<br />';
    $arr1 = array_merge($arr,array('ep'=> '2302,153','u'=> $referer));
    $arr1['et'] = 3;
    $url2 = $bdgif.http_build_query($arr1);
    //echo $url2.'<br />';
    $arr2 = array_merge($arr,array('ct'=> '!!','tt'=> 'title'));
    $url3 = $bdgif.http_build_query($arr2);
    //echo $url3.'<br />';

    for ($x=0; $x<$visitTimes; $x++) {
        file_get_contents($url1, false, $context);
        file_get_contents($url2, false, $context);
        file_get_contents($url3, false, $context);
    }
}

baiduVisit('bc24c40486ac78730f85549d164dac6f','https://zhwiki.netlify.app/','https://t66y.cf2',3);