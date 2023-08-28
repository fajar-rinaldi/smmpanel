<?php
require_once '../../../mainconfig.php';

$id = $_GET['id'];
if(!isset($id))
    die('No direct script access allowed!');
$query = mysqli_query($db, "SELECT * FROM layanan_sosmed WHERE id = '$id'");
$q = mysqli_fetch_assoc($query);

function status($s) {
    if ($s === "Aktif") {
        return '<div class="badge badge-success">Aktif</div>';
    } else {
        return '<div class="badge badge-danger">Tidak Aktif</div>';
    }
}
?>

<div class="row">
          <div class="col-md-12">     
          <div class="table-responsive">
    <table class="table table-bordered mb-0" style="border:1px solid #dee2e6;">
                          
          <tr>
          <th class="table-detail">Kategori</th>
          <td class="table-detail"><div class=""><?php echo $q['kategori']; ?></div></td>
          </tr>
          
          <tr>
          <th class="table-detail">Layanan</th>
          <td class="table-detail"><div class=""><?php echo $q['layanan']; ?></div></td>
          </tr>
          
          <tr>
          <th class="table-detail">Min</th>
          <td class="table-detail"><div class=""><?php echo $q['min']; ?></div></td>
          </tr>
          
          <tr>
          <th class="table-detail">Max</th>
          <td class="table-detail"><div class=""><?php echo $q['max']; ?></div></td>
          </tr>
          
          <tr>
          <th class="table-detail">Note</th>
          <td class="table-detail"><small><?php echo $q['catatan']; ?></small></td>
          </tr>
          
          <tr>
          <th class="table-detail">Harga</th>
          <td class="table-detail"><li>Rp <?php echo number_format($q['harga'],0,',','.'); ?> [Web]</li><li>Rp <?= number_format($q['harga_api'],0,',','.'); ?> [API]</li></td>
          </tr>
          
          <tr>
          <th class="table-detail">Status</th>
          <td class="table-detail"><?php echo status($q['status']); ?></td>
          </tr>
                   
          </table>
          </div>                                                         
          </div>
          </div>       