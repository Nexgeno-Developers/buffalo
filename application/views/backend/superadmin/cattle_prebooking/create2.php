<?php 

    $doctor = $this->db->select(['id', 'name'])->from('users')->where('role_type', 'doctor')->get()->result();
    $agent = $this->db->select(['id', 'applicant_name'])->where('id >=', 38)->from('app_gwala')->get()->result();
  
?>

<style>
    @media screen and (min-width: 767px) {
    div#right-modal .modal-dialog {
        min-width: 1100px !important;
        width: 1100px !important;
    }
}

.mt30
{
    margin-top:30px;
}
.pddleft0
{
    padding-left:0px;
        padding-right: 0;
}

.remove_btn
{
    padding: 8px 5px;
    background: #f00 !important;
    border: 0;
    cursor:pointer;
}

.add_btn
{
    padding: 8px 5px;
    background: green !important;
    border: 0;
    cursor:pointer;
}
</style>

<form id="cattle_pre_reg" method="POST" class="d-block ajaxForm" action="<?php echo route('cattle_prebooking/register2'); ?>">
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="doctor" class="form-label">Veterinarian Name<span class="text-danger req"> *</span></label>
            <select class="form-control form-select select2" id="doctor" name="doctor_name" required>
                <option value="">Select</option>
                <?php foreach($doctor as $row) { ?>
                    <option value="<?= $row->id ?>"><?= ucfirst($row->name) ?></option>
                <?php } ?>
            </select>
            <small id="doctor_help" class="form-text text-muted"><?php echo get_phrase('Please Select Veterinarian Name'); ?></small>
        </div> 
        
        <div class="form-group col-md-3">
            <label for="agent" class="form-label">Agent Name (Dawanwala) <span class="text-danger req"> *</span></label>
            <select class="form-control form-select select2" id="agent" name="gwala_id" required>
                <option value="">Select Dawanwala</option>
                <?php foreach($agent as $row) { ?>
                    <option value="<?= $row->id ?>"><?= ucfirst($row->applicant_name) ?></option>
                <?php } ?>
            </select>
            <small id="phone_help" class="form-text text-muted"><?php echo get_phrase('Please Select Agent Name'); ?></small>
        </div>    
        
         <div class="form-group col-md-3">
                            <label for="receipt_no"><?php echo get_phrase('Receipt No'); ?><span class="text-danger req"> *</span></label>
                            <input type="text" class="form-control" id="receipt_no" name="receipt_no" autocomplete="off" required>
                            <small id="receipt_no_help" class="form-text text-muted"><?php echo get_phrase('Please Provide Receipt No'); ?></small>
                        </div>
        
        <div class="form-group col-md-3">
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
        
        
        
        
        <div class="add-more-fields col-md-12">
            
            <div class="multi-field" id="multi-fields">
                <div class="form-row">
                    
                    <div class="form-group col-md-1 text-center">
                        <label>Sr. No.</label>
                        <p class="sr-no"></p>
                    </div>
                    
                    
                    <!--<div class="form-group col-md-2 pddleft0">
                        <label for="cattle_age"><?php echo get_phrase('Cattle Age'); ?><span class="text-danger req"> *</span></label>
                        <div class="form-row">
                            <div class="col-md-6">
                                <input type="text" class="form-control col-md-5" id="cattle_age" name = "years[]" placeholder="Year" autocomplete="off" required style="max-width: 100% !important;">
                            </div>
                            
                            <div class="col-md-6 pl-0">
                             <input type="text" class="form-control col-md-5" id="cattle_age" name = "months[]" placeholder="Month" autocomplete="off" required style="max-width: 100% !important;">
                           
                            </div>
                            
                            </div>
                        <small id="cattle_age_help" class="form-text text-muted"><?php echo get_phrase('Please Provide cattle Age'); ?></small>
                    </div>-->
            
                    
                    <div class="form-group col-md-3">
                        <label for="certificate_no"><?php echo get_phrase('Certificate No'); ?><span class="text-danger req"> *</span></label>
                        <input type="text" class="form-control" id="certificate_no"  name = "certificate_no[]" autocomplete="off" required>
                        <small id="certificate_no_help" class="form-text text-muted"><?php echo get_phrase('Please Provide Certificate No'); ?></small>
                    </div>
            
                    <div class="form-group col-md-3">
                        <label for="book_no"><?php echo get_phrase('Book No'); ?><span class="text-danger req"> *</span></label>
                        <input type="text" class="form-control" id="book_no"  name = "book_no[]" autocomplete="off" required>
                        <small id="book_no_help" class="form-text text-muted"><?php echo get_phrase('Please Provide Book No'); ?></small>
                    </div>
                    
                    <div class="form-group col-md-2">
                        <label for="tag_no"><?php echo get_phrase('Tag No'); ?><span class="text-danger req"> *</span></label>
                        <input type="text" class="form-control" id="tag_no"  name = "tag_no[]" autocomplete="off" required>
                        <small id="tag_no_help" class="form-text text-muted"><?php echo get_phrase('Please Provide Tag No'); ?></small>
                    </div>
                    
                    <div class="form-group col-md-2">
                        <?php /* <!--<label for="tag_no"><?php echo get_phrase('Old Tag No'); ?></label>-->
                        <!--<input type="text" class="form-control" id="old_tag_no"  name = "old_tag_no[]" autocomplete="off">--> */ ?>
                        <label for="old_tag_no"><?php echo get_phrase('transferred'); ?><span class="text-danger req"> *</span></label>
                        <select class="form-control form-select" id="old_tag_no" name="old_tag_no[]" required>
                            <option value="0" selected>No</option>
                            <option value="1">Yes</option>
                        </select>
                        <small id="tag_no_help" class="form-text text-muted"><?php echo get_phrase('Select Transferred Status'); ?></small>
                    </div>
                    
                    <div class="col-md-1 form-group change mt30">
                       <div class="btn btn-block btn-secondary add_btn" onclick="addnewFields();"><i class="fa fa-plus" aria-hidden="true"></i></div>
                    </div>
                </div>    
                
            </div>
            
        </div>

 
        <div class="form-group  col-md-2 mt10">
            <button class="btn btn-block btn-success" type="submit"><?php echo get_phrase('register'); ?></button>
        </div>
        
        <div class="form-group  col-md-2 mt10">
            <input class="btn btn-block btn-danger" onclick="reset_data();" type="button" value="Reset">
        </div>
    </div>
