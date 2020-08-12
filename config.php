<?php

$realpath = realpath(dirname(__FILE__));
$dn = dirname(__FILE__);
$dn = explode( "/", $dn );
$dn = end($dn);
$ppath = get_site_url() ."/wp-content/plugins/".$dn;

//// -----------------------------------------------------------------
//// These can be tweaked
//// -----------------------------------------------------------------

//// Use a 'backups' folder in the plugin's directory
//$bdir = $realpath."/backups";
//$bpath = get_site_url() ."/wp-content/plugins/".$dn."/backups";
//$ppath = get_site_url() ."/wp-content/plugins/".$dn;

//// Use a 'jet-wp-backup' folder in the uploads directory (e.g. cloudapps)
$bdir = $realpath."/../../uploads/jet-wp-backup";
$bpath = get_site_url() ."/wp-content/uploads/jet-wp-backup";

//// -----------------------------------------------------------------

function dbQuery($query) {
	return mysqliQuery($query);
}
function mysqliQuery($query)
{
	$dbHost = DB_HOST;
	$dbDatabase = DB_NAME;
	$dbUsername = DB_USER;
	$dbPassword = DB_PASSWORD;
	$query = trim($query);
	$dbLink = mysqli_connect($dbHost, $dbUsername, $dbPassword) or die ('I cannot connect to the database.');
	mysqli_set_charset($dbLink, 'utf8');
	mysqli_select_db($dbLink,$dbDatabase);
	$q = array();
	$q['id'] = 0;
	
	if (strtolower(substr($query,0,6))=="select")
	{
		$q['qresults'] = mysqli_query($dbLink,$query);	
		$q['numrows'] = 0;
		if ($q['qresults']!=null)
		{
			$q['numrows'] = mysqli_num_rows($q['qresults']);
			while($r = mysqli_fetch_array($q['qresults'])) {
				$q['results'][] = $r;
			}
		}
	}
	else if (strtolower(substr($query,0,11))=="show tables")
	{
		$q['qresults'] = mysqli_query($dbLink,$query);	
		$q['numrows'] = 0;
		if ($q['qresults']!=null)
		{
			$q['numrows'] = mysqli_num_rows($q['qresults']);
			while($r = mysqli_fetch_array($q['qresults'])) {
				$q['results'][] = $r;
			}
		}
	}
	else if (strtolower(substr($query,0,12))=="show columns")
	{
		$q['qresults'] = mysqli_query($dbLink,$query);	
		$q['numrows'] = 0;
		if ($q['qresults']!=null)
		{
			$q['numrows'] = mysqli_num_rows($q['qresults']);
			while($r = mysqli_fetch_array($q['qresults'])) {
				$q['results'][] = $r;
			}
		}
	}
	else if (strtolower(substr($query,0,6))=="insert")
	{
		mysqli_query($dbLink,$query);
		$q['id'] = mysqli_insert_id($dbLink);
	}
	else if (strtolower(substr($query,0,6))=="update")
	{
		mysqli_query($dbLink,$query);
		$q['numrows'] = mysqli_affected_rows($dbLink);
	}
	else if (strtolower(substr($query,0,6))=="delete")
	{
		mysqli_query($dbLink,$query);
		$q['numrows'] = mysqli_affected_rows($dbLink);
	}
	else
	{
		mysqli_query($dbLink,$query);
	}
	
	mysqli_close($dbLink);
	return $q;
}