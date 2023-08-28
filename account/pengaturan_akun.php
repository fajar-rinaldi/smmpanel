<?php

session_start();
require_once '../mainconfig.php';
// load('middleware', 'csrf');
load('middleware', 'user');

if (isset($_POST['wd'])) {
    $post_poin = $db->real_escape_string(e(@$_POST['poin']));
    $post_pin = $db->real_escape_string(e(@$_POST['pin']));

    $check_pin = mysqli_query($db, "SELECT * FROM users WHERE pin = '{$post_pin}'");
    $pin_check = mysqli_fetch_assoc($check_pin);

    if (!$post_poin || !$post_pin) {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
    } elseif ($_SESSION['user']['level'] == 'Lock') {
        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
    } elseif ($data_user['poin'] < $post_poin) {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Poin anda tidak cukup.'];
    } elseif ($post_poin < 500) {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Withdraw minimal 500 poin.'];
    } elseif ($pin_check == 0) {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Konfirmasi PIN tidak benar.'];
    } else {
        $kode = random_number(6);
        $tanggal = date('Y-m-d H:i:s');
        $insert_depo = $db->query("UPDATE users SET saldo = saldo+$post_poin WHERE username = '{$session}'");
        $insert_depo = $db->query("UPDATE users SET poin = poin-$post_poin WHERE username = '{$session}'");
        $insert_depo = $db->query("INSERT INTO history_saldo VALUES('', '{$session}', 'Penambahan Saldo', '{$post_poin}', 'Withdraw Poin :: ({$kode})', '{$tanggal}')");

        if ($insert_depo == true) {
            $_SESSION['alert'] = ['success', 'Success!', 'Withdraw berhasil Rp ' . number_format($post_poin, 0, ',', '.') . ''];
            exit(header("Location: " . base_url('/account/pengaturan_akun')));
        } else {
            $_SESSION['hasil'] = ['alert' => 'danger', 'judul' => 'Failed!', 'pesan' => 'Invalid Code #505'];
        }
    }
}

