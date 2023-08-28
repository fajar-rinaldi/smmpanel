<?php
session_start();
require_once '../mainconfig.php';

if (!isset($_SESSION['user'])) {
   die("Anda Tidak Memiliki Akses!");
}
if (isset($_POST['layanan'])) {
	$post_layanan = $db->real_escape_string(e(@$_POST['layanan']));
	$cek_layanan = $db->query("SELECT * FROM layanan_sosmed WHERE service_id = '$post_layanan' AND status = 'Aktif'");
	if (mysqli_num_rows($cek_layanan) == 1) {
		$data_layanan = mysqli_fetch_assoc($cek_layanan);
		if ($data_user['level'] === "Reseller") {
		    $harga = $data_layanan['harga_api'];
		    } else if ($data_user['level'] === "Admin") {
		    $harga = $data_layanan['harga_api'];
		} else {
		    $harga = $data_layanan['harga'];
		}
		$result = $harga / 1000;
		echo $result;
	} else {
		die("0");
	}
} else {
	die("0");
}