<?php if(!access('cattle_prebooking')){ redirect(route('dashboard')); } ?>

<style>

@media(max-width:767px)
{
    .dashboard_box .col-lg-4.col-md-4.col-sm-4.col-6:nth-child(even) {
    padding-left: 5px;
    padding-right: 10px;
}
.dashboard_box .col-lg-4.col-md-4.col-sm-4.col-6:nth-child(odd) {
    padding-right: 5px;
    padding-left: 10px;
}
}
</style>
<!--title-->

<!-- start page title -->
<div class="row ">
  <div class="col-xl-12 breadcrumes">
    <div class="card">
      <div class="card-body">
           <h4 class="page-title mt-0">
            <i class="mdi mdi-book-open-page-variant title_icon"></i> <?php echo get_phrase($page_title); ?>
            <?php if(access('cattle_prebooking')){ ?>
            <!-- <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle mx-2" onclick="rightModal('<?php echo site_url('modal/popup/cattle_prebooking/create2'); ?>', '<?php echo get_phrase('Registration'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('Register'); ?></button> -->
            <?php //if($this->session->userdata('role_type') == 'superadmin'){ ?>
            <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle mx-2  mt-md-0 mt-2" onclick="rightModal('<?php echo site_url('modal/popup/cattle_prebooking/import-prebooking'); ?>', '<?php echo get_phrase('Import'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('Import Prebooking data'); ?></button>
            <?php /* <!--<button type="button" class="btn btn-outline-primary btn-rounded alignToTitle" onclick="rightModal('<?php echo site_url('modal/popup/cattle_prebooking/create'); ?>', '<?php echo get_phrase('Registration'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('Register'); ?></button>--> */ ?>
            <?php //} ?>
            <?php } ?>
        </h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>
<!-- end page title -->


<div class="row ">
  <div class="col-xl-12 mt-2">
    <div class="card manvyap">
      <div class="card-body">
          
        <div class="prebooking-reports">
            <?php //include 'list-boxes.php'; ?>
        </div>

        
       
        
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<?php 
    $doctor = $this->db->select(['id', 'name'])->from('users')->where('role_type', 'doctor')->get()->result();
    $agent = $this->db->select(['id', 'applicant_name'])->from('app_gwala')->where('id >', '37')->order_by('applicant_name', 'asc')->get()->result();
?>

<div class="row">
    <div class="col-12">
        <div class="card mt-2">
            <div class="card-body">
            <div class="row mb-1">
                    <div class="col-md-2 mb-1" style="<?php if($this->session->userdata('role_type') == 'doctor'){ echo 'pointer-events: none;'; } ?>">
                        <label for="doctor" class="form-label">Veterinarian Name</label>
                        <select class="form-control form-select select12" id="doctor" name="doctor_name">
                            <?php if($this->session->userdata('role_type') != 'doctor'): ?>
                            <option value="">Select</option>
                            <?php endif; ?>
                            <?php foreach($doctor as $row) { ?>
                                <option value="<?= $row->id ?>" <?php if($this->session->userdata('user_id') == $row->id){ echo 'selected'; } ?>><?= ucfirst($row->name) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-1">
                        <label for="agent" class="form-label">Agent (Dawanwala)</label>
                        <select class="form-control form-select select12" id="agent" name="doctor_name">
                            <option value="">Select</option>
                            <?php foreach($agent as $row) { ?>
                                <option value="<?= $row->id ?>"><?= ucfirst($row->applicant_name) ?></option>
                            <?php } ?>
                        </select>
                    </div>                     
                    <div class="col-md-2 mb-1">
                        <label>Tag No</label>
                        <input type="text" name="tag_no" class="form-control" placeholder="Tag No">
                    </div> 
                    <div class="col-md-2 mb-1">
                        <label>Health Certificate</label>
                        <input type="text" name="health_certificate" class="form-control" placeholder="Health Certificate">
                    </div>      
                    <div class="col-md-2 col-6 mb-1">
                        <label>Receipt No</label>
                        <input type="text" name="receipt_no" class="form-control" placeholder=" Receipt No">
                    </div>
                    <div class="col-md-2 col-6 mb-1">
                        <label for="sex" class="form-label">Sex</label>
                        <select class="form-control form-select" id="sex" name="cattle_sex">
                          <option value="0">Select</option>    
                          <option value="1">MB</option>
                          <option value="2">SB</option>
                        </select>
                    </div> 
                    <div class="col-md-2 col-6 mb-1">
                        <label>Book No</label>
                        <input type="text" name="book_no" class="form-control" placeholder=" Book No">
                    </div> 
                    <div class="col-md-2 col-6 mb-1">
                        <label>From Date</label>
                        <input type="date" name="from" class="form-control" placeholder="From Date" value="<?php echo date('Y-m-d'); ?>">
                    </div>  
                    <div class="col-md-2 col-6 mb-1">
                        <label>To Date</label>
                        <input type="date" name="to" class="form-control" placeholder="To Date" value="<?php echo date('Y-m-d'); ?>">
                    </div>                    
                    <div class="col-md-2 col-3 repot-btn1 pr-1">
                        <button class="btn btn-block btn-secondary" onclick="filter()" >Search <i class="fa fa-search" aria-hidden="true"></i></button>
                    </div>                                                           
                    <div class="col-md-2 col-3 repot-btn1">
                        <button class="btn btn-block btn-danger" onclick="reset_filter()" >Reset <i class="fa fa-refresh" aria-hidden="true"></i></button>
                    </div>                    
                </div> 
                <div class="vyapari_content block-rep1">
                    <?php include 'list.php'; ?>
                </div>              
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        initSelect2(['#doctor', '#agent']);
    });
    var showAllCattlePreReg = function () {
        //alert(1);
        var url = '<?php echo route('cattle_prebooking/list'); ?>';

        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                $('.vyapari_content').html(response);
                initDataTable('basic-datatable');
                //submit_webcam_image();
                    // setTimeout(function() {
                    //     location.reload();
                    // }, 2000);                
            }
        });
    }
    
    var showAllCattlePreRegReports = function () {
        //alert(1);
        var url = '<?php echo route('cattle_prebooking/list-boxes'); ?>';
        var doctor_name = $('#doctor').val();
        var agent_name = $('#agent').val();
        var tag_no       = $('input[name="tag_no"]').val();
        var health_certificate  = $('input[name="health_certificate"]').val();
        var receipt_no      = $('input[name="receipt_no"]').val();
        var cattle_sex      = $('select[name="cattle_sex"]').val();
        var book_no      = $('input[name="book_no"]').val();
        var from      = $('input[name="from"]').val();
        var to      = $('input[name="to"]').val();        

        $.ajax({
            type : 'POST',
            url: url,
            data:{doctor_name: doctor_name,agent_name: agent_name, tag_no:tag_no, health_certificate: health_certificate, receipt_no: receipt_no, cattle_sex: cattle_sex, book_no: book_no, from: from, to},
            success : function(response) {
                $('.prebooking-reports').html(response);
                //initDataTable('basic-datatable');
            }
        });
    }    
    
    var SubmitImage = function () {
        
                    setTimeout(function() {
                        location.reload();
                    }, 2000);          
        
        var url = '<?php echo route('cattle_prebooking/list'); ?>';

        /*$.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                $('.vyapari_content').html(response);
                initDataTable('basic-datatable');
                submit_webcam_image();
            }
        });*/
    }

    function reset_filter()
    {
        $('#doctor').val('<?php echo ($this->session->userdata('role_type') == 'doctor') ? $this->session->userdata('user_id') : null; ?>').trigger('change');
        $('#agent').val(null).trigger('change');
        //$('input[name="doctor_name"]').val("");    
        $('input[name="tag_no"]').val("");    
        $('input[name="health_certificate"]').val("");  
        $('input[name="receipt_no"]').val("");
        $('input[name="cattle_sex"]').val("0"); 
        $('input[name="book_no"]').val("");
        $('input[name="from"]').val("");
        $('input[name="to"]').val("");
        
        $('#doctor').prop('selectedIndex',0);
        $('#sex').prop('selectedIndex',0);
        
        showAllCattlePreReg();  
        showAllCattlePreRegReports();
    }

    function filter()
    {
        showAllCattlePreReg();
        showAllCattlePreRegReports();
    } 
    
    showAllCattlePreRegReports();
    
    function confirmDelete(url) {
        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                type: 'POST',
                url: url,
                success: function(response) {
                    // Handle the response from the server
                    // alert('Record deleted successfully.');
                    // Optionally, you can refresh the page or remove the deleted item from the DOM
                    //location.reload(); // Refresh the page
                    var data = JSON.parse(response);
                    toastr.success(data.notification);
                    // Delay the page reload by 3 seconds (3000 milliseconds)
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    // Handle any errors
                    alert('Error deleting record: ' + error);
                }
            });
        }
    }
    
</script>
<style>
   div#basic-datatable-1_filter {
    display: none;
}
/* div#basic-datatable-1_length {
    display: none;
}*/
</style>