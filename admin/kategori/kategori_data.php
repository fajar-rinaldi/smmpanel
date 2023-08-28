<?php
require_once '../../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$table = 'kategori_layanan';
	$primaryKey = 'id';

	$columns = array(
	array( 'db' => 'kode', 'dt' => 0),
		array( 'db' => 'nama', 'dt' => 1),
		array( 'db' => 'tipe', 'dt' => 2),
		array( 'db' => 'id', 'dt' => 3)
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