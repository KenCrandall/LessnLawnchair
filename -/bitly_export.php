<?php
/**
 * The bitlyKey assigned to your bit.ly account. (http://bit.ly/a/account)
 */
define('bitlyKey', 'YOUR_BITLY_ASSIGNED_KEY');

/**
 * The bitlyLogin assigned to your bit.ly account. (http://bit.ly/a/account)
 */
define('bitlyLogin' , 'YOUR_BITLY_LOGIN');

/**
 * The client_id assigned to your OAuth app. (http://bit.ly/a/account)
 */
define('bitly_clientid' , 'YOUR_BITLY_ASSIGNED_CLIENT_ID_FOR_OAUTH');

/**
 * The client_secret assigned to your OAuth app. (http://bit.ly/a/account)
 */
define('bitly_secret' , 'YOUR_BITLY_ASSIGNED_CLIENT_SECRET_FOR_OAUTH');

/** 
 * This is the URL you previously used with bitly...
 */
$old_url = "http://YOUR_OLD_URL.HERE/";

include("bitly.php");
require_once("Lawnchair.php");
$ppl = new Lawnchair( array("name"=>"urls","store"=>"file") );	//	urls table

$here = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];

//$here = "http://rogerstringer.com/assets/demos/bitly/ex.php";
?>
<html>
<body>
<a href="https://bit.ly/oauth/authorize?client_id=<?= bitly_clientid ?>&redirect_uri=<?=$here?>">Authorize giftabit</a>
<div>
<?php
	if( isset($_REQUEST['code']) ){
		$token = bitly_oauth_access_token($_REQUEST['code'], $here);
		$json = array();
		$results = bitly_link_history($token['access_token']);
		foreach($results as $row){
			$key = str_replace($old_url,"",$row['link']);
			$url = $row['url'];
			echo "Saving {$key} ----- {$key}....<hr />";
			$ppl->save( array(
				"key"=>$key,
				"value"=>array(
					"slug"=>$key,
					"url"=>$url,
					"lastupdated"=>time() 
				)
			));
		}
	}
?>
</div>
</body>
</html>