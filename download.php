<?php

$db = new mysqli(getenv('DB_HOST'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'));

if(isset($_GET['id'])) {
	$safe_id = $db->real_escape_string($_GET['id']);
	$id = str_replace('/','',$_GET['id']);
} else {
	$safe_id = '';
}

$sql = "update `posts` set downloads = downloads + 1 WHERE bsg = 'uploads/" . $safe_id . "';";

$db->query($sql);

$file_url = 'http://s3.besiegedownloads.com' . '/uploads/' . $id;
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
if(ini_get('zlib.output_compression')) {
	ini_set('zlib.output_compression', 'Off');
}
header('Accept-Ranges: bytes');
header('Cache-control: no-cache, pre-check=0, post-check=0');
header('Cache-control: private');
header('Pragma: private');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // any date in the past
header('Content-disposition: attachment; filename="' . substr(basename($file_url), 13) . '"');
readfile($file_url); // do the double-download-dance (dirty but worky)
exit;