if (isset($_POST['save_general'])) {
    $post_full_name = $db->real_escape_string(e(@$_POST['full_name']));
    $post_phone = $db->real_escape_string(e(@$_POST['phone']));
    
    if (!$post_full_name || !$post_phone) {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
    } elseif ($_SESSION['user']['level'] == 'Lock') {
        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
    } else {
        $update_general = $db->query("UPDATE users SET name = '{$post_full_name}', phone = '{$post_phone}' WHERE id = '{$_SESSION['user']['id']}'");
        if ($update_general === true) {
            $_SESSION['user']['name'] = $post_full_name;
            $_SESSION['alert'] = ['success', 'Success!', 'Edit profile berhasil.'];
            exit(header("Location: " . base_url('/account/pengaturan_akun')));
        } else {
            $_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
        }
    }
} elseif (isset($_POST['save_pin'])) {
    $post_new_pin = $db->real_escape_string(e(@$_POST['new_pin']));
    $post_conf_pin = $db->real_escape_string(e(@$_POST['conf_pin']));
    $post_pin = $db->real_escape_string(e(@$_POST['pin']));

    $check_pin = mysqli_query($db, "SELECT * FROM users WHERE pin = '{$post_pin}'");
    $pin_check = mysqli_fetch_assoc($check_pin);

    if (!$post_new_pin || !$post_conf_pin || !$post_pin) {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
    } elseif ($_SESSION['user']['level'] == 'Lock') {
        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
    } elseif ($pin_check == 0) {
        $_SESSION['alert'] = ['danger', 'Failed!', 'PIN Lama anda tidak benar!'];
    } elseif ($post_new_pin !== $post_conf_pin) {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Konfirmasi PIN tidak sama.'];
    } else {
        $update_pin = $db->query("UPDATE users SET pin = '{$post_new_pin}' WHERE id = '{$_SESSION['user']['id']}'");
        if ($update_pin === true) {
            $_SESSION['alert'] = ['success', 'Success!', 'Edit PIN berhasil.'];
            exit(header("Location: " . base_url('/account/pengaturan_akun')));
        } else {
            $_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
        }
    }
} elseif (isset($_POST['u_api'])) {
    $post_api_status = $db->real_escape_string(e(@$_POST['api_status']));
    $post_ip = $db->real_escape_string(e(@$_POST['ip']));
    $post_pin = $db->real_escape_string(e(@$_POST['pin']));

    $check_pin = mysqli_query($db, "SELECT * FROM users WHERE pin = '{$post_pin}'");
    $pin_check = mysqli_fetch_assoc($check_pin);

    if (!$post_api_status || !$post_ip || !$post_pin) {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
    } elseif ($_SESSION['user']['level'] == 'Lock') {
        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked .'];
    } elseif ($pin_check == 0) {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Konfirmasi PIN tidak benar.'];
    } else {
        $update_db = $db->query("UPDATE users SET api_status = '{$post_api_status}', ip_static = '{$post_ip}' WHERE username = '{$session}'");
        if ($update_db === true) {
            $_SESSION['alert'] = ['success', 'Success!', 'Edit API berhasil.'];
            exit(header("Location: " . base_url('/account/pengaturan_akun')));
        } else {
            $_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
        }
    }
} elseif (isset($_POST['update_apikey'])) {
    $apikey = random(30);
    if ($db->query("UPDATE users SET api_key = '{$apikey}' WHERE username = '{$session}'") == true) {
        $_SESSION['alert'] = ['success', 'Success!', 'API key berhasil generate.'];
        exit(header("Location: " . base_url('/account/pengaturan_akun')));
    } else {
        $_SESSION['alert'] = ['danger', 'Failed!', '404.'];
    }
} elseif (isset($_POST['save_password'])) {
    $post_password = $db->real_escape_string(e(@$_POST['password']));
    $post_new_password = $db->real_escape_string(e(@$_POST['new-password']));
    $post_confirm_new_password = $db->real_escape_string(e(@$_POST['confirm-new-password']));

    if (!$post_password || !$post_new_password || !$post_confirm_new_password) {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Masih ada form kosong.'];
    } elseif ($post_new_password !== $post_confirm_new_password) {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Konfirmasi password tidak sama.'];
    } elseif ($_SESSION['user']['level'] == 'Lock') {
        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
    } elseif (password_verify($post_password, $_SESSION['user']['password']) === false) {
        $_SESSION['alert'] = ['danger', 'Failed!', 'Wrong Old Password.'];
    } else {
        $password = password_hash($post_new_password, PASSWORD_DEFAULT);
        $update_password = $db->query("UPDATE users SET password = '{$password}' WHERE id = '{$_SESSION['user']['id']}'");
        if ($update_password === true) {
            $_SESSION['user']['password'] = $password;
            $_SESSION['alert'] = ['success', 'Success!', 'Edit profile berhasil.'];
            exit(header("Location: " . base_url('/account/pengaturan_akun')));
        } else {
            $_SESSION['alert'] = ['danger', 'Failed!', 'System is busy, please try again later.'];
        }
    }
}

