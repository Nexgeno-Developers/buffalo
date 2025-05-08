<form method="POST" class="d-block ajaxForm" action="<?php echo base_url('Master/create'); ?>">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="applicant_name"><?php echo get_phrase('name'); ?></label>
            <input type="text" name="applicant_name" class="form-control" required />
        </div>
        <div class="form-group col-md-6">
            <label for="certificate_no"><?php echo get_phrase('Certificate No.'); ?></label>
            <input type="text" name="certificate_no" class="form-control" required />
        </div>
        <div class="form-group col-md-6">
            <label for="certificate_pdf"><?php echo get_phrase('Certificate PDF'); ?></label>
            <div>
                <input type="file" name="certificate_pdf" class="form-control-file" required />
            </div>
        </div>
        <? /*
        <!--<div class="form-group col-md-6">-->
        <!--    <label for="status"><?php echo get_phrase('status'); ?></label>-->
        <!--    <select name="status" class="form-control select2" data-toggle="select2" required>-->
        <!--        <option value="active">Active</option>-->
        <!--        <option value="inactive">Inactive</option>-->
        <!--    </select>-->
        <!--</div> -->
        */ ?>
        <div class="form-group col-md-12 mt-2">
            <button class="btn btn-block btn-primary btn-ajax" type="submit">
                <span class="form-button"><?php echo get_phrase('add_user'); ?></span> 
                <i class="fa fa-spinner fa-spin form-loader" style="display:none"></i>
            </button>
        </div>        
    </div>
</form>

<script>
    $(".ajaxForm").validate();
    $(".ajaxForm").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        ajaxSubmit(e, form);
    });
    
    $(document).ready(function() {
        initSelect2(['.select2']);
    });   
</script>
