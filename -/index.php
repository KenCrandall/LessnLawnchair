<?php

include('config.php');
require_once("underscore.php");
require_once("Lawnchair.php");
include('stats.php');

$ppl = new Lawnchair( array("name"=>"urls","store"=>"file") );	//	urls table

define('LESSN_VERSION',	'2.0.7');

define('LESSN_DOMAIN', 	preg_replace('#^www\.#', '', $_SERVER['SERVER_NAME']));
define('LESSN_URL', 	str_replace('-/index.php', '', 'http://'.LESSN_DOMAIN.$_SERVER['PHP_SELF']));

define('BCURLS_DOMAIN', 	preg_replace('#^www\.#', '', $_SERVER['SERVER_NAME']));
define('BCURLS_URL', 	str_replace('-/index.php', '', 'http://'.BCURLS_DOMAIN.$_SERVER['PHP_SELF']));
define('BCURLS_PATH', 	realpath('.') );


define('COOKIE_NAME', 	DB_PREFIX.'auth');
define('COOKIE_VALUE',	md5(USERNAME.PASSWORD.COOKIE_SALT));
define('COOKIE_DOMAIN', '.'.LESSN_DOMAIN);

if (!defined('API_SALT')) define('API_SALT', 'L35sm4K35M0U7hSAP1'); // added in 1.0.5
define('API_KEY', md5(USERNAME.PASSWORD.API_SALT));

define('NOW', 		time());
define('YEAR',		365 * 24 * 60 * 60);

if (isset($_POST['username'])){
	if (md5($_POST['username'].$_POST['password'].COOKIE_SALT) == COOKIE_VALUE){
		setcookie(COOKIE_NAME, COOKIE_VALUE, NOW + YEAR, '/', COOKIE_DOMAIN);
		$_COOKIE[COOKIE_NAME] = COOKIE_VALUE;
	}
}else if (isset($_GET['api']) && $_GET['api'] == API_KEY){
	$_COOKIE[COOKIE_NAME] = COOKIE_VALUE;
}

function bcurls_find_banned_word($slug) {
	global $bcurls_banned_words;
	REQUIRE_ONCE 'banned_words.php';
	foreach ($bcurls_banned_words as $banned){
		$strpos = stripos($slug, $banned); 
		if ($strpos !== false) {
//			bc_log('Found banned word '.$banned.' in '.$slug);
			return strlen($slug) - strlen($banned) - $strpos;
		}
	}
	return FALSE; 
}

function bcurls_find_banned_glyph($slug) {
	if (ADDITIONAL_HOMOGLYPHS_TO_AVOID === false)
		return false;
	$glyphs = str_split(ADDITIONAL_HOMOGLYPHS_TO_AVOID);	
	foreach ($glyphs as $banned){
		$strpos = strpos($slug, $banned); 
		if ($strpos !== FALSE) {
//			bc_log('Found banned glyph '.$banned.' in '.$slug);
			return (strlen($slug) - 1 - $strpos);
		}
	}
	return FALSE; 
}

if (isset($_GET['logout'])){
	setcookie(COOKIE_NAME, '', NOW - YEAR, '/', COOKIE_DOMAIN);
	unset($_COOKIE[COOKIE_NAME]);
	header('Location:./');
}
if (!isset($_COOKIE[COOKIE_NAME]) || $_COOKIE[COOKIE_NAME] != COOKIE_VALUE){
	include('pages/login.php');
	exit();
}else if (!isset($_GET['api'])){
	setcookie(COOKIE_NAME, COOKIE_VALUE, NOW + YEAR, '/', COOKIE_DOMAIN);
}

$lastid = $ppl->lastid();
if( $lastid < 0 ){
	$lastid = 0;
}else{
	$lastid = $lastid + 1;	
}
//	echo $lastid;

// new shortcut
if (isset($_GET['url']) && !empty($_GET['url'])){
	$url = $_GET['url'];
	if (!preg_match('#^[^:]+://#', $url))
	{
		$url = 'http://'.$url;
	}
	$checksum 		= sprintf('%u', crc32($url));
	$escaped_url 	= addslashes($url);
	$good = 1;
	if( $list = $ppl->find(array("field"=>"url","q"=>$url,"a"=>"eq")) ){
		$good = 0;
		foreach($list as $k=>$v){
			$id = $k;
			break;
		}		
	}else if(isset($_GET['custom_url']) && strlen(trim($_GET['custom_url']))){
		$lastid = $custom_url = trim($_GET['custom_url']);
		if( $list = $ppl->find(array("field"=>"slug","q"=>$lastid,"a"=>"eq")) ){
			$good = 0;
			foreach($list as $k=>$v){
				$id = $k;
				break;
			}
		}
	}
	if( $good ){
		$ppl->save( array(
			"key"=>$lastid,
			"value"=>array(
				"slug"=>$custom_url,
				"url"=>$url,
				"lastupdated"=>time() 
			)
		));
		$id = $lastid;
	}
	if( $custom_url ){
		$new_url = LESSN_URL.$custom_url;
	}else{
		$new_url = LESSN_URL.base_convert($id, 10, 36);
	}
	if (isset($_GET['tweet'])){
		$_GET['redirect'] = 'http://twitter.com/?status=%l';
	}
	if (isset($_GET['redirect'])){
		header('Location:'.str_replace('%l', urlencode($new_url), $_GET['redirect']));
		exit();
	}
	if (isset($_GET['api'])){
		echo $new_url;
		exit();
	}
	include('pages/done.php');
}else if(isset($_GET['stats'])){
	$top_urls = stats_top_urls($db);
	$top_referers = stats_top_referers($db);
	$todays_urls = stats_todays_stats($db);
	$weeks_urls = stats_thisweeks_stats($db);
	$number_lessnd = stats_total_lessnd($db);
	$number_redirected = stats_total_redirects($db);
	include('pages/stats.php');
}else{
	include('pages/add.php');
}