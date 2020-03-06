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

/*
function use_admin_jquery() {
    wp_enqueue_script( 'admin-jquery', plugin_dir_url( __FILE__ ) . '/jet-wp-backup.js', array( 'jquery' ), '1.0.0', false );
}
add_action( 'admin_enqueue_scripts', 'use_admin_jquery' );
*/

require_once(__DIR__."/config.php");
function jet_wp_backup_fun() {

	global $realpath;
	global $bdir;
	global $bpath;
	global $ppath;
	
	if (!file_exists($bdir)) {  
		mkdir($bdir);
	}
	
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized user');
    }
    if (isset($_POST['jet_wp_backup_secret'])) {
		$secret = $_POST['jet_wp_backup_secret'];
		update_option('jet_wp_backup_secret', $secret);
    }
    $secret = get_option('jet_wp_backup_secret', '');
	$burl = $ppath."/backup.php?secret=";
	$durl = $ppath."/delete.php?delete=";
	print "
		<h1>JET WP Backup</h1>
		<form method=\"POST\">
		<p>
			Secret: <a title=\"This value can be shared with a remote script that will ping the site regularly to run the backup, or this page can be visited directly. In either case, visit the URL generated below.\" href=\"javascript:alert('This value can be shared with a remote script that will ping the site regularly to run the backup, or this page can be visited directly. In either case, visit the URL generated below.');\">[?]</a><input name='jet_wp_backup_secret' id='jet_wp_backup_secret' value=\"".$secret."\" onkeyup=\"changeJetWpBackupSecret();\" />
		</p>
		<p>
			<a href='javascript:runBackup();' id='secret_url'>Run a backup now.</a>
		</p>
		<p id=\"jetwpbackupnotification\" style='color:red;'>
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
		function deleteBackup(x) {
			if ( confirm('Are you absolutely sure you want to delete the backup \''+x+'\'?') ) {
				var durl = '".$durl."'+x;
				console.log(durl);
				jQuery.get(durl, function(x) {
					getBackups();
				});
			} else {
				alert('ok, phew...');
			}
		}
		function getBackups() {
			var gburl = \"".$ppath."/getbackups.php\";
			jQuery.get(gburl, function(x) {
				jQuery('#jetwpbackupslist').html('');
				var d = JSON.parse(x);
				for (var b in d) {
					var div = jQuery('<div>').addClass('backup');
					jQuery('#jetwpbackupslist').append(div);
					var a = jQuery('<a>').html('[X]').attr('href','javascript:deleteBackup(\''+d[b]+'\');');
					div.append(a);
					div.append(' ');
					var a = jQuery('<a>').html(d[b]).attr('href','".$bpath."/'+d[b]);
					div.append(a);
				}
			});
		}
		function runBackup() {
			var burl = '".$burl."'+document.getElementById('jet_wp_backup_secret').value;
			console.log(burl);
		//	window.open(burl);
			jQuery.get(burl, function(x) {
				console.log(x);
				jQuery('#jetwpbackupnotification').html('Backup is running... it will appear in the list when it is ready.');
				var d = JSON.parse(x);
				console.log(d);
			});
		}
		jQuery(document).ready( function() {
			setInterval(getBackups, 5000);
		});
		</script>
	";

	$backups = scandir($bdir);
	sort($backups);
	$backups = array_reverse($backups);
	$backupdivs = "";
	print "
	<form action=\"".$ppath."/restore.php\" method=\"post\" enctype=\"multipart/form-data\">
		Restore from Zip file:
		<input type=\"file\" name=\"restoreFile\" id=\"restoreFile\" />
		<input type=\"submit\" value=\"Restore\" name=\"submit\" />
	</form>
	<h3>Backups</h3><hr />
	";
	foreach($backups as $backup) {
		if ($backup[0]!=".") {
			$ext = explode(".",$backup);
			$ext = end($ext);
			$ext = strtolower($ext);
			$shversion = $bdir."/".str_replace(".zip",".sh",$backup);
			if ($ext=="zip") {
				if (file_exists($shversion)) {
					unlink($shversion);
				}
				$backupdivs .= "<div class='backup'><a href=\"javascript:deleteBackup('".$backup."');\">[X]</a> <a href='".$bpath."/$backup'>$backup</a></div>";
			}
		}
	}
	print "
	<div id=\"jetwpbackupslist\">$backupdivs</div>
	";
	
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

