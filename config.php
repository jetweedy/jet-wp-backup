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

