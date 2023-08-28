<?php
require_once '../../../mainconfig.php';

$id = $_GET['id'];
if(!isset($id))
    die('No direct script access allowed!');
$query = mysqli_query($db, "SELECT * FROM setting_website WHERE id = '$id'");
$q = mysqli_fetch_assoc($query); 
?>

<form method="POST">
<div class="form-group">
        <label>ID</label>
        <input type="text" readonly class="form-control" name="id" value="<?= $q['id']; ?>">
    </div>
    <div class="form-group">
        <label>Title</label>
        <input type="text" class="form-control" name="NamaWeb" value="<?= $q['NamaWeb']; ?>">
    </div>
    <div class="form-group">
        <label>Keyword</label>
        <textarea class="form-control" name="Keyword" rows="7"><?= $q['Keyword']; ?></textarea>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea class="form-control" name="Description" rows="7"><?= $q['Description']; ?></textarea>
    </div>
    <div class="form-group">
        <label>Group Whatsapp</label>
        <input type="text" class="form-control" name="GrupWa" value="<?= $q['GrupWa']; ?>">
        <small class="text-danger">Isi dgn ID GrupWa</small>
    </div>
    <div class="form-group">
        <label>Nomor</label>
        <input type="text" class="form-control" name="Nomor" value="<?= $q['Nomor']; ?>">        
    </div>
    <div class="form-group">
        <label>Alamat</label>
        <input type="text" class="form-control" name="Alamat" value="<?= $q['Alamat']; ?>">        
    </div>
    <div class="form-group">
        <label>Maintenance</label>
        <select class="form-control" name="maintenance">
            <option value="true" <?= $q['maintenance'] == 'true' ? 'selected' : '' ?>>Off!</option>
            <option value="false" <?= $q['maintenance'] == 'false' ? 'selected' : '' ?>>On!</option>
        </select>
    </div>
    <div class="form-group">
        <label>Reason</label>
        <textarea class="form-control" name="reason" rows="5"><?= $q['reason_mt']; ?></textarea>
    </div>
    <hr>
    <div class="form-group">
        <div class="row">
            <div class="col-6">
                <button type="button" data-dismiss="modal" class="btn btn-block text-white bg-danger fw-bold">BACK</button>
            </div>
            <div class="col-6">
                <button type="submit" name="editmt" class="btn btn-block text-white bg-primary fw-bold">CHANGE</button>
            </div>
        </div>
    </div>
</form>
