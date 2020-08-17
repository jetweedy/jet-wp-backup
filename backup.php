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
	//////// Generate SQL file
	//// Using mysqldump:
	/*
	$c = "
cd ".$bdir."
mysqldump --user=".DB_USER." --password=".DB_PASSWORD." --host=".DB_HOST." ".DB_NAME." > ".$bdir."/".$ts.".sql
";
	exec($c);
	*/
	//// Using PHP:
	$queries = [];
	$q = dbQuery("SHOW TABLES");
	foreach($q['results'] as $r) {
		$table = $r[0];
		$queries[] = "DELETE FROM " . $table . ";";
		if (substr($table, 0, 3)=="wp_") {
			$q2 = dbQuery("SHOW COLUMNS FROM " . $table);
			$cols = [];
			foreach($q2['results'] as $r2) {
				$cols[] = $r2[0];
			}
			$query3 = "SELECT * FROM " . $table;
			$q3 = dbQuery($query3);
			if ($q3['numrows']>0) {		
				foreach($q3['results'] as $r) {
					$vals = [];
					foreach($cols as $col) {
						$val = $r[$col];
						$val = str_replace("'","\'",$val);
						$vals[] = $val;
					}
					$queries[] = "INSERT INTO $table (".implode(", ",$cols).") VALUES ('".implode("', '",$vals)."');";
				}
			}
		}
	}
	$sql = implode("\n-- [SEPARATOR]\n", $queries);
	file_put_contents($bdir."/".$ts.".sql", $sql);
	// Zip everything up
	$uploadsDir = $realpath."/../../";
	$uploadsStorage = $bdir;
	$bashfile = $uploadsStorage."/".$ts.".sh";
	$phpfile = $realpath."/backup_runner.php";
	$zipfile = $uploadsStorage."/".$ts.".zip";
	$x = "
php " . $phpfile . " " . $zipfile . " ".$uploadsDir."uploads/ ".$bdir."/".$ts.".sql
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