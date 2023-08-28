<?php
session_start();
require_once '../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if (!isset($_POST['kategori'])) {
		exit("No direct script access allowed!");
	}
if (isset($_POST['kategori'])) {
	$post_kategori = $db->real_escape_string(e(@$_POST['kategori']));
	$cek_layanan = $db->query("SELECT * FROM layanan_sosmed WHERE kategori = '{$post_kategori}' AND status = 'Aktif' ORDER BY harga ASC");
	?>
	<option value="0">Pilih Salah Satu...</option>
	<?php
	while ($data_layanan = mysqli_fetch_assoc($cek_layanan)) {
	?>
	<option value="<?php echo $data_layanan['service_id'];?>"><?php echo $data_layanan['layanan'];?></option>
	<?php
	}
} else {
?>
<option value="0">Error.</option>
<?php
}
} else {
	exit("No direct script access allowed!");
}