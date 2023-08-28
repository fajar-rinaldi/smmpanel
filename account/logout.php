<?php
session_start();
require_once '../mainconfig.php';

session_destroy();
$insert_user = $db->query("UPDATE users SET cookie_token = '0' WHERE username = '{$session}'");
if ($insert_user == TRUE) {
exit(header("Location: ".base_url('/auth/login')));
}

  
