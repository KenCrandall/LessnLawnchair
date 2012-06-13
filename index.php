<?php

include('-/config.php');
require_once("-/underscore.php");
require_once("-/Lawnchair.php");
include('-/stats.php');

$ppl = new Lawnchair( array("name"=>"urls","store"=>"file") );	//	urls table

// redirect
if (isset($_GET['token'])){
	@list($token, $ext) = explode('.', $_GET['token'], 2);
	if ( $row = $ppl->get( base_convert($token, 36, 10) ) ){
		record_stats($token);
		header($_SERVER['SERVER_PROTOCOL'].' 301 Moved Permanently');
		header('Location:'.stripslashes($row['url']));
		exit();
	}else if ( $row = $ppl->get( $token ) ){
		record_stats($token);
		header($_SERVER['SERVER_PROTOCOL'].' 301 Moved Permanently');
		header('Location:'.stripslashes($row['url']));
		exit();
	}
}
// no redirect
header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
header('Status:404');
die('404 Not Found');
exit;
