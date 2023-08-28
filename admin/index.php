<?php
session_start();
require_once '../mainconfig.php';
function currency($value) { return number_format($value, 0, ".", "."); }
function rdate($f,$x = '-0 days') { return date($f, strtotime($x, strtotime(date('Y-m-d H:i:s')))); }

load('middleware', 'user');
if ($_SESSION['user']['level'] == "Reseller" || $_SESSION['user']['level'] == "Member") {
    header('location:' . base_url());
}

$total_data_us = mysqli_num_rows($db->query("SELECT * FROM users"));
$total_data_trx = mysqli_num_rows($db->query("SELECT * FROM pembelian_sosmed WHERE status = 'Success'"));

$check_data = mysqli_query($db, "SELECT SUM(Saldo) AS total FROM users WHERE level != 'Admin'");
$total_saldo_us = mysqli_fetch_assoc($check_data);

$check_data_trx = mysqli_query($db, "SELECT SUM(harga) AS total FROM pembelian_sosmed WHERE status = 'Success'");
$total_trx_shop = mysqli_fetch_assoc($check_data_trx);
        
// MENAMPILKAN DOUGNUT
$qD = "SELECT device FROM agent";
$sD = $db->query($qD)->num_rows;

$qD2 = "SELECT browser FROM agent";
$sD2 = $db->query($qD)->num_rows;

$data_mobile = mysqli_num_rows($db->query("SELECT * FROM agent WHERE device = 'Mobile'"));
$data_desktop = mysqli_num_rows($db->query("SELECT * FROM agent WHERE device = 'Desktop'"));
$data_tablet = mysqli_num_rows($db->query("SELECT * FROM agent WHERE device = 'Tablet'"));
$data_unknown = mysqli_num_rows($db->query("SELECT * FROM agent WHERE device NOT IN ('Mobile','Desktop','Tablet')"));
// END TO DATA

// MENAMPILKAN PROGRESSBAR
$data_chrome = mysqli_num_rows($db->query("SELECT * FROM agent WHERE browser = 'Chrome'"));
$data_unknown_prog = mysqli_num_rows($db->query("SELECT * FROM agent WHERE browser NOT IN ('Chrome')"));
// END TO DATA

$revenue_this_month =$db->query("SELECT SUM(profit) AS x FROM pembelian_sosmed WHERE status = 'Success' AND MONTH(tanggal) = '".date('m')."' AND YEAR(tanggal) = '".date('Y')."'")->fetch_assoc()['x'];
                    
$revenue_last_month = $db->query("SELECT SUM(profit) AS x FROM pembelian_sosmed WHERE status IN ('Success') AND MONTH(tanggal) = '".rdate('m','-1 month')."' AND YEAR(tanggal) = '".rdate('Y','-1 month')."'")->fetch_assoc()['x'];
                    
$total_price_socmed = $db->query("SELECT SUM(harga) AS x FROM pembelian_sosmed WHERE status = 'Success'")->fetch_assoc()['x'];

