<?php

session_start();
require_once '../mainconfig.php';
// load('middleware', 'csrf');
load('middleware', 'user');

if (!isset($_SESSION['user'])) {
   die("Anda Tidak Memiliki Akses!");
} else {
   $update = $db->query("UPDATE users SET read_news = '1' WHERE username = '{$session}'");
}

?>