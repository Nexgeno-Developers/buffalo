<?php 

    $doctor = $this->db->select(['id', 'name'])->from('users')->where('role_type', 'doctor')->get()->result();
    $agent = $this->db->select(['id', 'applicant_name'])->from('app_gwala')->get()->result();
  
?>

<form id="cattle_pre_reg" method="POST" class="d-block ajaxForm" action="<?php echo route('cattle_prebooking/register'); ?>">
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="doctor" class="form-label">Veterinarian Name<span class="text-danger req"> *</span></label>
            <select class="form-control form-select select2" id="doctor" name="doctor_name" required>
                <option value="">Select</option>
                <?php foreach($doctor as $row) { ?>
                    <option value="<?= $row->id ?>"><?= ucfirst($row->name) ?></option>
                <?php } ?>
            </select>
            <small id="doctor_help" class="form-text text-muted"><?php echo get_phrase('Please Select Veterinarian Name'); ?></small>
        </div> 
        
        <div class="form-group col-md-4">
            <label for="agent" class="form-label">Agent Name (Dawanwala) <span class="text-danger req"> *</span></label>
            <select class="form-control form-select select2" id="agent" name="gwala_id" required>
                <option value="">Select Dawanwala</option>
                <?php foreach($agent as $row) { ?>
                    <option value="<?= $row->id ?>"><?= ucfirst($row->applicant_name) ?></option>
                <?php } ?>
            </select>
            <small id="phone_help" class="form-text text-muted"><?php echo get_phrase('Please Select Agent Name'); ?></small>
        </div>        
        
        <div class="form-group col-md-4">
            <label for="cattle_sex" class="form-label">Cattle Sex</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="cattle_sex" id="cattle_sex_mb" value="1" required>
                  <label class="form-check-label form-label" for="cattle_sex_mb">
                    MB
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="cattle_sex" id="cattle_sex_sb" value="2" required>
                  <label class="form-check-label form-label" for="cattle_sex_sb">
                    SB
                  </label>
                </div>
            <small id="cattle_sex_help" class="form-text text-muted"><?php echo get_phrase('Please Select cattle Sex'); ?></small>
        </div>

        
        <div class="form-group col-md-4">
            <label for="cattle_age"><?php echo get_phrase('Cattle Age'); ?><span class="text-danger req"> *</span></label>
            <div class="row">
                <div class="col-md-6">
                    <input type="number" class="form-control col-md-5 mx-1" id="cattle_age" min="1" max="50" name = "years" placeholder="Age in Year" autocomplete="off" required style="max-width: 100% !important;">
                </div>
                
                <div class="col-md-6 pl-0">
                 <input type="number" class="form-control col-md-5 mx-1" id="cattle_age" min="1" max="12" name = "months" placeholder="Age in Month" autocomplete="off" required style="max-width: 96% !important;">
               
                </div>
                
                </div>
            <small id="cattle_age_help" class="form-text text-muted"><?php echo get_phrase('Please Provide cattle Age'); ?></small>
        </div>

        <div class="form-group col-md-4">
            <label for="receipt_no"><?php echo get_phrase('Receipt No'); ?><span class="text-danger req"> *</span></label>
            <input type="text" class="form-control" id="receipt_no"  name = "receipt_no" autocomplete="off" required>
            <small id="receipt_no_help" class="form-text text-muted"><?php echo get_phrase('Please Provide Receipt No'); ?></small>
        </div>
        
        <div class="form-group col-md-4">
            <label for="certificate_no"><?php echo get_phrase('Certificate No'); ?><span class="text-danger req"> *</span></label>
            <input type="text" class="form-control" id="certificate_no"  name = "certificate_no" autocomplete="off" required>
            <small id="certificate_no_help" class="form-text text-muted"><?php echo get_phrase('Please Provide Certificate No'); ?></small>
        </div>

        <div class="form-group col-md-4">
            <label for="book_no"><?php echo get_phrase('Book No'); ?><span class="text-danger req"> *</span></label>
            <input type="text" class="form-control" id="book_no"  name = "book_no" autocomplete="off" required>
            <small id="book_no_help" class="form-text text-muted"><?php echo get_phrase('Please Provide Book No'); ?></small>
        </div>
        
        <div class="form-group col-md-4">
            <label for="tag_no"><?php echo get_phrase('Tag No'); ?><span class="text-danger req"> *</span></label>
            <input type="text" class="form-control" id="tag_no"  name = "tag_no" autocomplete="off" required>
            <small id="tag_no_help" class="form-text text-muted"><?php echo get_phrase('Please Provide Tag No'); ?></small>
        </div>
        
        <?/*
        <!--<div class="form-group col-md-4">-->
        <!--    <label for="slaughtering_type"><?php echo get_phrase('Slaughtering_type'); ?><span class="text-danger req"> *</span></label>-->
        <!--    <div class="">-->
        <!--        <div class="form-check">-->
        <!--          <input class="form-check-input" type="radio" name="slaughtering_type" id="slaughtering_type1" value="1" checked>-->
        <!--          <label class="form-check-label form-label" for="slaughtering_type1">-->
        <!--            Kurbani-->
        <!--          </label>-->
        <!--        </div>-->
        <!--        <div class="form-check">-->
        <!--          <input class="form-check-input" type="radio" name="slaughtering_type" id="slaughtering_type2" value="2">-->
        <!--          <label class="form-check-label form-label" for="slaughtering_type2">-->
        <!--            Regular-->
        <!--          </label>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>        -->
        */?>
        
        <div class="form-group col-md-4">
            <label for="tag_no"><?php echo get_phrase('Old Tag No'); ?></label>
            <input type="text" class="form-control" id="old_tag_no"  name = "old_tag_no" autocomplete="off">
            <small id="tag_no_help" class="form-text text-muted"><?php echo get_phrase('Please Provide Old Tag No'); ?></small>
        </div> 
        

        <div class="form-group  col-md-4 mt10">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('register'); ?></button>
        </div>
        
        <div class="form-group  col-md-4 mt10">
            <input class="btn btn-block btn-danger" onclick="reset_data();" type="button" value="Reset">
        </div>
    </div>
</form>

<script>
//jquery validator
function initValidate(selector) {
    $(selector).validate({
        errorElement: "div",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });
}
//select2
function initSelect2(selector) {
    $(selector).select2();
}

initValidate("#cattle_pre_reg");
initSelect2(".select2");

//$("#cattle_pre_reg").validate({}); // Jquery form validation initialization

$("#cattle_pre_reg").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, SubmitImage);
});

// initSelect2(['.select2']);

// $(".ajaxForm").submit(function(e) {
//     e.preventDefault();
//     if ($('#results').has('img').length > 0) {
//         var form = $(this);
//         ajaxSubmit(e, form, SubmitImage);        
//     }else{
//         toastr.error('Please take Vyapari photo!');
//     }
// });


</script>


<script language="JavaScript">

    function reset_data(){

        document.getElementById("cattle_age").value = '';
        document.getElementById("receipt_no").value = '';
        document.getElementById("certificate_no").value = '';
        document.getElementById("book_no").value = ''; 
        document.getElementById("tag_no").value = ''; 
        document.getElementById("old_tag_no").value = '';

        $('#agent').prop('selectedIndex',0);
        $('#cattle_sex').prop('selectedIndex',0);
        $('#doctor').prop('selectedIndex',0);
    }
    
</script>