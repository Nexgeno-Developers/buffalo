    <?php /*
        $startDate = new DateTime('2024-06-05');
        $endDate   = new DateTime('2024-06-20');
        $endDate   = $endDate->modify('+1 day'); // Include end date in the loop
        $period    = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
    ?>
    <div class="row">
        
            <div class="col-md-6">
                <div class="chart_box">
                
                    <p>Buffalo Selling - Qurbani Daily Report</p>
                        <table class="table table-striped dt-responsive nowrap">
                        	<thead class="chart-table-header">
                        		<tr>
                        		    <th>Sr. No.</th>
                        			<th>Date</th>
                        			<th>MB</th>
                        			<th>SB</th>
                        			<th>Total</th>
                        		</tr>
                        	</thead>
                            <tbody class="chart-table-body">
                                <?php 
                                $x = 1; 
                                foreach($period as $date): 
                                    $loopDate = $date->format('Y-m-d');
                                    if($loopDate <= date('Y-m-d')) {
                                        $mb    = get_cattle_count(2, '1', $loopDate); //selling male
                                        $sb    = get_cattle_count(2, '2', $loopDate); //selling female
                                        $total = $mb + $sb;
                                ?>
                                    <tr>
                                        <td><?php echo $x++; ?></td>
                                        <td><?php echo date('d M Y', strtotime($loopDate)); ?></td>
                                        <td><?php echo $mb ?></td>
                                        <td><?php echo $sb ?></td>
                                        <td><?php echo $total ?></td>
                                    </tr>
                                <?php } endforeach; ?>
                            </tbody>
                        </table>  
                </div>
            </div>
            
            
            <div class="col-md-6 pl-md-1">
                
                <div class="chart_box"> 
                
                    <p>Buffalo Slaughter - Regular Daily Report</p>
                    <table class="table table-striped dt-responsive nowrap">
                    	<thead class="chart-table-header">
                    		<tr>
                    		    <th>Sr. No.</th>
                    			<th>Date</th>
                    			<th>MB</th>
                    			<th>SB</th>
                    			<th>Total</th>
                    		</tr>
                    	</thead>
                        <tbody class="chart-table-body">
                            <?php 
                            $x = 1; 
                            foreach($period as $date): 
                                $loopDate = $date->format('Y-m-d');
                                if($loopDate <= date('Y-m-d')) {
                                    $mb    = get_cattle_count(1, '1', $loopDate); //selling male
                                    $sb    = get_cattle_count(1, '2', $loopDate); //selling female
                                    $total = $mb + $sb;
                            ?>
                                <tr>
                                    <td><?php echo $x++; ?></td>
                                    <td><?php echo date('d M Y', strtotime($loopDate)); ?></td>
                                    <td><?php echo $mb ?></td>
                                    <td><?php echo $sb ?></td>
                                    <td><?php echo $total ?></td>
                                </tr>
                            <?php } endforeach; ?>
                        </tbody>
                    </table>        
                </div>
            </div>
        
    </div>

*/ ?>


<?php
// $from = new DateTime('2024-06-05');
// $to = new DateTime('2024-06-20');

$from = new DateTime(get_common_settings('start_datetime'));
$to = new DateTime(get_common_settings('end_datetime'));

// Add one day to include the end date
$to->modify('+1 day');

$interval = new DateInterval('P1D'); // 1-day interval
$period = new DatePeriod($from, $interval, $to);

