<?php
require_once(__DIR__.'/../../../wp-blog-header.php');
require_once(__DIR__."/config.php");

header("Content-type:text/plain");
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
$sql = implode("\n", $queries);
print $sql;

?>