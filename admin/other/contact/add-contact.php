<?php
require_once '../../../mainconfig.php';
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') { ?>
<form method="POST">
<div class="form-group">
        <label>Name</label>
        <input type="text" class="form-control" name="nama">
    </div>
    <div class="form-group">
        <label>Alamat</label>
        <textarea class="form-control" name="alamat" rows="5"></textarea>
    </div>
    <div class="form-group">
        <label>Title</label>
        <select class="select form-control" name="jabatan">
        <option value="Staff">Staff</option>
        <option value="Developers">Developers</option>
        </select>
    </div>
    <div class="form-group">
        <label>Whatsapp</label>
        <input type="number" class="form-control" value="62" name="whatsapp">
    </div>
    <div class="form-group">
        <label>Instagram</label>
        <input type="text" class="form-control" name="instagram">
        <small class="text-danger">Cth. `agmedia`</small>
    </div>
    <div class="form-group">
        <label>Link</label>
        <input type="text" value="<?= base_url(); ?>" class="form-control" name="link">
    </div>
    <div class="form-group">
        <label>Profile Images</label>
        <input type="url" class="form-control" value="<?= asset('/images/team/user1.png') ?>" name="url">
    </div>
    <hr>
    <div class="form-group">
        <div class="row">
            <div class="col-6">
                <button type="button" data-dismiss="modal" class="btn btn-block text-white bg-danger fw-bold">BACK</button>
            </div>
            <div class="col-6">
                <button type="submit" name="add_contact" class="btn btn-block text-white bg-primary fw-bold">ADD</button>
            </div>
        </div>
    </div>
</form>
<?php } ?>