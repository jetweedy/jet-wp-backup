<?php

$realpath = realpath(dirname(__FILE__));
require_once($realpath . '/../../../wp-blog-header.php');
$secret = $_GET['secret'];
$SECRET = get_option("jet_wp_backup_secret");
//print $secret . " | " . $SECRET . "<hr />";
//print(DB_NAME."|".DB_USER."|".DB_PASSWORD."|".DB_HOST);

header("Content-type:text/plain");

if ($secret==$SECRET) {

	$ts = date("Y-m-d-h-i-s", time());
	$databasePath = $realpath."/backups/".$ts."/database.sql";
	$uploadsPath = $realpath."/backups/".$ts."/uploads.zip";
	$uploadsDir = $realpath."/../../uploads";
	$c1 = "mkdir ./backups/".$ts;
	$c2 = "mysqldump --user=".DB_USER." --password=".DB_PASSWORD." --host=".DB_HOST." ".DB_NAME." > ".$databasePath;
	$c3 = "cd ".$uploadsDir."; zip -r ".$uploadsPath." *";
	$c4 = "cd ".$realpath."/backups/".$ts."; zip ./".$ts." database.sql uploads.zip";
	// $c5 = ... upload the zip to some remote server
	$c6 = "cd ".$realpath."/backups; rm -rf ".$ts."";

	// Print the commands
	print "" . $c1 . "\n\n";
	print "" . $c2 . "\n\n";
	print "" . $c3 . "\n\n";
	print "" . $c4 . "\n\n";
	print "" . $c6 . "\n\n";

	// Execute the commands and print output
//	print shell_exec($c1) . "\n----------------------------\n";
//	print shell_exec($c2) . "\n----------------------------\n";
//	print shell_exec($c3) . "\n----------------------------\n";
//	print shell_exec($c4) . "\n----------------------------\n";
//	print shell_exec($c6) . "\n----------------------------\n";

	// Execute the commands
//	exec($c1);
//	exec($c2);
//	exec($c3);
//	exec($c4);
//	exec($c6);
	
	

	
}
