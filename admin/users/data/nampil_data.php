<?php
session_start();
require_once '../../../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	require_once '../../../system/helpers/ssp.class.php';
	$table = 'users';
	$primaryKey = 'id';
	
	$columns = array(
	array( 'db' => 'username', 'dt' => 0),
	array( 'db' => 'name', 'dt' => 1),
	array( 'db' => 'email', 'dt' => 2),
	array( 'db' => 'phone', 'dt' => 3),
		array( 'db' => 'saldo', 'dt' => 4, 'formatter' => function($i) {
			return "".number_format($i,0,',','.')."";
		}),
		array( 'db' => 'register_at', 'dt' => 5, 'formatter' => function($i) {
			return "<center>".format_date('id',$i)."</center>";
		}),
		array( 'db' => 'id', 'dt' => 6)
		
	);
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