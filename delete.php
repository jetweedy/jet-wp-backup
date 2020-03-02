<?php

$realpath = realpath(dirname(__FILE__));
require_once($realpath . '/../../../wp-blog-header.php');
$secret = $_GET['secret'];
$SECRET = get_option("jet_wp_backup_secret");
//print $secret . " | " . $SECRET . "<hr />";
//print(DB_NAME."|".DB_USER."|".DB_PASSWORD."|".DB_HOST);


if (current_user_can('administrator')) {

	$dn = dirname(__FILE__);
	$dn = explode( "/", $dn );
	$dn = end($dn);
	$realpath = realpath(dirname(__FILE__));
	$delete = $_GET['delete'];
	$delete = str_replace("/", "", $delete);
	$delete = str_replace("\\", "", $delete);
	$delete = str_replace("..", "", $delete);
	$delete = "../../uploads/jet_wp_backup/$delete";
	print $delete . "\n\n";
	if (file_exists($delete)) {
		unlink($delete);
	}
	
	print "
<script>
window.opener.location.reload();
window.close();
</script>
	";

} else{
	print "nope";
}
