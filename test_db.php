<?php

require_once 'system/Boot.php';
require_once 'app/Config/Paths.php';

$paths = new Config\Paths();
echo "writableDirectory: " . $paths->writableDirectory . "\n";
echo "realpath: " . realpath($paths->writableDirectory) . "\n";
define('WRITEPATH', realpath(rtrim($paths->writableDirectory, '\\/ ')) . DIRECTORY_SEPARATOR);
echo "WRITEPATH: " . WRITEPATH . "\n";

$dbPath = WRITEPATH . 'database.sqlite';
echo "dbPath: $dbPath\n";
$db = new SQLite3($dbPath);
echo "Database opened successfully\n";
$db->close();