include_once '../layouts/header.php'; ?>
<section id="page-account-settings">
    <div class="row">
        <div class="col-md-3 mb-2 mb-md-0">
            <ul class="nav nav-pills flex-column nav-left">
                <li class="nav-item">
                    <a class="nav-link active" id="account-pill-general" data-toggle="pill" href="#account-vertical-general" aria-expanded="true">
                        <i data-feather="user" class="font-medium-3 mr-1"></i>
                        <span class="font-weight-bold">Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="account-pill-password" data-toggle="pill" href="#account-vertical-password" aria-expanded="false">
                        <i data-feather="lock" class="font-medium-3 mr-1"></i>
                        <span class="font-weight-bold">Ubah Password</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="account-pill-pin" data-toggle="pill" href="#account-vertical-pin" aria-expanded="false">
                        <i data-feather="key" class="font-medium-3 mr-1"></i>
                        <span class="font-weight-bold">Ubah PIN</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="account-pill-poin" data-toggle="pill" href="#account-vertical-poin" aria-expanded="false">
                        <i data-feather="repeat" class="font-medium-3 mr-1"></i>
                        <span class="font-weight-bold">Withdraw Poin</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="account-pill-api" data-toggle="pill" href="#account-vertical-api" aria-expanded="false">
                        <i data-feather="code" class="font-medium-3 mr-1"></i>
                        <span class="font-weight-bold">API Setting</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="account-vertical-general" aria-labelledby="account-pill-general" aria-expanded="true">
                            <div class="media">
                                <a href="javascript:void(0);" class="mr-25">
                                    <img src="<?= gravatar($_SESSION['user']['email']) ?>" id="account-upload-img" class="rounded mr-50" alt="profile image" height="80" width="80" />
                                </a>
                                <div class="media-body mt-75 ml-1">
                                    <label for="account-upload" class="btn btn-sm btn-primary mb-75 mr-75" onclick="javascript:window.open('https://en.gravatar.com/emails/');">Ubah Avatar</label>
                                    <button class="btn btn-sm btn-outline-secondary mb-75" disabled>📸</button>
                                    <p>Foto Profil Bisa di ubah Via Gravatar</p>
                                </div>
                            </div>
                            <form class="validate-form mt-2" method="POST">
                                <input type="hidden" id="csrf_token" name="csrf_token" value="<?= csrf_token() ?>" />
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" class="form-control" name="full_name" value="<?= e($_SESSION['user']['name']) ?>" required />
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Pemakaian</label>
                                            <input type="text" class="form-control" value="Rp <?= number_format(e($_SESSION['user']['pemakaian'],0,',','.')) ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input type="number" class="form-control" name="phone" value="<?= e($_SESSION['user']['phone']) ?>"  required />
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" value="<?= e($_SESSION['user']['email']) ?>" disabled />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" name="save_general" class="btn btn-primary mt-2 mr-1">Simpan</button>
                                        <button type="reset" class="btn btn-outline-secondary mt-2">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
                            <form class="validate-form" method="POST">
                                <input type="hidden" id="csrf_token" name="csrf_token" value="<?= csrf_token() ?>" />
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Password Baru</label>
                                            <div class="input-group form-password-toggle input-group-merge">
                                                <input type="password" name="new-password" class="form-control" placeholder="" />
                                                <div class="input-group-append">
                                                    <div class="input-group-text cursor-pointer">
                                                        <i data-feather="eye"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Konfirmasi Password Baru</label>
                                            <div class="input-group form-password-toggle input-group-merge">
                                                <input type="password" class="form-control" name="confirm-new-password" placeholder="" />
                                                <div class="input-group-append">
                                                    <div class="input-group-text cursor-pointer"><i data-feather="eye"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Konfirmasi Password Lama</label>
                                            <div class="input-group form-password-toggle input-group-merge">
                                                <input type="password" class="form-control" name="password" required />
                                                <div class="input-group-append">
                                                    <div class="input-group-text cursor-pointer">
                                                        <i data-feather="eye"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" name="save_password" class="btn btn-primary mr-1 mt-1">Simpan</button>
                                        <button type="reset" class="btn btn-outline-danger mt-1">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="account-vertical-poin" role="tabpanel" aria-labelledby="account-pill-poin" aria-expanded="false">
                            <form class="validate-form" method="POST">
                                <input type="hidden" id="csrf_token" name="csrf_token" value="<?= csrf_token() ?>" />
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Jumlah Bonus Poin</label>
                                            <div class="input-group input-group-merge">
                                                <input type="number" class="form-control" name="poin" onkeyup="this.value=this.value.replace(/[^\d]+/g,'')" data-validation-required-message="This phone field is required" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Konfirmasi PIN</label>
                                            <div class="input-group form-password-toggle input-group-merge">
                                                <input
                                                    type="password"
                                                    class="form-control"
                                                    name="pin"
                                                    onkeyup="this.value=this.value.replace(/[^\d]+/g,'')"
                                                    placeholder=""
                                                    minlength="6"
                                                    maxlength="6"
                                                    data-validation-required-message="This phone field is required"
                                                    required
                                                />
                                                <div class="input-group-append">
                                                    <div class="input-group-text cursor-pointer">
                                                        <i data-feather="eye"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" name="wd" class="btn btn-primary mr-1 mt-1">Tukar Point</button>
                                        <button type="reset" class="btn btn-outline-secondary mt-1">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="account-vertical-pin" role="tabpanel" aria-labelledby="account-pill-pin" aria-expanded="false">
                            <form class="validate-form" method="POST">
                                <input type="hidden" id="csrf_token" name="csrf_token" value="<?= csrf_token() ?>" />
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>PIN Baru</label>
                                            <div class="input-group form-password-toggle input-group-merge">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="new_pin"
                                                    onkeyup="this.value=this.value.replace(/[^\d]+/g,'')"
                                                    placeholder="6 digit"
                                                    minlength="6"
                                                    maxlength="6"
                                                    data-validation-required-message="This phone field is required"
                                                    required
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Konfirmasi PIN Baru</label>
                                            <div class="input-group form-password-toggle input-group-merge">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="conf_pin"
                                                    onkeyup="this.value=this.value.replace(/[^\d]+/g,'')"
                                                    placeholder="6 digit"
                                                    minlength="6"
                                                    maxlength="6"
                                                    data-validation-required-message="This phone field is required"
                                                    required
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Konfirmasi PIN Lama</label>
                                            <div class="input-group form-password-toggle input-group-merge">
                                                <input
                                                    type="password"
                                                    class="form-control"
                                                    name="pin"
                                                    onkeyup="this.value=this.value.replace(/[^\d]+/g,'')"
                                                    placeholder=""
                                                    minlength="6"
                                                    maxlength="6"
                                                    data-validation-required-message="This phone field is required"
                                                    required
                                                />
                                                <div class="input-group-append">
                                                    <div class="input-group-text cursor-pointer">
                                                        <i data-feather="eye"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" name="save_pin" class="btn btn-primary mr-1 mt-1">Simpan</button>
                                        <button type="reset" class="btn btn-outline-danger mt-1">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="account-vertical-api" role="tabpanel" aria-labelledby="account-pill-api" aria-expanded="false">
                            <form method="POST">
                                <input type="hidden" id="csrf_token" name="csrf_token" value="<?= csrf_token() ?>" />
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Status API</label>
                                            <select class="form-control" name="api_status">
                                                <option value="ON" <?= $data_user['api_status'] == 'ON' ? 'selected' : '' ?>>ON | AKTIF</option>
                                                <option value="OFF" <?= $data_user['api_status'] == 'OFF' ? 'selected' : '' ?>>OFF | NOAKTIF</option>
                       
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>API Key</label>
                                            <div class="input-group">
                                                <input class="form-control" value="<?= $data_user['api_key'] ?>" disabled />
                                                <div class="input-group-append">
                                                    <button type="submit" name="update_apikey" class="btn btn-primary waves-effect waves-light">
                                                        <i class="fas fa-random"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>IP Static</label>
                                                <input class="form-control" name="ip" value="<?= $data_user['ip_static'] ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Konfirmasi PIN</label>
                                            <div class="input-group form-password-toggle input-group-merge">
                                                <input
                                                    type="password"
                                                    class="form-control"
                                                    name="pin"
                                                    onkeyup="this.value=this.value.replace(/[^\d]+/g,'')"
                                                    placeholder=""
                                                    minlength="6"
                                                    maxlength="6"
                                                    data-validation-required-message="This phone field is required"
                                                />
                                                <div class="input-group-append">
                                                    <div class="input-group-text cursor-pointer">
                                                        <i data-feather="eye"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" name="u_api" class="btn btn-primary mr-1 mt-1">Simpan</button>
                                        <button type="reset" class="btn btn-outline-secondary mt-1">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include_once '../layouts/footer.php'; ?>
