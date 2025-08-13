<?php

// Basic site settings 
if (!defined('UPLOAD_PATH')) {
define('UPLOAD_PATH', 'img/uploads/');  

define ('HOST', 'localhost');
define ('USER', 'root');
define ('PASS', '');
define ('DB', 'skripsi-warkop');
}

$koneksi = mysqli_connect(HOST, USER, PASS, DB) or die ('unable to connect');

?>