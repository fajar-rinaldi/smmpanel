<?php
session_start();
require_once '../../mainconfig.php';
load('middleware', 'user');

if ($_SESSION['user']['level'] == "Reseller" || $_SESSION['user']['level'] == "Member") {
    header('location:' . base_url());
}

include_once '../../layouts/header_admin.php'; ?>
<section id="dashboard-analytics">
<div class="row">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Delete Layanan & Kategori</h4>
                <hr>
                <?php if ($data_user['level'] == 'Admin') { ?>
                <div class="form-group">
                     <div class="col-md-12">
                        <a href="<?= config('web','url') ?>/layouts/dynamshop/delete/delete_kategori" class="pull-right btn btn-block btn--md btn-danger waves-effect w-md waves-light">Delete All Kategori </a>
                     </div>
                </div>
                <div class="form-group">
                     <div class="col-md-12">
                        <a href="<?= config('web','url') ?>/layouts/dynamshop/delete/delete_layanan" class="pull-right btn btn-block btn--md btn-danger waves-effect w-md waves-light">Delete All Layanan</a>
                     </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Ambil Layanan</h4>
                <hr>
                <?php if ($data_user['level'] == 'Admin') { ?>
                <div class="form-group">
                     <div class="col-md-12">
                        <a href="<?= config('web','url') ?>/autocron/status/status-sosmed.php" class="pull-right btn btn-block btn--md btn-success waves-effect w-md waves-light">Get Status</a>
                     </div>
                </div>
                <div class="form-group">
                     <div class="col-md-12">
                        <a href="<?= config('web','url') ?>/autocron/get-sosmedds.php" class="pull-right btn btn-block btn--md btn-success waves-effect w-md waves-light">Tambah Layanan</a>
                     </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</section>
<?php include_once '../../layouts/footer.php'; ?>