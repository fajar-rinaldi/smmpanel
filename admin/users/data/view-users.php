<?php
require_once '../../../mainconfig.php';

$id = $_GET['id'];
if(!isset($id))
    die('No direct script access allowed!');
$query = mysqli_query($db, "SELECT * FROM users WHERE id = '$id'");
$q = mysqli_fetch_assoc($query); 
?>

<div class="card-body card-dashboard">
    <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="profile-tab-fill" data-toggle="tab" href="#detail" role="tab" aria-controls="detail" aria-selected="false">Detail</a>
        </li>
            </ul>
    <div class="tab-content">
        <div class="tab-pane show active" id="detail">
            <div class="table-responsive">
                <table class="table table-bordered" style="border:1px solid #dee2e6;">
                    <tbody>
                        <tr><th>Full Name</th><td><?= $q['name']; ?></td></tr>
                        <tr><th>Email Address</th><td><?= $q['email']; ?></td></tr>
                        <tr><th>Phone Number</th><td><?= $q['phone']; ?></td></tr>
                        <tr><th>Username</th><td><?= $q['username']; ?></td></tr>
                        <tr><th>Balance</th><td>Rp <?= number_format($q['saldo'],0,',','.'); ?></td></tr>
                        <tr><th>Balance Usage</th><td>Rp <?= number_format($q['pemakaian'],0,',','.'); ?></td></tr>                       
                        <tr><th>Point</th><td>Rp <?= number_format($q['poin'],0,',','.'); ?></td></tr>
                        <tr><th>Level</th><td><?= $q['level']; ?></td></tr>
                        <tr><th>Referral</th><td><?= $q['refferal']; ?></td></tr>                        
                        <tr><th>Joined</th><td><?= $q['register_at']; ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
            </div>
</div>       