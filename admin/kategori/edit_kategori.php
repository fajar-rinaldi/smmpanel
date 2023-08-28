<?php
require_once '../../mainconfig.php';

$id = $_GET['id'];
if(!isset($id))
    die('No direct script access allowed!');
$query = mysqli_query($db, "SELECT * FROM kategori_layanan WHERE id = '$id'");
$q = mysqli_fetch_assoc($query); 
?>

<form method="POST">
<div class="form-group">
        <label>ID</label>
        <input type="text" readonly class="form-control" name="id" value="<?= $q['id']; ?>">
    </div>
<div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" value="<?= $q['nama']; ?>">
    </div>
    <div class="form-group">
        <label>Type</label>
        <input type="text" class="form-control" value="<?= $q['tipe']; ?>" readonly>
    </div>
    <hr>
    <div class="form-group">
        <div class="row">
            <div class="col-6">
                <button type="button" data-dismiss="modal" class="btn btn-block text-white bg-danger fw-bold">BACK</button>
            </div>
            <div class="col-6">
                <button type="submit" name="edit" class="btn btn-block text-white bg-primary fw-bold">CHANGE</button>
            </div>
        </div>
    </div>
</form>
