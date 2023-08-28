<?php
require_once '../../../mainconfig.php';
$id = $_GET['id'];
if(!isset($id))
    die('No direct script access allowed!');
$query = mysqli_query($db, "SELECT * FROM users WHERE id = '$id'");
$q = mysqli_fetch_assoc($query); 
?>

<form method="POST">
<div class="card-body card-dashboard">
        <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="profile-tab-fill" data-toggle="tab" href="#detail" role="tab" aria-controls="detail" aria-selected="false">Detail</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="home-tab-fill" data-toggle="tab" href="#userPass" role="tab" aria-controls="userPass" aria-selected="false">Password</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="home-tab-fill" data-toggle="tab" href="#minBalance" role="tab" aria-controls="minBalance" aria-selected="false">Balance</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane show active" id="detail">
            <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" name="u_user" value="<?= $q['username']; ?>" required="" data-validation-required-message="This username field is required" readonly>
                </div>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" class="form-control" name="u_name" value="<?= $q['name']; ?>" required="" data-validation-required-message="This name field is required" minlength="5" maxlength="32">
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" class="form-control" name="u_mail" value="<?= $q['email']; ?>" required="" data-validation-required-message="This email field is required">
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" class="form-control" name="u_phone" value="<?= $q['phone']; ?>" onkeyup="this.value=this.value.replace(/[^\d]+/g,'')" required="" data-validation-required-message="This phone field is required" minlength="12" maxlength="14">
                    <small class="text-danger">*Only supports Indonesian numbers.</small>
                </div>
                <div class="form-group">
                    <label>Level</label>
                    <select class="form-control" name="u_level" required="" data-validation-required-message="This level field is required">
            <option value="Member" <?= $q['level'] == 'Member' ? 'selected' : '' ?>>Member</option>
            <option value="Reseller" <?= $q['level'] == 'Reseller' ? 'selected' : '' ?>>Reseller</option>
            <option value="Admin" <?= $q['level'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
            <option value="Lock" <?= $q['level'] == 'Lock' ? 'selected' : '' ?>>Lock</option>
            </select>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <button type="button" name="reset" class="btn btn-danger btn-block" data-dismiss="modal"> BACK </button>
                    </div>
                    <div class="form-group col-6">
                        <button type="submit" name="users_edit1" class="btn btn-primary btn-block"> CHANGE </button>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="userPass">
            <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" name="up_user" value="<?= $q['username']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" class="form-control" name="up_new">
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" class="form-control" name="up_confirm">
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <button type="button" name="reset" class="btn btn-danger btn-block" data-dismiss="modal"> BACK </button>
                    </div>
                    <div class="form-group col-6">
                        <button type="submit" name="users_edit2" class="btn btn-primary btn-block"> CHANGE </button>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="minBalance">
            <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" name="ub_user" value="<?= $q['username']; ?>" readonly>
                </div>                
                <div class="form-group">
                    <label>Cut Balance</label>
                    <input type="number" class="form-control" name="ub_cut">
                </div>
                <div class="form-group">
                    <label>Reason</label>
                    <input type="text" class="form-control" name="ub_reason">
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <button type="button" name="reset" class="btn btn-danger btn-block" data-dismiss="modal"> BACK </button>
                    </div>
                    <div class="form-group col-6">
                        <button type="submit" name="users_edit4" class="btn btn-primary btn-block"> CUT </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
