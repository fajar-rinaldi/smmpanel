<?php
require_once '../../../mainconfig.php';

$id = $_GET['id'];
if(!isset($id))
    die('No direct script access allowed!');
$query = mysqli_query($db, "SELECT * FROM metode_depo WHERE id = '$id'");
$q = mysqli_fetch_assoc($query); 
?>

<form method="POST">
<div class="form-group">
        <label>ID</label>
        <input type="number" class="form-control" name="id" value="<?= $q['id']; ?>" readonly>
    </div>
<div class="form-group">
        <label>Tipe</label>
        <select class="form-control" name="tipe">
            <option value="BANK" <?= $q['tipe'] == 'BANK' ? 'selected' : '' ?>>BANK</option>
            <option value="E-MONEY" <?= $q['tipe'] == 'E-MONEY' ? 'selected' : '' ?>>E-MONEY</option>
            <option value="PULSA" <?= $q['tipe'] == 'PULSA' ? 'selected' : '' ?>>PULSA</option>
        </select>
    </div>
    <div class="form-group">
        <label>Code</label>
        <input type="text" class="form-control" name="code" value="<?= $q['provider']; ?>">
    </div>
    <div class="form-group">
        <label>Name</label>
        <input type="text" class="form-control" name="nama" value="<?= $q['nama']; ?>">
        </div>
    <div class="form-group">
        <label>Number</label>
        <input type="text" class="form-control" name="dest" value="<?= $q['tujuan']; ?>">
    </div>
    <div class="form-group">
        <label>A/N</label>
        <input type="text" class="form-control" name="an" value="<?= $q['an']; ?>">
    </div>
    <div class="form-group">
        <label>Rate</label>
        <input type="text" class="form-control" name="rate" value="<?= $q['rate']; ?>">
    </div>
    <div class="form-group">
        <label>Min</label>
        <input type="number" class="form-control" name="min" value="<?= $q['min']; ?>">
    </div>
    <div class="form-group">
        <label>Max</label>
        <input type="number" class="form-control" name="max" value="<?= $q['max']; ?>">
    </div>
    <div class="form-group">
        <label>Status</label>
        <select class="form-control" name="status">
            <option value="on" <?= $q['status'] == 'on' ? 'selected' : '' ?>>ON || ACTIVE</option>
            <option value="off" <?= $q['status'] == 'off' ? 'selected' : '' ?>>OFF | NON ACTIVE</option>
        </select>
    </div>
    <hr>
    <div class="form-group">
        <div class="row">
            <div class="col-6">
                <button type="button" data-dismiss="modal" class="btn btn-block text-white bg-danger fw-bold">BACK</button>
            </div>
            <div class="col-6">
                <button type="submit" name="edit_method" class="btn btn-block text-white bg-primary fw-bold">CHANGE</button>
            </div>
        </div>
    </div>
</form>
