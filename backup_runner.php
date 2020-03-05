<?php

ini_set('max_execution_time', 120); // 120 (seconds) = 2 Minutes
header("Content-type:text/plain");
$zipcreated = $argv[1];
$pathdir = $argv[2];
if ($pathdir[strlen($pathdir)-1]!="/") { $pathdir .= "/"; }
print "zipcreated: " . $zipcreated . "\n\n";
print "pathdir: " . $pathdir . "\n\n";

//// ---------------------------------------------------------------------------
//// http://lampjs.com/php-create-zip-from-directory-recursively/
//// ---------------------------------------------------------------------------
function zipme($source, $destination)
{
    if (!file_exists($source)) {
        echo "source doesn't exist";
        return false;
    } 
    if (!extension_loaded('zip')) {
        echo "zip extension not loaded in php";
        return false;
    }
    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        echo "failed to create zip file on destination";
        return false;
    }
    $source = str_replace("\\", "/", realpath($source));
     if (is_dir($source) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($files as $file) {
            $file = str_replace("\\", "/", $file);
            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;
            $file = realpath($file);
            if (is_dir($file) === true) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } else if (is_file($file) === true) {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    } else if (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }
    return $zip->close();
}
//// ---------------------------------------------------------------------------
zipme($pathdir, $zipcreated);

?>