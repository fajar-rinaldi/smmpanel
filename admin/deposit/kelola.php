<?php
session_start();
require_once '../../mainconfig.php';
load('middleware', 'user');

if ($_SESSION['user']['level'] == "Reseller" || $_SESSION['user']['level'] == "Member") {
    header('location:' . base_url());
}

if (isset($_POST['edit_depo'])) {
    $post_k_id = $db->real_escape_string(base64_encode($_POST['k_id']));
    $check_depo = $db->query("SELECT * FROM deposit WHERE kode_deposit = '{$post_k_id}'");
    $check_data = $check_depo->fetch_assoc();
    $saldo = $check_data['get_saldo'] - $check_data['acakin'];
    $username = $check_data['username'];
    $k_id = $check_data['kode_deposit'];
    $tanggal = date('Y-m-d H:i:s');

    if (!$post_k_id) {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
    } else if ($_SESSION['user']['level'] == 'Lock') {
        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
    } else if ($check_data['status'] == 'Success') {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Deposit Success tidak dapat dirubah.'];
    } else if ($check_data['status'] == 'Cancelled') {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Deposit Cancelled tidak dapat dirubah.'];
    } else {
        $edit_depoo = $db->query("UPDATE users SET saldo = saldo+{$saldo} WHERE username = '{$username}'");
        $edit_depoo = $db->query("UPDATE deposit SET status = 'Success' WHERE kode_deposit = '{$post_k_id}'");
        $edit_depoo = $db->query("INSERT INTO history_saldo VALUES('', '{$username}', 'Penambahan Saldo', '{$saldo}', 'Deposit Saldo :: ({$k_id})', '{$tanggal}')");
        if ($edit_depoo === true) {
            $pesan = "*Hallo $username*

*Saldo Sebesar " . rp($saldo) . " Berhasil Ditambahkan ke akun Anda*
            
*Kode Deposit : $k_id*
*Tanggal : $date*
*Time : $time WIB*
*Status : Success*

*Terimakasih telah Bergabung Bersama kami di Plus Sosmed ðŸ˜Š.*";
            fonteSend($ceknomor['phone'], $pesan); // to member
            // fonteSend('Isi Nomor admin', $pesan); // to admin
            $_SESSION['alert'] = ['success', 'Success!', 'Deposit successfully change.'];
            exit(header("Location: " . base_url('/admin/deposit/kelola')));
        } else {
            $_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
        }
    }
}

if (isset($_POST['btl_depo'])) {
    $post_k_id = $db->real_escape_string(base64_encode($_POST['k_id']));

    if (!$post_k_id) {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
    } else if ($_SESSION['user']['level'] == 'Lock') {
        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
    } else if ($check_data['status'] == 'Success') {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Deposit Success tidak dapat dirubah.'];
    } else if ($check_data['status'] == 'Cancelled') {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Deposit Cancelled tidak dapat dirubah.'];
    } else {
        $edit_depoo = $db->query("UPDATE deposit SET status = 'Cancelled' WHERE kode_deposit = '{$post_k_id}'");
        if ($edit_depoo === true) {
            $_SESSION['alert'] = ['success', 'Success!', 'Deposit successfully cancelled.'];
            exit(header("Location: " . base_url('/admin/deposit/kelola')));
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
        $delete_depo = $db->query("DELETE FROM deposit WHERE id = '{$post_id}'");
        if ($delete_depo === true) {
            $_SESSION['alert'] = ['success', 'Success!', 'Deposit successfully delete.'];
            exit(header("Location: " . base_url('/admin/deposit/kelola')));
        } else {
            $_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
        }
    }
}
include_once '../../layouts/header_admin.php'; ?>
<section id="basic-vertical-layouts">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Deposit Manage</h4>
                </div>
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped zero-configuration" id="datatabl3s">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Faktur</th>
                                    <th>Username</th>
                                    <th>Payment</th>
                                    <th>Jumlah TF</th>
                                    <th>Sender</th>
                                    <th>Status</th>
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
        <!--/ Stats Horizontal Card -->
</section>
<script type="text/javascript">
    $(document).ready(function() {
        $('#datatabl3s').DataTable({
            "order": [
                [0, 'desc']
            ],
            "processing": false,
            "serverSide": true,
            "paging": true,
            "pagingType": "simple_numbers",
            "ajax": "<?= base_url(); ?>/admin/deposit/topup/nampil_data.php",
            "keys": !0,
            "drawCallback": function() {
                $(".dataTables_paginate > .pagination").addClass("pagination")
            },
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
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "<i class='fas fa-chevron-right'>",
                    "previous": "<i class='fas fa-chevron-left'>"
                },
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            },
            columnDefs: [{
                "searhable": false,
                "orderable": false,
                "targets": 7,
                "render": function(data, type, row) {
                    var btn = "<a href = \"javascript:;\" onclick=\"modal('Edit Deposit','<?= base_url(); ?>/admin/deposit/topup/edit_deposit?id=" + data + "')\"><p class=\"font-medium-5 mr-1\" style=\"text-decoration:none;\"><i class=\"mdi mdi-pencil-box-outline mdi-24px\"></i></a></p><a href = \"javascript:;\" onclick=\"modal('Delete Deposit','<?= base_url(); ?>/admin/deposit/topup/delete_deposit?id=" + data + "')\"><p class=\"font-medium-5 ml-1\" style=\"text-decoration:none;\"><i class=\"mdi mdi-trash-can mdi-24px\"></i></a></p>"
                    return btn;
                }
            }]

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
</div>
</div>
</div>
</div>
<?php include_once '../../layouts/footer.php'; ?>