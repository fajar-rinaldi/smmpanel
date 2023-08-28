<?php
session_start();
require_once '../../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	require_once '../../system/helpers/ssp.class.php';
	$table = 'user_notifications';
	$primaryKey = 'id';
	
	$columns = array(
		array( 'db' => 'created_at', 'dt' => 0, 'formatter' => function($i) {
			return "<center>".format_date('id',$i)."</center>";
		}),
		array( 'db' => 'title', 'dt' => 1),
		array( 'db' => 'message', 'dt' => 2, 'formatter' => function($i, $a) {
			return "".$i."<br>( ".$a['4']." )";
		}),
		array( 'db' => 'chookie', 'dt' => 3),
		array( 'db' => 'city', 'dt' => 4)
		
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