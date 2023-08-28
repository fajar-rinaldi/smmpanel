<?php
session_start();
require_once '../../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$table = 'tiket';
	$primaryKey = 'id';
        function status($s) {
        if ($s === "Responded") {
            return '<div class="badge badge-success">Responded</div>';
        } else if ($s === "Waiting") {
            return '<div class="badge badge-warning">Waiting</div>';
        } else if ($s === "Closed") {
            return '<div class="badge badge-danger">Closed</div>';
        } else {
            return '<div class="badge badge-warning">Pending</div>';
               }
        }
	
	$columns = array(
	        array( 'db' => 'user', 'dt' => 0),
	        array( 'db' => 'tanggal',  'dt' => 1, 'formatter' => function($i) {
			return "<center>".format_date('en',$i)."</center>";
		}),
		array( 'db' => 'tanggal_at',  'dt' => 2, 'formatter' => function($i) {
			return "<center>".format_date('en',$i)."</center>";
		}),
		array( 'db' => 'tipe', 'dt' => 3),
		array( 'db' => 'status',  'dt' => 4, 'formatter' => function($i) {
			return "".status($i)."";
		}),
		array( 'db' => 'id',  'dt' => 5, 'formatter' => function($i) {
			return '<a href="../ticket/replay?id='.base64_encode($i).'" class="btn btn-relief-primary btn-sm round"><i class="fas fa-folder-open me-1"></i>Balas Tiket</a><br/> <br><a href="../ticket/delete?id='.base64_encode($i).'" class="btn btn-relief-danger btn-sm round"><i class="fas fa-trash me-1"></i>Hapus Tiket</a>';
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
	$extraWhere = '';
	$groupBy = '';
	$having = '';
	print(json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
	));
} else {
	exit("No direct script access allowed!");
}