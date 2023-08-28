<?php
session_start();
require_once '../mainconfig.php';
if (!isset($_SESSION['user'])) {
   die("Anda Tidak Memiliki Akses!");
}
if (isset($_POST['provider'])) {
	$cek = $db->real_escape_string(e(@$_POST['provider']));
	$cek_db = $db->query("SELECT * FROM metode_depo WHERE id = '$cek'");
	if (mysqli_num_rows($cek_db) == 1) {
		$qDb = mysqli_fetch_assoc($cek_db);
	?>
                   <div class="form-group">
                        <label class="col-md-12 control-label"><b>Nominal</b></label>
                        <div class="col-md-12">
                            <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="" required>
                            <small class="text-danger"><b>Minimal: <?= number_format($qDb['min'],0,',','.'); ?></b></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12 control-label"><b>No Pengirim/Rek</b></label>
                        <div class="col-md-12">
                            <input type="number" name="pengirim" id="pengirim" class="form-control" placeholder="">
                        </div>
                    </div>
	<?php
	} else {
	?>
	
<div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert">
<i class="mdi mdi-block-helper"></i>
<b>Gagal :</b>Metode Deposit Tidak Ditemukan
	</div>
	<?php
	}
     } else {
        ?>
<?php
}
