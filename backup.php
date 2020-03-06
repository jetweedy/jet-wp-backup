<?php

require_once(__DIR__.'/../../../wp-blog-header.php');
require_once(__DIR__."/config.php");
$secret = $_GET['secret'];
$SECRET = get_option("jet_wp_backup_secret");
//print $secret . " | " . $SECRET . "<hr />";
//print(DB_NAME."|".DB_USER."|".DB_PASSWORD."|".DB_HOST);
//phpinfo(); die;

header("Content-type:text/plain");

$dn = dirname(__FILE__);
$dn = explode( "/", $dn );
$dn = end($dn);

if ($secret==$SECRET) {

	$ts = date("Y-m-d-h-i-s", time());
	$c = "
cd ".$bdir."
mysqldump --user=".DB_USER." --password=".DB_PASSWORD." --host=".DB_HOST." ".DB_NAME." > ".$bdir."/".$ts.".sql
";
	exec($c);

	$uploadsDir = $realpath."/../../";
	$uploadsStorage = $bdir;
	$bashfile = $uploadsStorage."/".$ts.".sh";

	$phpfile = $realpath."/backup_runner.php";
	$zipfile = $uploadsStorage."/".$ts.".zip";
	$x = "
php " . $phpfile . " " . $zipfile . " ".$uploadsDir."uploads/ ".$ts.".sql ".$bdir."/".$ts.".sql
";
//	print $x . "\n\n";
	$y = "nohup bash " . $bashfile . " >/dev/null 2>&1 &";
//	print $y . "\n\n";

	file_put_contents($bashfile, $x);
	exec($y);
	print "{\"ts\":\"$ts\"}";

/*	
	$c = "
cd ".$uploadsDir."
zip -r ".$uploadsStorage."/".$ts.".zip uploads
cd ".$uploadsStorage."
mysqldump --user=".DB_USER." --password=".DB_PASSWORD." --host=".DB_HOST." ".DB_NAME." > ".$ts.".sql
zip -g ".$ts.".zip ".$ts.".sql
rm -rf ".$ts.".sql
";

	$x = "nohup bash " . $uploadsStorage . "/" . $ts . ".sh >/dev/null 2>&1 &";
	print $c;
	print "\n\n";
	print $bashfile;
	print "\n\n";
	print $x;
	print "\n\n";
	print $ts;
	//file_put_contents($bashfile, $c);
	//exec($x);
*/

	// Execute the commands and print output
	//	print shell_exec($c) . "\n----------------------------\n";

	// Execute the commands
//		exec($c);
	
	// Print the commands
	//print "" . $c . "\n\n";

	// Serve up the ZIP file instead of printing output
//	$yourfile = $uploadsStorage."/".$ts.".zip";
//	$file_name = basename($yourfile);
//	header("Content-Type: application/zip");
//	header("Content-Disposition: attachment; filename=$file_name");
//	header("Content-Length: " . filesize($yourfile));
//	readfile($yourfile);
//	exit;
	
}