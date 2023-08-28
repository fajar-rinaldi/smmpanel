<?php
require_once '../../../mainconfig.php';

$id = $_GET['id'];
if(!isset($id))
    die('No direct script access allowed!');
$query = mysqli_query($db, "SELECT * FROM keuntungan WHERE id = '$id'");
$q = mysqli_fetch_assoc($query); 
?>
<form method="POST">
    <div class="form-group">
        <label>Code</label>
        <input type="text" readonly class="form-control" name="code" value="<?= $q['code']; ?>" />
    </div>
    <div class="form-group">
        <label>WEB (khusus Member)</label>
        <input type="number" class="form-control" name="web" value="<?= $q['web']; ?>" />
        <small class="text-danger">Profit satuan %</small>
    </div>
    <div class="form-group">
        <label>API (khusus Admin & Reseller)</label>
        <input type="number" class="form-control" name="api" value="<?= $q['api']; ?>" />
        <small class="text-danger">Profit satuan %</small>
    </div> 
    <hr />
    <div class="form-group">
        <div class="row">
            <div class="col-6">
                <button type="button" data-dismiss="modal" class="btn btn-block text-white bg-danger fw-bold">BACK</button>
            </div>
            <div class="col-6">
                <button type="submit" name="edit_keuntungan" class="btn btn-block text-white bg-primary fw-bold">CHANGE</button>
            </div>
        </div>
    </div>
</form>
