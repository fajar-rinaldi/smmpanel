<?php
require_once '../../../mainconfig.php';

$id = $_GET['id'];
if(!isset($id))
    die('No direct script access allowed!');
$query = mysqli_query($db, "SELECT * FROM kontak_kami WHERE id = '$id'");
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
        <label>Alamat</label>
        <textarea class="form-control" name="alamat" rows="5"><?= $q['alamat']; ?></textarea>
    </div>
    <div class="form-group">
        <label>Title</label>
        <select class="form-control" name="jabatan">
            <option value="Developers" <?= $q['jabatan'] == 'Developers' ? 'selected' : '' ?>>Developers</option>
            <option value="Staff" <?= $q['jabatan'] == 'Staff' ? 'selected' : '' ?>>Staff</option>
        </select>
    </div>
    <div class="form-group">
        <label>Whatsapp</label>
        <input type="number" class="form-control" name="whatsapp" placeholder="62" value="<?= $q['whatsapp']; ?>">
    </div>
    <div class="form-group">
        <label>Instagram</label>
        <input type="text" class="form-control" name="instagram" value="<?= $q['instagram']; ?>">
    </div>
    <div class="form-group">
        <label>Link</label>
        <input type="text" class="form-control" name="link" value="<?= $q['link']; ?>">
    </div>
    <div class="form-group">
        <label>Profile Images</label>
        <input type="url" class="form-control" name="url" value="<?= $q['url']; ?>">
    </div>
    <hr>
    <div class="form-group">
        <div class="row">
            <div class="col-6">
                <button type="button" data-dismiss="modal" class="btn btn-block text-white bg-danger fw-bold">BACK</button>
            </div>
            <div class="col-6">
                <button type="submit" name="edit_contact" class="btn btn-block text-white bg-primary fw-bold">CHANGE</button>
            </div>
        </div>
    </div>
</form>
