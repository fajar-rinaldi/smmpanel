<?php
require_once '../../../mainconfig.php'; 
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') { ?>
<form method="POST">
<div class="form-group">
        <label>Tipe</label>
        <select class="form-control" name="tipe">
        <option value="0">- Select One -</option>
            <option value="BANK">BANK</option>
            <option value="E-MONEY">E-MONEY</option>
            <option value="PULSA">PULSA</option>
        </select>
    </div>
    <div class="form-group">
        <label>Code</label>
        <input type="text" class="form-control" name="code">
        <small class="text-danger">Apabila menggunakan harap sesuaikan dgn code (TRIPAY)</small>
    </div>
    <div class="form-group">
        <label>Name</label>
        <input type="text" class="form-control" placeholder="Contoh: DANA" name="nama">
    </div>
    <div class="form-group">
        <label>Number</label>
        <input type="text" class="form-control" placeholder="Cth. 08xxxx" name="tujuan">
    </div>
    <div class="form-group">
        <label>A/N</label>
        <input type="text" class="form-control" placeholder="Dynamshop" name="an">
    </div>
    <div class="form-group">
        <label>Rate</label>
        <input type="text" class="form-control" placeholder="1" name="rate">
        <small class="text-danger">Rate hanya bisa digunakan khusus metode deposit manual!!</small>
    </div>
    <div class="form-group">
        <label>Min</label>
        <input type="number" class="form-control" placeholder="10000" name="min">
    </div>
    <div class="form-group">
        <label>Max</label>
        <input type="number" class="form-control" placeholder="500000" name="max">
    </div>
    <hr>
    <div class="form-group">
        <div class="row">
            <div class="col-6">
                <button type="button" data-dismiss="modal" class="btn btn-block text-white bg-danger fw-bold">BACK</button>
            </div>
            <div class="col-6">
                <button type="submit" name="add" class="btn btn-block text-white bg-primary fw-bold">ADD</button>
            </div>
        </div>
    </div>
</form>
<?php } ?>