include_once '../layouts/header_admin.php'; ?>
<div class="row">
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-end">
                <h4>Sessions By Device</h4>
            </div>
            <div class="card-content">
                <div class="card-body pt-50">
                    <div id="session-by-device" class="mb-1"></div>
                    <div class="chart-info d-flex justify-content-between mb-1">
                        <div class="series-info d-flex align-items-center">
                            <i class="text-primary" data-feather="monitor"></i>
                            <span class="text-bold-600 mx-50">Desktop</span>
                        </div>
                        <div class="series-result">
                            <span><?= round(($data_desktop/$sD)*100, 2); ?>%</span>
                        </div>
                    </div>
                    <div class="chart-info d-flex justify-content-between mb-1">
                        <div class="series-info d-flex align-items-center">
                            <i class="text-warning" data-feather="tablet"></i>
                            <span class="text-bold-600 mx-50">Mobile</span>
                        </div>
                        <div class="series-result">
                            <span><?= round(($data_mobile/$sD)*100, 2); ?>%</span>
                        </div>
                    </div>
                    <div class="chart-info d-flex justify-content-between mb-50">
                        <div class="series-info d-flex align-items-center">
                            <i class="text-danger" data-feather="tablet"></i>
                            <span class="text-bold-600 mx-50">Tablet</span>
                        </div>
                        <div class="series-result">
                            <span><?= round(($data_tablet/$sD)*100, 2); ?>%</span>
                        </div>
                    </div>
                    <div class="chart-info d-flex justify-content-between mb-50">
                        <div class="series-info d-flex align-items-center">
                            <i class="text-black" data-feather="help-circle"></i>
                            <span class="text-bold-600 mx-50">Unknown</span>
                        </div>
                        <div class="series-result">
                            <span><?= round(($data_unknown_prog/$sD)*100, 2); ?>%</span>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>    
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar bg-light-success p-50 m-0">
                            <div class="avatar-content">
                                <i class="text-success font-medium-5" data-feather="users"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1"><?= $total_data_us; ?></h2>
                        <p class="mb-0">Total Users</p>
                    </div>
                    <div class="card-content">
                        <div id="users-chart"></div>
                    </div>
                </div>
            </div>
     <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar bg-light-primary p-50 m-0">
                            <div class="avatar-content">
                                <i class="text-primary font-medium-5" data-feather="dollar-sign"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1">Rp <?= number_format($total_saldo_us['total'],0,',','.'); ?></h2>
                        <p class="mb-0">Total Balance</p>
                    </div>
                    <div class="card-content">
                        <div id="balance-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar bg-light-danger p-50 m-0">
                            <div class="avatar-content">
                                <i class="text-danger font-medium-5" data-feather="shopping-cart"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1"><?= $total_data_trx; ?></h2>
                        <p class="mb-0">Transaction Success</p>
                    </div>
                    <div class="card-content">
                        <div id="transaction-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i class="text-warning font-medium-5" data-feather="package"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1">Rp <?= currency($total_price_socmed) ?></h2>
                        <p class="mb-0">Shopping Total</p>
                    </div>
                    <div class="card-content">
                        <div id="shopping-chart"></div>
                    </div>
                </div>
            </div>
            
            <!-- Revenue Card -->
    <div class="col-md-12 col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="card-title">Revenue Success</h4>
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-start mb-3">
            <div class="me-2">
              <p class="card-text mb-50">Bulan Ini</p>
              <h3 class="fw-bolder">
                <sup class="font-medium-1 fw-bold">Rp</sup>
                <span class="text-success"><?= currency($revenue_this_month) ?></span>
              </h3>
            </div>
            <div>
              <p class="card-text mb-50">Bulan Lalu</p>
              <h3 class="fw-bolder">
                <sup class="font-medium-1 fw-bold">Rp</sup>
                <span><?= currency($revenue_last_month) ?></span>
              </h3>
            </div>
          </div>
          <div id="revenue-chart"></div>
        </div>
      </div>
    </div>
    <!--/ Revenue Card -->
    
            <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Browser Statistics</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                                        <div class="d-flex justify-content-between mb-25">
                        <div class="browser-info">
                            <p class="mb-25">Chrome</p>
                        </div>
                        <div class="stastics-info text-right">
                            <span><?= round(($data_chrome/$sD2)*100,2); ?>%</span>
                        </div>
                    </div>
                    <div class="progress progress-bar-primary mb-2">
                        <div class="progress-bar" role="progressbar" aria-valuenow="<?= round(($data_chrome/$sD2)*100,2); ?>" aria-valuemin="<?= round(($data_chrome/$sD2)*100,2); ?>" aria-valuemax="100" style="width:<?= round(($data_chrome/$sD2)*100,2); ?>%"></div>
                    </div>
                                        <div class="d-flex justify-content-between mb-25">
                        <div class="browser-info">
                            <p class="mb-25">Unknown</p>
                        </div>
                        <div class="stastics-info text-right">
                            <span><?= round(($data_unknown_prog/$sD)*100, 2); ?>%</span>
                        </div>
                    </div>
                    <div class="progress progress-bar-primary mb-2">
                        <div class="progress-bar" role="progressbar" aria-valuenow="<?= round(($data_unknown_prog/$sD)*100, 2); ?>" aria-valuemin="<?= round(($data_unknown_prog/$sD)*100, 2); ?>" aria-valuemax="100" style="width:<?= round(($data_unknown_prog/$sD)*100, 2); ?>%"></div>
                                           </div>
                                      </div>
                                 </div>
                            </div>
                       </div>
                  </div>
