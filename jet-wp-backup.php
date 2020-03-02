<?php
/**
 * @package Triangle Web Tech Tools
 * @version 1.6
 */
/*
Plugin Name: JET WP Backup
Plugin URI: http://trianglewebtech.com/wordpress/plugins/
Description: Enables simple database and uploads backup for WordPress.
Author: Jonathan Tweedy
Version: 1.0
Author URI: http://jonathantweedy.com
*/

//require_once(realpath(dirname(__FILE__)) . '/../../../wp-blog-header.php');

//function jet_wp_backup( $atts, $templatecontent ){
//	return "";
//}
//add_shortcode( 'jet-wp-backup', 'jet_wp_backup' );


function jet_wp_backup_fun() {
	
	$dn = dirname(__FILE__);
	$dn = explode( "/", $dn );
	$dn = end($dn);
	$realpath = realpath(dirname(__FILE__));
	if (!file_exists($realpath."/../../uploads/backups/")) {  mkdir($realpath."/../../uploads/backups/"); }
	$burl = get_site_url() ."/wp-content/plugins/".$dn."/backup.php?secret=".$value;
	
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized user');
    }
    if (isset($_POST['jet_wp_backup_secret'])) {
		$secret = $_POST['jet_wp_backup_secret'];
		update_option('jet_wp_backup_secret', $secret);
    }
    $value = get_option('jet_wp_backup_secret', '');
	$json = json_encode($value);
	$burl = get_site_url() ."/wp-content/plugins/".$dn."/backup.php?secret=".$value;
	print "
		<h1>JET WP Backup</h1>
		<form method=\"POST\">
		<p>
			Secret: <a title=\"This value can be shared with a remote script that will ping the site regularly to run the backup, or this page can be visited directly. In either case, visit the URL generated below.\" href=\"javascript:alert('This value can be shared with a remote script that will ping the site regularly to run the backup, or this page can be visited directly. In either case, visit the URL generated below.');\">[?]</a><input name='jet_wp_backup_secret' id='jet_wp_backup_secret' value=\"".$value."\" onkeyup=\"changeJetWpBackupSecret();\" />
		</p>
		<p>
			Run a backup now: <a target='_blank' href='".$burl."' id='secret_url'>".$burl."</a>
		</p>
		<p>
			<input type='submit' value='Save' class='button button-primary button-large'>
		</p>
		</form>
		<script>
		function changeJetWpBackupSecret() {
			document.getElementById('secret_url').innerHTML = '".get_site_url() ."/wp-content/plugins/".$dn."/backup.php?secret=' + document.getElementById('jet_wp_backup_secret').value;
			document.getElementById('secret_url').href = '".get_site_url() ."/wp-content/plugins/".$dn."/backup.php?secret=' + document.getElementById('jet_wp_backup_secret').value;
			
		}
		</script>
	";
	$backups = scandir("../wp-content/uploads/backups");
	$backupdivs = "";
	foreach($backups as $backup) {
		if ($backup[0]!=".") {
			$backupdivs .= "<div id='backup'><a href='../wp-content/uploads/backups/$backup'>$backup</a></div>";
		}
	}
	if ($backupdivs!="") {
		print "
		<h3>Backups</h3>
		$backupdivs
		";
	}
	
}



add_action('admin_menu', 'jet_wp_backup_options_page_create');
function jet_wp_backup_options_page_create() {
    $page_title = 'JET WP Backup';
    $menu_title = 'JET WP Backup';
    $capability = 'edit_posts';
    $menu_slug = 'jet_wp_backup';
    $function = 'jet_wp_backup_fun';
    $icon_url = '';
    $position = 100;
    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
}

