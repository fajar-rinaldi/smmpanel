<?php
require '../mainconfig.php';
header('Content-Type: application/json');
if ($maintenance == 1) {
	$hasilnya = array('status' => false, 'data' => array('pesan' => 'Maintenance'));
	exit(json_encode($hasilnya, JSON_PRETTY_PRINT));
}
if (isset($_POST['api_key']) AND isset($_POST['action'])) {
	$api = $db->real_escape_string($_POST['api_key']);
	$act = $_POST['action'];

	$cek_user = $db->query("SELECT * FROM users WHERE api_key = '$api'");
        $data_user = $cek_user->fetch_assoc(); 
        
	if (!$api || !$act) {
		$hasilnya = array('status' => false, 'data' => array('pesan' => 'Permintaan tidak sesuai.'));
	} else if ($_SESSION['user']['level'] == 'Lock') {
                        $_SESSION['alert'] = ['danger', 'Failed!', 'your account has been locked.'];
	} else if (mysqli_num_rows($cek_user) == 0) {
		$hasilnya = array('status' => false, 'data' => array('pesan' => 'API Key salah.'));
	} else if ($_SERVER['REMOTE_ADDR'] !== $data_user['ip_static']) {
	        $hasilnya = array('status' => false, 'data' => array('pesan' => 'IP Static '.$_SERVER['REMOTE_ADDR'].' tidak dikenali.'));
	} else if ($data_user['api_status'] != 'ON') { 
	        $hasilnya = array('status' => false, 'data' => array('pesan' => 'API status anda OFF.'));
       
        } else { 
        
		$cek_usernya = $db->query("SELECT * FROM users WHERE api_key = '{$api}'");
		$datanya = $cek_usernya->fetch_assoc();
		if (mysqli_num_rows($cek_usernya) == 1) {
		if ($act == 'pemesanan') {
		if (isset($_POST['layanan']) AND isset($_POST['target']) AND isset($_POST['jumlah'])) {
			$layanan = $db->real_escape_string(trim(filter($_POST['layanan'])));
			$target = $db->real_escape_string(trim(filter($_POST['target'])));
			$jumlah = $db->real_escape_string(trim(filter($_POST['jumlah'])));
		
		if (!$layanan || !$target || !$jumlah) {
		$hasilnya = array('status' => false, 'data' => array('pesan' => 'Permintaan tidak sesuai'));
		} else {
		$cek_layanan = $db->query("SELECT * FROM layanan_sosmed WHERE provider_id = '{$layanan}' AND status = 'Aktif'");
		$data_layanan = $cek_layanan->fetch_assoc();
		if (mysqli_num_rows($cek_layanan) == 0) {
		$hasilnya = array('status' => false, 'data' => array('pesan' =>'server overload silahkan gunakan layanan lain'));
		} else {
			$order_id = random_number(6);
			$cek_profit = $data_layanan['profit'] / 500;
			$cek_harga = $data_layanan['harga_api'] / 500;
			$profit = $cek_profit*$jumlah;
			$harga = $cek_harga*$jumlah;
			$provider = $data_layanan['provider'];
        		
        		//Get Start Count
        		if ($data_layanan['kategori'] == "Instagram Likes" AND "Instagram Likes Indonesia" AND "Instagram Likes [Targeted Negara]" AND "Instagram Likes/Followers Per Minute") {
            		$start_count = likes_count($target);
        		} else if ($data_layanan['kategori'] == "Instagram Followers No Refill/Not Guaranteed" AND "Instagram Followers Indonesia" AND "Instagram Followers [Negara]" AND "Instagram Followers [Refill] [Guaranteed] [NonDrop]") {
            		$start_count = followers_count($target);
        		} else if ($data_layanan['kategori'] == "Instagram Views") {
            		$start_count = views_count($target);
        		} else {
            			$start_count = 0;
        		}
			
			if ($jumlah < $data_layanan['min']) {
			$hasilnya = array('status' => false, 'data' => array('pesan' =>'Minimum order is not appropriate'));
			} else if ($jumlah > $data_layanan['max']) {
			$hasilnya = array('status' => false, 'data' => array('pesan' =>'Max order does not match'));
			} else if ($datanya['saldo'] < $harga) {
			$hasilnya = array('status' => false, 'data' => array('pesan' =>'Your balance is not sufficient to place this order'));
			} else {
			$cek_provider = $db->query("SELECT * FROM provider WHERE id = '2'");
			$q = $cek_provider->fetch_assoc();
			
        if ($provider) {
            // PROVIDER 2
            $post_api = [
                'api_id' => $q['api_id'],
                'api_key' => $q['api_key'],
                'secret_key' => $q['secret_key'],
                'service' => $data_layanan['provider_id'],
                'target' => $post_target,
                'quantity' => $post_jumlah,
            ];
            $endpoint = "" . $q['link'] . "order";
            $curl = post_curl($endpoint, $post_api);

            $result = json_decode($curl, true);
            if (isset($result['response']) and $result['response'] == true) {
                $provider_oid = $result['data']['id'];
                $result_api = true;
            }
        }
        //print_r($result);
        if ($result_api == false) {
            $hasilnya = array('status' => false, 'data' => array('pesan' => ''.$result['data']['msg']));
        }        
	
	$tanggal = date('Y-m-d H:i:s');
	if($db->query("INSERT INTO pembelian_sosmed VALUES ('','{$order_id}', '{$provider_oid}', '{$datanya['username']}', '{$datanya['layanan']}', '{$target}', '-', '{$jumlah}', '0', '{$start_count}', '{$harga}', '{$profit}', 'Pending', '{$tanggal}', '-', '{$provider}', 'API', 'no')") == true) {
	$db->query("UPDATE users SET saldo = saldo-{$harga}, pemakaian = pemakaian+{$harga} WHERE username = '{$datanya['username']}'");
        $db->query("INSERT INTO history_saldo VALUES('', '{$datanya['username']}', 'Pengurangan Saldo', '{$harga}', 'Order with API :: ({$order_id})', '{$tanggal}')");	
	$hasilnya = array('status' => true, 'data' => array('id' => $order_id, 'start_count' => $start_count));
	} else {
		$hasilnya = array('status' => false, 'data' => array('pesan' => 'System Error'));
									}
								}
							}
						}
					}
		} else {
			$hasilnya = array('status' => false, 'data' => array('pesan' => 'System Error'));
					}
					
			} else if ($act == 'status') {
				if (isset($_POST['id'])) {
					$order_id = $db->real_escape_string(trim($_POST['id']));
					$cek_pesanan = $db->query("SELECT * FROM pembelian_sosmed WHERE oid = '{$order_id}' AND provider != 'Manual' AND user = '{$datanya['username']}'");
					$data_pesanan = mysqli_fetch_array($cek_pesanan);
					if (mysqli_num_rows($cek_pesanan) == 0) {
						$hasilnya = array('status' => false, 'data' => array('pesan' => 'Order ID tidak ditemukan'));
					} else {
						$hasilnya = array('status' => true, 'data' => array("id" => $data_pesanan['oid'], 'status' => $data_pesanan['status'], 'start_count' => $data_pesanan['start_count'], 'remains' => $data_pesanan['remains']));
					}
				} else {
					$hasilnya = array('status' => false, 'data' => array('pesan' => 'Permintaan tidak sesuai'));
				}
				
			} else if ($act == 'layanan') {
					$cek_layanan = $db->query("SELECT * FROM layanan_sosmed WHERE status = 'Aktif' AND tipe = 'Sosial Media' AND provider != 'Manual' ORDER BY provider_id ASC");
					while($rows = mysqli_fetch_array($cek_layanan)){
					$hasilnya = "-";
					$this_data[] = array('id' => $rows['provider_id'], 'category' => $rows['kategori'], 'service' => $rows['layanan'], 'note' => $rows['catatan'], 'min' => $rows['min'], 'max' => $rows['max'], 'price' => $rows['harga_api']);
                }
						$hasilnya = array('status' => true, 'data' => $this_data);
			} else {
				$hasilnya = array('status' => false, 'data' => array('pesan' => 'Permintaan salah'));
			}
		} else {
			$hasilnya = array('status' => false, 'data' => array('pesan' => 'API key salah'));
		}
	}
} else {
	$hasilnya = array('status' => false, 'data' => array('pesan' => 'Permintaan tidak sesuai'));
}

print(json_encode($hasilnya, JSON_PRETTY_PRINT));