</div>

<?php include_once '../layouts/footer.php'; ?>
<script type="text/javascript">
$(window).on("load", function () {
    new ApexCharts(document.querySelector("#session-by-device"), {
        chart: { type: "donut", height: 315, toolbar: { show: !1 } },
        dataLabels: { enabled: !1 },
        series: [
            <?= round(($data_desktop/$sD)*100, 2); ?>,
            <?= round(($data_mobile/$sD)*100, 2); ?>,
            <?= round(($data_tablet/$sD)*100, 2); ?>,
            <?= round(($data_unknown_prog/$sD)*100, 2); ?>       
             ],
        legend: { show: !1 },
        comparedResult: [2, -3, 8],
        labels: ["Desktop", "Mobile", "Tablet", "Unknown"],
        stroke: { width: 0 },
        colors: ["#7367F0", "#FF9F43", "#EA5455", "#28313B"],
        fill: { type: "gradient", gradient: { gradientToColors: ["#9c8cfc", "#FFC085", "#f29292", "#485461"] } },
    }).render();
   
    new ApexCharts(document.querySelector("#users-chart"), {
        chart: { height: 100, type: "area", toolbar: { show: !1 }, sparkline: { enabled: !0 }, grid: { show: !1, padding: { left: 0, right: 0 } } },
        colors: ["#28C76F"],
        dataLabels: { enabled: !1 },
        stroke: { curve: "smooth", width: 2.5 },
        fill: { type: "gradient", gradient: { shadeIntensity: 0.9, opacityFrom: 0.7, opacityTo: 0.5, stops: [0, 80, 100] } },
        series: [{ name: "Active Users", data: [
            <?php
            for($i = 6; $i >= 0; $i--) {
                $source_date = rdate('Y-m-d',"-$i days");
                print $db->query("SELECT id FROM users WHERE status = 'active' AND register_at LIKE '$source_date%'")->num_rows.',';
            }
            ?>
        ] }],
        xaxis: { labels: { show: !1 }, axisBorder: { show: !1 } },
        yaxis: [{ y: 0, offsetX: 0, offsetY: 0, padding: { left: 0, right: 0 } }],
        tooltip: { x: { show: !1 } },
    }).render();
    
    new ApexCharts(document.querySelector("#balance-chart"), {
        chart: { height: 100, type: "area", toolbar: { show: !1 }, sparkline: { enabled: !0 }, grid: { show: !1, padding: { left: 0, right: 0 } } },
        colors: ["#7367F0"],
        dataLabels: { enabled: !1 },
        stroke: { curve: "smooth", width: 2.5 },
        fill: { type: "gradient", gradient: { shadeIntensity: 0.9, opacityFrom: 0.7, opacityTo: 0.5, stops: [0, 80, 100] } },
        series: [{ name: "Total Balance", data: [
            0,0,0,0,0,0,0,        ] }],
        xaxis: { labels: { show: !1 }, axisBorder: { show: !1 } },
        yaxis: [{ y: 0, offsetX: 0, offsetY: 0, padding: { left: 0, right: 0 } }],
        tooltip: { x: { show: !1 } },
    }).render();
    
    new ApexCharts(document.querySelector("#transaction-chart"), {
        chart: { height: 100, type: "area", toolbar: { show: !1 }, sparkline: { enabled: !0 }, grid: { show: !1, padding: { left: 0, right: 0 } } },
        colors: ["#EA5455"],
        dataLabels: { enabled: !1 },
        stroke: { curve: "smooth", width: 2.5 },
        fill: { type: "gradient", gradient: { shadeIntensity: 0.9, opacityFrom: 0.7, opacityTo: 0.5, stops: [0, 80, 100] } },
        series: [{ name: "Transaction Success", data: [
            <?php
            for($i = 6; $i >= 0; $i--) {
                $source_date = rdate('Y-m-d',"-$i days");
                $count_socmed = $db->query("SELECT id FROM pembelian_sosmed WHERE status = 'Success' AND tanggal LIKE '$source_date%'")->num_rows;
                print ($count_socmed).',';
            }
            ?>
        ] }],
        xaxis: { labels: { show: !1 }, axisBorder: { show: !1 } },
        yaxis: [{ y: 0, offsetX: 0, offsetY: 0, padding: { left: 0, right: 0 } }],
        tooltip: { x: { show: !1 } },
    }).render();
    
    new ApexCharts(document.querySelector("#shopping-chart"), {
        chart: { height: 100, type: "area", toolbar: { show: !1 }, sparkline: { enabled: !0 }, grid: { show: !1, padding: { left: 0, right: 0 } } },
        colors: ["#FF9F43"],
        dataLabels: { enabled: !1 },
        stroke: { curve: "smooth", width: 2.5 },
        fill: { type: "gradient", gradient: { shadeIntensity: 0.9, opacityFrom: 0.7, opacityTo: 0.5, stops: [0, 80, 100] } },
        series: [{ name: "Shopping Total", data: [
            <?php
            for($i = 6; $i >= 0; $i--) {
                $source_date = rdate('Y-m-d',"-$i days");
                $count_socmed = $db->query("SELECT SUM(harga) AS x FROM pembelian_sosmed WHERE status = 'Success' AND tanggal LIKE '$source_date%'")->fetch_assoc()['x'];
                print ($count_socmed).',';
            }
            ?>
        ] }],
        xaxis: { labels: { show: !1 }, axisBorder: { show: !1 } },
        yaxis: [{ y: 0, offsetX: 0, offsetY: 0, padding: { left: 0, right: 0 } }],
        tooltip: { x: { show: !1 } },
    }).render();
    
    new ApexCharts(document.querySelector("#revenue-chart"), {
        chart: { height: 270, toolbar: { show: !1 }, type: "line" },
        stroke: { curve: "smooth", dashArray: [0, 8], width: [4, 2] },
        grid: { borderColor: "#e7e7e7" },
        legend: { show: !1 },
        colors: ["#f29292", "#b9c3cd"],
        fill: { type: "gradient", gradient: { shade: "dark", inverseColors: !1, gradientToColors: ["#7367F0", "#b9c3cd"], shadeIntensity: 1, type: "horizontal", opacityFrom: 1, opacityTo: 1, stops: [0, 100, 100, 100] } },
        markers: { size: 0, hover: { size: 5 } },
        xaxis: { labels: { style: { colors: "#b9c3cd" } }, axisTicks: { show: !1 }, categories: [<?php
            for($i = 7; $i >= 0; $i--) {
                $source_date = rdate('d/m',"-$i days");
                print "\"$source_date\",";
            }
        ?>], axisBorder: { show: !1 }, tickPlacement: "on" },
        yaxis: {
            tickAmount: 5,
            labels: {
                style: { color: "#b9c3cd" },
                formatter: function (e) {
                    return 999 < e ? (e / 1e3).toFixed(1) + "00" : e;
                },
            },
        },
        tooltip: { x: { show: !1 } },
        series: [
            { name: "Bulan Ini", data: [<?php
                for($i = 7; $i >= 0; $i--) {
                    $source_date = rdate('Y-m-d',"-$i days");
                    $this_month = $db->query("SELECT SUM(profit) AS x FROM pembelian_sosmed WHERE status = 'Success' AND tanggal LIKE '$source_date%'")->fetch_assoc()['x'];
                    print "$this_month,";
                }
            ?>] },
            { name: "Bulan Lalu", data: [<?php
                for($i = 7; $i >= 0; $i--) {
                    $source_date = rdate('Y-m-d',"-$i days, -1 month");
                    $last_month = $db->query("SELECT SUM(profit) AS x FROM pembelian_sosmed WHERE status = 'Success' AND tanggal LIKE '$source_date%'")->fetch_assoc()['x'];
                    print "$last_month,";
                }
            ?>] },
        ],
    }).render();
});
</script>