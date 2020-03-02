<?php

$realpath = realpath(dirname(__FILE__));
require_once($realpath . '/../../../wp-blog-header.php');
$secret = $_GET['secret'];
$SECRET = get_option("jet_wp_backup_secret");
//print $secret . " | " . $SECRET . "<hr />";
//print(DB_NAME."|".DB_USER."|".DB_PASSWORD."|".DB_HOST);

header("Content-type:text/plain");
//phpinfo(); die;

$dn = dirname(__FILE__);
$dn = explode( "/", $dn );
$dn = end($dn);
$realpath = realpath(dirname(__FILE__));
if (!file_exists($realpath."/../../uploads/backups/")) {  mkdir($realpath."/../../uploads/backups/"); }


if ($secret==$SECRET) {

	$ts = date("Y-m-d-h-i-s", time());
	$databasePath = $realpath."/backups/".$ts."/database.sql";
	$uploadsPath = $realpath."/backups/".$ts."/uploads.zip";
	$uploadsDir = $realpath."/../../";
	$uploadsStorage = $realpath."/../../uploads/backups";

$c = "
cd ".$realpath."
cd ".$uploadsDir."
zip -r ".$uploadsStorage."/".$ts.".zip uploads
cd ".$uploadsStorage."
mysqldump --user=".DB_USER." --password=".DB_PASSWORD." --host=".DB_HOST." ".DB_NAME." > ".$ts.".sql
zip -g ".$ts.".zip ".$ts.".sql
rm -rf ".$ts.".sql
";


	// Execute the commands and print output
	//	print shell_exec($c) . "\n----------------------------\n";

	// Execute the commands
	//	exec($c);
	
	// Print the commands
	print "" . $c . "\n\n";
	
    $yourfile = $uploadsStorage."/".$ts.".zip";
	print $yourfile;

/*
    $file_name = basename($yourfile);
    header("Content-Type: application/zip");
    header("Content-Disposition: attachment; filename=$file_name");
    header("Content-Length: " . filesize($yourfile));
    readfile($yourfile);
    exit;
*/
	
}