</form>

<script>

    $(document).ready(function() {
        $('body').on('keydown', 'input[name="tag_no[]"]', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();  // Prevent the form from submitting
                $('input[name="tag_no[]"]').each(function() {
                    if ($(this).val() === '') {
                        $(this).focus();
                        return false; // Break out of the each loop
                    }
                });
                
            }
        });
    });

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

//initValidate("#cattle_pre_reg");
initSelect2(".select2");

//$("#cattle_pre_reg").validate({}); // Jquery form validation initialization

$("#cattle_pre_reg").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, SubmitImage);
});

</script>


    <script>
        function updateSrNo() {
            // Select all elements with the class 'multi-field'
            const multiFields = document.querySelectorAll('.multi-field');
            
            // Loop through each element and update its serial number
            multiFields.forEach((field, index) => {
                field.querySelector('.sr-no').textContent = `${index + 1}.`;
            });
        }

        function removenewFields(elem) {
            $(elem).closest('#multi-fields').remove();
            updateSrNo();
        }

        function addnewFields() {
            var html_code = `
                <div class="multi-field" id="multi-fields">
                    <div class="form-row">
                    <div class="form-group col-md-1 text-center">
                        <label>Sr. No.</label>
                        <p class="sr-no"></p>
                    </div>
                        <!--<div class="form-group col-md-2 pddleft0">
                            <label for="cattle_age"><?php echo get_phrase('Cattle Age'); ?><span class="text-danger req"> *</span></label>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control col-md-5" id="cattle_age" name="years[]" placeholder="Year" autocomplete="off" required style="max-width: 100% !important;">
                                </div>
                                <div class="col-md-6 pl-0">
                                    <input type="text" class="form-control col-md-5" id="cattle_age" name="months[]" placeholder="Month" autocomplete="off" required style="max-width: 100% !important;">
                                </div>
                            </div>
                            <small id="cattle_age_help" class="form-text text-muted"><?php echo get_phrase('Please Provide cattle Age'); ?></small>
                        </div>-->
                       
                        <div class="form-group col-md-3">
                            <label for="certificate_no"><?php echo get_phrase('Certificate No'); ?><span class="text-danger req"> *</span></label>
                            <input type="text" class="form-control" id="certificate_no" name="certificate_no[]" autocomplete="off" required>
                            <small id="certificate_no_help" class="form-text text-muted"><?php echo get_phrase('Please Provide Certificate No'); ?></small>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="book_no"><?php echo get_phrase('Book No'); ?><span class="text-danger req"> *</span></label>
                            <input type="text" class="form-control" id="book_no" name="book_no[]" autocomplete="off" required>
                            <small id="book_no_help" class="form-text text-muted"><?php echo get_phrase('Please Provide Book No'); ?></small>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="tag_no"><?php echo get_phrase('Tag No'); ?><span class="text-danger req"> *</span></label>
                            <input type="text" class="form-control" id="tag_no" name="tag_no[]" autocomplete="off" required>
                            <small id="tag_no_help" class="form-text text-muted"><?php echo get_phrase('Please Provide Tag No'); ?></small>
                        </div>
                         <div class="form-group col-md-2">
                            <label for="old_tag_no"><?php echo get_phrase('transferred'); ?><span class="text-danger req"> *</span></label>
                            <select class="form-control form-select" id="old_tag_no" name="old_tag_no[]" required>
                                <option value="0" selected>No</option>
                                <option value="1">Yes</option>
                            </select>
                        <small id="tag_no_help" class="form-text text-muted"><?php echo get_phrase('Select Transferred Status'); ?></small>
                    </div>
                        
                        <div class="col-md-1 form-group change mt30">
                            <div class="btn btn-block btn-secondary remove_btn" onclick="removenewFields(this);"><i class="fa fa-minus" aria-hidden="true"></i></div>
                        </div>
                    </div>
                </div>
            `;
            $(".add-more-fields").append(html_code);
            updateSrNo();
        }
        // Call updateSrNo initially to set the serial numbers correctly on page load
        updateSrNo();
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