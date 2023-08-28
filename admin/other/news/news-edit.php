<?php
session_start();
require_once '../../../mainconfig.php';

if ($_SESSION['user']['level'] == "Reseller" || $_SESSION['user']['level'] == "Member") {
    header('location:' . base_url());
}

if (isset($_POST['editnews'])) {
        $post_id = $db->real_escape_string(e(@$_POST['id'])); 
        $post_tipe = $db->real_escape_string(e(@$_POST['tipe'])); 
	$post_content = $db->real_escape_string(e(@$_POST['content']));
	if (!$post_id) {
		$_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	
	} else { 
		$edit_news = $db->query("UPDATE information SET tipe = '{$post_tipe}', content = '{$post_content}' WHERE id = '{$post_id}'"); 
			if ($edit_news === true) {
				$_SESSION['alert'] = ['success', 'Success!', 'News successfully change.'];
				exit(header("Location: ".base_url('/admin/other/news_data')));
			} else {
				$_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
			}
		}
	}

if (!isset($_GET['id'])) {
    exit(header("Location: ".base_url()));
} else {
    $kode_news = $db->real_escape_string(e($_GET['id']));
    $check_news = $db->query("SELECT * FROM information WHERE id = '{$kode_news}'");
    if (mysqli_num_rows($check_news) == 0) {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Information not found.'];
        
        exit(header("Location: ".base_url()));
    } else {
        $news = $check_news->fetch_assoc();
                
        }
 }
require '../../../layouts/header_admin.php'; 
?>
<section id="dashboard-analytics">
<div class="row">
    <div class="col-12">
        <div class="card overflow-hidden">
            <div class="card-header">
                <h4 class="card-title">Edit News</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                 <input type="hidden" id="csrf_token" name="csrf_token" value="<?= csrf_token() ?>">
               <div class="form-group">
                        <label>Type</label>
                        <input type="text" class="form-control" name="id" value="<?= $news['id']; ?>" readonly>
                    </div> 
                    <div class="form-group">
                                    <label>Tipe</label>
                                    <select class="form-control" name="tipe">
                                        <option value="news" <?= $news['tipe'] == 'news' ? 'selected' : '' ?>>NEWS</option>
                                        <option value="info" <?= $news['tipe'] == 'info' ? 'selected' : '' ?>>INFO</option>
                                        <option value="update" <?= $news['tipe'] == 'update' ? 'selected' : '' ?>>UPDATE</option>
                                        <option value="maintenance" <?= $news['tipe'] == 'maintenance' ? 'selected' : '' ?>>MAINTENANCE</option>
                                    </select>
                                </div>                    
                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="content"><?= $news['content']; ?></textarea>
                    </div>
                    <div class="form-group text-right">
                        <button type="button" onclick="javascript:location.href='<?= base_url(); ?>admin/other/news_data'" class="btn btn-warning waves-effect waves-light">Back</button>                        
                        <button type="submit" name="editnews" class="btn btn-primary waves-effect waves-light">CHANGE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</section>
<?php require '../../../layouts/footer.php'; ?>
<script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script><script>CKEDITOR.replace('content',{toolbarGroups:[{'name':'basicstyles','groups':['basicstyles']},{'name':'links','groups':['links']},{'name':'paragraph','groups':['list','blocks']},{'name':'document','groups':['mode']},{'name':'insert','groups':['insert']},{'name':'styles','groups':['styles']},{'name':'about','groups':['about']}],removeButtons:'Blockquote,Table,Source,Strike,Subscript,Superscript,Anchor,Styles,SpecialChar,About'});</script>