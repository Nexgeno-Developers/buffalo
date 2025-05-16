<?php 
   $vyapari = $this->db->where('vyapari_id', $vyapari_id)->get('app_vyapari')->row_array();
   if(empty($vyapari))
   {
       redirect(route('dashboard'));
   }
?>
<style>
    div#basic-datatable-0_filter {
    position: absolute;
    right: 13%;
    top: 21px;
}
.vyapari_qrcodes .dt-buttons.btn-group {
    position: absolute;
    right: 15px;
    top: 20px;
}
.apbtn button {
    width: 80%;
}
</style>
<!--title-->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
          <div class="row">
        <div class="col-md-9 col-12">
        <h4 class="page-title vyapdet mt-0">
            <i class="mdi mdi-book-open-page-variant title_icon"></i> <?php echo get_phrase($page_title); ?>
            <!--Allocate QR-->
            <!--Allocate Pandaal-->
            </h4>
        </div>

        <?php if(access('allocate_pass_button')){ ?>
        <div class="col-md-3 col-6">
            <button type="button" class="btn btn-outline-success btn-rounded alignToTitle pass" onclick="rightModal('<?php echo site_url('modal/popup/vyapari/allocate-qrcode/'.$vyapari['vyapari_id']); ?>', '<?php echo get_phrase('pass_allocate'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('add_pass'); ?></button>
        </div>
        <?php } ?>

       <?php if(access('allocate_pandol')){ ?>
        <div class="col-md-2 col-6 d-none">
           <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle pandol" onclick="rightModal('<?php echo site_url('modal/popup/vyapari/edit-pandal/'.$vyapari['vyapari_id']); ?>', '<?php echo get_phrase('pandol_allocate'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('add_pandol'); ?></button>
        </div>
        <?php } ?>
        
        <div>
      </div></div>
          
          
       
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<div class="row">
    <div class="col-12">
        <div class="card mt-2">
            <div class="card-body"> 
                <div class="vyapari_content">
                    <div class="row tclass1">
                        <div class="col-md-1 col-12 mb-2 vpdimg"><?= $vyapari['photo'] ? '<a target="_blank" href="'.base_url().'uploads/vyapari_photo/'.$vyapari['photo']. '?' . time() . '"><img width="70px" height="70px" src="'.base_url().'uploads/vyapari_photo/'.$vyapari['photo']. '?' . time() . '"></a>' : null; ?></div>
                        <div class="col-md-2 col-6">
                            <p><span>Vyapari ID :</span> <?= vyapari_id($vyapari['vyapari_id']); ?></p>
                            <p><span>State :</span> <?= $vyapari['state']; ?></p>
                        </div>
                        <div class="col-md-2 col-6">
                            <p><span>Name :</span> <?= $vyapari['name']; ?></p>
                            <p><span>Location :</span> <?= $vyapari['locality']; ?> </p>
                            </div>
                        <div class="col-md-2 col-6">
                            <p><span>Phone :</span> <?= $vyapari['phone']; ?></p>
                            <p><span>Address :</span> <?= $vyapari['address']; ?></p>
                            </div>
                        <div class="col-md-3 col-6">
                            <p><span>Aadhar No :</span> <?= $vyapari['aadhar_no']; ?></p>
                            
                            <?php
                                $pandal = array();
                                $pandaal__no = $this->db->select('pandaal_no')->from('app_qrcode')->where('vyapari_id', $vyapari['vyapari_id'])->group_by('pandaal_no')->get()->result(); 
                                foreach ($pandaal__no as $row) {
                                        $pandal[] = $row->pandaal_no; // Append each pandaal_no to the $pandal array
                                    }
                                ?>
                            <p><span>Pandol No :</span> <?php
                                                            if ($pandal) {
                                                                echo implode(", ",$pandal);
                                                            } else {
                                                                echo 'Not Allocated';
                                                            }
                                                        ?></p>
                                                        
                                                        
                            <?php
                                $receipt = array();
                                $receipt__no = $this->db->select('receipt_no')->from('app_qrcode')->where('vyapari_id', $vyapari['vyapari_id'])->group_by('receipt_no')->get()->result(); 
                                foreach ($receipt__no as $row) {
                                        $receipt[] = $row->receipt_no; // Append each pandaal_no to the $pandal array
                                    }
                                ?>
                            <p><span>Receipt No :</span> <?php
                                                            if ($receipt) {
                                                                echo implode(", ",$receipt);
                                                            } else {
                                                                echo '  -  ';
                                                            }
                                                        ?></p>
                            
                            <!--<p><span>Receipt No :</span> <?= $vyapari['receipt_no'] ? $vyapari['receipt_no'] : '-'; ?></p>-->
                            </div>
                            <?php if(access('printid_button')){ ?>
                        <div class="col-md-2 col-12"><a target="_blank" class="btn btn-sm btn-success printbtn" href="<?php echo base_url('superadmin/manage_vyapari/print/'.$vyapari['vyapari_id']); ?>">Print ID</a>
                        <!--<a target="_blank" class="btn btn-sm btn-success printbtn mt-2" href="<?php echo base_url('superadmin/manage_vyapari/print2/'.$vyapari['vyapari_id']); ?>">Print ID 2</a>-->
                            
                    </div>
                     
                     <div class="col-md-12"><hr> </div>
                    <div class="col-md-12 col-12">
                        <div class="row">
                    <?php } ?>
                    
                    
                    <?php $receipt_no = $this->db->select(['receipt_no','timestamp'])->from('app_qrcode')->where('vyapari_id', $vyapari['vyapari_id'])->group_by('receipt_no')->order_by('timestamp', 'DESC')->get()->result(); ?>
                    
                    <?php foreach($receipt_no as $row) { ?>
                        <?php 
                           //$certificates = $this->db->select('qrcode as certificate_no, timestamp')->where('vyapari_id', $vyapari['vyapari_id'])->where('receipt_no', $row->receipt_no)->order_by('qrcode', 'asc')->get('app_qrcode')->result();
                            
                             $certificates = $this->db->select(['app_qrcode.qrcode', 'cattle_pre_booking.certificate_no','app_qrcode.timestamp'])->from('app_qrcode')
                                         ->join('cattle_pre_booking', 'app_qrcode.qrcode = cattle_pre_booking.tag_no')
                                         ->where('app_qrcode.vyapari_id', $vyapari['vyapari_id'])
                                         ->where('app_qrcode.receipt_no', $row->receipt_no)
                                         ->order_by('cattle_pre_booking.certificate_no', 'asc')
                                         ->get()
                                         ->result();

                        ?>                    
                    
                        <?php if(access('printid_button')){ ?>
                            <div class="col-md-12 col-2 mb-2 reciept_no_btn">
                                <label> <b>Receipt No : <?php echo $row->receipt_no; ?></b></label>
                                <!--<a target="_blank" class="btn btn-sm btn-success" href="<?php echo base_url('Qrcodegenerator/create_bulk_tag_qr/'.$vyapari['vyapari_id']); ?>">Print Tag No</a>-->
                                <a target="_blank" class="btn btn-sm btn-success" href="<?php echo base_url('Qrcodegenerator/create_bulk_tag_qr/?vyapari_id=' . $vyapari['vyapari_id'] . '&receipt_no=' . $row->receipt_no); ?>">Print Tag No QR Code</a>
                                <small>Receipt Date :<b><?php echo date('d-m-Y h:iA', strtotime(end($certificates)->timestamp)); ?></b> / TOTAL TAGS : <b><?php echo count($certificates); ?></b> / From <b><?php echo reset($certificates)->certificate_no; ?></b> to <b><?php echo end($certificates)->certificate_no; ?></b> certificate no.</small>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    </div>
                    </div>
                    
                    
                </div>              
            </div>
        </div>
    </div>
</div>


<?php 
    $this->db->select('*');
    $this->db->from('app_qrcode use index (vyapari_id)');
    $this->db->where('vyapari_id', $vyapari['vyapari_id']);
    $this->db->order_by('qrcode', 'asc');
    $vyapari_qrcodes = $this->db->get()->result_array();
?>

    <div class="col-12">
        <div class="card mt-2">
            <div class="card-body"> 
                <div class="vyapari_qrcodes">
                
                <div class="row totaldiv">
                    <div class="col-md-3">
                        <h3 id="qrtotal">Total Pass: <?php echo count($vyapari_qrcodes); ?></h3>
                    </div>
                <?php if(access('manage_pass_button')){ ?>  
                <div class="col-md-3 col-6 serhbox">
                    <select name="action" class="form-control action">
                        <option value="">Bulk actions</option>
                        <option value="block">Block</option>
                        <option value="unblock">Unblock</option>
                    </select>
                </div>
                
                <div class="col-md-2 col-6 apbtn">
                    <button type="button" class="btn btn-secondary btn-block" onclick="rightModal('<?php echo site_url('modal/popup/vyapari/bulk-complaint-qrcode/'.$vyapari['vyapari_id']); ?>', '<?php echo get_phrase('block_qrcode'); ?>')">Apply</button>
                </div> 
                <?php } ?>
                
                <div class="col-md-2 col-6 hidden">
                    <button type="button" class="btn btn-secondary btn-block" onclick="rightModal('<?php echo site_url('modal/popup/vyapari/reallocate-qrcode/'); ?>', '<?php echo get_phrase('reallocate_qrcode'); ?>')">Reallocate QRcode</button>
                </div>                 
                
                </div>
                    <table id="basic-datatable-0" class="table-responsive-xl table-responsive-lg table-responsive-md table-responsive-sm table table-striped " data-page-length="2000" width="100%"> <!--nowrap-->
                        <thead>
                            <tr style="background-color: #313a46; color: #ababab;">
                                <th width="3%"><input type="checkbox" class="check_all" name="check_all" value="1"></th>
                                <th width="10%"><?php echo get_phrase('sr_no'); ?></th>
                                <th width="10%"><?php echo get_phrase('pass_no'); ?></th>
                                <th width="20%" class="hidden"><?php echo get_phrase('vyapari_name'); ?></th>
                                <th width="20%" class="hidden"><?php echo get_phrase('vyapari_phone'); ?></th>
                                <th width="15%"><?php echo get_phrase('current_status'); ?></th>
                                <th width="12%"><?php echo get_phrase('notes'); ?></th>
                                <th width="25%"><?php echo get_phrase('Other Details'); ?></th>
                                <th width="13%"><?php echo get_phrase('created_at'); ?></th>
                                <th width="28%"><?php echo get_phrase('options'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 1; 
                                foreach($vyapari_qrcodes as $row){
                                $notes = $this->db->where('qrcode_id', $row['qrcode_id'])->get('app_qrcode_complaints')->result_array();
                            ?>
                                <tr>
                                    <td>
                                        <?php if($row['status'] != 'exit'){ ?>
                                        <input type="checkbox" class="complaints_checkbox" name="complaints_checkbox[]" value="<?php echo $row['qrcode_id']; ?>">
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $i++;; ?></td>
                                    <td><?php echo $row['qrcode']; ?></td>
                                    <td class="hidden"><?php echo $vyapari['name']; ?></td>
                                    <td class="hidden"><?php echo $vyapari['phone']; ?></td>
                                    <td>
                                        <?php if($row['status'] == 'block'){ ?>
                                             <b><span class="text-danger">Blocked</span></b>
                                        <?php }elseif($row['status'] == 'unblock'){ ?>
                                             <b><span class="text-warning">Active</span></b>
                                        <?php }elseif($row['status'] == 'exit'){ ?>
                                             <b><span class="text-success">Exit</span></b>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php $x=1; if($row['status'] != 'exit'){ foreach($notes as $row1){ ?>
                                            <div>
                                                <?php echo $x++.'. '.$row1['status'].':-'.$row1['notes']; ?>
                                                <br>
                                                <b>Date : <?php echo $row1['timestamp']; ?></b>
                                                <hr style="margin:5px;">
                                            </div>
                                        <?php }} ?>
                                    </td>
                                    <td>
                                        <label>Certificate No : <?php echo $this->db->where('tag_no', $row['qrcode'])->get('cattle_pre_booking')->row()->certificate_no; ?></label>
                                        <label>Receipt No : <?php echo $row['receipt_no']; ?></label>
                                        <?php $bk_n = $this->db->get_where('app_broker', array('id' => $row['broker_id']))->row()->applicant_name ?>
                                        <?php $gw_n = $this->db->get_where('app_gwala', array('id' => $row['gwala_id']))->row()->applicant_name ?>
                                        <br> <label>Beef Helkari Name : <?php echo $bk_n; ?></label>
                                        <br> <label>Dawanwala Name : <?php echo $gw_n; ?></label>
                                        <br> <label>Slaughtering type : <?php echo ($row['slaughtering_type'] == 2) ? 'Selling' : 'Slaughtering'; ?></label>
                                    </td>
                                    <td><?php echo $row['timestamp']; ?></td>
                                    <td>
                                        <?php if(access('manage_pass_button')){ ?>
                                        <?php if($row['status'] == 'unblock'){ ?>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="rightModal('<?php echo site_url('modal/popup/vyapari/complaint-qrcode/'.$row['qrcode_id'].'/block'); ?>', '<?php echo get_phrase('block_qrcode'); ?>')"><?php echo get_phrase('do_block'); ?></button>
                                        <?php }elseif($row['status'] == 'block'){ ?>

                                            <button type="button" class="btn btn-sm btn-info" onclick="rightModal('<?php echo site_url('modal/popup/vyapari/complaint-qrcode/'.$row['qrcode_id'].'/unblock'); ?>', '<?php echo get_phrase('unblock_qrcode'); ?>')"><?php echo get_phrase('do_unblock'); ?></button>                                        <?php }elseif($row['status'] == 'exit'){ ?> 
                                             
                                             <b><span class="text-success">Scanned at <?= $row['exit_date'] ?></span></b>
                                        <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>              
            </div>
        </div>
    </div>

<style>
.vyapari_qrcodes .dt-buttons.btn-group {
   position: absolute;
}

.dataTables_length {
    display: none;
}   
.tclass1 td {
        padding:0px 40px 0px 0px;
    }

        .tclass1 span {
    font-weight: bold;
    color: #363636;
}
    
    .tclass1 p{
        margin-bottom: 10px;
    }
    
.alignToTitle {
    margin-left: 20px;
}
.one {
    max-width: 10%;
}
/*.row.totaldiv {
    position: absolute;
    left: 20px;
    right: 0;
    
    z-index: 1;
}*/
.reciept_no_btn a {
    padding: 3px 12px;
    font-size: 12px;
    border-radius: 50px;
    background: linear-gradient(124deg, #ca5c816e 0, #e91e63ad 73%) !important;
    border: 0;
    box-shadow: none;
}

div#basic-datatable-0_filter input {
   z-index: 9;
   position: relative;
}

@media (max-width: 767px){
 
    a.printbtn {
    display: grid;
    position: initial;
}
.vpdimg {
    display: grid;
    justify-content: center;
}
h4.vyapdet {
    text-align: center;
    margin-bottom: 20px;
}
.alignToTitle {
    float: none;
}
h3#qrtotal {
    text-align: center;
}

.col-md-3.serhbox {
    margin-bottom: 20px;
}

.col-md-2.apbtn {
    margin-bottom: 20px;
}
.row.totaldiv{
    position: initial;
}
.wrapper {
    padding-top: 0;
}
}


</style>

<script>
    $("body").on('change', '.check_all', function() {
    if(this.checked)
    {
        $('.complaints_checkbox').attr('checked','checked');
    }
    else
    {
         $('.complaints_checkbox').removeAttr('checked');
    }
});

$(document).ready(function () {
    $('#basic-datatable-0').DataTable({
       order: [],
		dom: 'lBfrtip',
		buttons: [
		    {
                extend: 'csvHtml5',
                filename: 'blocked-passes', 
                text: 'Export',
                className: 'btn-sm btn-secondary btn-data-export',
                exportOptions: {
                    columns: [ 1, 2, 3, 4, 5, 6, 7 , 8]
                }                    
            }
        ],       
        "columnDefs": [
            { "orderable": false, "targets": 0 },
            { "orderable": false, "targets": 1 },
            { "orderable": false, "targets": 2 },
            { "orderable": false, "targets": 3 },
            { "orderable": false, "targets": 4 },
            { "orderable": false, "targets": 5 },
            { "orderable": false, "targets": 6 },
            { "orderable": false, "targets": 7 },
            { "orderable": false, "targets": 8 },

        ],        
    });
});
</script>
