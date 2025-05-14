<style>
a.btn-sm {
    font-size: 12px;
    padding: 2px 6px;
}
</style>
<!-- start page title -->
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body manage_users1">
                <h4 class="page-title mt-0">
                    <i class="mdi mdi-database title_icon"></i> <?php echo get_phrase($page_title); ?>
                    <?php if(access('manage_user_button')){ ?>
                    <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle" onclick="rightModal('<?php echo site_url('modal/popup/master/master_form'); ?>', '<?php echo get_phrase('Master Create Form'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('add_user'); ?></button>
                    <?php } ?>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<!-- end page title -->

<div class="row ">
    <div class="col-xl-12">
        <div class="card mt-2">
            <div class="card-body">
                <div class = "book_content">
                    
                      <div class="table-responsive-sm">
                        <table id="basic-datatable-1" class="table table-striped dt-responsive nowrap" width="100%" data-page-length="100">
                          <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Applicant Name</th>
                                <th>Certificate pdf</th>
                                <th>Certificate No</th>
                                <!--<th>Status</th>-->
                                <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($entries as $entry): ?>
                            <tr>
                                <td><?php echo $entry->id; ?></td>
                                <td><?php echo $entry->applicant_name; ?></td>
                                <td>
                                    <?php if (!empty($entry->certificate_pdf)): ?>
                                        <a href="<?php echo base_url('uploads/master_pdf/'.$entry->certificate_pdf); ?>" target="_blank">view</a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $entry->certificate_no; ?></td>
                                <!--<td><?php echo $entry->status; ?></td>-->
                                <td>
                                    <a href="javascript:void(0);" class="btn-sm btn-success" onclick="rightModal('<?php echo base_url('master/edit/' . $entry->id); ?>', 'Edit user');">Edit</a>
                                    <!--<a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="confirmModal('<?php echo base_url('master/delete/' . $entry->id); ?>', 'Delete user');">Delete</a>-->
                                </td>
                            </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                </div> <!-- end table-responsive-->
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<script>  
	$(document).ready(function(){
		$('#basic-datatable-1').DataTable({
             'responsive': false,
             'order': [[0, 'desc']] 
		});
}); 
</script>


  