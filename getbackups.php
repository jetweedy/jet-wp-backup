<?php

require_once(__DIR__.'/../../../wp-blog-header.php');
require_once(__DIR__."/config.php");
$secret = $_GET['secret'];
$SECRET = get_option("jet_wp_backup_secret");
//print $secret . " | " . $SECRET . "<hr />";
//print(DB_NAME."|".DB_USER."|".DB_PASSWORD."|".DB_HOST);


if (current_user_can('administrator')) {

	$files = [];
	$backups = scandir($bdir);
	sort($backups);
	$backups = array_reverse($backups);
	$backupdivs = "";
	foreach($backups as $backup) {
		if ($backup[0]!=".") {
			$ext = explode(".",$backup);
			$ext = end($ext);
			$ext = strtolower($ext);
			if ($ext=="zip") {
				$files[] = $backup;
			}
		}
	}
	print json_encode($files);

} else{
	print "{\"error\":\"No access!\"";
}
