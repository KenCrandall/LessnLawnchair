<?php

function record_stats($token) {
	$stats = new Lawnchair( array("name"=>"url_stats","store"=>"file") );	//	urls table
	$stats->save( array(
		"value"=>array(
			"token"=>$token,
			"ip_address"=>$_SERVER['REMOTE_ADDR'],
			"referer"=>isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null,
			"lastupdated"=>date('Y-m-d H:i:s')
		)
	));
}

function stats_top_urls($db, $count=25) {
/*
	$stmt = $db->query('SELECT u.id,u.url,u.custom_url,COUNT(s.url_id) as hits FROM '.DB_PREFIX.'urls u LEFT JOIN '.DB_PREFIX.'url_stats s ON u.id = s.url_id GROUP BY u.id,u.url ORDER BY hits desc LIMIT '.$count);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
*/
	$stats = new Lawnchair( array("name"=>"url_stats","store"=>"file") );	//	urls table
	$list = $stats->all();
	$hits = array();
	foreach($list as $v){
		$hits[ $v['token'] ]++;
	}
	ksort($hits);
	arsort($hits);
	$hits = array_slice($hits,0,$count);
	$list = array();
	foreach($hits as $k=>$v){
			
	}
	return $list;
}

function stats_top_referers($db, $count=10) {
/*
	$query = 'SELECT s.referer,COUNT(s.referer) as hits FROM '.DB_PREFIX.'url_stats s WHERE s.referer IS NOT NULL GROUP BY s.referer ORDER BY hits DESC LIMIT '.$count;
	$stmt = $db->query($query);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $rows;
*/
	$stats = new Lawnchair( array("name"=>"url_stats","store"=>"file") );	//	urls table
	$list = $stats->all();
	$hits = array();
	foreach($list as $v){
		$hits[ $v['referer'] ]++;
	}
	ksort($hits);
	arsort($hits);
	$hits = array_slice($hits,0,$count);
	return $hits;
}

function stats_todays_stats($db, $count=false, $floor=0) {
/*
	if ( $offset = stats_get_UTC_offset() ) {
		$db->exec('SET time_zone = "'. $offset .'";');
	}
	$limit = ($count) ? " LIMIT {$count} " : '';
	$query = 'SELECT url_id, urls.url, urls.custom_url, COUNT(url_id) as hits FROM '. DB_PREFIX . 'url_stats LEFT JOIN '. DB_PREFIX .'urls as urls on ( urls.id = url_id ) WHERE DATE(created_on) = DATE(NOW()) GROUP BY url_id HAVING COUNT(url_id) > '. $floor .' ORDER BY hits DESC' . $limit;
	$stmt = $db->query($query);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $rows;
*/
}

function stats_thisweeks_stats($db, $count=false, $floor=0) {
/*
	if ( $offset = stats_get_UTC_offset() ) {
		$db->exec('SET time_zone = "'. $offset .'";');
	}
	$limit = ($count) ? " LIMIT {$count} " : '';
	$query = 'SELECT url_id, urls.url, urls.custom_url, COUNT(url_id) as hits FROM '. DB_PREFIX . 'url_stats LEFT JOIN '. DB_PREFIX .'urls as urls on ( urls.id = url_id ) WHERE WEEK(created_on) = WEEK(NOW()) GROUP BY url_id HAVING COUNT(url_id) > '. $floor .' ORDER BY hits DESC' . $limit;
	
	$stmt = $db->query($query);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $rows;
*/
}

function stats_total_lessnd($db) {
/*
	$query = 'SELECT COUNT(id) as num FROM '. DB_PREFIX .'urls ';
	$stmt = $db->query($query);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return ( isset($rows[0]['num']) ) ? $rows[0]['num'] : 0;
*/
}

function stats_total_redirects($db) {
/*
	$query = 'SELECT COUNT(*) as hits from '. DB_PREFIX .'url_stats';
	$stmt = $db->query($query);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return ( isset($rows[0]['hits']) ) ? $rows[0]['hits'] : 0;
*/
}

function stats_get_UTC_offset() {
	if ( ! defined('TIMEZONE') || TIMEZONE === "" ) {
		return false;
	}
	date_default_timezone_set(TIMEZONE);
	return date('P');
}

/* put a span around the protocol */
function stats_display_url($url) {
	$url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
	echo preg_replace( '/^https?:\/\//', '<span>$0</span>', $url );
}