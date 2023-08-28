<?php
session_start();
require_once '../mainconfig.php';

if (!isset($_SESSION['user'])) {
   die("Anda Tidak Memiliki Akses!");
}

function reffil($s) {
    if ($s === "1") {
        return '<span class="text-success small"><i class="fas fa-check-circle"></i> <b>Refill Button</b></span>';
    } else {
        return '<span class="text-danger small"><i class="fas fa-times-circle"></i> <b>Refill Button</b></span>';
    }
}	

if (isset($_POST['layanan'])) {
	$post_layanan = $db->real_escape_string(e(@$_POST['layanan']));
	$cek_layanan = $db->query("SELECT * FROM layanan_sosmed WHERE service_id = '{$post_layanan}' AND status = 'Aktif'");
	if (mysqli_num_rows($cek_layanan) == 1) {
		$data_layanan = mysqli_fetch_assoc($cek_layanan);
		if ($data_user['level'] === "Reseller") {
		    $harga = $data_layanan['harga_api'];
		} else if ($data_user['level'] === "Admin") {
		    $harga = $data_layanan['harga_api'];
		} else {
		    $harga = $data_layanan['harga'];
		}
	
	?>
<p class="text-right"><?= reffil($data_layanan['refill']) ?></p>
<div class="alert alert-primary mt-1" role="alert">
  <!----  <h4 class="alert-heading"><b>Deskripsi Layanan</b><br /></h4>---!>
    <div class="alert-body">
        <p><b>Deskripsi</b></p>
        <p><?= $data_layanan['catatan'] ?></p>
        <hr />
        <p>Harga/K: Rp <?= number_format($harga,0,',','.') ?></p>
        <p>Minimal: <?= number_format($data_layanan['min'],0,',','.') ?></p>
        <p>Maksimal: <?= number_format($data_layanan['max'],0,',','.') ?></p>
    </div>
</div>
<?php
	} else {
	?>
<div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <i class="mdi mdi-block-helper"></i>
    <b>Gagal :</b> Service Tidak Ditemukan
</div>
<?php
	}
       } else {
?>
<div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <i class="mdi mdi-block-helper"></i>
    <b>Gagal : </b> Terjadi Kesalahan.
</div>

<?php
}

