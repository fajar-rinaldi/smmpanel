<?php
session_start();
require_once '../../mainconfig.php';
load('middleware', 'user');

if ($_SESSION['user']['level'] == "Reseller" || $_SESSION['user']['level'] == "Member") {
    header('location:' . base_url());
}

if (isset($_POST['buat'])) {
	$post_jumlah = $db->real_escape_string(e(@$_POST['jumlah']));
	
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '{$post_penerima}'");
	$data_penerima = mysqli_fetch_assoc($check_user);
	
	if (!$post_jumlah) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	} else {
	$tanggal = date('Y-m-d H:i:s');
	$kode = random_number(11);
	
	$insert_db = $db->query("INSERT INTO voucher_deposit VALUES('', '{$kode}', '{$session}', '{$post_jumlah}', 'belom diredeem', 'Available', '{$tanggal}')");
			if ($insert_db === true) {
				$_SESSION['alert'] = ['success', 'Success!', 'Berhasil membuat voucher.'];
			exit(header("Location: ".base_url('/admin/deposit/voucher_list')));
			} else {
				$_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
			}
		}
	}

include_once '../../layouts/header_admin.php';
?>
<section id="dashboard-ecommerce">
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="card">
                 <div class="card-body">
                    <h4 class="m-t-0 text-uppercase header-title"><i class=""></i> VOUCHER</h4><hr>
                    <form method="POST">
                        <input type="hidden" id="csrf_token" name="csrf_token" value="<?= csrf_token() ?>">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Nominal</label>
                                    <input type="number" name="jumlah" class="form-control" onkeyup="this.value=this.value.replace(/[^\d]+/g,'')" data-validation-required-message="This phone field is required" required>
                                           </div>
                                      </div>
                                </div>       
                        <div class="form-group"> 
                                    <div class="row">
                                <div class="col-md-12">
                                        <button type="submit" name="buat" class="pull-right btn btn-block btn--md btn-primary waves-effect w-md waves-light">Created</button> 
                                        </div>
                                </div>
                           </div>
                    </form>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="riwayat" data-toggle="tab" href="#riwayat" aria-controls="riwayat" role="tab" aria-selected="false">Riwayat</a>
                        </li>
                    </ul>
                        <div class="tab-pane active" id="riwayat" aria-labelledby="credit-tab" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered datatable" id="Myriwayat" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Balance</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                            $check_data = $db->query("SELECT * FROM voucher_deposit ORDER BY id DESC LIMIT 5");
                            while($data_history = $check_data->fetch_assoc()) :
                            if ($data_history['status'] == "belom diredeem") {
                            $label = "success";
                            $icon = "far fa-check-circle";
                            } else if ($data_history['status'] == "sudah diredeem") {
                            $label = "danger";
                            $icon = "far fa-times-circle";
                            }
                            ?>
                                     <tr>
                                        <td><?= $data_history['voucher'] ?></td>
                                        <td>Rp <?= number_format($data_history['saldo'],0,',','.') ?></td>
                                        <td><b class="badge badge-<?= $label; ?> badge-pill"><i class="<?= $icon; ?>"></i> <?= $data_history['penerima']; ?></b></td>
                                        <td><center><?= format_date('id',$data_history['tanggal']) ?></center></td>
                                      </tr>
                                      <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   
</section>     
<?php include_once '../../layouts/footer.php'; ?>