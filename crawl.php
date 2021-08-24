<?php

require_once(__DIR__."/db.php");

function insert($domain, $protocol){
	global $db;
	$sql="INSERT INTO tracker (domain, protocol, trackid) VALUES (\"$domain\",\"$protocol\",\"new\")";
	if(__DEBUG__) echo $sql, "\n";
	$r=$db->exec($sql);
	//if(!$r) echo $db->lastErrorMsg();
}

function update($domain, $trackid){
	global $db;
	$sql="UPDATE tracker SET trackid='$trackid' WHERE domain='$domain'";
	if(__DEBUG__) echo $sql, "\n";
	$r=$db->exec($sql);
	if(!$r) echo $db->lastErrorMsg();
}

function get_next_url(){
	global $db;
	$sql="SELECT * FROM tracker where trackid='new' limit 1";
	$r = $db->query($sql);
	return $r->fetchArray(SQLITE3_ASSOC);
}

function crawl(){
	$next_url = get_next_url();
	echo "Processing ", $next_url['protocol'], '://', $next_url['domain'], "\n";
	//var_dump($next_url);

	$cmd = "curl --connect-timeout 20 ". $next_url['protocol']."://".$next_url['domain'];
	//echo $cmd;

	$ctx = shell_exec($cmd);
	//echo $ctx;

	// get all urls
	preg_match_all('/(http:|https:)\/\/([^\'"\/\@\&\?\*\#\(\)]*)/', $ctx, $m);
	$all=array_unique($m[0]);
	//var_dump($all);
	foreach ($all as $one) {
		$a = explode('://',$one);
		//var_dump($a);

		if(strrpos($a[1], 'gov.cn')) continue;
		if(strrpos($a[1], 'baidu.com')) continue;
		if(strrpos($a[1], '360.cn')) continue;
		if(strrpos($a[1], 'google.com')) continue;
		if(strrpos($a[1], 'google.cn')) continue;
		if(strrpos($a[1], '1688.com')) continue;
		if(strrpos($a[1], 'sogou.com')) continue;
		if(strrpos($a[1], 'cnzz.com')) continue;
		if(strrpos($a[1], 'qq.com')) continue;

		insert($a[1],$a[0]);
	}

	// get bdtarckid
	$bdtrackid = 'bad';
	preg_match('/hm\.baidu\.com\/hm\.js\?([^\'"]*)/', $ctx, $m);
	if($m && $m[1]) $bdtrackid = $m[1];
	update($next_url['domain'], $bdtrackid);
}

//for ($i = 20; $i > 0; $i--)
while(1)	crawl();
