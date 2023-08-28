<?php
session_start();
require_once '../../mainconfig.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	require_once '../../system/helpers/ssp.class.php';
	$table = 'pembelian_sosmed';
	$primaryKey = 'id';
	
function status($s) {
    if ($s === "Success") {
        return '<div class="badge badge-light-success"><i class="far fa-check-circle"></i> Success</div>';
    } else if ($s === "Pending") {
        return '<div class="badge badge-warning"><i class="mdi mdi-autorenew mdi-spin"></i> Pending</div>';
    } else if ($s === "Processing") {
        return '<div class="badge badge-info"><i class="mdi mdi-cube-send"></i> Processing</div>';
    } else if ($s === "Partial") {
        return '<div class="badge badge-secondary"><i class="far fa-times-circle"></i> Partial</div>';
    } else {
        return '<div class="badge badge-danger"><i class="far fa-times-circle"></i> Error</div>';
    }
}
	
function refill($s, $oid){
    if($s){        
        return '<a href="'.base_url('/pemesanan/refill?oid='.$oid['id']).'" class="btn btn-outline-primary btn-sm btn-rounded font-size-13"><i class="mdi mdi-cog-clockwise me-1"></i>Refill</a>';
    } else {
        return '<a href="javascript:;" class="btn btn-outline-danger btn-sm btn-rounded font-size-13 disabled"><i class="far fa-times-circle"></i> Refill</a>';
    }
}

    $oid = "";
    
	$columns = array(
		array( 'db' => 'tanggal', 'dt' => 0, 'formatter' => function($i) {
			return "<center>".format_date('id',$i)."</center>";
		}),
		array( 'db' => 'oid', 'dt' => 1, 'formatter' => function($i) {
		    $oid = $i;
			return "#".$i."";
		}),
		array( 'db' => 'layanan', 'dt' => 2),
		array( 'db' => 'target',  'dt' => 3, 'formatter' => function($i) {
			return 
			"
		<td style=\"min-width: 10px;\"<div class=\"input-group\"><input type=\"text\" value=\"".$i."\" class=\"form-control form-control-sm\" readonly=\"\">
                        </div></td>
			";
		}),  
		array( 'db' => 'jumlah', 'dt' => 4),
		array( 'db' => 'harga', 'dt' => 5, 'formatter' => function($i) {
			return "Rp ".number_format($i,0,',','.')."";
		}),
		array( 'db' => 'refill', 'dt' => 6, 'formatter' => function($i, $row){
		    return "".refill($i, $row)."";
		}),
		array( 'db' => 'status', 'dt' => 7, 'formatter' => function($i) {
			return "". status($i)."";
		}),
		array( 'db' => 'id', 'dt' => 8)
		
		
	);

	$sql_details = array(
		'user' => $_CONFIG['db']['username'],
		'pass' => $_CONFIG['db']['password'],
		'db'   => $_CONFIG['db']['name'],
		'host' => $_CONFIG['db']['host']
	);
	$joinQuery = null;
	$extraWhere = "user = '$session' ORDER BY id DESC";
	$groupBy = '';
	$having = '';
	print(json_encode(
		SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
	));
} else {
	exit("No direct script access allowed!");
}