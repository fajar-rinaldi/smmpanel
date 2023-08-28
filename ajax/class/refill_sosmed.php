<?php
session_start();
require_once '../../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	require_once '../../system/helpers/ssp.class.php';
	$table = 'refill_sosmed';
	$primaryKey = 'id';
	
function status($s) {
    if ($s === "Success") {
        return '<div class="badge badge-success">Success</div>';
    } else if ($s === "Pending") {
        return '<div class="badge badge-warning"><i class="mdi mdi-autorenew mdi-spin"></i> Pending</div>';
    } else if ($s === "Processing") {
        return '<div class="badge badge-info"><i class="mdi mdi-cube-send"></i> Processing</div>';
    } else if ($s === "Partial") {
        return '<div class="badge badge-secondary">Partial</div>';
    } else {
        return '<div class="badge badge-danger">Error</div>';
    }
}

	$columns = array(
		array( 'db' => 'tanggal', 'dt' => 0, 'formatter' => function($i) {
			return "<center>".format_date('id',$i)."</center>";
		}),
		array( 'db' => 'order_id', 'dt' => 1, 'formatter' => function($i) {
		    $oid = $i;
			return "#".$i."";
		}),
		array( 'db' => 'status', 'dt' => 2, 'formatter' => function($i) {
			return "". status($i)."";
		}),
	);

	$sql_details = array(
		'user' => $_CONFIG['db']['username'],
		'pass' => $_CONFIG['db']['password'],
		'db'   => $_CONFIG['db']['name'],
		'host' => $_CONFIG['db']['host']
	);
	$joinQuery = null;
	$extraWhere = "username = '$session' ORDER BY id DESC";
	$groupBy = '';
	$having = '';
	print(json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
	));
} else {
	exit("No direct script access allowed!");
}