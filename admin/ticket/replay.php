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

mysqli_query($db, "UPDATE tiket SET is_admin = '1' WHERE id = '".$post_id."'");

function status($s)
{
    if ($s === "Waiting") {
        return '<span class="badge rounded-pill badge-light-warning">Waiting</span>';
    } elseif ($s === "Responded") {
        return '<span class="badge rounded-pill badge-light-success">Responded</span>';
    } elseif ($s === "Closed") {
        return '<span class="badge rounded-pill badge-light-danger">Closed</span>';
    } 
}

if (mysqli_num_rows($cek_tiket) == 0) {
    $_SESSION['alert'] = ['danger', 'Failed!', 'Ticket Not Found.'];
    exit(header("Location: " . base_url('/admin/ticket/list')));
} else {
    if (isset($_POST['balas'])) {
        $pesan = $db->real_escape_string(trim(filter($_POST['pesan'])));
        if ($data_tiket['status'] == "Closed") {
            $_SESSION['alert'] = ['danger', 'Failed!', 'Ticket Closed.'];
            exit(header("Location: " . base_url('/admin/ticket/list')));
        } elseif (!$pesan) {
            $_SESSION['alert'] = ['danger', 'Failed!', 'Mohon isi semua input.'];
        } elseif (strlen($pesan) > 500) {
            $_SESSION['alert'] = ['danger', 'Failed!', 'Maksimal pesan 500 Karakter.'];
        } else {
            $date = date('Y-m-d H:i:s');
            $user = $data_tiket['user'];

            $tiketDB = $db->query("INSERT INTO pesan_tiket VALUES ('', '{$post_id}', 'Admin', '{$user}', '{$pesan}',  '{$date}','{$date}')");
            $tiketDB = $db->query("UPDATE tiket SET tanggal_at = '{$date}', is_admin = '1', is_user = '0', status = 'Responded' WHERE id = '{$post_id}'");
            if ($tiketDB == true) {
                $_SESSION['alert'] = ['success', 'Success!', 'Ticket successfully send.'];
                exit(header("Location: " . base_url('/admin/ticket/replay?id=' . base64_encode($post_id))));
            } else {
                $_SESSION['alert'] = ['danger', 'Failed!', 'Ticket Failed send.'];
                exit(header("Location: " . base_url('/admin/ticket/replay?id=' . base64_encode($post_id))));
            }
        }
    }
    if (isset($_POST['tutup'])) {
        $postID = $db->real_escape_string(e(base64_decode($_POST['id'])));
        $CheckTiket = $db->query("SELECT * FROM tiket WHERE id = '{$postID}'");
        if ($CheckTiket->num_rows == 0) {
            $_SESSION['alert'] = ['danger', 'Failed!', 'Ticket Not Found.'];
        } else {
            $tutup = $db->query("UPDATE tiket SET status = 'Closed' WHERE id = '{$postID}'");
            if ($tutup == true) {
                $_SESSION['alert'] = ['success', 'Success!', 'Ticket successfully Closed.'];
                exit(header("Location: " . base_url('/admin/ticket/replay?id=' . base64_encode($postID))));
            } else {
                $_SESSION['alert'] = ['danger', 'Failed!', 'Ticket Failed Closed.'];
                exit(header("Location: " . base_url('/admin/ticket/replay?id=' . base64_encode($postID))));
            }
        }
    }
}
include_once '../../layouts/header_admin.php'; ?>
<div class="row justify-content-left">
    <div class="col-md-7 col-12">
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
                                <span class="fw-bold">Pembaruan Terakhir</span><br />
                                <?= format_date('id',$data_tiket['tanggal_at']); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-right">
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-body border-bottom">
                <h4 class="card-title"><i class="far fa-comments me-1"></i> Balas Tiket</h4>
                <div class="card-body" style="max-height: 400px; overflow: auto;">
                    <div class="row">
                        <?php
                    $check_data = mysqli_query($db, "SELECT * FROM pesan_tiket WHERE id_tiket = '{$post_id}'");
                    while($query = mysqli_fetch_assoc($check_data)) : ?>
                        <div class="col-md-12">
                            <div class="alert alert-<?= ($query['pengirim'] == 'Admin') ? 'success' : 'info' ?>" role="alert">
                                <div class="alert-body">
                                    <h5 class="text-info mb-1">
                                        <strong>
                                            <?= ($query['pengirim'] == 'Admin') ? 'Admin' : $query['user'] ?>
                                        </strong>
                                        <br />
                                        <span class="text-secondary mb-1 float-end">
                                            <small><?= format_date('id',$query['tanggal']); ?></small>
                                        </span>
                                    </h5>
                                    <p class="text-secondary mb-1">
                                        <strong>
                                            <em><?= $query['pesan']; ?></em>
                                        </strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <form method="POST">
                    ​<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>" /> ​
                    <div class="mb-1">
                        ​<label class="form-label">Pesan <span class="text-danger">*</span></label> ​<textarea class="form-control" name="pesan" rows="2"></textarea> ​
                    </div>
                    ​
                        ​<button type="submit" name="balas" class="btn btn-relief-primary float-end"><i class="fas fa-reply me-1"></i> Kirim</button> ​
                        <button type="reset" class="btn btn-relief-danger float-end me-1"><i class="fas fa-sync me-1"></i> Ulangi</button>
                        <?php if ($data_tiket['status'] !== 'Closed') { ?>
                        <button type="submit" name="tutup" class="btn btn-relief-danger""><i class="fas fa-window-close me-1"></i> Tutup Tiket</button>​
                        <?php } ?>
                    </div>
                    ​
                </form>
                ​
            </div>
            ​
        </div>
        ​
    </div>
</div>

<?php include_once '../../layouts/footer.php'; ?>