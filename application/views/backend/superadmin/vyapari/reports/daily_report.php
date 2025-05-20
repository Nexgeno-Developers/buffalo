    <?php 
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
