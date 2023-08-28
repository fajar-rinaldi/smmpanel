<?php
require_once '../../mainconfig.php';

if (isset($_POST['tipe'])) {
	$post_tipe = $db->real_escape_string(e(@$_POST['tipe']));
	$cek_layanan = $db->query("SELECT * FROM kategori_layanan WHERE tipe = '$post_tipe' ORDER BY nama ASC");
	?>
	<option value="0">- Select One -</option>
	<?php
	while ($data_layanan = $cek_layanan->fetch_assoc()) {
	?>
	<option value="<?php echo $data_layanan['kode'];?>"><?php echo $data_layanan['nama'];?></option>
	<?php
	}
} else {
?>
<option value="0">Error.</option>
<?php
}