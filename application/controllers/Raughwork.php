<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/


class Raughwork extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->database();
		$this->load->library('session');

		/*cache control*/
		$this->output->set_header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
		$this->output->set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
		$this->output->set_header("Pragma: no-cache");


	}

	// INDEX FUNCTION
	// default function
	public function dummy_records() 
	{
	    /*exit;
	    $rand  = rand(100,999);
        $users = $this->db->select('vyapari_id')->get('app_vyapari')->result_array();
        foreach($users as $row)
        {
            for ($x = 0; $x <= 1000; $x++)
            {
        		$insert['vyapari_id'] = 1000;
        		$insert['qrcode']     = $x.$row['vyapari_id'].$rand;	
        		$insert['status']     = 'unblock';
        		$insert['inward_by']  = $this->session->userdata('user_id');
        		$insert['inward_date']= date('Y-m-d H:i:s');
        		$insert['timestamp']  = date('Y-m-d H:i:s');  
        		$this->db->insert('app_qrcode111', $insert);
            }            
        }*/
	}
	
	function test_query()
	{
	    /*$this->db->select('vyapari_id,COUNT(vyapari_id)');
	    $this->db->from('app_qrcode');
	    $this->db->group_by('vyapari_id');
	    $this->db->where('status !=', 'exit');
	    $data = $this->db->get()->result_array();
	    
	    echo '<pre>';
	    var_dump($data);
	    echo '</pre>';*/
	}
	
	function remove_zero()
	{
	    echo 'Not found'; exit;
	    /*$this->db->select('qrcode_id,qrcode');
	    $this->db->from('app_qrcode');	 
	    $this->db->where('qrcode_id >=', 1);
	    $this->db->where('qrcode_id <=', 1694);
	    $result = $this->db->get()->result_array();
	    
	    foreach($result as $row)
	    {
	       $qrcode_new = substr($row['qrcode'], 1, 10);
	       
	       //echo $qrcode_new.'---'.$row['qrcode'].'---'.$row['qrcode_id'];
	       //echo '<br>';
	       
	       $update['qrcode'] = $qrcode_new;
           $this->db->where('qrcode_id', $row['qrcode_id']);
           $this->db->update('app_qrcode111111', $update);
	    }*/
	}
	
	function count_passes()
	{
	    echo 
	    '
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
        
        <form method="POST" class="" action="">
        <div class="col-md-7" style="margin-top:15px;">
            <div class="qrcode-block">
                <div class="qrcode-fields">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <input type="number" class="form-control S1" placeholder="Sequence From" name="sequence_from[]" value="0" onfocus="countPasses()" onchange="countPasses();" onkeyup="countPasses()" maxlength="10" autofocus required>
                        </div>
                        <div class="col-md-4 form-group">
                            <input type="number" class="form-control S2" placeholder="Sequence To" name="sequence_to[]" value="0" maxlength="10" onfocus="countPasses()" onchange="countPasses();" onkeyup="countPasses()" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <!--<div class="btn btn-block btn-primary" onclick="countPasses();">Count</div>-->
                            <div><h3 style="margin: 4px;">Pass : <span id="cp">0</span></h3></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        </form>
	    
	    <script>
            function countPasses()
            {
                if($(".S1").val() == 0 || $(".S2").val() == 0)
                {
                    $("#cp").html(0);
                    return false;
                }
                var all_passes = 0;
                var seq_from = $(".S1").map(function(){return $(this).val();}).get();
                var seq_to   = $(".S2").map(function(){return $(this).val();}).get();
            
                $.each(seq_from, function(index, value){
                    for (let i = value; i <= seq_to[index]; i++)
                    {
                      all_passes += 1;
                    }
                });   
                
            
                console.log(seq_from); 
                console.log(seq_to); 
                console.log(all_passes);
                
                //alert(all_passes);
                $("#cp").html(all_passes);
            }	    
	    </script>
	    '
	    ;
	}
	
	
}