<?php
require_once '../../mainconfig.php';

$id = $_GET['id'];
if(!isset($id))
    die('No direct script access allowed!');
$query = mysqli_query($db, "SELECT * FROM provider WHERE id = '$id'");
$q = mysqli_fetch_assoc($query); 
?>

<form method="POST">
<div class="form-group">
        <label>Code</label>
        <input type="text" class="form-control" name="code" value="<?= $q['code']; ?>">
    </div>
<div class="form-group">
        <label>Link</label>
        <input type="text" class="form-control" name="link" value="<?= $q['link']; ?>">
    </div>
    <div class="form-group">
        <label>API Key</label>
        <input type="text" class="form-control" name="api_key" value="<?= $q['api_key']; ?>">
    </div>
    <div class="form-group">
        <label>API ID</label>
        <input type="text" class="form-control" name="api_id" value="<?= $q['api_id']; ?>">
        <small class="text-danger">Kosongkan jika tidak diperlukan!</small>
    </div>
    <div class="form-group">
        <label>SECRET KEY</label>
        <input type="text" class="form-control" name="secret_key" value="<?= $q['secret_key']; ?>">
        <small class="text-danger">Kosongkan jika tidak diperlukan!</small>
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
