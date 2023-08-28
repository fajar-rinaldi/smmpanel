<?php
session_start();
require_once '../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if (!isset($_POST['category'])) {
		exit("No direct script access allowed!");
	}
if (isset($_POST['category'])) {
	$post_kategori = $db->real_escape_string(e(@$_POST['category']));
	
	$data = $db->query("SELECT * FROM kategori_layanan ORDER BY nama ASC");	
        $cek = $data->fetch_assoc();     
           
function type($a, $i) {
	if ($a === "All") {
	   return "'". $i ."'";
	} else if ($a === "Instagram") {
	   return "'". preg_match('/Instagram/', $i) ."'";
	} else if ($a === "Facebook") {
	   return "'". preg_match('/Facebook/', $i) ."'";
	} else if ($a === "Twitter") {
	   return "'". preg_match('/Twitter/', $i) ."'";
	} else if ($a === "TikTok") {
	   return "'". preg_match('/TikTok/', $i) ."'";
	} else if ($a === "Spotify") {
	   return "'". preg_match('/Spotify/', $i) ."'";
	} else if ($a === "Google") {
	   return "'". preg_match('/Google/', $i) ."'";
	} else if ($a === "Telegram") {
	   return "'". preg_match('/Telegram/', $i) ."'";
	} else if ($a === "Discord") {
	   return "'". preg_match('/Discord/', $i) ."'";
	} else if ($a === "Twitch") {
	   return "'". preg_match('/Twitch/', $i) ."'";
	} else if ($a === "Website Traffic") {
	   return "'". preg_match('/Website Traffic/', $i) ."'"; 
	} else if ($a === "Youtube") {
	   return "'". preg_match('/Youtube/', $i) ."'";	
	} 
}
	
	$query = $db->query("SELECT * FROM kategori_layanan WHERE kode = '". type($post_kategori, $cek['kode']) ."' ORDER BY kode ASC");	
	?>	
        <option value="0">Pilih Salah Satu...</option>
        <?php
	    while ($q = mysqli_fetch_assoc($query)) {
	?>
	<option value="<?= $q; ?>"><?= $q; ?></option>
	<?php
	}
} else {
?>
        <option value="0">Error.</option>
<?php
}
} else {
	exit("No direct script access allowed!");
}
