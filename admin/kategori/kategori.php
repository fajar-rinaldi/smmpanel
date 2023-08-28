<?php
session_start();
require_once '../../mainconfig.php';
load('middleware', 'user');

if ($_SESSION['user']['level'] == "Reseller" || $_SESSION['user']['level'] == "Member") {
    header('location:' . base_url());
}

if (isset($_POST['edit'])) {
        $post_id = $db->real_escape_string(e(@$_POST['id']));
        $post_nama = $db->real_escape_string(e(@$_POST['nama']));
	if (!$post_id) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	} else { 
		$edit = $db->query("UPDATE kategori_layanan SET kode = '{$post_nama}', nama = '{$post_nama}' WHERE id = '{$post_id}'"); 
			if ($edit === true) {
				$_SESSION['alert'] = ['success', 'Success!', 'Kategori successfully change.'];
				exit(header("Location: ".base_url('/admin/kategori/kategori')));
			} else {
				$_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
			}
		}
	}
	
if (isset($_POST['add_kategori'])) {
	$post_nama = $db->real_escape_string(e(@$_POST['nama']));
	$post_tipe = $db->real_escape_string(e(@$_POST['tipe']));

	if (!$post_nama || !$post_tipe) {
	$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
        } else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	} else {
			$insert_method = $db->query("INSERT INTO kategori_layanan VALUES('', '{$post_nama}', '{$post_nama}', '{$post_tipe}')");
			if ($insert_method === true) {
				$_SESSION['alert'] = ['success', 'Success!', 'Kategori successfully add.'];
				exit(header("Location: ".base_url('/admin/kategori/kategori')));
			} else {
				$_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
			}
		}
	}
	
if (isset($_POST['delete'])) {
	$post_id = $db->real_escape_string(e(@$_POST['id']));
	if (!$post_id) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
        } else { 
                $delete_k = $db->query("DELETE FROM kategori_layanan WHERE id = '{$post_id}'");
                if ($delete_k === true) {
                    $_SESSION['alert'] = ['success', 'Success!', 'Kategori successfully delete.'];
                    exit(header("Location: ".base_url('/admin/kategori/kategori')));
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
                    <h4 class="card-title">Kategori</h4>
                    <a href="javascript:;" onclick="modal('Add Kategori','<?= base_url(); ?>admin/kategori/add-kategori','','md')">
                        <p class="font-medium-5 mb-0"><i class="text-primary" data-feather="plus-circle" style="width: 24px; height: 24px"></i></p>
                            </a>
                </div>
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped zero-configuration" id="datatabl3s">
                            <thead>
                                <tr>
                                <th>Code</th>
                                    <th>Nama</th>
                                    <th>Type</th>
                                    <th>Act.</th>
                                </tr>
                            </thead>
                            <tbody>
                            <td colspan="4" class="text-center">Loading data from server...</td>
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
        "ajax": "<?= base_url(); ?>/admin/kategori/kategori_data.php",
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
        "targets" : 3,
        "render" : function(data, type, row) {
            var btn = "<a href = \"javascript:;\" onclick=\"modal('Edit Kategori','<?= base_url(); ?>/admin/kategori/edit_kategori?id="+data+"')\"><p class=\"font-medium-5 mr-1\" style=\"text-decoration:none;\"><i class=\"mdi mdi-pencil-box-outline mdi-24px\"></i></a></p><a href = \"javascript:;\" onclick=\"modal('Delete Kategori','<?= base_url(); ?>/admin/kategori/delete-kategori?id="+data+"')\"><p class=\"font-medium-5 ml-1\" style=\"text-decoration:none;\"><i class=\"mdi mdi-trash-can mdi-24px\"></i></a></p>"
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
