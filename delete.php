<?php

require_once(__DIR__.'/../../../wp-blog-header.php');
require_once(__DIR__."/config.php");
$secret = $_GET['secret'];
$SECRET = get_option("jet_wp_backup_secret");
//print $secret . " | " . $SECRET . "<hr />";
//print(DB_NAME."|".DB_USER."|".DB_PASSWORD."|".DB_HOST);

if (current_user_can('administrator')) {
	
	$delete = $_GET['delete'];
	$delete = str_replace("/", "", $delete);
	$delete = str_replace("\\", "", $delete);
	$delete = str_replace("..", "", $delete);
	$delete = $bdir."/$delete";
	print $delete . "\n\n";
	if (file_exists($delete)) {
		unlink($delete);
	}
//	print "<script>window.opener.location.reload();window.close();</script>";
} else{
	print "nope";
}
