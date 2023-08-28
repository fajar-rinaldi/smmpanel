<?php
session_start();
require_once '../mainconfig.php';
// load('middleware', 'csrf');
load('middleware', 'user');

include_once '../layouts/header.php';
?>

<section id="basic-datatable">
    <form method="POST">
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="m-t-0 header-title"><i class=""></i> Referral</h4>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Refferal Kamu</label>
                                <div class="input-group">
                                    <input class="form-control" value="<?= $data_user['refferal'] ?>" disabled />
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p style="font-size: 130%;"><b>Contoh Perhitungan Bonus Transaksi</b></p>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered nowrap m-0">
                                    <tbody>
                                        <tr>
                                            <th>Jumlah refferal kamu</th>
                                            <th>Bonus</th>
                                            <th>Transaksi/hari</th>
                                            <th>Bonus/hari</th>
                                            <th>Bonus/bulan</th>
                                        </tr>
                                    </tbody>
                                    <tbody>
                                        <tr class="table-success">
                                            <td>1</td>
                                            <td>Rp 20,-</td>
                                            <td>60 trx/hari</td>
                                            <td>Rp 1.200,-</td>
                                            <td>Rp 36.000,-</td>
                                        </tr>
                                        <tr class="table-success">
                                            <td>10</td>
                                            <td>Rp 20,-</td>
                                            <td>60 trx/hari</td>
                                            <td>Rp 12.000,-</td>
                                            <td>Rp 360.000,-</td>
                                        </tr>
                                        <tr class="table-success">
                                            <td>100</td>
                                            <td>Rp 20,-</td>
                                            <td>60 trx/hari</td>
                                            <td>Rp 120.000,-</td>
                                            <td>Rp 3.600.000,-</td>
                                        </tr>
                                        <tr class="table-success">
                                            <td>1.000</td>
                                            <td>Rp 20,-</td>
                                            <td>60 trx/hari</td>
                                            <td>Rp 1.200.000,-</td>
                                            <td>Rp 36.000.000,-</td>
                                        </tr>
                                        <tr class="table-success">
                                            <td>10.000</td>
                                            <td>Rp 50,-</td>
                                            <td>60 trx/hari</td>
                                            <td>Rp 30.000.000,-</td>
                                            <td>Rp 900.000.000,-</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Downline</h4>
                    </div>
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped zero-configuration" id="datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Transaksi</th>
                                        <th>Terdaftar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                            $check_reff = $db->query("SELECT * FROM users WHERE uplink = '{$data_user['refferal']}' ORDER BY id DESC"); while($view_data = $check_reff->fetch_assoc()) : $user_downline = $view_data['username']; $check_order =
                                    mysqli_query($db, "SELECT SUM(harga) AS total FROM pembelian_sosmed WHERE user = '{$user_downline}' AND status = 'Success'"); $data_order = mysqli_fetch_assoc($check_order); $count_order =
                                    mysqli_num_rows(mysqli_query($db, "SELECT * FROM pembelian_sosmed WHERE user = '{$user_downline}' AND status = 'Success'")); ?>
                                    <tr>
                                        <td><?php echo $view_data['id']; ?></td>
                                        <td><?php echo $view_data['username']; ?></td>
                                        <td>
                                            Rp
                                            <?php echo number_format($data_order['total'],0,',','.'); ?>
                                            (<?php echo number_format($count_order,0,',','.'); ?>
                                            trx)
                                        </td>
                                        <td>
                                            <center><?php echo format_date('id',$view_data['register_at']); ?></center>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
<?php include_once '../layouts/footer.php'; ?>
