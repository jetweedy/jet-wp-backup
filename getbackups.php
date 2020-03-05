<?php

$realpath = realpath(dirname(__FILE__));
require_once($realpath . '/../../../wp-blog-header.php');
$secret = $_GET['secret'];
$SECRET = get_option("jet_wp_backup_secret");
//print $secret . " | " . $SECRET . "<hr />";
//print(DB_NAME."|".DB_USER."|".DB_PASSWORD."|".DB_HOST);


if (current_user_can('administrator')) {

	$files = [];
	$backups = scandir("./backups");
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