function get_daily_cattle_summary($from, $to)
{
    $CI = & get_instance();

    $CI->db->select("
        DATE(AQ.inward_date) AS report_date,
        AQ.slaughtering_type,
        CPB.cattle_sex,
        COUNT(*) AS total
    ");
    $CI->db->from("app_qrcode AS AQ");
    $CI->db->join("cattle_pre_booking AS CPB", "CPB.certificate_no = AQ.qrcode", "inner");
    $CI->db->where("DATE(AQ.inward_date) >=", $from);
    $CI->db->where("DATE(AQ.inward_date) <=", $to);
    $CI->db->group_by(["report_date", "AQ.slaughtering_type", "CPB.cattle_sex"]);

    $query = $CI->db->get();
    return $query->result_array(); // will return multiple rows
}

$summary = get_daily_cattle_summary(get_common_settings('start_datetime'), get_common_settings('end_datetime'));
$dataMap = [];

foreach ($summary as $row) {
    $date = $row['report_date'];
    $type = $row['slaughtering_type']; // 1 = Regular, 2 = Qurbani
    $sex  = $row['cattle_sex'];        // 1 = MB, 2 = SB
    $dataMap[$date][$type][$sex] = $row['total'];
}

// $sr = 1;
// foreach ($period as $date) {
//     $loopDate = $date->format('Y-m-d');
//     if ($loopDate <= date('Y-m-d')) {
//         $mb = $dataMap[$loopDate][2][1] ?? 0;
//         $sb = $dataMap[$loopDate][2][2] ?? 0;
//         $total = $mb + $sb;
//         echo "<tr>
//             <td>{$sr}</td>
//             <td>" . date('d M Y', strtotime($loopDate)) . "</td>
//             <td>{$mb}</td>
//             <td>{$sb}</td>
//             <td>{$total}</td>
//         </tr>";
//         $sr++;
//     }
// }

// $sr = 1;
// foreach ($period as $date) {
//     $loopDate = $date->format('Y-m-d');
//     if ($loopDate <= date('Y-m-d')) {
//         $mb = $dataMap[$loopDate][1][1] ?? 0;
//         $sb = $dataMap[$loopDate][1][2] ?? 0;
//         $total = $mb + $sb;
//         echo "<tr>
//             <td>{$sr}</td>
//             <td>" . date('d M Y', strtotime($loopDate)) . "</td>
//             <td>{$mb}</td>
//             <td>{$sb}</td>
//             <td>{$total}</td>
//         </tr>";
//         $sr++;
//     }
// }

?>

<?php /*<!--title-->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <h4 class="page-title mt-0">
            <i class="mdi mdi-book-open-page-variant title_icon"></i> <?php echo get_phrase($page_title); ?>
        </h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<div class="row">

    <!-- Buffalo Selling Report -->
    <div class="col-md-6">
        <div class="chart_box">
            <p>Buffalo Selling - Qurbani Daily Report</p>
            <table class="table table-striped dt-responsive nowrap">
                <thead class="chart-table-header">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Date</th>
                        <th>MB</th>
                        <th>SB</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody class="chart-table-body">
                    <?php 
                    $sr = 1;
                    foreach ($period as $date): 
                        $loopDate = $date->format('Y-m-d');
                        if ($loopDate <= date('Y-m-d')) {
                            $mb = $dataMap[$loopDate][2][1] ?? 0; // Selling Male
                            $sb = $dataMap[$loopDate][2][2] ?? 0; // Selling Female
                            $total = $mb + $sb;
                    ?>
                        <tr>
                            <td><?php echo $sr++; ?></td>
                            <td><?php echo date('d M Y', strtotime($loopDate)); ?></td>
                            <td><?php echo $mb; ?></td>
                            <td><?php echo $sb; ?></td>
                            <td><?php echo $total; ?></td>
                        </tr>
                    <?php } endforeach; ?>
                </tbody>
            </table>  
        </div>
    </div>

    <!-- Buffalo Slaughter Report -->
    <div class="col-md-6 pl-md-1">
        <div class="chart_box"> 
            <p>Buffalo Slaughter - Regular Daily Report</p>
            <table class="table table-striped dt-responsive nowrap">
                <thead class="chart-table-header">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Date</th>
                        <th>MB</th>
                        <th>SB</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody class="chart-table-body">
                    <?php 
                    $sr = 1;
                    foreach ($period as $date): 
                        $loopDate = $date->format('Y-m-d');
                        if ($loopDate <= date('Y-m-d')) {
                            $mb = $dataMap[$loopDate][1][1] ?? 0; // Slaughter Male
                            $sb = $dataMap[$loopDate][1][2] ?? 0; // Slaughter Female
                            $total = $mb + $sb;
                    ?>
                        <tr>
                            <td><?php echo $sr++; ?></td>
                            <td><?php echo date('d M Y', strtotime($loopDate)); ?></td>
                            <td><?php echo $mb; ?></td>
                            <td><?php echo $sb; ?></td>
                            <td><?php echo $total; ?></td>
                        </tr>
                    <?php } endforeach; ?>
                </tbody>
            </table>        
        </div>
    </div>

</div>
*/ ?>

<div class="row">

    <!-- Buffalo Selling Report -->
    <div class="col-md-6">
        <div class="chart_box">
            <p>Buffalo Selling - Qurbani Daily Report</p>
            <table class="table table-striped dt-responsive nowrap">
                <thead class="chart-table-header">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Date</th>
                        <th>MB</th>
                        <th>SB</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody class="chart-table-body">
                    <?php 
                    $sr = 1;
                    $totalSellingMB = 0;
                    $totalSellingSB = 0;
                    foreach ($period as $date): 
                        $loopDate = $date->format('Y-m-d');
                        if ($loopDate <= date('Y-m-d')) {
                            $mb = $dataMap[$loopDate][2][1] ?? 0; // Selling Male
                            $sb = $dataMap[$loopDate][2][2] ?? 0; // Selling Female
                            $total = $mb + $sb;
                            $totalSellingMB += $mb;
                            $totalSellingSB += $sb;
                    ?>
                        <tr>
                            <td><?php echo $sr++; ?></td>
                            <td><?php echo date('d M Y', strtotime($loopDate)); ?></td>
                            <td><?php echo $mb; ?></td>
                            <td><?php echo $sb; ?></td>
                            <td><?php echo $total; ?></td>
                        </tr>
                    <?php } endforeach; ?>
                </tbody>
                <tfoot class="font-weight-bold">
                    <tr>
                        <td style="padding: .35rem;" colspan="2">Grand Total</td>
                        <td style="padding: .35rem;"><?php echo $totalSellingMB; ?></td>
                        <td style="padding: .35rem;"><?php echo $totalSellingSB; ?></td>
                        <td style="padding: .35rem;"><?php echo $totalSellingMB + $totalSellingSB; ?></td>
                    </tr>
                </tfoot>
            </table>  
        </div>
    </div>

    <!-- Buffalo Slaughter Report -->
    <div class="col-md-6 pl-md-1">
        <div class="chart_box"> 
            <p>Buffalo Slaughter - Regular Daily Report</p>
            <table class="table table-striped dt-responsive nowrap">
                <thead class="chart-table-header">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Date</th>
                        <th>MB</th>
                        <th>SB</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody class="chart-table-body">
                    <?php 
                    $sr = 1;
                    $totalSlaughterMB = 0;
                    $totalSlaughterSB = 0;
                    foreach ($period as $date): 
                        $loopDate = $date->format('Y-m-d');
                        if ($loopDate <= date('Y-m-d')) {
                            $mb = $dataMap[$loopDate][1][1] ?? 0; // Slaughter Male
                            $sb = $dataMap[$loopDate][1][2] ?? 0; // Slaughter Female
                            $total = $mb + $sb;
                            $totalSlaughterMB += $mb;
                            $totalSlaughterSB += $sb;
                    ?>
                        <tr>
                            <td><?php echo $sr++; ?></td>
                            <td><?php echo date('d M Y', strtotime($loopDate)); ?></td>
                            <td><?php echo $mb; ?></td>
                            <td><?php echo $sb; ?></td>
                            <td><?php echo $total; ?></td>
                        </tr>
                    <?php } endforeach; ?>
                </tbody>
                <tfoot class="font-weight-bold">
                    <tr>
                        <td style="padding: .35rem;" colspan="2">Grand Total</td>
                        <td style="padding: .35rem;"><?php echo $totalSlaughterMB; ?></td>
                        <td style="padding: .35rem;"><?php echo $totalSlaughterSB; ?></td>
                        <td style="padding: .35rem;"><?php echo $totalSlaughterMB + $totalSlaughterSB; ?></td>
                    </tr>
                </tfoot>
            </table>        
        </div>
    </div>

</div>

