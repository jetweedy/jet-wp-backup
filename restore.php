<?php

require_once(__DIR__.'/../../../wp-blog-header.php');
require_once(__DIR__."/config.php");
$secret = $_GET['secret'];
$SECRET = get_option("jet_wp_backup_secret");
//print $secret . " | " . $SECRET . "<hr />";
//print(DB_NAME."|".DB_USER."|".DB_PASSWORD."|".DB_HOST);

header("Content-type:text/plain");

if (current_user_can('administrator')) {
		print_r($_FILES);
		if (isset($_FILES['restoreFile'])) {
			$zname = $_FILES['restoreFile']['name'];
			$fname = $bdir . "/" . str_replace(".zip", "", $zname);
			$tname = $_FILES['restoreFile']['tmp_name'];
			print $zname . " | " . $fname . " | " . $tname . "\n\n";
			$zip = new ZipArchive;
			if ($zip->open($tname) === TRUE) {
				$zip->extractTo($fname);
				$zip->close();
			}
		}
}