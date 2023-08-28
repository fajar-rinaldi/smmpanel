<?php
session_start();
require_once '../../mainconfig.php';
load('middleware', 'user');

if ($_SESSION['user']['level'] == "Reseller" || $_SESSION['user']['level'] == "Member") {
    header('location:' . base_url());
}

if (isset($_POST['editmt'])) {
        $post_id = $db->real_escape_string(e(@$_POST['id']));   
        $post_nama = $db->real_escape_string(e(@$_POST['NamaWeb']));    
	$post_keywords = $db->real_escape_string(e(@$_POST['Keyword']));
	$post_description = $db->real_escape_string(e(@$_POST['Description']));
	$post_wa = $db->real_escape_string(e(@$_POST['GrupWa']));
	$post_nomor = $db->real_escape_string(e(@$_POST['Nomor']));
	$post_alamat = $db->real_escape_string(e(@$_POST['Alamat']));
	$post_maintenance = $db->real_escape_string(e(@$_POST['maintenance']));
	$post_reason = $db->real_escape_string(e(@$_POST['reason']));

	if (!$post_id || !$post_nama || !$post_keywords || !$post_description || !$post_wa || !$post_nomor || !$post_alamat || !$post_maintenance || !$post_reason) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	} else {
	        $edit_mt = $db->query("UPDATE setting_website SET NamaWeb = '{$post_nama}', Keyword = '{$post_keywords}', Description = '{$post_description}', GrupWa = '{$post_wa}', Nomor = '{$post_nomor}', Alamat = '{$post_alamat}', maintenance = '{$post_maintenance}', reason_mt = '{$post_reason}' WHERE id = '{$post_id}'");
	        if ($edit_mt === true) {
				$_SESSION['alert'] = ['success', 'Success!', 'Configuration successfully change.'];
				exit(header("Location: ".base_url('/admin/other/config')));
			} else {
				$_SESSION['alert'] = ['success', 'Success!', 'Configuration successfully change.'];
			        exit(header("Location: ".base_url('/admin/other/config')));
			       }
			}
	} 
include_once '../../layouts/header_admin.php'; ?>
<section id="basic-vertical-layouts">
  <div class="row">
    <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Configuration</h4>
                </div>
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped zero-configuration" id="datatable">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Keyword</th>
                                    <th>Description</th>
                                    <th>Group WhatsApp</th>
                                    <th>Nomer</th>
                                    <th>Alamat</th>
                                    <th>Maintenance</th>
                                    <th>Reason MT</th>
                                    <th>Act.</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                    <?php
                    $check_config = $db->query("SELECT * FROM setting_website WHERE id = '1' ORDER BY id DESC LIMIT 1");
                    while($data_config = $check_config->fetch_assoc()) :
                    ?>
                            <tr>
                                <td><?= $data_config['NamaWeb']; ?></td>
                                <td><?= $data_config['Keyword']; ?></td>
                                <td><?= $data_config['Description']; ?></td>
                                <td><?= $data_config['GrupWa']; ?></td>
                                <td><?= $data_config['Nomor']; ?></td>
                                <td><?= $data_config['Alamat']; ?></td>
                                <td><?= $data_config['maintenance']; ?></td>
                                <td><?= $data_config['reason_mt']; ?></td>
                                <td>
                            <a href="javascript:;" onclick="modal('Edit Configuration','<?= base_url(); ?>admin/other/configuration/edit-config?id=<?= $data_config['id']; ?>','','md')">
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