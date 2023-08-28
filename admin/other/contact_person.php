<?php
session_start();
require_once '../../mainconfig.php';
load('middleware', 'user');

if ($_SESSION['user']['level'] == "Reseller" || $_SESSION['user']['level'] == "Member") {
    header('location:' . base_url());
}

if (isset($_POST['edit_contact'])) {
        $post_id = $db->real_escape_string(e(@$_POST['id']));
        
	$post_nama = $db->real_escape_string(e(@$_POST['nama']));
	$post_alamat = $db->real_escape_string(e(@$_POST['alamat']));
	$post_jabatan = $db->real_escape_string(e(@$_POST['jabatan']));
	$post_whatsapp = $db->real_escape_string(e(@$_POST['whatsapp']));
	$post_instagram = $db->real_escape_string(e(@$_POST['instagram']));
	$post_link = $db->real_escape_string(e(@$_POST['link']));
	$post_url = $db->real_escape_string(e(@$_POST['url']));
	
	if (!$post_id) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	} else {
	        $edit_contact = $db->query("UPDATE kontak_kami SET nama = '{$post_nama}', alamat = '{$post_alamat}', jabatan = '{$post_jabatan}', whatsapp = '{$post_whatsapp}', instagram = '{$post_instagram}', link = '{$post_link}', url = '{$post_url}' WHERE id = '{$post_id}'");
	        if ($edit_contact === true) {
				$_SESSION['alert'] = ['success', 'Success!', 'Contact successfully change.'];
				exit(header("Location: ".base_url('/admin/other/contact_person')));
			} else {
				$_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
			       }
			}
	} 
	
if (isset($_POST['add_contact'])) {
	$post_nama = $db->real_escape_string(e(@$_POST['nama']));
	$post_alamat = $db->real_escape_string(e(@$_POST['alamat']));
	$post_jabatan = $db->real_escape_string(e(@$_POST['jabatan']));
	$post_whatsapp = $db->real_escape_string(e(@$_POST['whatsapp']));
	$post_instagram = $db->real_escape_string(e(@$_POST['instagram']));
	$post_link = $db->real_escape_string(e(@$_POST['link']));
	$post_url = $db->real_escape_string(e(@$_POST['url']));
	
	if (!$post_nama || !$post_alamat || !$post_jabatan || !$post_whatsapp || !$post_instagram || !$post_link || !$post_url) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	} else {
		$insert_contact = $db->query("INSERT INTO kontak_kami VALUES('', '{$post_nama}', '{$post_alamat}', '{$post_jabatan}', '{$post_whatsapp}', '{$post_instagram}', '{$post_link}', '{$post_url}')");
			if ($insert_contact === true) {
				$_SESSION['alert'] = ['success', 'Success!', 'Contact successfully add.'];
				exit(header("Location: ".base_url('/admin/other/contact_person')));
			} else {
				$_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
			}
		}
	}

if (isset($_POST['delete'])) {
	$post_contact_name = $db->real_escape_string(e(@$_POST['contact_name']));
	if (!$post_contact_name) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
        } else { 
                $delete_contact = $db->query("DELETE FROM kontak_kami WHERE nama = '{$post_contact_name}'");
                if ($delete_contact === true) {
                    $_SESSION['alert'] = ['success', 'Success!', 'Contact successfully delete.'];
                    exit(header("Location: ".base_url('/admin/other/contact_person')));
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
                    <h4 class="card-title">Contact Manage</h4>
                    <a href="javascript:;" onclick="modal('Add Contact','<?= base_url(); ?>admin/other/contact/add-contact','','md')">
                        <p class="font-medium-5 mb-0"><i class="text-primary" data-feather="plus-circle" style="width: 24px; height: 24px"></i></p>
                            </a>
                </div>
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped zero-configuration" id="datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Alamat</th>
                                    <th>Title</th>
                                    <th>Site</th>
                                    <th>Act.</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                    <?php
                    $check_cntk = $db->query("SELECT * FROM kontak_kami ORDER BY id DESC");
                    while($data_contact = $check_cntk->fetch_assoc()) :
                    ?>
                            <tr>
                                <td><?= $data_contact['nama']; ?></td>
                                <td><?= $data_contact['alamat']; ?></td>
                                <td><?= $data_contact['jabatan']; ?></td>
                                <td><li class="mt-1">Whatsapp: <b><?= $data_contact['whatsapp']; ?></b></li><li>Instagram: <b><?= $data_contact['instagram']; ?></b></li><li>Link: <b><?= $data_contact['link']; ?></b></li><li>Profile Images: <b><?= $data_contact['url']; ?></b></li></td>
                                <td>
                            <a href="javascript:;" onclick="modal('Edit Contact','<?= base_url(); ?>admin/other/contact/edit-contact?id=<?= $data_contact['id']; ?>','','md')">
                        <p class="font-medium-5 mr-1" style="text-decoration:none;"><i class="mdi mdi-pencil-box-outline mdi-24px"></i></p>
                            </a>
                            <a href="javascript:;" onclick="modal('Delete Contact','<?= base_url(); ?>admin/other/contact/delete-contact?id=<?= $data_contact['id']; ?>','','md')">
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