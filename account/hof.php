<?php
session_start();
require_once '../mainconfig.php';
load('middleware', 'user'); 
include_once '../layouts/header.php'; 

$pembelian_sosmed = $db->query("SELECT SUM(pembelian_sosmed.harga) AS total_pembelian, count(pembelian_sosmed.id) AS tcount, pembelian_sosmed.user, users.name FROM pembelian_sosmed INNER JOIN users ON pembelian_sosmed.user = users.username WHERE pembelian_sosmed.status = 'Success' GROUP BY pembelian_sosmed.user ORDER BY total_pembelian DESC LIMIT 10");

$top_deposit = $db->query("SELECT SUM(deposit.get_saldo) AS tamount, count(deposit.id) AS tcount, deposit.username, users.name FROM deposit INNER JOIN users ON deposit.username = users.username WHERE deposit.status = 'Success' GROUP BY deposit.username ORDER BY tamount DESC LIMIT 10");

$top_layanan = $db->query("SELECT SUM(pembelian_sosmed.harga) AS tamount, count(pembelian_sosmed.id) AS tcount, pembelian_sosmed.layanan, layanan_sosmed.layanan FROM pembelian_sosmed JOIN layanan_sosmed ON pembelian_sosmed.layanan = layanan_sosmed.layanan WHERE pembelian_sosmed.status = 'Success' GROUP BY pembelian_sosmed.layanan ORDER BY tamount DESC LIMIT 10");
									
?>
<!-- Dashboard Analytics Start -->
<section id="dashboard-analytics">
    <div class="row match-height">
        <div class="col-12">
            <div class="card card-congratulations">
                <div class="card-body text-center">
                    <img src="<?= asset('/logo-baru/decore-left.png') ?>" class="congratulations-img-left" alt="card-img-left" />
                    <img src="<?= asset('/logo-baru/decore-right.png') ?>" class="congratulations-img-right" alt="card-img-right" />
                    <div class="avatar avatar-xl bg-primary shadow">
                        <div class="avatar-content">
                            <i data-feather="award" class="font-large-1"></i>
                        </div>
                    </div>
                    <div class="text-center">
                        <h1 class="mb-1 text-white">
                            Hallo
                            <?= $data_user['username'] ?>
                        </h1>
                        <p class="card-text m-auto w-75">
                            Dibawah ini merupakan top pengguna pemesanan tertinggi bulan ini. Terima kasih telah menjadi pelanggan setia kami!
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="m-t-0 text-uppercase text-center header-title">TOP PEMESANAN</h5>
                    <hr />
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered nowrap m-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
$no = 1;
while($data = mysqli_fetch_array($pembelian_sosmed)){
$total_pembelian = number_format($data['total_pembelian'],0,',','.');
$tcount = number_format($data['tcount'],0,',','.');
?>
                                <tr>
                                    <td>
                                        <span class="badge badge-primary"><?php echo $no++; ?></span>
                                    </td>
                                    <td><?php echo $data['name']; ?></td>
                                    <td>
                                        Rp
                                        <?php echo $total_pembelian; ?> ( <?php echo $tcount; ?> )
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="m-t-0 text-uppercase text-center header-title">TOP DEPOSIT</h5>
                    <hr />
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered nowrap m-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
$no = 1;
while($data_deposit = mysqli_fetch_array($top_deposit)){
$total_deposit = number_format($data_deposit['tamount'],0,',','.');
$tcount = number_format($data_deposit['tcount'],0,',','.');
?>
                                <tr>
                                    <td>
                                        <span class="badge badge-primary"><?php echo $no++; ?></span>
                                    </td>
                                    <td><?php echo $data_deposit['name']; ?></td>
                                    <td>
                                        Rp
                                        <?php echo $total_deposit; ?> ( <?php echo $tcount; ?> )
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="m-t-0 text-uppercase text-center header-title">TOP LAYANAN</h5>
                    <hr />
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered nowrap m-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
$no = 1;
while($hah = mysqli_fetch_array($top_layanan)){
$total = number_format($hah['tamount'],0,',','.');
$tcount = number_format($hah['tcount'],0,',','.');
?>
                                <tr>
                                    <td>
                                        <span class="badge badge-primary"><?php echo $no++; ?></span>
                                    </td>
                                    <td><?php echo $hah['layanan']; ?></td>
                                    <td>
                                        Rp
                                        <?php echo $total; ?> ( <?php echo $tcount; ?> )
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Ecommerce ends -->
    </div>
</section>

<?php include_once '../layouts/footer.php'; ?>