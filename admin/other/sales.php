<?php
session_start();
require_once '../../mainconfig.php';
load('middleware', 'user');

if ($_SESSION['user']['level'] == "Reseller" || $_SESSION['user']['level'] == "Member") {
    header('location:' . base_url());
}

include_once '../../layouts/header_admin.php';
?>

<div class="row">
     <div class="col-md-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Sosial Media</h4>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Transaksi</th>
                            <th>Dirty</th>
                            <th>Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr> 
                    <td><span class=""><?php echo $CountProfitSosmed; ?>x</span></td>
                    <td><span class=""> Rp <?php echo number_format($AllSosmed['total'],0,',','.') ?></span></td>  
                    <td><span class=""> Rp <?php echo number_format($ProfitSosmed['total'],0,',','.'); ?></span></td>                              
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once '../../layouts/footer.php'; ?>
          