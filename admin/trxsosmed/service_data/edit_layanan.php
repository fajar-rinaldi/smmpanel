<?php
require_once '../../../mainconfig.php';
$check_provider = $db->query("SELECT * FROM provider ORDER BY id DESC");
$check_category = $db->query("SELECT * FROM kategori_layanan ORDER BY id DESC");

$id = $_GET['id'];
if(!isset($id))
    die('No direct script access allowed!');
$query = mysqli_query($db, "SELECT * FROM layanan_sosmed WHERE id = '$id'");
$q = mysqli_fetch_assoc($query); 
?>

<form method="POST">
<div class="form-group">
        <label>ID</label>
        <input type="text" class="form-control" name="pid" value="<?= $q['id']; ?>" readonly>
    </div>
<div class="form-group">
        <label>Layanan</label>
        <input type="text" class="form-control" name="layanan" value="<?= $q['layanan']; ?>">
    </div>
<div class="form-group">
        <label>Note</label>
        <textarea class="form-control" name="note" rows="5"><?= $q['catatan']; ?></textarea>
    </div>
    <div class="form-group">
        <label>Harga Web/K</label>
        <input type="number" class="form-control" name="harga" value="<?= $q['harga']; ?>">
    </div>
    <div class="form-group">
        <label>Harga API/K</label>
        <input type="text" class="form-control" name="harga_api" value="<?= $q['harga_api']; ?>">
    </div>
    <div class="form-group">
        <label>Profit Web</label>
        <input type="text" class="form-control" value="<?= $q['harga'] - $q['profit']; ?>" readonly>
        <small class="text-danger">*Ini hitung profit harga website.</small>
    </div>
    <div class="form-group">
        <label>Profit API/Ress</label>
        <input type="text" class="form-control" value="<?= $q['harga_api'] - $q['profit']; ?>" readonly>
        <small class="text-danger">*Ini hitung profit harga API.</small>
    </div>
    <div class="form-group">
        <label>Status</label>
        <select class="form-control" name="status">
            <option value="Aktif" <?= $q['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
            <option value="Tidak Aktif" <?= $q['status'] == 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
    </select>
    </div>
     <div class="form-group">
                <label>Provider</label>
                <select class="form-control" name="provider">
                    <option value="">- Select One -</option>
                    <?php while ($prv = $check_provider->fetch_assoc()) { ?>
                    <option value="<?= e($prv['code']) ?>"><?= e($prv['code']) ?></option>
                    <?php } ?>
                </select>
                <small class="text-danger">*Now: <?= $q['provider']; ?></small>
            </div>
        <div class="form-group">
        <label>Category</label>
        <select class="form-control" name="kategori">
				<option value="0">- Select One -</option>
				
	<?php while ($data_category = $check_category->fetch_assoc()) { ?>
	<option value="<?= $data_category['nama']; ?>"><?= $data_category['nama']; ?></option>	
	<?php } ?>
			</select>
				<small class="text-danger">*Now: <?= $q['kategori']; ?></small>
    </div>
    <hr>
    <div class="form-group">
        <div class="row">
            <div class="col-6">
                <button type="button" data-dismiss="modal" class="btn btn-block text-white bg-danger fw-bold">BACK</button>
            </div>
            <div class="col-6">
                <button type="submit" name="edit_layanan" class="btn btn-block text-white bg-primary fw-bold">CHANGE</button>
            </div>
        </div>
    </div>
</form>
