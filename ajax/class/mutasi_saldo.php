<?php
session_start();
require_once '../../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	require_once '../../system/helpers/ssp.class.php';
	$table = 'history_saldo';
	$primaryKey = 'id';
	
	$columns = array(
		array( 'db' => 'tanggal', 'dt' => 0, 'formatter' => function($i) {
			return "<center>".format_date('id',$i)."</center>";
		}),
		array( 'db' => 'pesan', 'dt' => 1),
		array( 'db' => 'nominal',  'dt' => 2, 'formatter' => function($i, $a) {
			if ($a['3'] == "Penambahan Saldo") {
                            $label = "success";
                            $icon = "+";
                            } else {
                            $label = "danger";
                            $icon = "-";
                            }
			return "<span class=\"badge badge-".$label."\">".$icon." Rp ".number_format($i,0,',','.')."</span>";
		}),
		array( 'db' => 'aksi', 'dt' => 3)
		
		
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