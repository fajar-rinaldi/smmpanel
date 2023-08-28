<?php
require_once '../../mainconfig.php';

if (isset($_POST['operator'])) {
    $post_tipe = $db->real_escape_string(e(@$_POST['tipe']));
	$post_kategori = $db->real_escape_string(e(@$_POST['operator']));
	$cek_layanan = $db->query("SELECT * FROM layanan_pulsa WHERE operator = '$post_kategori' AND tipe ='$post_tipe' ORDER BY harga ASC");
	if (mysqli_num_rows($cek_layanan) != 0) {
	?>

                     <div class="table-responsive">
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Layanan</th>
                                    <th style="min-width: 110px;">WEB</th>
                                    <th style="min-width: 110px;">RES</th>
                                    <th style="min-width: 100px;">API</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($data_layanan = mysqli_fetch_assoc($cek_layanan)) {
                            if($data_layanan['status'] == "Normal") {
                                $label = "success";
                                $icon = "mdi mdi-checkbox-marked-circle-outline";
                            } else if($data_layanan['status'] == "Gangguan") {
                                $label = "danger";
                                $icon = "mdi mdi-close-circle-outline";
                            }
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $data_layanan['provider_id']; ?></th>
                                    <td><?php echo $data_layanan['layanan']; ?></td>
                                    <td><span class="badge badge-light-primary">Rp <?php echo number_format($data_layanan['harga'],0,',','.'); ?></span></td>
                                    <td><span class="badge badge-light-success">Rp <?php echo number_format($data_layanan['harga_reseller'],0,',','.'); ?></span></td>
                                    <td><span class="badge badge-light-warning">Rp <?php echo number_format($data_layanan['harga_api'],0,',','.'); ?></span></td>
                                    <td><small><?php echo $data_layanan['note']; ?></small></td>
                                    <td><span class="badge badge-light-<?php echo $label; ?>"><i class="<?php echo $icon; ?>"></i> <?php echo $data_layanan['status']; ?></span></td>
                                </tr>
                                <?php } ?>
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