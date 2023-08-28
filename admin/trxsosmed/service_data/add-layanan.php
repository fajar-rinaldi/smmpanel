<?php
require_once '../../../mainconfig.php';
$check_data = $db->query("SELECT * FROM provider ORDER BY id DESC");
$check_category = $db->query("SELECT * FROM kategori_layanan ORDER BY id DESC");
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') { ?>
<form method="POST">
<div class="form-group">
        <label>Provider</label>
        <select class="form-control" name="provider">
				<option value="0">- Select One -</option>
				
	<?php while ($data_provider = $check_data->fetch_assoc()) { ?>
	<option value="<?= $data_provider['code']; ?>"><?= $data_provider['code']; ?></option>
	<?php } ?>
			</select>
    </div>
    <div class="form-group">
        <label>Category</label>
        <select class="form-control" name="kategori">
				<option value="0">- Select One -</option>
				
	<?php while ($data_category = $check_category->fetch_assoc()) { ?>
	<option value="<?= $data_category['nama']; ?>"><?= $data_category['nama']; ?></option>
	<?php } ?>
			</select>
    </div>
<div class="form-group">
        <label>Layanan</label>
        <input type="text" class="form-control" name="layanan">
    </div>
    <div class="form-group">
        <label>Note</label>
        <textarea class="form-control" name="note" rows="5"></textarea>
    </div>
    <div class="form-group">
        <label>Min</label>
        <input type="number" class="form-control" name="min">
    </div>
    <div class="form-group">
        <label>Max</label>
        <input type="number" class="form-control" name="max">
    </div>
    <div class="form-group">
        <label>Harga Web/K</label>
        <input type="number" class="form-control" name="harga_web">
    </div>
    <div class="form-group">
        <label>Harga API/K</label>
        <input type="number" class="form-control" name="harga_api">
    </div>
    <div class="form-group">
        <label>Profit</label>
        <input type="number" class="form-control" placeholder="contoh: 10115" name="profit">
        <small class="text-danger">Harga asli layanannya, biar sistem yang ngitung profit.</small>
    </div>
    <hr>
    <div class="form-group">
        <div class="row">
            <div class="col-6">
                <button type="button" data-dismiss="modal" class="btn btn-block text-white bg-danger fw-bold">BACK</button>
            </div>
            <div class="col-6">
                <button type="submit" name="addlayanan" class="btn btn-block text-white bg-primary fw-bold">ADD</button>
            </div>
        </div>
    </div>
</form>
<?php } ?>