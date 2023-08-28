<?php
session_start();
require_once '../../mainconfig.php';
load('middleware', 'user');

if ($_SESSION['user']['level'] == "Reseller" || $_SESSION['user']['level'] == "Member") {
    header('location:' . base_url());
}

if (isset($_POST['add'])) {
	$post_tipe = $db->real_escape_string(e(@$_POST['tipe']));
	$post_content = $db->real_escape_string(e(@$_POST['content']));
	if (!$post_tipe || !$post_content) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'There is still a blank form.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	} else {
	$date = date('Y-m-d H:i:s');
	$insert_news = $db->query("INSERT INTO information VALUES('', '{$post_tipe}', '{$post_content}','{$date}')");
	$insert_news = $db->query("UPDATE users SET read_news = '0'"); 		
			if ($insert_news === true) {
				$_SESSION['alert'] = ['success', 'Success!', 'News successfully add.'];
				exit(header("Location: ".base_url('/admin/other/news_data')));
			} else {
				$_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
			}
		}
	}

if (isset($_POST['delete'])) {
	$post_content = $db->real_escape_string(e(@$_POST['content']));
	if (!$post_content) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
        } else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
        } else { 
                $delete_news = $db->query("DELETE FROM information WHERE content = '{$post_content}'");
                if ($delete_news === true) {
                    $_SESSION['alert'] = ['success', 'Success!', 'News successfully delete.'];
                    exit(header("Location: ".base_url('/admin/other/news_data')));
                } else {
                    $_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
                }
           }
      }      	
include_once '../../layouts/header_admin.php'; ?>
<section id="basic-tabs-components">
    <div class="row">
        <div class="col-12">
            <div class="card overflow-hidden">
                <div class="card-header">
                    <h4 class="card-title">News Manage</h4>
                </div>
                <div class="card-body card-dashboard">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="NaaList-tab" data-toggle="tab" href="#NaaList" aria-controls="NaaList" role="tab" aria-selected="true">List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="NaaAdd-tab" data-toggle="tab" href="#NaaAdd" aria-controls="NaaAdd" role="tab" aria-selected="false">Content</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="NaaList" aria-labelledby="NaaList-tab" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered zero-configuration datatable" id="datatable" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Content</th>
                                            <th>Act.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $check_data = $db->query("SELECT * FROM information ORDER BY id DESC");
                                    while($data_berita = mysqli_fetch_assoc($check_data)) : ?>
                                        <tr>
                                        <td><b><i class="far fa-calendar-alt mr-1"></i> <?= format_date('id',$data_berita['tanggal']); ?> | <?= $data_berita['tipe']; ?><hr></b> <?= html_entity_decode($data_berita['content']) ?></td>
                                        <td>
                                        <a href="../../admin/other/news/news-edit?id=<?= $data_berita['id']; ?>"> <p class="font-medium-5 mr-1" style="text-decoration:none;"><i class="mdi mdi-pencil-box-outline mdi-24px"></i></p>
                                        </a>
                            <a href="javascript:;" onclick="modal('Delete News','<?= base_url(); ?>admin/other/news/delete-news?id=<?= $data_berita['id']; ?>','','md')">
                        <p class="font-medium-5 ml-1" style="text-decoration:none;"><i class="mdi mdi-trash-can mdi-24px"></i></p>
                            </a></td>
                                        </tr>
                                    <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="NaaAdd" aria-labelledby="NaaAdd-tab" role="tabpanel">
                            <form method="POST">
                                <input type="hidden" id="csrf_token" name="csrf_token" value="<?= csrf_token() ?>">
                                <div class="form-group">
                                    <label>Tipe</label>
                                    <select class="form-control" name="tipe">
                                        <option value="0" selected disabled>- Select One -</option>
                                        <option value="news">NEWS</option>
                                        <option value="info">INFO</option>
                                        <option value="update">UPDATE</option>
                                        <option value="maintenance">MAINTENANCE</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Content</label>
                                    <textarea name="content"></textarea>
                                </div>
                                <div class="form-group text-right">
                                    <button type="reset" class="btn btn-danger waves-effect waves-light">Reset</button>
                                    <button type="submit" name="add" class="btn btn-primary waves-effect waves-light">ADD</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
<script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script><script>CKEDITOR.replace('content',{toolbarGroups:[{'name':'basicstyles','groups':['basicstyles']},{'name':'links','groups':['links']},{'name':'paragraph','groups':['list','blocks']},{'name':'document','groups':['mode']},{'name':'insert','groups':['insert']},{'name':'styles','groups':['styles']},{'name':'about','groups':['about']}],removeButtons:'Blockquote,Table,Source,Strike,Subscript,Superscript,Anchor,Styles,SpecialChar,About'});</script>