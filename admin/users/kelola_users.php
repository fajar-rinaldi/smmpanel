<?php
session_start();
require_once '../../mainconfig.php';
load('middleware', 'user');

if ($_SESSION['user']['level'] == "Reseller" || $_SESSION['user']['level'] == "Member") {
    header('location:' . base_url());
}
 
if (isset($_POST['users_edit1'])) {
        $post_u_user = $db->real_escape_string(e(@$_POST['u_user'])); 
	$post_u_name = $db->real_escape_string(e(@$_POST['u_name']));
	$post_u_mail = $db->real_escape_string(e(@$_POST['u_mail']));
	$post_u_phone = $db->real_escape_string($_POST['u_phone']);
	$post_u_level = $db->real_escape_string(e(@$_POST['u_level']));
	if (!$post_u_user) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	} else if (filter_var($post_u_mail, FILTER_VALIDATE_EMAIL) === false) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Email Address tidak valid.'];
	} else {
		$change_user = $db->query("UPDATE users SET name = '{$post_u_name}', email = '{$post_u_mail}', phone = '{$post_u_phone}', level = '{$post_u_level}' WHERE username = '{$post_u_user}'"); 
			if ($change_user === true) {
				$_SESSION['alert'] = ['success', 'Success!', 'Edit akun berhasil.'];
				exit(header("Location: ".base_url('/admin/users/kelola_users')));
			} else {
				$_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
			}
		}
	}

if (isset($_POST['users_edit4'])) {
        $post_ub_user = $db->real_escape_string(e(@$_POST['ub_user'])); 
	$post_ub_cut = $db->real_escape_string(e(@$_POST['ub_cut']));
	$post_ub_reason = $db->real_escape_string(e(@$_POST['ub_reason']));
	if (!$post_ub_user) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	} else {
	$tanggal = date('Y-m-d H:i:s');
	$cut_up = $db->query("UPDATE users SET saldo = saldo-{$post_ub_cut} WHERE username = '{$post_ub_user}'");
	$db->query("INSERT INTO history_saldo VALUES('', '{$post_ub_user}', '-', '{$post_ub_cut}', '{$post_ub_reason}', '{$tanggal}')"); 
			if ($cut_up  === true) {
				$_SESSION['alert'] = ['success', 'Success!', 'CUT '.$post_ub_user.' berhasil.'];
				exit(header("Location: ".base_url('/admin/users/kelola_users')));
			} else {
				$_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
			}
		}
	}
	
if (isset($_POST['users_edit2'])) {
	$post_up_user = $db->real_escape_string(e(@$_POST['up_user']));
	$post_up_new = $db->real_escape_string(e(@$_POST['up_new']));
	$post_up_confirm = $db->real_escape_string(e(@$_POST['up_confirm']));
	
	if (!$post_up_user || !$post_up_confirm) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	} else if ($post_up_new !== $post_up_confirm) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Konfirmasi password tidak sama.'];
	} else {
		$password = password_hash($post_up_new, PASSWORD_DEFAULT);
		$update_password = $db->query("UPDATE users SET password = '{$password}' WHERE username = '{$post_up_user}'");
		if ($update_password === true) {
			$_SESSION['alert'] = ['success', 'Success!', 'Edit akun berhasil.'];
			exit(header("Location: ".base_url('/admin/users/kelola_users')));
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
                    <h4 class="card-title">Kelola Users</h4>
                </div>
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped zero-configuration" id="datatabl3s">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Nomer HP</th>
                                    <th>Balance</th>
                                    <th>Joined</th>
                                    <th>Act.</th>
                                </tr>
                            </thead>
                            <tbody>
                            <td colspan="7" class="text-center">Loading data from server...</td>
                            </tbody>		
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
$(document).ready(function() {
    $('#datatabl3s').DataTable({
        "order": [[0, 'desc']],
        "processing": false,
        "serverSide": true,
        "paging": true,
        "pagingType": "simple_numbers",
        "ajax": "<?= base_url(); ?>/admin/users/data/nampil_data.php",
        "keys": !0,
        "drawCallback": function() { $(".dataTables_paginate > .pagination").addClass("pagination") },
        "language": {
            "emptyTable": "No data in table",
            "info": "Showing _START_ to _END_ of _TOTAL_ data",
            "infoEmpty": "",
            "infoFiltered": "",
            "infoPostFix": "",
            "thousands": ".",
            "lengthMenu": "Show _MENU_ data",
            "loadingRecords": "Waiting...",
            "processing": "Processing...",
            "search": "Search:",
            "searchPlaceholder": "Ytb:Rigaming",
            "zeroRecords": "Data not found",
            "paginate": {"first": "First","last": "Last","next": "<i class='fas fa-chevron-right'>","previous": "<i class='fas fa-chevron-left'>"},
            "aria": {"sortAscending": ": activate to sort column ascending","sortDescending": ": activate to sort column descending"}
        },
        columnDefs : [
        {
        "searhable" : false,
        "orderable" : false,
        "targets" : 6,
        "render" : function(data, type, row) {
            var btn = "<a href = \"javascript:;\" onclick=\"modal('Detail Users','<?= base_url(); ?>/admin/users/data/view-users?id="+data+"')\"><p class=\"font-medium-5 mr-1\" style=\"text-decoration:none;\"><i class=\"mdi mdi-eye mdi-24px\"></i></p></a><a href = \"javascript:;\" onclick=\"modal('Edit Users','<?= base_url(); ?>/admin/users/data/edit-users?id="+data+"')\"><p class=\"font-medium-5 ml-1\" style=\"text-decoration:none;\"><i class=\"mdi mdi-pencil-box-outline mdi-24px\"></i></p></a>"
            return btn;
        }
        } 
                     ]
        
    });
});
</script>
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