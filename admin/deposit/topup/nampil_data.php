<?php
session_start();
require_once '../../../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	require_once '../../../system/helpers/ssp.class.php';
	$table = 'deposit';
	$primaryKey = 'id';
	
function status($s) {
    if ($s === "Success") {
        return '<div class="badge badge-success">Success</div>';
    } else if ($s === "Pending") {
        return '<div class="badge badge-warning">Pending</div>';
    } else {
        return '<div class="badge badge-danger">Cancelled</div>';
    }
}
	
	$columns = array(
	        array( 'db' => 'acakin'),
		array( 'db' => 'tanggal', 'dt' => 0, 'formatter' => function($i) {
			return "<center>".format_date('id',$i)."</center>";
		}),
		array( 'db' => 'kode_deposit', 'dt' => 1),
		array( 'db' => 'username', 'dt' => 2),
		array( 'db' => 'payment', 'dt' => 3),
		array( 'db' => 'jumlah_transfer', 'dt' => 4, 'formatter' => function($i,$a) {
			return "Rp ".number_format($i+$a['acakin'],0,',','.')."";
		}),
		array( 'db' => 'nomor_pengirim', 'dt' => 5),
		array( 'db' => 'status', 'dt' => 6, 'formatter' => function($i) {
			return "". status($i)."";
		}),
		array( 'db' => 'id', 'dt' => 7)
		
		
	);
	$sql_details = array(
		'user' => $_CONFIG['db']['username'],
		'pass' => $_CONFIG['db']['password'],
		'db'   => $_CONFIG['db']['name'],
		'host' => $_CONFIG['db']['host']
	);
	$joinQuery = null;
	$extraWhere ='';
	$groupBy = '';
	$having = '';
	print(json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
	));
} else {
	exit("No direct script access allowed!");
}