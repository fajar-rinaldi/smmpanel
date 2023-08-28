<?php
session_start();
require_once '../mainconfig.php';

if(!isset($_SESSION['user'])) {
    header('location:' . base_url('/auth/login'));
}

include_once '../layouts/header.php'; ?>
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Aktifitas Login</h4>     
                </div>
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped zero-configuration" id="tabl3">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pesan</th>
                                    <th>IP</th>
                                    <th>Device</th>
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
    $('#tabl3').DataTable({
        "ordering": false,
        "processing": false,
        "serverSide": true,
        "paging": true,
        "pagingType": "simple_numbers",
        "ajax": "<?= base_url(); ?>/ajax/class/aktifitas_login.php",
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
            "searchPlaceholder": "Ex:Ytb Rigaming",
            "zeroRecords": "Data not found",
            "paginate": {"first": "First","last": "Last","next": "<i class='fas fa-chevron-right'>","previous": "<i class='fas fa-chevron-left'>"},
            "aria": {"sortAscending": ": activate to sort column ascending","sortDescending": ": activate to sort column descending"}
        }
    });
});
</script>
<?php include_once '../layouts/footer.php'; ?>