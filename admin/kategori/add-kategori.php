<?php
require_once '../../mainconfig.php';
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') { ?>
<form method="POST">
    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" />
    </div>
    <div class="form-group">
        <label>Type</label>
        <select class="form-control" name="tipe">
            <option value="0">- Select One -</option>

            <option value="Sosial Media">Sosial Media</option>
            <option value="Lainnya">Lainnya</option>
        </select>
    </div>
    <hr />
    <div class="form-group">
        <div class="row">
            <div class="col-6">
                <button type="button" data-dismiss="modal" class="btn btn-block text-white bg-danger fw-bold">BACK</button>
            </div>
            <div class="col-6">
                <button type="submit" name="add_kategori" class="btn btn-block text-white bg-primary fw-bold">ADD</button>
            </div>
        </div>
    </div>
</form>

<?php } ?>