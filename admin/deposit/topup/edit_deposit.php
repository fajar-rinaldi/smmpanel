<?php
require_once '../../../mainconfig.php';

$id = $_GET['id'];
if(!isset($id))
    die('No direct script access allowed!');
$query = mysqli_query($db, "SELECT * FROM deposit WHERE id = '$id'");
$q = mysqli_fetch_assoc($query); 
?>

<form method="POST">
<div class="form-group">
        <label>ID</label>
        <input type="text" class="form-control" name="k_id" value="<?= base64_decode($q['kode_deposit']); ?>" readonly>
    </div>
    <div class="form-group">
        <label>Username</label>
        <input type="text" class="form-control" value="<?= $q['username']; ?>" readonly>
    </div>
    <div class="form-group">
        <label>Payment</label>
        <input type="text" class="form-control" value="<?= $q['payment']; ?>" readonly>
    </div>
    <div class="form-group">
        <label>Get Saldo</label>
        <input type="text" class="form-control" value="Rp <?= number_format($q['get_saldo'],0,',','.'); ?>" readonly>
    </div>
    <hr>
    <?php if ($q['status'] == 'Cancelled') : ?>
    <div class="form-group">
        <div class="row">
            <div class="col-12">
                <button type="button" data-dismiss="modal" class="btn btn-block text-white bg-danger fw-bold">BACK</button>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($q['status'] == 'Success') : ?>
    <div class="form-group">
        <div class="row">
            <div class="col-12">
                <button type="button" data-dismiss="modal" class="btn btn-block text-white bg-danger fw-bold">BACK</button>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($q['status'] == 'Pending') : ?>
    <div class="form-group">
        <div class="row">
            <div class="col-6">
                <button type="submit" name="btl_depo" class="btn btn-block text-white bg-danger fw-bold">CANCEL</button>
            </div>
            <div class="col-6">
                <button type="submit" name="edit_depo" class="btn btn-block text-white bg-success fw-bold">ACC</button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</form>
