<?php

ini_set('max_execution_time', 120); // 120 (seconds) = 2 Minutes
header("Content-type:text/plain");
$zipcreated = $argv[1];
$pathdir = $argv[2];
$sqlpath = $argv[3];
if ($pathdir[strlen($pathdir)-1]!="/") { $pathdir .= "/"; }
print "zipcreated: " . $zipcreated . "\n\n";
print "pathdir: " . $pathdir . "\n\n";
function zipme($source, $sqlpath, $destination)
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
    if (!$zip->open($destination, ZIPARCHIVE::CREATE | ZipArchive::OVERWRITE)) {
        echo "failed to create zip file on destination";
        return false;
    }
    $zip->addFromString("jet-wp-backup.sql", file_get_contents($sqlpath));
    $zip->addEmptyDir(str_replace($source . '/', '', 'uploads/'));
    $source = str_replace("\\", "/", realpath($source));
     if (is_dir($source) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source)
            , RecursiveIteratorIterator::SELF_FIRST
        //    , RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $name => $file) {
            if (str_replace("jet-wp-backup","",strtolower($name))!=strtolower($name))
                continue;
            if( in_array($name, array('.', '..')) )
                continue;
            // https://stackoverflow.com/questions/4914750/how-to-zip-a-whole-folder-using-php
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($source) - 7);
                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }
    } else if (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }
	unlink($sqlpath);
    return $zip->close();
}
zipme($pathdir, $sqlpath, $zipcreated);
?>