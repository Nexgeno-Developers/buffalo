<?php if(!access('pass_inward_report')){ redirect(route('dashboard')); } ?>

<!--title-->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <h4 class="page-title mt-0 pb-1">
        <i class="mdi mdi-book title_icon"></i> <?php echo get_phrase($page_title); ?>
        </h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>



<div class="row">
    <div class="col-12">
        <div class="card mt-2">
            <div class="card-body">
                
                <form id="filterForm" method="get" action="">
                    <div class="row mb-1">
                    
                        <div class="col-md-2 col-6 mb-1">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" placeholder="Date">
                        </div>  
                       
                        <div class="col-md-2 col-6 repot-btn">
                            <button class="btn btn-block btn-secondary" onclick="filter()" >Search <i class="fa fa-search" aria-hidden="true"></i></button>
                        </div>                                                           
                        <div class="col-md-2 col-6 repot-btn">
                            <button class="btn btn-block btn-danger" onclick="reset_filter()" >Reset <i class="fa fa-refresh" aria-hidden="true"></i></button>
                        </div>                    
                    </div>
                </form>
                
                <div class="vyapari_content block-rep">
                   <div class="table-responsive">
                    <table id="basic-datatable-1" class="table table-striped dt-responsive nowrap" data-page-length="10" width="100%">
                    	<thead>
                    		<tr style="background-color: #313a46; color: #ababab;">
                    		    <th width="15%">Exit Date Hour</th>
                    			<th width="15%">Number of Buffalo</th>
                    		</tr>
                    	</thead>
                    	<tbody>
                            <?php foreach ($hourly as $row) : ?>
                                <tr>
                                    <td><?= $row->exit_date_hour ?></td>
                                    <td><?= $row->Number_of_buffalo ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table> 
                    </div>
                </div>              
            </div>
        </div>
    </div>
</div>

<STYLE>
    div#basic-datatable-1_filter {
    display: NONE;
}
</STYLE>

<script>
   

</script>