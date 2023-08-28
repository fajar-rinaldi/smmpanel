<?php
require_once '../../../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        require_once '../../../system/helpers/ssp.class.php';
	$table = 'history_transfer';
	$primaryKey = 'id';

	$columns = array(
		array( 'db' => 'username', 'dt' => 0, 'formatter' => function($i, $o) {
			return "".$i."<br>(#".$o['3'].")";
		}),
		array( 'db' => 'jumlah', 'dt' => 1, 'formatter' => function($i) {
			return "<span class=\"badge badge-success\">Rp ".number_format($i,0,',','.')."</span>";
		}),
		array( 'db' => 'tanggal', 'dt' => 2, 'formatter' => function($i) {
			return "<center>".format_date('id',$i)."</center>";
		}),
		array( 'db' => 'rid', 'dt' => 3)
		
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