<?php
require_once '../../mainconfig.php';

if (isset($_POST['kategori'])) {
    $post_server = $db->real_escape_string(e(@$_POST['server']));
	$post_kategori = $db->real_escape_string(e(@$_POST['kategori']));
	$cek_layanan = $db->query("SELECT * FROM layanan_sosmed WHERE kategori = '$post_kategori' AND tipe = '$post_server' ORDER BY harga ASC");
	if (mysqli_num_rows($cek_layanan) != 0) {
	
function status($s) {
    if ($s === "Aktif") {
        return '<div class="badge badge-light-success">Aktif</div>';
    } else {
        return '<div class="badge badge-light-danger">Tidak Aktif</div>';
           }
    }
	?>

                    <div class="table-responsive">
                                    <table class="table table-bordered table-striped zero-configuration mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center" rowspan="2" style="vertical-align:middle">ID</th>
                                                <th class="text-center" rowspan="2" style="vertical-align:middle">Layanan</th>
                                                <th class="text-center" rowspan="2" style="vertical-align:middle">Min</th>
                                                <th class="text-center" rowspan="2" style="vertical-align:middle">Max</th>
                                                <th class="text-center" rowspan="2" style="vertical-align:middle;width:30%">Deskripsi</th>
                                                <th class="text-center" colspan="2" style="vertical-align:middle">Harga/1K</th>
                                                <th class="text-center" rowspan="2" style="vertical-align:middle">Status</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Member</th>
                                                <th class="text-center">Reseller</th>                                              
                                            </tr>
                                        </thead>
                            <tbody>
                            <?php
                            while ($data_layanan = mysqli_fetch_assoc($cek_layanan)) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $data_layanan['provider_id']; ?></th>
                                    <td><?php echo $data_layanan['layanan']; ?></td>
                                    <td><?php echo $data_layanan['min']; ?></td>
                                    <td><?php echo $data_layanan['max']; ?></td>
                                    <td><small><?php echo $data_layanan['catatan']; ?></small></td>
                                    <td><span class="badge badge-light-primary">Rp <?php echo number_format($data_layanan['harga'],0,',','.'); ?></span></td>                                   
                                    <td><span class="badge badge-light-warning">Rp <?php echo number_format($data_layanan['harga_api'],0,',','.'); ?></span></td>
                                    <td><?php echo status($data_layanan['status']); ?></td>
                                    
                                    
                                </tr>
                            <?php
                            } 
                            ?>
                            </tbody>
                        </table>
                    </div>
<?php
} else {
?>
</div>
<?php
}
}