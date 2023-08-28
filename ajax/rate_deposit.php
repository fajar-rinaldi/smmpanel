<?php
// Script By Nur Rifqi Alhusaini
require_once '../mainconfig.php';
$pembayaran = $db->real_escape_string(e(@$_POST['pembayaran']));
$nominal = $db->real_escape_string(e(@$_POST['jumlah']));
$cek_rate = $db->query("SELECT * FROM metode_depo WHERE id = '$pembayaran'");
$cek_hasil = $cek_rate->fetch_array();
$menghitung = $nominal * $cek_hasil['rate'];
echo $menghitung;
?>