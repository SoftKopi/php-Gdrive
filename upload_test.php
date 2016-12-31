<?php
include_once __DIR__ . "/vendor/autoload.php";
include_once "authorization.php";
 
$fileId = '0BxdFR4K6P8E3eUZtdkdzSnBVSmc';
$content = $driveService->files->get($fileId, array(
  'alt' => 'media' ));

$outHandle = fopen(__DIR__ . "/downloads/temp.jpg", "w+");

// Until we have reached the EOF, read 1024 bytes at a time and write to the output file handle.

while (!$content->getBody()->eof()) {
        fwrite($outHandle, $content->getBody()->read(1024));
}

// Close output file handle.

fclose($outHandle);

?>