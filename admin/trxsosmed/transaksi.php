<?php
session_start();
require_once '../../mainconfig.php';
load('middleware', 'user');

if ($_SESSION['user']['level'] == "Reseller" || $_SESSION['user']['level'] == "Member") {
    header('location:' . base_url());
}

function status($s) {
    if ($s === "Success") {
        return '<div class="badge badge-success">Success</div>';
    } else if ($s === "Pending") {
        return '<div class="badge badge-warning">Pending</div>';
    } else if ($s === "Partial") {
        return '<div class="badge badge-secondary">Partial</div>';
   } else if ($s === "Processing") {
        return '<div class="badge badge-primary">Processing</div>'; 
    } else {
        return '<div class="badge badge-danger">Error</div>';
    }
}

//DATA STATUS
$total_data_pending = mysqli_num_rows($db->query("SELECT * FROM pembelian_sosmed WHERE status = 'Pending'"));
$total_data_processing = mysqli_num_rows($db->query("SELECT * FROM pembelian_sosmed WHERE status = 'Processing'"));
$total_data_success = mysqli_num_rows($db->query("SELECT * FROM pembelian_sosmed WHERE status = 'Success'"));
$total_data_error = mysqli_num_rows($db->query("SELECT * FROM pembelian_sosmed WHERE status IN ('Error','Partial')"));
//END TO DATA

if (isset($_POST['edit_trx'])) {
        $post_oid = $db->real_escape_string(e(@$_POST['oid']));
        $post_status = $db->real_escape_string(e(@$_POST['status']));
	
	$dt_update = date('Y-m-d H:i:s');
	
	if (!$post_oid) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	} else { 
		$edit_trxx = $db->query("UPDATE pembelian_sosmed SET status = '{$post_status}', tanggal_at = '{$dt_update}' WHERE oid = '{$post_oid}'"); 
			if ($edit_trxx === true) {
				$_SESSION['alert'] = ['success', 'Success!', 'Transaksi SMM successfully change.'];
				exit(header("Location: ".base_url('/admin/trxsosmed/transaksi')));
			} else {
				$_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
			}
		}
	}

include_once '../../layouts/header_admin.php'; ?>
<section id="basic-vertical-layouts">
  <div class="row">
    <div class="col-md-3 col-sm-6 col-12">
      <div class="card">
        <div class="card-header">
          <div>
            <h2 class="font-weight-bolder mb-0"><?= number_format($total_data_pending,0,',','.'); ?></h2>
            <p class="card-text">Pending</p>
          </div>
          <div class="avatar bg-light-warning p-50 m-0">
            <div class="avatar-content">
              <i data-feather="loader" class="font-medium-5"></i>
            </div>
          </div>
        </div>
      </div>
</div>
    <div class="col-md-3 col-sm-6 col-12">
      <div class="card">
        <div class="card-header">
          <div>
            <h2 class="font-weight-bolder mb-0"><?= number_format($total_data_error,0,',','.'); ?></h2>
            <p class="card-text">Error & Partial</p>
          </div>
          <div class="avatar bg-light-danger p-50 m-0">
            <div class="avatar-content">
              <i data-feather="x-octagon" class="font-medium-5"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
      <div class="card">
        <div class="card-header">
          <div>
            <h2 class="font-weight-bolder mb-0"><?= number_format($total_data_processing,0,',','.'); ?></h2>
            <p class="card-text">Processing</p>
          </div>
          <div class="avatar bg-light-primary p-50 m-0">
            <div class="avatar-content">
              <i data-feather="filter" class="font-medium-5"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
      <div class="card">
        <div class="card-header">
          <div>
            <h2 class="font-weight-bolder mb-0"><?= number_format($total_data_success,0,',','.'); ?></h2>
            <p class="card-text">Success</p>
          </div>
          <div class="avatar bg-light-success p-50 m-0">
            <div class="avatar-content">
              <i data-feather="package" class="font-medium-5"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Social Media</h4>
                </div>
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped zero-configuration" id="datatabl3s">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Order ID</th>
                                    <th>Provider ID</th>
                                    <th>Username</th>
                                    <th>Layanan</th>
                                    <th>Target</th>
                                    <th>Status</th>
                                    <th>Act.</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                            <td colspan="8" class="text-center">Loading data from server...</td>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </section>
        <script type="text/javascript">
$(document).ready(function() {
    $('#datatabl3s').DataTable({
        "order": [[0, 'desc']],
        "processing": false,
        "serverSide": true,
        "paging": true,
        "pagingType": "simple_numbers",
        "ajax": "<?= base_url(); ?>/admin/trxsosmed/data_ajax/admin_pesanan_sosmed.php",
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
        "targets" : 7,
        "render" : function(data, type, row) {
            var btn = "<a href = \"javascript:;\" onclick=\"modal('Detail Pesanan','<?= base_url(); ?>/admin/trxsosmed/data_ajax/view-trxsosmed?id="+data+"')\"><p class=\"font-medium-5 mr-1\" style=\"text-decoration:none;\"><i class=\"mdi mdi-eye mdi-24px\"></i></a></p><a href = \"javascript:;\" onclick=\"modal('Edit Pesanan','<?= base_url(); ?>/admin/trxsosmed/data_ajax/edit-trxsosmed?id="+data+"')\"><p class=\"font-medium-5 ml-1\" style=\"text-decoration:none;\"><i class=\"mdi mdi-pencil-box-outline mdi-24px\"></i></a></p>"
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