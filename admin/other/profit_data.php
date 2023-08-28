<?php
session_start();
require_once '../../mainconfig.php';
load('middleware', 'user');

if ($_SESSION['user']['level'] == "Reseller" || $_SESSION['user']['level'] == "Member") {
    header('location:' . base_url());
}

if (isset($_POST['edit_keuntungan'])) {
        $post_code = $db->real_escape_string(e(@$_POST['code']));
        
	$post_web = $db->real_escape_string(e(@$_POST['web']));
	$post_api = $db->real_escape_string(e(@$_POST['api']));
	
	if (!$post_code || !$post_web || !$post_api) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	
	} else {
	        $edit_profit = $db->query("UPDATE keuntungan SET web = '{$post_web}', api = '{$post_api}' WHERE code = '{$post_code}'");
	        if ($edit_profit === true) {
				$_SESSION['alert'] = ['success', 'Success!', 'Profit successfully change.'];
				exit(header("Location: ".base_url('/admin/other/profit_data')));
			         } 
			}
	} 
include_once '../../layouts/header_admin.php'; ?>
<section id="basic-vertical-layouts">
  <div class="row">
    <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Profit Manage</h4>
                </div>
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped zero-configuration" id="datatable">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Web</th>
                                    <th>API</th>
                                    <th>Act.</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                    <?php
                    $check_keuntungan = $db->query("SELECT * FROM keuntungan ORDER BY id DESC");
                    while($data_k = $check_keuntungan->fetch_assoc()) :
                    ?>
                            <tr>
                                <td><?= $data_k['code']; ?></td>
                                <td><?= number_format($data_k['web'],0,',','.'); ?></td>
                                <td><?= number_format($data_k['api'],0,',','.'); ?></td>
                                <td>
                            <a href="javascript:;" onclick="modal('Edit Profit','<?= base_url(); ?>admin/other/profit/edit-profit?id=<?= $data_k['id']; ?>','','md')">
                                <i class="mdi mdi-pencil-box-outline mdi-24px"></i>
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
  <!--/ Stats Horizontal Card -->
</section>
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