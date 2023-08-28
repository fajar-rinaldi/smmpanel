<?php
session_start();
require_once '../mainconfig.php';

if (!isset($_SESSION['user'])) {
   die("Anda Tidak Memiliki Akses!");
}
if (isset($_POST['tipe'])) {
	$post_tipe = $db->real_escape_string(e(@$_POST['tipe']));
	$cek_metode = $db->query("SELECT * FROM metode_depo WHERE tipe = '$post_tipe' AND provider != 'QRIS' AND status = 'ON' ORDER BY id ASC");
	?>
	<option value="0">- Select One -</option>
	<?php
	while ($data_metode = $cek_metode->fetch_assoc()) {
	?>
	<option value="<?php echo $data_metode['id'];?>"><?php echo $data_metode['nama'];?></option>
	<?php
	}
} else {
?>
<option value="0"></option>
<?php
}