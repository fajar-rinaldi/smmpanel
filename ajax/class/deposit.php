<?php
session_start();
require_once '../../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$table = 'deposit';
	$primaryKey = 'id';
        function status($s) {
        if ($s === "Success") {
        return '<div class="badge badge-success">Success</div>';
        } else if ($s === "Pending") {
        return '<div class="badge badge-warning">Pending</div>';
        } else if ($s === "Cancelled") {
        return '<div class="badge badge-danger">Cancelled</div>';
        } else {
        return '';
               }
        }
	
	$columns = array(
	array('db' => 'tid'),
	array('db' => 'status'),
	array('db' => 'id'),
	array( 'db' => 'tanggal',  'dt' => 0, 'formatter' => function($i) {
			return "<center>".format_date('en',$i)."</center>";
		}),
		array( 'db' => 'payment', 'dt' => 1),
		array( 'db' => 'get_saldo',  'dt' => 2, 'formatter' => function($i) {
			return "Rp ".number_format($i,0,',','.')."</center>";
		}),
		array( 'db' => 'nomor_pengirim',  'dt' => 3, 'formatter' => function($i) {
			return "<center>".$i."</center>";
		}),
		array( 'db' => 'status',  'dt' => 4, 'formatter' => function($i) {
			return "".status($i)."";
		}),
		array( 'db' => 'kode_deposit',  'dt' => 5, 'formatter' => function($i,$a) {
			if($a['tid'] == '' && $a['status'] == 'Pending') $but = '<a href="../../deposit/invoice?kode_deposit='.base64_encode($i).'" class="text-primary"><i class="mdi mdi-barcode-scan mdi-36px"></i></a>';
			if($a['tid'] !== '' && $a['status'] == 'Pending') $but = '<a href="https://tripay.co.id/checkout/'.$a['tid'].'" class="text-primary"><i class="mdi mdi-barcode-scan mdi-36px"></i></a><hr><a href="'.base_url('/deposit/cancel-get?id='.$a['id']).'" class="text-danger"><i class="mdi mdi-close-circle-outline mdi-36px"></i></a>';			
			return $but;
		}),
	);
	require_once '../../system/helpers/ssp.class.php';
	$sql_details = array(
		'user' => $_CONFIG['db']['username'],
		'pass' => $_CONFIG['db']['password'],
		'db'   => $_CONFIG['db']['name'],
		'host' => $_CONFIG['db']['host']
	);
	$joinQuery = null;
	$extraWhere = "username = '{$_SESSION['user']['username']}' ORDER BY id DESC";
	$groupBy = '';
	$having = '';
	print(json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
	));
} else {
	exit("No direct script access allowed!");
}