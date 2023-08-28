<?php
session_start();
require_once '../../mainconfig.php';
load('middleware', 'user');

if ($_SESSION['user']['level'] == "Reseller" || $_SESSION['user']['level'] == "Member") {
    header('location:' . base_url());
}

if (isset($_POST['edit'])) {
        $post_code = $db->real_escape_string(e(@$_POST['code'])); 
	$post_link = $db->real_escape_string(e(@$_POST['link']));
	$post_api_key = $db->real_escape_string(e(@$_POST['api_key']));
	$post_api_id = $db->real_escape_string(e(@$_POST['api_id']));
	$post_secret_key = $db->real_escape_string(e(@$_POST['secret_key']));
	if (!$post_code) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	} else { 
		$edit_prov = $db->query("UPDATE provider SET link = '{$post_link}', api_key = '{$post_api_key}', api_id = '{$post_api_id}', secret_key = '{$post_secret_key}' WHERE code = '{$post_code}'"); 
			if ($edit_prov === true) {
				$_SESSION['alert'] = ['success', 'Success!', 'Provider berhasil di edit.'];
				exit(header("Location: ".base_url('/admin/provider/provider')));
			} else {
				$_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
			}
		}
	}

include_once '../../layouts/header_admin.php'; ?>
<div class="row">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Provider</h4>
                </div>
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped zero-configuration" id="datatabl3s">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Link</th>
                                    <th>Act.</th>
                                </tr>
                            </thead>
                            <tbody>
                            <td colspan="3" class="text-center">Loading data from server...</td>
                            </tbody>		
                        </table>
                    </div>
                    <div class="col-md-12">
                        <a href="/admin/other/act" class="btn btn-relief-primary float-end">Get Layanan / Delete </a>
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
        "ajax": "<?= base_url(); ?>/admin/provider/provider_data.php",
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
        "targets" : 2,
        "render" : function(data, type, row) {
            var btn = "<a href = \"javascript:;\" onclick=\"modal('Edit Provider','<?= base_url(); ?>/admin/provider/edit-provider?id="+data+"')\"><p class=\"font-medium-5 mr-1\" style=\"text-decoration:none;\"><i class=\"mdi mdi-pencil-box-outline mdi-24px\"></i></a></p><a href = \"javascript:;\" onclick=\"modal('Check Data','<?= base_url(); ?>/admin/provider/view-provider?id="+data+"')\"><p class=\"font-medium-5 ml-1\" style=\"text-decoration:none;\"><i class=\"mdi mdi-access-point mdi-24px\"></i></a></p>"
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