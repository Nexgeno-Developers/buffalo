<script>  

	$(document).ready(function(){

		$('#basic-datatable-1').DataTable({

             //"pageLength": 50,

             'responsive': false,		    

			 'processing': true,

			 'serverSide': true,

			 'serverMethod': 'post',

			 'ajax': {

				 'url':'<?php echo base_url('superadmin/cattle_prebooking/ajaxlist')?>',

                "data": function ( search )

                {

                    search.doctor_name = $('#doctor').val();
                    search.agent_name = $('#agent').val();

                    search.tag_no       = $('input[name="tag_no"]').val();

                    search.health_certificate  = $('input[name="health_certificate"]').val();

                    search.receipt_no      = $('input[name="receipt_no"]').val();
                    
                    search.cattle_sex      = $('select[name="cattle_sex"]').val();
                    
                    search.book_no      = $('input[name="book_no"]').val();

                    search.from      = $('input[name="from"]').val();

                    search.to      = $('input[name="to"]').val();

                }				 

			 },

			 "order": [[ 1, "desc" ]],

    		dom: 'lBfrtip',

    		buttons: [

    		    {

                    extend: 'csvHtml5',

                    filename: 'Cattle-pre-registered-report', 

                    text: 'Export',

                    className: 'btn-sm btn-secondary btn-data-export',

                    exportOptions: {

                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9]

                    }                    

                }

            ],			 

			 'columns': [

			        { data: 'sr_no' },

					{ data: 'tag_no' },

					{ data: 'doctor_name' },

					{ data: 'certificate_no' },

					{ data: 'receipt_no' },

					{ data: 'book_no' },

					{ data: 'agent' },

					{ data: 'dob' },

					{ data: 'cattle_sex' },
					
					{ data: 'timestamp' },
					
					{ data: 'options' },

			 ],

            "columnDefs": [

                { "orderable": false, "targets": 0 },

                { "orderable": true, "targets": 1 },

                { "orderable": false, "targets": 2 },

                { "orderable": true, "targets": 3 },

                { "orderable": true, "targets": 4 },

                { "orderable": true, "targets": 5 },

                { "orderable": false, "targets": 6 },

                { "orderable": false, "targets": 7 },
                
                { "orderable": false, "targets": 8 },
                
                { "orderable": false, "targets": 9 },
                
                { "orderable": false, "targets": 10 },



            ],

            "lengthMenu": [[5, 10, 25, 50, 500, 1000], [5, 10, 25, 50, 500, 1000]],

            initComplete : function() {

                $('.dataTables_filter input').hide();

            },            

		});

}); 

</script>



<div class="table-responsive">

<table id="basic-datatable-1" class="table table-striped dt-responsive nowrap" data-page-length="10" width="100%">

	<thead>

		<tr style="background-color: #313a46; color: #ababab;">

		    <th width="10%">Sr No</th>

			<th width="10%"><?php echo get_phrase('tag_no'); ?></th>

			<th width="20%"><?php echo get_phrase('Veterinarian'); ?></th>

			<th width="10%"><?php echo get_phrase('certificate_no'); ?></th>

			<th width="10%"><?php echo get_phrase('receipt_no'); ?></th>

			<th width="10%"><?php echo get_phrase('book_no'); ?></th>

			<th width="20%"><?php echo get_phrase('agent'); ?></th>

			<th width="10%"><?php echo get_phrase('age'); ?></th>

			<th width="10%"><?php echo get_phrase('sex'); ?></th>
			
			<th width="10%"><?php echo get_phrase('timestamp'); ?></th>
			
		    <th width="10%"><?php echo get_phrase('options'); ?></th>

		</tr>

	</thead>

</table>

<div>