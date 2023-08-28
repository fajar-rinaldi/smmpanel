<?php
session_start();
require '../mainconfig.php';
header('Content-Type: application/json');

if ($maintenance == 1) {
	$hasilnya = array('status' => false, 'data' => array('pesan' => 'API sedang maintenance'));
	exit(json_encode($hasilnya, JSON_PRETTY_PRINT));
}

if (isset($_POST['api_key']) AND isset($_POST['action'])) {
	$api_key = $db->real_escape_string($_POST['api_key']);
	$action = $_POST['action'];
	
	$cek_user = $db->query("SELECT * FROM users WHERE api_key = '{$api_key}'");
        $data_user = $cek_user->fetch_assoc(); 
        
	if (!$api_key || !$action) {
		$hasilnya = array('status' => false, 'data' => array('pesan' => 'Permintaan tidak sesuai'));
	} else if ($_SESSION['user']['level'] == 'Lock') {
                $_SESSION['alert'] = ['danger', 'Failed!', 'Your account has been locked.'];
	} else if (mysqli_num_rows($cek_user) == 0) {
		$hasilnya = array('status' => false, 'data' => array('pesan' => 'API KEY salah'));
	} else if ($_SERVER['REMOTE_ADDR'] !== $data_user['ip_static']) {
	        $hasilnya = array('status' => false, 'data' => array('pesan' => 'IP Static '.$_SERVER['REMOTE_ADDR'].' tidak diizinkan.'));
	} else if ($data_user['api_status'] != 'ON') { 
	        $hasilnya = array('status' => false, 'data' => array('pesan' => 'API status anda OFF'));
       
        } else { 
        
		if ($action == 'profile') {
		$cek_user = $db->query("SELECT * FROM users WHERE api_key = '{$api_key}'");
		
		while($rows = mysqli_fetch_array($cek_user)){
                $hasilnya = "-";
		$this_data[] = array('username' => $rows['username'], 'balance' => $rows['saldo'], 'point' => $rows['poin'], 'spent' => $rows['pemakaian']);
                }
		$hasilnya = array('status' => true, 'data' => $this_data);
		
		} else {
		
		$hasilnya = array('status' => false, 'data' => array('pesan' => 'Permintaan salah'));
		}
	}
	
} else {

	$hasilnya = array('status' => false, 'data' => array('pesan' => 'Permintaan tidak sesuai'));
}

print(json_encode($hasilnya, JSON_PRETTY_PRINT));