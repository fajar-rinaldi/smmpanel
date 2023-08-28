<?php
session_start();
require_once '../../mainconfig.php';
load('middleware', 'user');

if ($_SESSION['user']['level'] == "Reseller" || $_SESSION['user']['level'] == "Member") {
    header('location:' . base_url());
}

if (isset($_POST['add'])) {
	$post_tipe = $db->real_escape_string(e(@$_POST['tipe']));
	$post_code = $db->real_escape_string(e(@$_POST['code']));
	$post_nama = $db->real_escape_string(e(@$_POST['nama']));
	$post_tujuan = $db->real_escape_string(e(@$_POST['tujuan']));
	$post_an = $db->real_escape_string(e(@$_POST['an']));
	$post_rate = $db->real_escape_string(e(@$_POST['rate']));
	$post_min = $db->real_escape_string(e(@$_POST['min']));
	$post_max = $db->real_escape_string(e(@$_POST['max']));
	
	if (!$post_tipe || !$post_code || !$post_nama || !$post_tujuan || !$post_an || !$post_rate || !$post_min || !$post_max) {
	$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	} else {
			$insert_method = $db->query("INSERT INTO metode_depo VALUES('', '{$post_code}', '{$post_nama}', '{$post_rate}', '{$post_tujuan}', '{$post_an}', '{$post_tipe}', '{$post_min}', '{$post_max}', '22:00:00', '01:05:00', 'on')");
			if ($insert_method === true) {
				$_SESSION['alert'] = ['success', 'Success!', 'Method successfully add.'];
				exit(header("Location: ".base_url('/admin/deposit/method_data')));
			} else {
				$_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
			}
		}
	}

if (isset($_POST['edit_method'])) {
        $post_id = $db->real_escape_string(e(@$_POST['id'])); 
	$post_tipe = $db->real_escape_string(e(@$_POST['tipe']));
	$post_code = $db->real_escape_string(e(@$_POST['code']));
	$post_nama = $db->real_escape_string(e(@$_POST['nama']));
	$post_dest = $db->real_escape_string(e(@$_POST['dest']));
	$post_an = $db->real_escape_string(e(@$_POST['an']));
	$post_rate = $db->real_escape_string(e(@$_POST['rate']));
	$post_min = $db->real_escape_string(e(@$_POST['min']));
	$post_max = $db->real_escape_string(e(@$_POST['max']));
	$post_status = $db->real_escape_string(e(@$_POST['status']));
	if (!$post_id) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	
	} else {
		$edit_methodd = $db->query("UPDATE metode_depo SET tipe = '{$post_tipe}', provider = '{$post_code}', nama = '{$post_nama}', tujuan = '{$post_dest}', an = '{$post_an}', rate = '{$post_rate}', min = '{$post_min}', max = '{$post_max}', status = '{$post_status}' WHERE id = '{$post_id}'"); 
			if ($data_methodd === true) {
				$_SESSION['alert'] = ['success', 'Success!', 'Method successfully change.'];
				exit(header("Location: ".base_url('/admin/deposit/method_data')));
			} else {
				$_SESSION['alert'] = ['success', 'Success!', 'Method berhasil di edit.'];
			}
		}
	}

if (isset($_POST['delete'])) {
	$post_method = $db->real_escape_string(e(@$_POST['method']));
	if (!$post_method) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	
        } else { 
                $delete_method = $db->query("DELETE FROM metode_depo WHERE nama = '{$post_method}'");
                if ($delete_method === true) {
                    $_SESSION['alert'] = ['success', 'Success!', 'Method successfully delete.'];
                    exit(header("Location: ".base_url('/admin/deposit/method_data')));
                } else {
                    $_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
                }
           }
      }
	

include_once '../../layouts/header_admin.php'; ?>

<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Method Manage</h4>   
                    <a href="javascript:;" onclick="modal('Add Method','<?= base_url(); ?>admin/deposit/method/add-method','','md')">
                        <p class="font-medium-5 mb-0"><i class="text-primary" data-feather="plus-circle" style="width: 24px; height: 24px"></i></p>
                            </a>
                </div>
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped zero-configuration" id="datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Rate</th>
                                    <th>Min</th>
                                    <th>Max</th>
                                    <th>Number</th>
                                    <th>A/N</th>
                                    <th>Act.</th>
                                </tr>
                            </thead>
                            <tbody>
                    <?php
                    $check_data = $db->query("SELECT * FROM metode_depo ORDER BY id DESC");
                    while($data_method = $check_data->fetch_assoc()) :
                    ?>
                            <tr>
                                <td><li>Type: <b><?= $data_method['tipe']; ?></b></li><li>Name: <b><?= $data_method['nama']; ?></b></li></td>
                                <td><?= $data_method['rate']; ?></td>
                                <td>Rp <?= number_format($data_method['min'],0,',','.'); ?></td>
                                <td>Rp <?= number_format($data_method['max'],0,',','.'); ?></td>
                                <td><?= $data_method['tujuan']; ?></td>
                                <td><?= $data_method['an']; ?></td>
                                <td>
                                <a href="javascript:;" onclick="modal('Edit Method','<?= base_url(); ?>admin/deposit/method/edit-method?id=<?= $data_method['id']; ?>','','md')">
                        <p class="font-medium-5 mr-1" style="text-decoration:none;"><i class="mdi mdi-pencil-box-outline mdi-24px"></i></p>
                            </a>
                                <a href="javascript:;" onclick="modal('Delete Method','<?= base_url(); ?>admin/deposit/method/delete-method?id=<?= $data_method['id']; ?>','','md')">
                        <p class="font-medium-5 ml-1" style="text-decoration:none;"><i class="mdi mdi-trash-can mdi-24px"></i></p>
                            </a>
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
                       <div class="card position-static">
                            <div class="card-body">
                                <h4 class="card-title">Kode Pembayaran TRIPAY</h4>
                                <a href="https://tripay.co.id/developer?tab=channels" class="pull-right btn btn-block btn--md btn-primary waves-effect w-md waves-light">KLIK DISINI</a>

                                <p class="card-text">
                                    <small class="text-muted">Rigaming</small>
                                </p>
                            </div>
                        </div>    
<script type="text/javascript">
    function modal(name, link, size) {
        var sizes = '';
        if (size == 'smaller' || size == 'xs') sizes = 'modal-xs';
        if (size == 'small' || size == 'sm') sizes = 'modal-sm';
        if (size == 'large' || size == 'lg') sizes = 'modal-lg';
        if (size == 'larger' || size == 'xl') sizes = 'modal-xl';

        $.ajax({
            type: "GET",
            url: link,
            beforeSend: function() {
                $('#SModal-body').html('Loading...');
            },
            success: function(result) {
                $('#SModal-body').html(result);
            },
            error: function() {
                $('#SModal-body').html('Failed to get contents...');
            }
        });

        $('#SModal-title').html(name);
        $('#SModal-size').removeClass('modal-xs');
        $('#SModal-size').removeClass('modal-sm');
        $('#SModal-size').removeClass('modal-lg');
        $('#SModal-size').removeClass('modal-xl');
        $('#SModal-size').addClass(sizes);
        $('#SModal').modal();
    }
</script>
<div class="modal fade text-left" id="SModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" id="SModal-size" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="SModal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="SModal-body"></div>
        </div>
    </div>
</div>                </div>
                </div>
                </div>
<?php include_once '../../layouts/footer.php'; ?>