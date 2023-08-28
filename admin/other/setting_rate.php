<?php
session_start();
require_once '../../mainconfig.php';
load('middleware', 'user');

if ($_SESSION['user']['level'] == "Reseller" || $_SESSION['user']['level'] == "Member") {
    header('location:' . base_url());
}

if (isset($_POST['update'])) {
        $post_jumlah = $db->real_escape_string(e(@$_POST['jumlah']));    
	$post_jumlah2 = $db->real_escape_string(e(@$_POST['rate_reff']));    
	
	if (!$post_jumlah || !$post_jumlah2) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	} else {
	        $Update = $db->query("UPDATE keuntungan SET rate_upgrade = '{$post_jumlah}', rate_reff = '{$post_jumlah2}' WHERE id = '1'");
	        if ($Update === true) {
				$_SESSION['alert'] = ['success', 'Success!', 'Configuration successfully change.'];
				exit(header("Location: ".base_url('/admin/other/setting_rate')));
			} else {
				$_SESSION['alert'] = ['danger', 'Failed!', 'Configuration Failed change.'];
			        exit(header("Location: ".base_url('/admin/other/setting_rate')));
			       }
			}
	} 
include_once '../../layouts/header_admin.php'; ?>
<section id="basic-vertical-layouts">
    <div class="row">
        <div class="col-md-10 offset-md-1 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">ATUR RATE</h4>
                </div>
                <div class="card-body card-dashboard">
                    <form method="POST" class="form form-vertical">
                        <input type="hidden" id="csrf_token" name="csrf_token" value="<?= csrf_token() ?>">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Harga Upgrade Akun</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i data-feather="credit-card"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="jumlah" value="<?= e ($priceUP['rate_upgrade']) ?>" onkeyup="this.value=this.value.replace(/[^\d]+/g,'')" placeholder="harga upgrafe" data-validation-required-message="This price field is required" required />                                        
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Rate Referall</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i data-feather="credit-card"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="rate_reff" value="<?= e ($priceUP['rate_reff']) ?>" onkeyup="this.value=this.value.replace(/[^\d]+/g,'')" placeholder="rate refferal" data-validation-required-message="This price field is required" required />                                        
                                    </div>
                                </div>
                            </div>                            
                            <div class="col-12">
                                <button type="submit" name="update" class="btn btn-primary mr-1">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once '../../layouts/footer.php'; ?>