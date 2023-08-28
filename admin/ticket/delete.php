<?php
session_start();
require_once '../../mainconfig.php';
load('middleware', 'user');

if ($_SESSION['user']['level'] == "Reseller" || $_SESSION['user']['level'] == "Member") {
    header('location:' . base_url());
}

$post_id = $db->real_escape_string(e(base64_decode($_GET['id'])));
$cek_tiket = $db->query("SELECT * FROM tiket WHERE id = '{$post_id}'");
$data_tiket = $cek_tiket->fetch_assoc();

if (!isset($_GET['id'])) {
    exit(header("Location: " . base_url('/admin/ticket/list')));
}

function status($s)
{
    if ($s === "Waiting") {
        return '<span class="badge rounded-pill badge-light-warning">Waiting</span>';
    } elseif ($s === "Responded") {
        return '<span class="badge rounded-pill badge-light-success">Responded</span>';
    } elseif ($s === "Closed") {
        return '<span class="badge rounded-pill badge-light-danger">Closed</span>';
    } else {
        return '<span class="badge rounded-pill badge-light-warning">Pending</span>';
    }
}

if (mysqli_num_rows($cek_tiket) == 0) {
    $_SESSION['alert'] = ['danger', 'Failed!', 'Ticket Not Found.'];
    exit(header("Location: " . base_url('/admin/ticket/list')));
} else {
    if (isset($_POST['delete'])) {
        $postID = $db->real_escape_string(e(base64_decode($_POST['id'])));
        if ($data_user['user']['level'] == 'Lock') {
            $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
            exit(header("Location: " . base_url('/admin/ticket/list')));
        } else { 
            $ticketDEL = $db->query("DELETE FROM tiket WHERE id = '{$postID}'");
            $ticketDEL = $db->query("DELETE FROM pesan_tiket WHERE id_tiket = '{$postID}'");
            if ($ticketDEL == true) {
                $_SESSION['alert'] = ['success', 'Success!', 'Ticket successfully delete.'];
                exit(header("Location: " . base_url('/admin/ticket/list')));
            } else {
                $_SESSION['alert'] = ['danger', 'Failed!', 'Ticket Failed delete.'];
                exit(header("Location: " . base_url('/admin/ticket/list')));
            }
        }
    }
}
include_once '../../layouts/header_admin.php'; ?>
<div class="row">
    <div class="col-12">
        <a href="<?= base_url('/admin/ticket/list') ?>" class="btn btn-relief-primary" style="margin-bottom: 15px;"><i class="fas fa-arrow-circle-left me-1"></i>Kembali</a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body border-bottom">
                <h4 class="card-title"><i class="fas fa-info-circle me-1"></i>Informasi</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <td>
                                <span class="fw-bold">Tipe</span><br />
                                <?= $data_tiket['tipe']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="fw-bold">Pesan</span><br />
                                <?= $data_tiket['pesan']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="fw-bold">Status</span><br />
                                <?= status($data_tiket['status']); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="fw-bold">Tgl Dibuat</span><br />
                                <?= format_date('id',$data_tiket['tanggal']); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-12">
                <form method="POST">
                    ​<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>" /> ​
                    <input type="hidden" class="form-control" name="id" value="<?= base64_encode($data_tiket['id']); ?>" readonly />
                    <div class="mb-0">
                        ​<button type="submit" name="delete" class="btn btn-block btn-success">DELETE</button>
                        ​
                    </div>
                    ​
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once '../../layouts/footer.php'; ?>
