<?php

$realpath = realpath(dirname(__FILE__));
require_once($realpath . '/../../../wp-blog-header.php');
$secret = $_GET['secret'];
$SECRET = get_option("jet_wp_backup_secret");
//print $secret . " | " . $SECRET . "<hr />";
//print(DB_NAME."|".DB_USER."|".DB_PASSWORD."|".DB_HOST);

header("Content-type:text/plain");

$dn = dirname(__FILE__);
$dn = explode( "/", $dn );
$dn = end($dn);
$realpath = realpath(dirname(__FILE__));
if (!file_exists($realpath."/../../uploads/backups/")) {  mkdir($realpath."/../../uploads/backups/"); }

if ($secret==$SECRET) {
	
}
