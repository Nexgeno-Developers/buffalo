<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Deonar_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}
	
	function UploadWebCamImage()
	{
        $img = $_POST['webcam_image'];
        if(!empty($img))
        {
            $folderPath = "uploads/vyapari_photo/";
          
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
          
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = time() . '.png';
          
            $file = $folderPath . $fileName;
            file_put_contents($file, $image_base64);
            
            $app_vyapari['photo'] = $fileName;
        	$this->db->where('temp_code', $this->input->post('temp_code'));
        	$this->db->update('app_vyapari', $app_vyapari);		
        }	
	}

	public function vyapari_create()
	{
		$app_vyapari['name'] = $this->input->post('name');
		$app_vyapari['phone'] = $this->input->post('phone');
		$app_vyapari['aadhar_no'] = $this->input->post('aadhar_no');
		$app_vyapari['address'] = $this->input->post('address');
		$app_vyapari['animal_quantity'] = $this->input->post('animal_quantity');
		$app_vyapari['status'] = 'active';
		$app_vyapari['state'] = $this->input->post('state');
		$app_vyapari['locality'] = $this->input->post('locality');
		$app_vyapari['photo'] = $this->input->post('photo');
		$app_vyapari['temp_code'] = $this->input->post('temp_code');
		$app_vyapari['timestamp'] = date('Y-m-d H:i:s');

		$is_exist_phone = $this->db->select('vyapari_id')->where('phone', $app_vyapari['phone'])->get('app_vyapari')->num_rows();
		$is_exist_aadhar_no = $this->db->select('vyapari_id')->where('aadhar_no', $app_vyapari['aadhar_no'])->get('app_vyapari')->num_rows();

		if($is_exist_phone > 0)
		{
			$response = array(
				'status' => false,
				'notification' => get_phrase('phone_number_already_exist')
			);
			return json_encode($response);
		}

		if($is_exist_aadhar_no > 0)
		{
			$response = array(
				'status' => false,
				'notification' => get_phrase('aadhar_number_already_exist')
			);
			return json_encode($response);
		}
		
		$photoname = time();
		if ($_FILES['photo']['name'] != "") {
			$app_vyapari['photo'] = $photoname.'photo.jpg';
			$path = 'uploads/vyapari_photo/'.$app_vyapari['photo'];
			move_uploaded_file($_FILES['photo']['tmp_name'], $path);
			//compressImage($path, $path, 30); 
		}		

		$this->db->insert('app_vyapari', $app_vyapari);
		$vyapari_id = $this->db->insert_id();
		old_vyapari_check($this->input->post());
		
		if($vyapari_id)
		{
			$log = array(
				'name' => 'vyapari_registration',
				'description' => '<b>'.vyapari_id($vyapari_id).'</b> registered successfully.',
			 );
			 app_log($log);
	 
			 $response = array(
				 'status' => true,
				 'notification' => get_phrase('vyapari_added_successfully')
			 );			
		}
		else
		{
			$response = array(
				'status' => false,
				'notification' => get_phrase('something_went_wrong!')
			);
		}

		return json_encode($response);		

		/*if($vyapari_id)
		{
			$sequence_from = $this->input->post('sequence_from');
			$sequence_to   = $this->input->post('sequence_to');
			
			$x = 0;
			$qrcodes = array();
			foreach($sequence_from as $seq_from)
			{
				for ($i = $seq_from; $i <= $sequence_to[$x]; $i++) 
				{
					$qrcodes[] = 'MAK-'.$i;
				}			    
				$x++;
			}
	
			foreach($qrcodes as $qr)
			{
				$app_qrcode['vyapari_id'] = $vyapari_id;
				$app_qrcode['qrcode'] = $qr;
				$app_qrcode['status'] = 'active';
				$app_qrcode['timestamp'] = date('Y-m-d H:i:s');
				$this->db->insert('app_qrcode', $app_qrcode);
			}
		}*/
	}

	function vyapari_pandaal_update($vyapari_id)
    {
        $pandol = trim(strtoupper($this->input->post('pandaal_no')));
        
        $app_vyapari['receipt_no'] = trim($this->input->post('receipt_no'));
		$app_vyapari['pandaal_no'] = $pandol;	
		$this->db->where('vyapari_id', $vyapari_id);
		$updated = $this->db->update('app_vyapari', $app_vyapari);	
		
        $upd['pandaal_no'] = $pandol;
		$this->db->where('vyapari_id', $vyapari_id);
		$updated = $this->db->update('app_qrcode', $upd);		
		
		if($updated)
		{
			$log = array(
				'name' => 'pandaal_allocation',
				'description' => '<b>'.vyapari_id($vyapari_id).'</b> Pandaal (('.$app_vyapari['pandaal_no'].')) allocated successfully.',
			 );
			 app_log($log);
	 
			 $response = array(
				 'status' => true,
				 'notification' => get_phrase('pandaal_allocated_successfully')
			 );
		}
		else
		{
			$response = array(
				'status' => false,
				'notification' => get_phrase('something_went_wrong!')
			);
		}

		 return json_encode($response);
	}

	function get_all_vyapari($post=null){	
	
		$response = array();
  
		## Read value
		$draw = $post['draw'];
		$start = $post['start'];
		$rowperpage = $post['length']; // Rows display per page
		$columnIndex = $post['order'][0]['column']; // Column index
		$columnName = $post['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $post['order'][0]['dir']; // asc or desc

		$searchValue = trim($post['search']['value']);
		$vyapari_id = trim($post['vyapari_id']);
		$name = trim($post['name']);
		$phone = trim($post['phone']);
		$aadhar_no = trim($post['aadhar_no']);
		$from  = $post['from'] ? trim(date('Y-m-d 00:00:00' ,strtotime($post['from']))) : null;
		$to = $post['to'] ? trim(date('Y-m-d 23:59:59' ,strtotime($post['to']))) : null;
		
		## Total number of record with filtering
		$this->db->select('vyapari_id');
		$this->db->from('app_vyapari');
		// if(!empty($vyapari_id))
		// {
    	// 	$this->db->where('vyapari_id', $vyapari_id);
		// }
		if(!empty($vyapari_id))
		{
			// Extract the current year
			$currentYear = date('Y');
			// Convert to uppercase
			$vyapari_id = strtoupper($vyapari_id);
			// Use regex to remove "V2025-" and get the remaining part
			$vyapari_id = preg_replace("/^V$currentYear-/", '', $vyapari_id);

    		$this->db->where('vyapari_id', $vyapari_id);
		}
		if(!empty($name))
		{
    		$this->db->like('name', $name);
		}
		if(!empty($phone))
		{
    		$this->db->where('phone', $phone);
		}
		if(!empty($aadhar_no))
		{
    		$this->db->where('aadhar_no', $aadhar_no);
		}
        if(!empty($from) && !empty($to))
        {
            $this->db->where('timestamp >=', $from);
            $this->db->where('timestamp <=', $to);
        }		
		$records = $this->db->get()->num_rows();
		$totalRecordwithFilter = $records;
  	
		## Fetch records
		$this->db->select('*');
		$this->db->from('app_vyapari');
		// if(!empty($vyapari_id))
		// {
    	// 	$this->db->where('vyapari_id', $vyapari_id);
		// }
		if(!empty($vyapari_id))
		{
			// Extract the current year
			$currentYear = date('Y');
			// Convert to uppercase
			$vyapari_id = strtoupper($vyapari_id);
			// Use regex to remove "V2025-" and get the remaining part
			$vyapari_id = preg_replace("/^V$currentYear-/", '', $vyapari_id);

    		$this->db->where('vyapari_id', $vyapari_id);
		}
		if(!empty($name))
		{
    		$this->db->like('name', $name);
		}
		if(!empty($phone))
		{
    		$this->db->where('phone', $phone);
		}
		if(!empty($aadhar_no))
		{
    		$this->db->where('aadhar_no', $aadhar_no);
		}
        if(!empty($from) && !empty($to))
        {
            $this->db->where('timestamp >=', $from);
            $this->db->where('timestamp <=', $to);
        }		
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get()->result_array();

		$data = array();
        $sr = $start + 1;
		foreach($records as $record)
		{
		    $total_inward  = $this->db->select('qrcode_id')->where('vyapari_id', $record['vyapari_id'])->get('app_qrcode')->num_rows();
		    $total_outward = $this->db->select('qrcode_id')->where('status', 'exit')->where('vyapari_id', $record['vyapari_id'])->get('app_qrcode')->num_rows();
		    
			$btn_view = '<a target="_blank" href="'.site_url('superadmin/manage_vyapari/view/'.$record['vyapari_id']).'" class="btn-sm btn-link">'.get_phrase('Details').'</a>';
			
			
			if(access('printid_button'))
			{
    			$btn_print = '<a target="_blank" href="'.site_url('superadmin/manage_vyapari/print/'.$record['vyapari_id']).'" class="btn-sm btn-link">Print ID</a>';
			
			if($this->session->userdata('user_id') == 1 || $this->session->userdata('role_type') == 'inward')
			{
			    $event = "rightModal('".site_url('modal/popup/vyapari/edit-vyapari/'.$record['vyapari_id'])."','Edit Vyapari')";
                $btn_edit = '<a href="javascript:void(0);" class="btn-sm btn-link" onclick="'.$event.'">'.get_phrase('edit').'</a>';			    
			}
			    
			}
			else
			{
			    $btn_print = null;
			    $btn_edit  = null;
			}
			
            $pandal = array();
            $pandaal__no = $this->db->select('pandaal_no')->from('app_qrcode')->where('vyapari_id', $record['vyapari_id'])->group_by('pandaal_no')->get()->result(); 
            foreach ($pandaal__no as $row) {
                    $pandal[] = $row->pandaal_no;
            }
            
            $receipt = array();
            $receipt__no = $this->db->select('receipt_no')->from('app_qrcode')->where('vyapari_id', $record['vyapari_id'])->group_by('receipt_no')->get()->result(); 
            foreach ($receipt__no as $row) {
                    $receipt[] = $row->receipt_no;
            }

                        
			$data[] = array( 
			    "sr_no"  => $sr,
				"vyapari_id"  => vyapari_id($record['vyapari_id']),
				"name" => '<a target="_blank" href="'.site_url('superadmin/manage_vyapari/view/'.$record['vyapari_id']).'" class="text-dark">'.$record['name'].'</a>',
				"receipt_no" => $receipt ? implode(", ",$receipt) : ' - ',
				"pandaal_no" => $pandal ? implode(", ",$pandal) : '<span class="text-danger"><b>Not allocated</b></span>',
				"total_inward" => $total_inward,
				"total_outward" => $total_outward,
				"total_balance" => $total_inward - $total_outward,
				"aadhar_number" => $record['aadhar_no'],
				"phone" => $record['phone'],
				"timestamp" => date('d MY H:i:s', strtotime($record['timestamp'])),
				"options" => $btn_view.''.$btn_print. ''.$btn_edit,
			);
			
			$sr++;
		}
  
		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecordwithFilter,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);
  
		return $response; 
	}
	
	
	//-------------- new 2024 --------------------------//
	
		function get_all_cattle_pre_reg($post=null){	
	
		$response = array();
  
		## Read value
		$draw = $post['draw'];
		$start = $post['start'];
		$rowperpage = $post['length']; // Rows display per page
		$columnIndex = $post['order'][0]['column']; // Column index
		$columnName = $post['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $post['order'][0]['dir']; // asc or desc

		$searchValue = trim($post['search']['value']);
		$doctor_name = trim($post['doctor_name']);
		$agent_name = trim($post['agent_name']);
		$tag_no = trim($post['tag_no']);
		$health_certificate = trim($post['health_certificate']);
		$receipt_no = trim($post['receipt_no']);
		
		$cattle_sex = trim($post['cattle_sex']);
		$book_no = trim($post['book_no']);
		
		$from  = $post['from'] ? trim(date('Y-m-d 00:00:00' ,strtotime($post['from']))) : null;
		$to = $post['to'] ? trim(date('Y-m-d 23:59:59' ,strtotime($post['to']))) : null;
		
		## Total number of record with filtering
		$this->db->select('*');
		$this->db->from('cattle_pre_booking');
		if(!empty($doctor_name))
		{
    		$this->db->where('doctor_name', $doctor_name);
		}
		if(!empty($agent_name))
		{
    		$this->db->where('gwala_id', $agent_name);
		}
		if(!empty($tag_no))
		{
    		$this->db->like('tag_no', $tag_no);
		}
		if(!empty($health_certificate))
		{
    		$this->db->where('certificate_no', $health_certificate);
		}
		if(!empty($receipt_no))
		{
    		$this->db->where('receipt_no', $receipt_no);
		}
		
		if(!empty($cattle_sex) || $$cattle_sex != 0)
		{
    		$this->db->where('cattle_sex', $cattle_sex);
		}
		
		if(!empty($book_no))
		{
    		$this->db->where('book_no', $book_no);
		}
		
        if(!empty($from) && !empty($to))
        {
            $this->db->where('timestamp >=', $from);
            $this->db->where('timestamp <=', $to);
        }		
		$records = $this->db->get()->num_rows();
		$totalRecordwithFilter = $records;
  	
		## Fetch records
		$this->db->select('*');
		$this->db->from('cattle_pre_booking');
		if(!empty($doctor_name))
		{
    		$this->db->where('doctor_name', $doctor_name);
		}
		if(!empty($agent_name))
		{
    		$this->db->where('gwala_id', $agent_name);
		}		
		if(!empty($tag_no))
		{
    		$this->db->like('tag_no', $tag_no);
		}
		if(!empty($health_certificate))
		{
    		$this->db->where('certificate_no', $health_certificate);
		}
		if(!empty($receipt_no))
		{
    		$this->db->where('receipt_no', $receipt_no);
		}
		
		if(!empty($cattle_sex) || $$cattle_sex != 0)
		{
    		$this->db->where('cattle_sex', $cattle_sex);
		}
		
		if(!empty($book_no))
		{
    		$this->db->where('book_no', $book_no);
		}
		
        if(!empty($from) && !empty($to))
        {
            $this->db->where('timestamp >=', $from);
            $this->db->where('timestamp <=', $to);
        }		
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get()->result_array();

		$data = array();
        $sr = $start + 1;
		foreach($records as $record)
		{
		    
            if (access('cattle_prebooking_delete')) {
                $btn_delete = '<a href="#" onclick="confirmDelete(\'' . site_url('superadmin/cattle_prebooking/delete/' . $record['id']) . '\')" class="btn-sm btn-link">Delete</a>';
            } else {
                $btn_delete = null;
            }
		    
            if($record['doctor_name'] != null || !empty($record['doctor_name']) || $record['doctor_name'] != ''){
                $doctor = $this->db->select('name')->from('users')->where('id', $record['doctor_name'])->get()->row();
                $doctor = $doctor->name;
            } else {
                 $doctor = null;
            }
            
            if($record['gwala_id'] != null || !empty($record['gwala_id']) || $record['gwala_id'] != ''){
                $agent = $this->db->select('applicant_name')->from('app_gwala')->where('id', $record['gwala_id'])->get()->row();
                $agent = $agent->applicant_name;
            } else {
                 $agent = null;
            }
            
            if($record['cattle_sex'] != null || !empty($record['cattle_sex']) || $record['cattle_sex'] != ''){
                if($record['cattle_sex'] == 1){
                    $cattle_sex = 'MB';
                } else {
                    $cattle_sex = 'SB';
                }
 
            } else {
                 $cattle_sex = null;
            }

			$data[] = array( 
			    "sr_no"  => $sr,
				"tag_no"  => $record['tag_no'] ? '"'.$record['tag_no'] : '-',
				"doctor_name" => $doctor ? $doctor : '-',
				"certificate_no" => $record['certificate_no'] ? '"'.$record['certificate_no'] : '-',
				"receipt_no" => $record['receipt_no'] ? '"'.$record['receipt_no'] : '-',
				"book_no" => $record['book_no'] ? '"'.$record['book_no'] : '-',
				"agent" => $agent ? $agent : '-',
				"dob" => $record['cattle_age'] ? $record['cattle_age'] : '-',
				"cattle_sex" => $cattle_sex ? $cattle_sex : '-',
				"timestamp" => date('d MY H:i:s', strtotime($record['timestamp'])),
				"options" => $btn_delete,
			);
			
			$sr++;
		}
  
		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecordwithFilter,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);
  
		return $response;  
	}
	
	
	function get_all_cattle_pre_reg_reports($post=null) {	
	
		$doctor_name = trim($post['doctor_name']);
		$agent_name = trim($post['agent_name']);
		$tag_no = trim($post['tag_no']);
		$health_certificate = trim($post['health_certificate']);
		$receipt_no = trim($post['receipt_no']);
		$cattle_sex = trim($post['cattle_sex']);
		$book_no = trim($post['book_no']);
		$from  = $post['from'] ? trim(date('Y-m-d 00:00:00' ,strtotime($post['from']))) : null;
		$to = $post['to'] ? trim(date('Y-m-d 23:59:59' ,strtotime($post['to']))) : null;
		
		//Total Counting Buffelo
		$this->db->select('id');
		$this->db->from('cattle_pre_booking');
		if(!empty($doctor_name))
		{
    		$this->db->where('doctor_name', $doctor_name);
		}
		if(!empty($agent_name))
		{
    		$this->db->where('gwala_id', $agent_name);
		}		
		if(!empty($tag_no))
		{
    		$this->db->like('tag_no', $tag_no);
		}
		if(!empty($health_certificate))
		{
    		$this->db->where('certificate_no', $health_certificate);
		}
		if(!empty($receipt_no))
		{
    		$this->db->where('receipt_no', $receipt_no);
		}
		
		if(!empty($cattle_sex) || $$cattle_sex != 0)
		{
    		$this->db->where('cattle_sex', $cattle_sex);
		}
		
		if(!empty($book_no))
		{
    		$this->db->where('book_no', $book_no);
		}
		
        if(!empty($from) && !empty($to))
        {
            $this->db->where('timestamp >=', $from);
            $this->db->where('timestamp <=', $to);
        }	
        
		$response['prebookingCount'] = $this->db->get()->num_rows();
		
		//Total Counting Male
        $this->db->select('id');
        $this->db->from('cattle_pre_booking');
		if(!empty($doctor_name))
		{
    		$this->db->where('doctor_name', $doctor_name);
		}
		if(!empty($agent_name))
		{
    		$this->db->where('gwala_id', $agent_name);
		}		
		if(!empty($tag_no))
		{
    		$this->db->like('tag_no', $tag_no);
		}
		if(!empty($health_certificate))
		{
    		$this->db->where('certificate_no', $health_certificate);
		}
		if(!empty($receipt_no))
		{
    		$this->db->where('receipt_no', $receipt_no);
		}
		
		if(!empty($cattle_sex) || $$cattle_sex != 0)
		{
    		$this->db->where('cattle_sex', $cattle_sex);
		}
		
		if(!empty($book_no))
		{
    		$this->db->where('book_no', $book_no);
		}
		
        if(!empty($from) && !empty($to))
        {
            $this->db->where('timestamp >=', $from);
            $this->db->where('timestamp <=', $to);
        }	
        $this->db->where('cattle_sex','1');
		$response['male'] = $this->db->get()->num_rows();	
		
		//Total Counting Male
        $this->db->select('id');
        $this->db->from('cattle_pre_booking');
		if(!empty($doctor_name))
		{
    		$this->db->where('doctor_name', $doctor_name);
		}
		if(!empty($agent_name))
		{
    		$this->db->where('gwala_id', $agent_name);
		}		
		if(!empty($tag_no))
		{
    		$this->db->like('tag_no', $tag_no);
		}
		if(!empty($health_certificate))
		{
    		$this->db->where('certificate_no', $health_certificate);
		}
		if(!empty($receipt_no))
		{
    		$this->db->where('receipt_no', $receipt_no);
		}
		
		if(!empty($cattle_sex) || $$cattle_sex != 0)
		{
    		$this->db->where('cattle_sex', $cattle_sex);
		}
		
		if(!empty($book_no))
		{
    		$this->db->where('book_no', $book_no);
		}
		
        if(!empty($from) && !empty($to))
        {
            $this->db->where('timestamp >=', $from);
            $this->db->where('timestamp <=', $to);
        }	
        $this->db->where('cattle_sex','2');
		$response['female'] = $this->db->get()->num_rows();	
		
		//Total Counting Male
        $this->db->select('id');
        $this->db->from('cattle_pre_booking');
		if(!empty($doctor_name))
		{
    		$this->db->where('doctor_name', $doctor_name);
		}
		if(!empty($agent_name))
		{
    		$this->db->where('gwala_id', $agent_name);
		}		
		if(!empty($tag_no))
		{
    		$this->db->like('tag_no', $tag_no);
		}
		if(!empty($health_certificate))
		{
    		$this->db->where('certificate_no', $health_certificate);
		}
		if(!empty($receipt_no))
		{
    		$this->db->where('receipt_no', $receipt_no);
		}
		
		if(!empty($cattle_sex) || $$cattle_sex != 0)
		{
    		$this->db->where('cattle_sex', $cattle_sex);
		}
		
		if(!empty($book_no))
		{
    		$this->db->where('book_no', $book_no);
		}
		
        if(!empty($from) && !empty($to))
        {
            $this->db->where('timestamp >=', $from);
            $this->db->where('timestamp <=', $to);
        }	
        $this->db->where('old_tag_no', 1);
		$response['transferred'] = $this->db->get()->num_rows();		
		
		return $response; 
		
		/*## Total number of record with filtering
		$this->db->select('*');
		$this->db->from('cattle_pre_booking');
		if(!empty($doctor_name))
		{
    		$this->db->where('doctor_name', $doctor_name);
		}
		if(!empty($tag_no))
		{
    		$this->db->like('tag_no', $tag_no);
		}
		if(!empty($health_certificate))
		{
    		$this->db->where('certificate_no', $health_certificate);
		}
		if(!empty($receipt_no))
		{
    		$this->db->where('receipt_no', $receipt_no);
		}
		
		if(!empty($cattle_sex) || $$cattle_sex != 0)
		{
    		$this->db->where('cattle_sex', $cattle_sex);
		}
		
		if(!empty($book_no))
		{
    		$this->db->where('book_no', $book_no);
		}
		
        if(!empty($from) && !empty($to))
        {
            $this->db->where('timestamp >=', $from);
            $this->db->where('timestamp <=', $to);
        }		
		$records = $this->db->get()->num_rows();
		$totalRecordwithFilter = $records;*/
	}	
	
	function reg_cattle_pre_booking($post){
	    
	    $tag_no = $post['tag_no'];
	    $certificate_no = $post['certificate_no'];
	    
	    //------------ validation --------------------//
	    $is_tag_no_exist = $this->db->select('id')->where('tag_no', $tag_no)->get('cattle_pre_booking')->num_rows();
	
		if($is_tag_no_exist > 0)
		{
			$response = array(
				'status' => false,
				'notification' => get_phrase($tag_no.' tag_no_already_exist')
			);
			return json_encode($response);
		}
		
	    $is_certificate_no_exist = $this->db->select('id')->where('certificate_no', $certificate_no)->get('cattle_pre_booking')->num_rows();
		
		if($is_certificate_no_exist > 0)
		{
			$response = array(
				'status' => false,
				'notification' => get_phrase($certificate_no.' certificate_no_already_exist')
			);
			return json_encode($response);
		}
	    //------------ validation --------------------//
	    
        /*$qrcode = $certificate_no;
        if(empty($qrcode))
        {
    		$response = array(
    			'status' => false,
    			'notification' => '<b>Pass number field is required!</b>'
    		);  
    		return json_encode($response);
        }*/
        
        $cattle_age = $post['years'].'-'.$post['months'];
		//$app_qrcode['qrcode']     = $qrcode;	
		//$app_qrcode['status']     = 'unblock';
		$app_qrcode['inward_by']  = $this->session->userdata('user_id');
		$app_qrcode['inward_date']= date('Y-m-d H:i:s');
		$app_qrcode['doctor_name']= $post['doctor_name'];
		$app_qrcode['cattle_sex']= $post['cattle_sex'];
		$app_qrcode['cattle_age']= $cattle_age;
		$app_qrcode['certificate_no']= $post['certificate_no'];
		$app_qrcode['book_no']= $post['book_no'];;
		$app_qrcode['tag_no']= $tag_no;
		$app_qrcode['gwala_id']= $post['gwala_id'];
		$app_qrcode['receipt_no']= $post['receipt_no'];
        $app_qrcode['old_tag_no']= $post['old_tag_no'];
        // $app_qrcode['slaughtering_type']= $post['slaughtering_type'];
		$app_qrcode['timestamp']  = date('Y-m-d H:i:s');


		$insert = $this->db->insert('cattle_pre_booking', $app_qrcode);	
		
		if($insert)
		{
			$log = array(
				'name' => 'qrcode_creation',
				'description' => '<b>'.$app_qrcode['qrcode'].'</b> QR Code Added successfully.',
			 );
			 app_log($log);
	 
			 $response = array(
				 'status' => true,
				 'notification' => get_phrase($app_qrcode['qrcode'].'_buffelo_added_successfully')
			 );
		}
		else
		{
			$response = array(
				'status' => false,
				'notification' => get_phrase('something_went_wrong!')
			);
		}

		 return json_encode($response);
	    
	}
	
	    //========= 2024 reg pre booking 2 ============== // 


    function reg_cattle_pre_booking2($post){
        $doctor_name = $post['doctor_name'];
        $agent_name = $post['gwala_id'];
        $cattle_sex = $post['cattle_sex'];
        
        /*$year = $post['years'];
        $months = $post['months'];
        
        $date = [];
        foreach ($year as $index => $row) {
            $date[$index] = $year[$index] . '-' . $months[$index];
        }*/
        
        //-------- certificate --------//
        $certificate_no = $post['certificate_no'];
        
        // Count occurrences
        $counts_cert = array_count_values($certificate_no);
        
        // Filter duplicates
        $duplicates_cert = array_filter($counts_cert, function($count) {
            return $count > 1;
        });
        
        // Extract duplicate values
        $duplicate_values_cert = array_keys($duplicates_cert);
        
        if(count($duplicate_values_cert) > 0){
            $errors = 'Duplicate Certificate Present!';
            foreach($duplicate_values_cert as $error) {
                $errors .= '<p>' . $error . ' Duplicate Certificate No Present!<p>';
            }
            
            $response = array(
                'status' => false,
                'notification' => get_phrase($errors)
            );	
            return json_encode($response);   
        }
        
        $error_cert = '';
        $status_cert = true;
        foreach($certificate_no as $row){
            $is_certificate_no_exist = $this->db->select('id')->where('certificate_no', $row)->get('cattle_pre_booking')->num_rows();
            
            if($is_certificate_no_exist > 0) {
                $error_cert .= '<p>' . $row . ' Duplicate Certificate No Exist in Record!<p>';
                $status_cert = false;
            }
        }
        
        if(!$status_cert) {
            $response = array(
                'status' => false,
                'notification' => get_phrase($error_cert)
            );	
            return json_encode($response);  
        }
        
        //-------- tag_no --------//
        $tag_no = $post['tag_no'];
        
        // Count occurrences
        $counts_tag = array_count_values($tag_no);
        
        // Filter duplicates
        $duplicates_tag = array_filter($counts_tag, function($count) {
            return $count > 1;
        });
        
        // Extract duplicate values
        $duplicate_values_tag = array_keys($duplicates_tag);
        
        if(count($duplicate_values_tag) > 0){
            $errors = 'Duplicate Tag No!';
            foreach($duplicate_values_tag as $error) {
                $errors .= '<p>' . $error . ' Duplicate Tag No Present!<p>';
            }
            
            $response = array(
                'status' => false,
                'notification' => get_phrase($errors)
            );	
            return json_encode($response);   
        }
        
        $error_tag = '';
        $status_tag = true;
        foreach($tag_no as $row){
            $is_tag_no_exist = $this->db->select('id')->where('tag_no', $row)->get('cattle_pre_booking')->num_rows();
            
            if($is_tag_no_exist > 0) {
                $error_tag .= '<p>' . $row . ' Duplicate Tag No Exist in Record!<p>';
                $status_tag = false;
            }
        }
        
        if(!$status_tag) {
            $response = array(
                'status' => false,
                'notification' => get_phrase($error_tag)
            );	
            return json_encode($response);  
        }
        
        // Additional fields
        $receipt_no = $post['receipt_no'];
        $book_no = $post['book_no'];
        $old_tag_no = $post['old_tag_no'];
        
        foreach($book_no as $index => $row) {
            $app_qrcode['inward_by'] = $this->session->userdata('user_id');
            $app_qrcode['inward_date'] = date('Y-m-d H:i:s');
            $app_qrcode['doctor_name'] = $doctor_name;
            $app_qrcode['cattle_sex'] = $cattle_sex;
            //$app_qrcode['cattle_age'] = $date[$index];
            $app_qrcode['certificate_no'] = $certificate_no[$index];
            $app_qrcode['book_no'] = $book_no[$index];
            $app_qrcode['tag_no'] = $tag_no[$index];
            $app_qrcode['gwala_id'] = $agent_name;
            $app_qrcode['receipt_no'] = $receipt_no;
            $app_qrcode['old_tag_no'] = array_key_exists($index, $old_tag_no) ? $old_tag_no[$index] : "null";
            $app_qrcode['timestamp'] = date('Y-m-d H:i:s');
    
            $insert = $this->db->insert('cattle_pre_booking', $app_qrcode);	
            
            if($insert) {
                $log = array(
                    'name' => 'qrcode_creation',
                    'description' => '<b>' . $app_qrcode['qrcode'] . '</b> QR Code Added successfully.',
                );
                app_log($log);
            }
        }
        
        $response = array(
            'status' => true,
            'notification' => get_phrase('buffelo_added_successfully')
        );
    
        return json_encode($response);
    }


	    // ============= end pre book reg 2024 ================ 
	
	public function cattle_pre_booking_delete($param2 = '')
	{
	    
	    $data = $this->db->select('certificate_no')->from('cattle_pre_booking')->where('id', $param2)->get();
	    
		$this->db->where('id', $param2);
	    $delete = $this->db->delete('cattle_pre_booking');
	    
	    
	    if($delete)
		{
			$log = array(
				'name' => 'cattle_pre_booking_qrcode_deleted',
				'description' => '<b>'.$data->certificate_no.'</b> QR Code Deleted successfully.',
			 );
			 app_log($log);
	 
			 $response = array(
				 'status' => true,
				 'notification' => get_phrase($data->certificate_no.' deleted_successfully')
			 );
		}
		else
		{
			$response = array(
				'status' => false,
				'notification' => get_phrase('something_went_wrong!')
			);
		}
    
        
		return json_encode($response);
	}
	
	
	// ----------------------- end new 2024 ----------------------------//
	
	function get_all_prebooking_vyapari($post=null){	
	
		$response = array();
  
		## Read value
		$draw = $post['draw'];
		$start = $post['start'];
		$rowperpage = $post['length']; // Rows display per page
		$columnIndex = $post['order'][0]['column']; // Column index
		$columnName = $post['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $post['order'][0]['dir']; // asc or desc

		$searchValue = trim($post['search']['value']);
		$vyapari_id = trim($post['vyapari_id']);
		$name = trim($post['name']);
		$phone = trim($post['phone']);
		$advanceSearch = trim($post['advanceSearch']);
		$aadhar_no = trim($post['aadhar_no']);
		$from  = $post['from'] ? trim(date('Y-m-d 00:00:00' ,strtotime($post['from']))) : null;
		$to = $post['to'] ? trim(date('Y-m-d 23:59:59' ,strtotime($post['to']))) : null;
		
		## Total number of record with filtering
		$this->db->select('id');
		$this->db->from('prebooking_vyapari');
		/*if(!empty($vyapari_id))
		{
    		$this->db->where('vyapari_id', $vyapari_id);
		}
		if(!empty($name))
		{
    		$this->db->like('name', $name);
		}
		if(!empty($phone))
		{
    		$this->db->where('phone', $phone);
		}
		if(!empty($aadhar_no))
		{
    		$this->db->where('aadhar_no', $aadhar_no);
		}*/
		if(!empty($advanceSearch)){
    		$this->db->group_start();
            $this->db->like('name', $advanceSearch);
            $this->db->or_where('phone', $advanceSearch);
            $this->db->or_where('aadhar_no', $advanceSearch);
            $this->db->or_like('state', $advanceSearch);
            $this->db->or_where('vehicle_no', $advanceSearch);
            $this->db->or_where('route', $advanceSearch);
            $this->db->group_end();
		}
		
        if(!empty($from) && !empty($to))
        {
            $this->db->where('timestamp >=', $from);
            $this->db->where('timestamp <=', $to);
        }
		$records = $this->db->get()->num_rows();
		$totalRecordwithFilter = $records;
  	
		## Fetch records
		$this->db->select('*');
		$this->db->from('prebooking_vyapari');
		/*if(!empty($vyapari_id))
		{
    		$this->db->where('vyapari_id', $vyapari_id);
		}
		if(!empty($name))
		{
    		$this->db->like('name', $name);
		}
		if(!empty($phone))
		{
    		$this->db->where('phone', $phone);
		}
		if(!empty($aadhar_no))
		{
    		$this->db->where('aadhar_no', $aadhar_no);
		}*/
		if(!empty($advanceSearch)){
    		$this->db->group_start();
            $this->db->like('name', $advanceSearch);
            $this->db->or_where('phone', $advanceSearch);
            $this->db->or_where('aadhar_no', $advanceSearch);
            $this->db->or_like('state', $advanceSearch);
            $this->db->or_where('vehicle_no', $advanceSearch);
            $this->db->or_where('route', $advanceSearch);
            $this->db->group_end();
		}
        if(!empty($from) && !empty($to))
        {
            $this->db->where('timestamp >=', $from);
            $this->db->where('timestamp <=', $to);
        }	
		$this->db->order_by($columnName, $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get()->result_array();

		$data = array();
        $sr = $start + 1;
		foreach($records as $record)
		{
			$data[] = array( 
			    "sr_no"  => $sr,
				"id"  => $record['id'],
				"name" => $record['name'],
				"phone" => $record['phone'],
				"aadhar_no" => $record['aadhar_no'],
				"address" => $record['address'],
				"animal_quantity" => $record['animal_quantity'],
				"state" => $record['state'],
				"locality" => $record['locality'],
				"photo" => '<a target="_blank" href="'.base_url($record['photo']).'" >View</a>',
				"expected_date" => $record['expected_date'],
				"vehicle_no" => $record['vehicle_no'],
				"route" => $record['route'],
				"timestamp" => $record['timestamp'],
			);
			
			$sr++;
		}
  
		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecordwithFilter,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);
  
		return $response; 
	}	



	function qrcode_insert()
    {
        $qrcode = trim($this->input->post('qrcode'));
        if(empty($qrcode))
        {
    		$response = array(
    			'status' => false,
    			'notification' => '<b>Pass number field is required!</b>'
    		);  
    		return json_encode($response);
        }
        
		$app_qrcode['vyapari_id'] = $this->input->post('vyapari_id');
		$app_qrcode['pandaal_no'] = $this->input->post('pandaal_no');
		$app_qrcode['qrcode']     = $qrcode;	
		$app_qrcode['status']     = 'unblock';
		$app_qrcode['inward_by']  = $this->session->userdata('user_id');
		$app_qrcode['inward_date']= date('Y-m-d H:i:s');
		$app_qrcode['timestamp']  = date('Y-m-d H:i:s');

		//check QRCODE
		$is_qrcode_exist = $this->db->select('qrcode_id')->where('qrcode', $app_qrcode['qrcode'])->get('app_qrcode')->num_rows();
		
		if($is_qrcode_exist > 0)
		{
			$response = array(
				'status' => false,
				'notification' => get_phrase($app_qrcode['qrcode'].' qrcode_already_exist')
			);
			return json_encode($response);
		}

		$insert = $this->db->insert('app_qrcode', $app_qrcode);	
		
		if($insert)
		{
			$log = array(
				'name' => 'qrcode_creation',
				'description' => '<b>'.vyapari_id($app_qrcode['vyapari_id']).'</b> of <b>'.$app_qrcode['qrcode'].'</b> QR Code Added successfully.',
			 );
			 app_log($log);
	 
			 $response = array(
				 'status' => true,
				 'notification' => get_phrase($app_qrcode['qrcode'].'_qrcode_added_successfully')
			 );
		}
		else
		{
			$response = array(
				'status' => false,
				'notification' => get_phrase('something_went_wrong!')
			);
		}

		 return json_encode($response);
	}
	
	//old
// 	function bulk_insert_qrcode($vyapari_id)
// 	{
	    
// 		$sequence_from = $this->input->post('sequence_from');
// 		$sequence_to   = $this->input->post('sequence_to');
// 		$receipt_no    = $this->input->post('receipt_no');
// 		$pandaal_no    = $this->input->post('pandaal_no');
// 		$broker_id     = $this->input->post('broker_id');
// 		$gwala_id      = $this->input->post('gwala_id');
// 		$series        = 0;//$this->input->post('qr_digit');
		
// 		$x = 0;
// 		$qrcodes = array();
// 		foreach($sequence_from as $seq_from)
// 		{
// 			for ($i = $seq_from; $i <= $sequence_to[$x]; $i++) 
// 			{
// 			    $qrdigit = str_pad($i, $series, '0', STR_PAD_LEFT); 
// 				$qrcodes[] = $qrdigit;
// 			}			    
// 			$x++;
// 		}
		
// 		$qrcodes = array_unique($qrcodes);
		
// 		$is_qrcodes_exist = $this->db->select('qrcode')->where_in('qrcode', $qrcodes)->get('app_qrcode')->result_array();
//         if(count($is_qrcodes_exist) > 0)
//         {
//             $errors = 'Pass entry failed!';
//             foreach($is_qrcodes_exist as $error)
//             {
//                 $errors .= '<p>'.$error['qrcode'].' already exist!<p>';
//             }
            
//     		$response = array(
//     			'status' => false,
//     			'notification' => get_phrase($errors)
//     		);	
//     		return json_encode($response);            
//         }
//         else
//         {
//     		foreach($qrcodes as $qr)
//     		{
//     			$insert['vyapari_id'] = $vyapari_id;
//     			$insert['receipt_no'] = $receipt_no;
//     			$insert['pandaal_no'] = $pandaal_no;
//     			$insert['broker_id']  = $broker_id;
//     			$insert['gwala_id']   = $gwala_id;
//     			$insert['qrcode']     = $qr;
//     			$insert['status']     = 'unblock';
//         		$insert['inward_by']  = $this->session->userdata('user_id');
//         		$insert['inward_date']= date('Y-m-d H:i:s');    			
//     			$insert['timestamp'] = date('Y-m-d H:i:s');
//     			$this->db->insert('app_qrcode', $insert);
    			
//     			$log = array(
//     				'name' => 'qrcode_creation',
//     				'description' => '<b>'.vyapari_id($insert['vyapari_id']).'</b> of <b>'.$insert['qrcode'].'</b> QR Code Added successfully.',
//     			 );
//     			 app_log($log);			
//     		}
    	    
//     		$response = array(
//     			'status' => true,
//     			'notification' => get_phrase('qrcodes_added_successfull!')
//     		);	
//     		return json_encode($response);            
//         }
// 	}

    //new
    /*function bulk_insert_qrcode($vyapari_id)
    {
        
        $sequence_from = $this->input->post('sequence_from');
        $sequence_to   = $this->input->post('sequence_to');
        //$receipt_no    = $this->input->post('receipt_no');
        $pandaal_no    = $this->input->post('pandaal_no');
        $broker_id     = $this->input->post('broker_id');
        //$gwala_id      = $this->input->post('gwala_id');
        $series        = 0;//$this->input->post('qr_digit');
        
        $x = 0;
        $qrcodes = array();
        foreach($sequence_from as $seq_from)
        {
            for ($i = $seq_from; $i <= $sequence_to[$x]; $i++) 
            {
                $qrdigit = str_pad($i, $series, '0', STR_PAD_LEFT); 
                $qrcodes[] = $qrdigit;
            }               
            $x++;
        }
        
        $qrcodes = array_unique($qrcodes);
        
        $is_qrcodes_exist = $this->db->select('qrcode')->where_in('qrcode', $qrcodes)->get('app_qrcode')->row_array();
        if(count($is_qrcodes_exist) > 0)
        {
            $errors = 'Pass entry failed!';
            foreach($is_qrcodes_exist as $error)
            {
                $errors .= '<p>'.$error['qrcode'].' already exist!<p>';
            }
            
            $response = array(
                'status' => false,
                'notification' => get_phrase($errors)
            );  
            return json_encode($response);            
        }
        else
        {
            foreach($qrcodes as $qr)
            {
                $prebooking = $this->db->where('qrcode', $qr)->get('cattle_pre_booking')->result_array();
                $insert['vyapari_id'] = $vyapari_id;
                $insert['receipt_no'] = $prebooking['receipt_no'];
                $insert['pandaal_no'] = $pandaal_no;
                $insert['broker_id']  = $broker_id;
                $insert['gwala_id']   = $prebooking['gwala_id'];
                $insert['qrcode']     = $prebooking['qrcode'];
                $insert['status']     = $prebooking['status'];
                $insert['inward_by']  = $prebooking['inward_by'];
                $insert['inward_date']= date('Y-m-d H:i:s');                
                $insert['timestamp']  = date('Y-m-d H:i:s');
                $this->db->insert('app_qrcode', $insert);
                
                $log = array(
                    'name' => 'qrcode_creation',
                    'description' => '<b>'.vyapari_id($insert['vyapari_id']).'</b> of <b>'.$insert['qrcode'].'</b> QR Code Added successfully.',
                 );
                 app_log($log);         
            }
            
            $response = array(
                'status' => true,
                'notification' => get_phrase('qrcodes_added_successfull!')
            );  
            return json_encode($response);            
        }
    }*/
    
    function bulk_insert_qrcode($vyapari_id)
    {
        $sequence_from = $this->input->post('sequence_from');
        $sequence_to   = $this->input->post('sequence_to');
        //$receipt_no    = $this->input->post('receipt_no');
        $pandaal_no    = $this->input->post('pandaal_no');
        $broker_id     = $this->input->post('broker_id');
        
        $slaughtering_type = $this->input->post('slaughtering_type');
        
        //$gwala_id    = $this->input->post('gwala_id');
        $series        = 0; //$this->input->post('qr_digit');
        
        $x = 0;
        $qrcodes = array();
        foreach($sequence_from as $seq_from)
        {
            for ($i = $seq_from; $i <= $sequence_to[$x]; $i++) 
            {
                $qrdigit = str_pad($i, $series, '0', STR_PAD_LEFT); 
                $qrcodes[] = $qrdigit;
            }			    
            $x++;
        }
        
        $qrcodes = array_unique($qrcodes);
        
        //check if qr already exist in app_qrcode
        $is_qrcodes_exist = $this->db->select('qrcode')->where_in('qrcode', $qrcodes)->get('app_qrcode')->result_array();
        if(count($is_qrcodes_exist) > 0)
        {
            $errors = 'Pass entry failed!';
            foreach($is_qrcodes_exist as $error)
            {
                $errors .= '<p>'.$error['qrcode'].' already exist!<p>';
            }
            
            $response = array(
                'status' => false,
                'notification' => get_phrase($errors)
            );	
            return json_encode($response);            
        }
        
        //check if all entered qr are available in cattle_pre_booking
        $is_qrcodes_available = $this->db->select('certificate_no')->where_in('certificate_no', $qrcodes)->get('cattle_pre_booking')->result_array();
        $certificate_nos = array_map(function($item) {
            return $item['certificate_no'];
        }, $is_qrcodes_available);        
        $errors2 = null;
        foreach($qrcodes as $qr) {
            if(!in_array($qr, $certificate_nos)){
                $errors2 .= '<p>'.$qr.' is not registered in prebooking!<p>';
            }
        }
        if($errors2) {
            $response = array(
                'status' => false,
                'notification' => get_phrase($errors2)
            );	
            return json_encode($response);             
        }
        
        //insert
        foreach($qrcodes as $qr)
        {
            $prebookingData = $this->db->where('certificate_no', $qr)->get('cattle_pre_booking')->row_array();
            $insert['vyapari_id'] = $vyapari_id;
            $insert['receipt_no'] = $prebookingData['receipt_no'];
            $insert['pandaal_no'] = $pandaal_no;
            $insert['broker_id']  = $broker_id;
            $insert['gwala_id']   = $prebookingData['gwala_id'];
            $insert['qrcode']     = $qr; //$prebookingData['tag_no'];
            $insert['status']     = 'unblock';
            $insert['inward_by']  = $this->session->userdata('user_id');
            $insert['inward_date']= date('Y-m-d H:i:s');
            $insert['slaughtering_type']= $slaughtering_type;
            $insert['timestamp'] = date('Y-m-d H:i:s');
            $this->db->insert('app_qrcode', $insert);
            
            //mark mapped
            $map['is_mapped'] = 1;
            $this->db->where('id', $prebookingData['id']);
            $this->db->update('cattle_pre_booking' , $map);
            
            $log = array(
                'name' => 'qrcode_creation',
                'description' => '<b>'.vyapari_id($insert['vyapari_id']).'</b> of <b>'.$insert['qrcode'].'</b> QR Code Added successfully.',
                );
                app_log($log);			
        }
        
        $response = array(
            'status' => true,
            'notification' => get_phrase('qrcodes_added_successfull!')
        );	
        return json_encode($response);            
    }    
	
	function qrcode_complaint($qrcode_id)
    {
        //qrcode table
		$app_qrcode['status'] = $this->input->post('status');
        $this->db->where('qrcode_id', $qrcode_id);
		$update = $this->db->update('app_qrcode', $app_qrcode);	
		
		//complaint table
		$complaint['user_id'] = $this->session->userdata('user_id');
		$complaint['status'] = $this->input->post('status');
		$complaint['qrcode_id'] = $qrcode_id;
		$complaint['notes'] = $this->input->post('notes');
		$complaint['timestamp'] = date('Y-m-d H:i:s');
		$insert = $this->db->insert('app_qrcode_complaints', $complaint);		
		
		if($insert)
		{
			$log = array(
				'name' => 'qrcode_'.$app_qrcode['status'].'_complaints',
				'description' => '<b>'.$app_qrcode['status'].'ing</b> of <b>'.$this->input->post('qrcode').'</b> QR Code successfull.',
			 );
			 app_log($log);
	 
			 $response = array(
				 'status' => true,
				 'notification' => get_phrase('qrcode_'.$app_qrcode['status'].'_successfully')
			 );
		}
		else
		{
			$response = array(
				'status' => false,
				'notification' => get_phrase('something_went_wrong!')
			);
		}

		 return json_encode($response);
	}
	
	function bulk_complaint_qrcode($vyapari_id)
	{
	    $status = $this->input->post('bulk_status');
	    $notes  = $this->input->post('notes');
	    $qrcode_ids = explode(',', $this->input->post('bulk_qrcode_ids'));
	    
        //qrcode table
		$app_qrcode['status'] = $status;
        $this->db->where_in('qrcode_id', $qrcode_ids);
        $this->db->where('status !=', 'exit');
        $this->db->where('vyapari_id', $vyapari_id);
		$update = $this->db->update('app_qrcode', $app_qrcode);	    
	    
	    //complaint table
	    foreach($qrcode_ids as $qrcode_id)
	    {
	        $complaint['user_id'] = $this->session->userdata('user_id');
    		$complaint['status'] = $status;
    		$complaint['qrcode_id'] = $qrcode_id;
    		$complaint['notes'] = $notes;
    		$complaint['timestamp'] = date('Y-m-d H:i:s');
    		$insert = $this->db->insert('app_qrcode_complaints', $complaint);	        
	    }
	    
		$log = array(
			'name' => 'qrcode_'.$app_qrcode['status'].'_complaints',
			'description' => '<b>'.$app_qrcode['status'].'ing</b> (('.implode(',', $qrcode_ids).')) of QR Code successfull.',
		 );
		 app_log($log);
 
		 $response = array(
			 'status' => true,
			 'notification' => get_phrase('all_qrcode_'.$app_qrcode['status'].'_successfully')
		 );	 
		 
		 return json_encode($response);
	}
	
	function mark_complete_qrcode()
	{
	    $qrcode = intval(trim($this->input->post('qrcode')));
	    
        if(empty($qrcode))
        {
    		$response = array(
    			'status' => false,
    			'notification' => '<b>Pass number field is required!</b>'
    		);  
    		return json_encode($response);
        }

	    $this->db->select('status,exit_gate');
	    $this->db->from('app_qrcode');
	    $this->db->where('qrcode', $qrcode);
	    $result = $this->db->get()->row_array();
	    
	    $status    = $result['status'];
	    $exit_gate = $result['exit_gate'];
	    
	    if($status == 'block')
	    {
    		$response = array(
    			'status' => false,
    			'notification' => 'Pass number <span class="alert-heading">'.$qrcode.'</span> is blocked!'
    		);	        
	    }
	    elseif($status == 'unblock')
	    {
	        $update['status'] = 'exit';
	        $update['exit_by'] = $this->session->userdata('user_id');
	        $update['exit_gate'] = $this->session->userdata('gate_no');
	        $update['exit_date'] = date('Y-m-d H:i:s');
	        $this->db->where('qrcode', $qrcode);
	        $res = $this->db->update('app_qrcode', $update);
	        
	        if($res)
	        {
        		$response = array(
        			'status' => true,
        			'notification' => 'Pass Number <span class="alert-heading">'.$qrcode.'</span> exited successfully from <span class="alert-heading">GATE NO.'.$update['exit_gate'].'</span>'
        		);	
        		
        		$log = array(
        			'name' => 'qrcode_exit',
        			'description' => '<b>'.$update['status'].'ing</b> of QR Code successfull.',
        		 );
        		 app_log($log);        		
	        }
	        else
	        {
        		$response = array(
        			'status' => false,
        			'notification' => get_phrase('process_failed!_please_try_again.')
        		);	            
	        }
	    }
	    elseif($status == 'exit')
	    {
    		$response = array(
    			'status' => false,
    			'notification' => 'Pass Number <span class="alert-heading">'.$qrcode.'</span> is already exit from <span class="alert-heading">GATE NO.'.$exit_gate.'</span>'
    		);	        
	    }
	    else
	    {
    		$response = array(
    			'status' => false,
    			'notification' => 'Pass Number <span class="alert-heading">'.$qrcode.'</span> not found on system!'
    		);	        
	    }
		return json_encode($response);
	}
	
	
	function blocked_ajaxlist($post=null){	

		$response = array();
  
		## Read value
		$draw = $post['draw'];
		$start = $post['start'];
		$rowperpage = $post['length']; // Rows display per page
		$columnIndex = $post['order'][0]['column']; // Column index
		$columnName = $post['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $post['order'][0]['dir']; // asc or desc

		$searchValue = trim($post['search']['value']);
		$qrcode = trim($post['qrcode']);
		$from  = $post['from'] ? trim(date('Y-m-d 00:00:00' ,strtotime($post['from']))) : null;
		$to = $post['to'] ? trim(date('Y-m-d 23:59:59' ,strtotime($post['to']))) : null;
		
		## Total number of record with filtering
		$this->db->select('AQ.qrcode');
		$this->db->from('app_qrcode as AQ');
		$this->db->join('app_vyapari as AV', 'AV.vyapari_id=AQ.vyapari_id', 'left');
		$this->db->join('app_qrcode_complaints as AQC', 'AQC.qrcode_id=AQ.qrcode_id', 'left');
		$this->db->join('users as U', 'U.id=AQC.user_id', 'left');
		if(!empty($qrcode))
		{
    		$this->db->where('AQ.qrcode', $qrcode);
		}
        // if(!empty($from) && !empty($to))
        // {
        //     $this->db->where('AQC.timestamp >=', $from);
        //     $this->db->where('AQC.timestamp <=', $to);
        // }

		if (!empty($from)) {
			$this->db->where('AQC.timestamp >=', $from);
		}

		if (!empty($to)) {
			$this->db->where('AQC.timestamp <=', $to);
		}

        $this->db->where('AQC.status', 'block');
        $this->db->where('AQ.status', 'block');
		$records = $this->db->get()->num_rows();
		$totalRecordwithFilter = $records;
  	
		## Fetch records
		$this->db->select('AQ.*,AV.name as vyapari_name,AV.phone as vyapari_phone,AQC.notes as all_notes,U.name as admin_name,AQC.timestamp as block_date');
		$this->db->from('app_qrcode as AQ');
		$this->db->join('app_vyapari as AV', 'AV.vyapari_id=AQ.vyapari_id', 'left');
		$this->db->join('app_qrcode_complaints as AQC', 'AQC.qrcode_id=AQ.qrcode_id', 'left');
		$this->db->join('users as U', 'U.id=AQC.user_id', 'left');
		if(!empty($qrcode))
		{
    		$this->db->where('AQ.qrcode', $qrcode);
		}
        // if(!empty($from) && !empty($to))
        // {
        //     $this->db->where('AQC.timestamp >=', $from);
        //     $this->db->where('AQC.timestamp <=', $to);
        // }
		
		if (!empty($from)) {
			$this->db->where('AQC.timestamp >=', $from);
		}

		if (!empty($to)) {
			$this->db->where('AQC.timestamp <=', $to);
		}
		
        $this->db->where('AQC.status', 'block');
        $this->db->where('AQ.status', 'block');
		$this->db->order_by('AQ.qrcode_id', $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get()->result_array();

		$data = array();
        $sr = $start + 1;
		foreach($records as $record)
		{ 
		    $btn_view = '<a href="'.route('manage_vyapari/view/').$record['vyapari_id'].'">Details</a>';
			$data[] = array( 
			    "sr_no"  => $sr,
				"qrcode"  => $record['qrcode'],
				"notes" => $record['all_notes'],
				"blocked_by" => $record['admin_name'],
				"blocked_date" => date('d MY H:i:s', strtotime($record['block_date'])),
				"vyapari_name" => $record['vyapari_name'],
				"phone" => $record['vyapari_phone'],
				"options" => $btn_view,
			);
			
			$sr++;
		}
  
		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecordwithFilter,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);
  
		return $response; 
	}
	
	
	function inwardpasses_ajaxlist($post=null){	

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

		$response = array();
  
		## Read value
		$draw = $post['draw'];
		$start = $post['start'];
		$rowperpage = $post['length']; // Rows display per page
		$columnIndex = $post['order'][0]['column']; // Column index
		$columnName = $post['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $post['order'][0]['dir']; // asc or desc

		$searchValue = trim($post['search']['value']);
		$qrcode = trim($post['qrcode']);
		$from  = $post['from'] ? trim(date('Y-m-d 00:00:00' ,strtotime($post['from']))) : null;
		$to = $post['to'] ? trim(date('Y-m-d 23:59:59' ,strtotime($post['to']))) : null;
		
		## Total number of record with filtering
		$this->db->select('AQ.qrcode');
		$this->db->from('app_qrcode as AQ');
		$this->db->join('app_vyapari as AV', 'AV.vyapari_id=AQ.vyapari_id', 'left');
		$this->db->join('users as U', 'U.id=AQ.inward_by', 'left');
		if(!empty($qrcode))
		{
    		$this->db->where('AQ.qrcode', $qrcode);
		}
        // if(!empty($from) && !empty($to))
        // {
        //     $this->db->where('AQ.inward_date >=', $from);
        //     $this->db->where('AQ.inward_date <=', $to);
        // }	
		
		if (!empty($from)) {
			$this->db->where('AQ.inward_date >=', $from);
		}

		if (!empty($to)) {
			$this->db->where('AQ.inward_date <=', $to);
		}
		
        //$this->db->where('AQ.status', 'unblock');

		$records = $this->db->get()->num_rows();
		$totalRecordwithFilter = $records;
  	
		## Fetch records
		$this->db->select('AQ.*,AV.name as vyapari_name,AV.phone as vyapari_phone,U.name as admin_name');
		$this->db->from('app_qrcode as AQ');
		$this->db->join('app_vyapari as AV', 'AV.vyapari_id=AQ.vyapari_id', 'left');
		$this->db->join('users as U', 'U.id=AQ.inward_by', 'left');
		if(!empty($qrcode))
		{
    		$this->db->where('AQ.qrcode', $qrcode);
		}
        // if(!empty($from) && !empty($to))
        // {
        //     $this->db->where('AQ.inward_date >=', $from);
        //     $this->db->where('AQ.inward_date <=', $to);
        // }
		
		if (!empty($from)) {
			$this->db->where('AQ.inward_date >=', $from);
		}

		if (!empty($to)) {
			$this->db->where('AQ.inward_date <=', $to);
		}
		
        //$this->db->where('AQ.status', 'unblock');
		$this->db->order_by('AQ.qrcode_id', $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get()->result_array();

		$data = array();
        $sr = $start + 1;
		foreach($records as $record)
		{ 
		    $btn_view = '<a href="'.route('manage_vyapari/view/').$record['vyapari_id'].'">Details</a>';
			$data[] = array( 
			    "sr_no"  => $sr,
				"qrcode"  => $record['qrcode'],
				"inward_by" => $record['admin_name'],
				"inward_date" => date('d MY H:i:s', strtotime($record['inward_date'])),
				"vyapari_name" => $record['vyapari_name'],
				"phone" => $record['vyapari_phone'],
				"options" => $btn_view,
			);
			
			$sr++;
		}
  
		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecordwithFilter,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);
  
		return $response; 
	}
	
	
	//============ 2024 new ============================//
	
		function dawanwala_ajaxlist($post=null){	

		$response = array();
  
		## Read value
		$draw = $post['draw'];
		$start = $post['start'];
		$rowperpage = $post['length']; // Rows display per page
		$columnIndex = $post['order'][0]['column']; // Column index
		$columnName = $post['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $post['order'][0]['dir']; // asc or desc

		$searchValue = trim($post['search']['value']);
		$agent = trim($post['agent']);
		$from  = $post['from'] ? trim(date('Y-m-d 00:00:00' ,strtotime($post['from']))) : null;
		$to = $post['to'] ? trim(date('Y-m-d 23:59:59' ,strtotime($post['to']))) : null;
		
		## Total number of record with filtering
		$this->db->select('AQ.qrcode');
		$this->db->from('app_qrcode as AQ');
		$this->db->join('app_vyapari as AV', 'AV.vyapari_id=AQ.vyapari_id', 'left');
		$this->db->join('users as U', 'U.id=AQ.inward_by', 'left');
		if(!empty($agent))
		{
    		$this->db->where('AQ.gwala_id', $agent);
		}
        // if(!empty($from) && !empty($to))
        // {
        //     $this->db->where('AQ.inward_date >=', $from);
        //     $this->db->where('AQ.inward_date <=', $to);
        // }
		
		if (!empty($from)) {
			$this->db->where('AQ.inward_date >=', $from);
		}

		if (!empty($to)) {
			$this->db->where('AQ.inward_date <=', $to);
		}
		
        //$this->db->where('AQ.status', 'unblock');

		$records = $this->db->get()->num_rows();
		$totalRecordwithFilter = $records;
  	
		## Fetch records
		$this->db->select('AQ.*,AV.name as vyapari_name,AV.phone as vyapari_phone,U.name as admin_name');
		$this->db->from('app_qrcode as AQ');
		$this->db->join('app_vyapari as AV', 'AV.vyapari_id=AQ.vyapari_id', 'left');
		$this->db->join('users as U', 'U.id=AQ.inward_by', 'left');
		if(!empty($agent))
		{
    		$this->db->where('AQ.gwala_id', $agent);
		}
        // if(!empty($from) && !empty($to))
        // {
        //     $this->db->where('AQ.inward_date >=', $from);
        //     $this->db->where('AQ.inward_date <=', $to);
        // }
		
		if (!empty($from)) {
			$this->db->where('AQ.inward_date >=', $from);
		}

		if (!empty($to)) {
			$this->db->where('AQ.inward_date <=', $to);
		}
		
        //$this->db->where('AQ.status', 'unblock');
		$this->db->order_by('AQ.qrcode_id', $columnSortOrder);
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get()->result_array();

		$data = array();
        $sr = $start + 1;
		foreach($records as $record)
		{ 
		    $agent_name = $this->db->select(['id', 'applicant_name'])->where('id', $record['gwala_id'])->from('app_gwala')->get()->row();
		    
		    $btn_view = '<a href="'.route('manage_vyapari/view/').$record['vyapari_id'].'">Details</a>';
			$data[] = array( 
			    "sr_no"  => $sr,
			    "agent" => $agent_name->applicant_name,
				"qrcode"  => $record['qrcode'],
				"inward_by" => $record['admin_name'],
				"inward_date" => date('d MY H:i:s', strtotime($record['inward_date'])),
				"vyapari_name" => $record['vyapari_name'],
				"phone" => $record['vyapari_phone'],
				"options" => $btn_view,
			);
			
			$sr++;
		}
  
		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecordwithFilter,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);
  
		return $response; 
	}
	
	//============== 2024 new ============================//
	
	
	
	function outwardpasses_ajaxlist($post=null){	

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

		$response = array();
  
		## Read value
		$draw = $post['draw'];
		$start = $post['start'];
		$rowperpage = $post['length']; // Rows display per page
		$columnIndex = $post['order'][0]['column']; // Column index
		$columnName = $post['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $post['order'][0]['dir']; // asc or desc

		$searchValue = trim($post['search']['value']);
		$qrcode = trim($post['qrcode']);
		$from  = $post['from'] ? trim(date('Y-m-d 00:00:00' ,strtotime($post['from']))) : null;
		$to = $post['to'] ? trim(date('Y-m-d 23:59:59' ,strtotime($post['to']))) : null;
		
		## Total number of record with filtering
		$this->db->select('AQ.qrcode');
		$this->db->from('app_qrcode as AQ');
		$this->db->join('app_vyapari as AV', 'AV.vyapari_id=AQ.vyapari_id', 'left');
		$this->db->join('users as U', 'U.id=AQ.exit_by', 'left');
		if(!empty($qrcode))
		{
    		$this->db->where('AQ.qrcode', $qrcode);
		}
        // if(!empty($from) && !empty($to))
        // {
        //     $this->db->where('AQ.exit_date >=', $from);
        //     $this->db->where('AQ.exit_date <=', $to);
        // }
		
		if (!empty($from)) {
			$this->db->where('AQ.exit_date >=', $from);
		}

		if (!empty($to)) {
			$this->db->where('AQ.exit_date <=', $to);
		}
		
        $this->db->where('AQ.status', 'exit');

		$records = $this->db->get()->num_rows();
		$totalRecordwithFilter = $records;
  	
		## Fetch records
		$this->db->select('AQ.*,AV.name as vyapari_name,AV.phone as vyapari_phone,U.name as admin_name');
		$this->db->from('app_qrcode as AQ');
		$this->db->join('app_vyapari as AV', 'AV.vyapari_id=AQ.vyapari_id', 'left');
		$this->db->join('users as U', 'U.id=AQ.exit_by', 'left');
		if(!empty($qrcode))
		{
    		$this->db->where('AQ.qrcode', $qrcode);
		}
        // if(!empty($from) && !empty($to))
        // {
        //     $this->db->where('AQ.exit_date >=', $from);
        //     $this->db->where('AQ.exit_date <=', $to);
        // }
		
		if (!empty($from)) {
			$this->db->where('AQ.exit_date >=', $from);
		}

		if (!empty($to)) {
			$this->db->where('AQ.exit_date <=', $to);
		}
		
        $this->db->where('AQ.status', 'exit');
		$this->db->order_by('AQ.exit_date', 'desc');
		$this->db->limit($rowperpage, $start);
		$records = $this->db->get()->result_array();

		$data = array();
        $sr = $start + 1;
		foreach($records as $record)
		{ 
		    $btn_view = '<a href="'.route('manage_vyapari/view/').$record['vyapari_id'].'">Details</a>';
			$data[] = array( 
			    "sr_no"  => $sr,
				"qrcode"  => $record['qrcode'],
				"outward_by" => $record['admin_name'],
				"outward_date" => date('d MY H:i:s', strtotime($record['exit_date'])),
				"outward_gate" => $record['exit_gate'],
				"vyapari_name" => $record['vyapari_name'],
				"phone" => $record['vyapari_phone'],
				"options" => $btn_view,
			);
			
			$sr++;
		}
  
		## Response
		$response = array(
			"draw" => intval($draw),
			"iTotalRecords" => $totalRecordwithFilter,
			"iTotalDisplayRecords" => $totalRecordwithFilter,
			"aaData" => $data
		);
  
		return $response; 
	}
	
	function admin_status($user_id, $status)
	{
        $update['user_status'] = $status;

        $this->db->where('id', $user_id);
        $res = $this->db->update('users', $update);
        
        if($res)
        {
    		$response = array(
    			'status' => true,
    			'notification' => get_phrase($status.'_successfully.')
    		);	
    		
    		$log = array(
    			'name' => 'manage_users',
    			'description' => '(('.$user_id.')) '.$status.' successfull.',
    		 );
    		 app_log($log);        		
        }
        return json_encode($response);
	}
	
	function reallocate_qrcode()
	{
        $vyapari_id = $this->input->post('vyapari_id_for_reallocation');
	    $qrcode_ids = explode(',', $this->input->post('bulk_qrcode_ids_for_reallocation'));
	    
        //qrcode table
		$app_qrcode['vyapari_id'] = $vyapari_id;
        $this->db->where_in('qrcode_id', $qrcode_ids);
        $this->db->where('status !=', 'exit');
		$update = $this->db->update('app_qrcode', $app_qrcode);	    
	    
		$log = array(
			'name' => 'qrcode_reallocation',
			'description' => '<b>Reallocating</b> qrcode_ids (('.implode(',', $qrcode_ids).')) to vyapari_id (('.$vyapari_id.')) of QR Code successfull.',
		 );
		 app_log($log);
 
		 $response = array(
			 'status' => true,
			 'notification' => get_phrase('reallocation_successfully')
		 );	 
		 
		 return json_encode($response);
	}
	
	
	function vyapari_update($vyapari_id)
    {
        $update['name'] = trim($this->input->post('name'));
		$update['aadhar_no'] = trim($this->input->post('aadhar_no'));
		$update['state'] = trim($this->input->post('state'));
		$update['locality'] = trim($this->input->post('locality'));
		$update['address'] = trim($this->input->post('address'));
		$update['phone'] = trim($this->input->post('phone'));
		
		$this->db->where('vyapari_id', $vyapari_id);
		$updated = $this->db->update('app_vyapari', $update);	
		
		if($updated)
		{
			$log = array(
				'name' => 'vyapari_update',
				'description' => '<b>'.vyapari_id($vyapari_id).'</b> updated successfully.',
			 );
			 app_log($log);

			old_vyapari_check($this->input->post());
	 
			 $response = array(
				 'status' => true,
				 'notification' => get_phrase('vyapari_edited_successfully')
			 );
		}
		else
		{
			$response = array(
				'status' => false,
				'notification' => get_phrase('something_went_wrong!')
			);
		}

		 return json_encode($response);
	}
	
	
	
    public function uploadPrebookingCSV() {
        // Load the file helper
        $this->load->helper('file');
    
        // Check if a file has been uploaded
        if (isset($_FILES['csv_file']) && is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
            // Get the uploaded file
            $file = $_FILES['csv_file']['tmp_name'];
    
            // Open the file for reading
            $handle = fopen($file, 'r');
    
            // Check if the file is opened successfully
            if ($handle !== false) {
                // Initialize an empty array to store CSV data
                $csvData = [];
    
                // Read each line of the file until end of file
                while (($data = fgetcsv($handle)) !== false) {
                    // Push each row of CSV data into the array
                    $csvData[] = $data;
                }
    
                // Close the file
                fclose($handle);
    
                // Validate the CSV header
                if ($csvData[0][0] != 'doctor_name' || $csvData[0][1] != 'receipt_no' || $csvData[0][2] != 'certificate_no' || 
                    $csvData[0][3] != 'book_no' || $csvData[0][4] != 'tag_no' || $csvData[0][5] != 'inward_date' || 
                    $csvData[0][6] != 'agent' || $csvData[0][7] != 'cattle_sex') {
                    $response = array(
                        'status' => false,
                        'notification' => get_phrase('csv_is_incorrect!')
                    );
                    echo json_encode($response);
                    return;
                }
                
                // Validate the number of rows in the CSV file
                /*$response = array(
                    'status' => false,
                    'notification' => get_phrase('Under maintainanace')
                );
                echo json_encode($response);
                return false;*/
               
                
                // Validate the number of rows in the CSV file
                if (count($csvData) > 500) { // Including the header, so limit is 500 rows
                    $response = array(
                        'status' => false,
                        'notification' => get_phrase('The uploaded CSV file exceeds the maximum allowed rows of 500.')
                    );
                    echo json_encode($response);
                    return false;
                }   
    
                $data = [];
                $fieldTxt  = '';
                $insertTxt = '';
                $updateTxt = '';
                foreach ($csvData as $row) {
                    if ($row === $csvData[0]) continue; // Skip the header row
                    
                        $doctor = !empty($row[0]) ? trim($row[0]) : null;
                        if ($doctor) {
                            $doctorQuery = $this->db->where('role_type', 'doctor')->where('name', $doctor)->get('users')->row();
                            $doctor = $doctorQuery ? $doctorQuery->id : null;
                        }
                        
                        $receipt = !empty($row[1]) ? trim($row[1]) : null;
                        $certificate = !empty($row[2]) ? trim($row[2]) : null;
                        $book = !empty($row[3]) ? trim($row[3]) : null;
                        $tag = !empty($row[4]) ? trim($row[4]) : 'unavailable';
                        $inwardDate = !empty($row[5]) ? date('Y-m-d', strtotime(trim($row[5]))) . ' ' . date('H:i:s') : null;
                        
                        $agent = !empty($row[6]) ? trim($row[6]) : null;
                        if ($agent) {
                            $agentQuery = $this->db->where('applicant_name', $agent)->get('app_gwala')->row();
                            $agent = $agentQuery ? $agentQuery->id : null;
                        }
                        
                        //$sex = !empty($row[7]) ? (strtolower(trim($row[7])) == 'mb' ? 1 : 2) : null; //old

						//new
						$sex_input = strtolower(trim($row[7]));
						$sex = in_array($sex_input, ['mb', 'sb']) ? ($sex_input === 'mb' ? 1 : 2) : null;

                        //$transfer = !empty($row[8]) ? (strtolower(trim($row[8])) == 'yes' ? 1 : 0) : null; //old

						//new
						$transfer_value = strtolower(trim($row[8]));
						$transfer = $transfer_value == 'yes' ? 1 : ($transfer_value == 'no' ? 0 : null);						
                    
                    if ($doctor === null || $receipt === null || $certificate === null || $book === null || $tag === null || $inwardDate === null || $agent === null || $sex === null || $transfer === null) {
                        //$fieldTxt .= 'tag no: '. $tag. ' - incorrect data filled in csv row!' . "\n"; //old
                        $fieldTxt .= 'Certificate No: '. $certificate. ' - incorrect data filled in csv row!' . "\n"; //new
                        $fieldTxt .= 'Doctor:'.$doctor.', Receipt No:'.$receipt.', Certificate No:'.$certificate.', Book No:'.$book.', Tag No:'.$tag.', Inward Date'.$inwardDate.', Agent:'.$agent.', Sex:'.$sex.', Transfer:'.$transfer. "\n\n";
                    }else{
                        //$record = $this->db->where('tag_no', $tag)->get('cattle_pre_booking')->row(); //old
                        $record = $this->db->where('certificate_no', $certificate)->get('cattle_pre_booking')->row(); //new
                        if(!empty($record)){
                            //$fieldTxt .= 'tag no: '.$tag. ' - already inserted!' . "\n"; //old
                            $fieldTxt .= 'Certificate No: '.$certificate. ' - already inserted!' . "\n"; //new 
                        }else{
                            //insert here
                            $isCertExist = $this->db->where('certificate_no', $certificate)->get('cattle_pre_booking')->num_rows(); 
                            if($isCertExist == 0){
                                $insert['inward_by']      = $this->session->userdata('user_id');
                                $insert['inward_date']    = $inwardDate;
                                $insert['doctor_name']    = $doctor;
                                $insert['cattle_sex']     = $sex;
                                $insert['certificate_no'] = $certificate;
                                $insert['book_no']        = $book;
                                $insert['tag_no']         = $tag;
                                $insert['gwala_id']       = $agent;
                                $insert['receipt_no']     = $receipt;
                                $insert['old_tag_no']     = $transfer;
                                $insert['timestamp']      = date('Y-m-d H:i:s');
                                $this->db->insert('cattle_pre_booking', $insert);
                                //$insertTxt .= 'tag no: '.$tag. ' - inserted successfully!' . "\n"; //old                                
                                $insertTxt .= 'Certificate No: '.$certificate. ' - inserted successfully!' . "\n"; //new                                 
                            }else{
                                //$fieldTxt .= 'tag no: '. $tag. ' - insertion failed - duplicate certificate number: '.$certificate.'!' . "\n";
                                $fieldTxt .= 'Certificate No: '. $certificate. ' - insertion failed - duplicate certificate number: '.$certificate.'!' . "\n";
                                $fieldTxt .= 'Doctor:'.$doctor.', Receipt No:'.$receipt.', Certificate No:'.$certificate.', Book No:'.$book.', Tag No:'.$tag.', Inward Date'.$inwardDate.', Agent:'.$agent.', Sex:'.$sex.', Transfer:'.$transfer. "\n\n";
                            }
                        }                        
                    }
                }
                
                //make a txt file
                $allTxt = $fieldTxt . $updateTxt . $insertTxt;
                $fileName = date('Y-m-d@H.i.s') . '@' .trim($csvData[1][4]). ".txt";
                $filePath = "./uploads/prebooking-csv-response/" . $fileName;
                $urlPath = base_url("/uploads/prebooking-csv-response").'/'.$fileName;
                file_put_contents($filePath, $allTxt);//               
    
                // Return success response
                $response = array(
                    'status' => true,
                    'notification' => get_phrase('CSV file processed successfully!'),
                    'downloadurl' => $urlPath,
                    'downloadtxtname' => $fileName
                );
                echo json_encode($response);
                return;
            } else {
                // Error handling if file cannot be opened
                $response = array(
                    'status' => false,
                    'notification' => get_phrase('Unable to open CSV file!')
                );
                echo json_encode($response);
                return;
            }
        } else {
            // Error handling if no file is uploaded
            $response = array(
                'status' => false,
                'notification' => get_phrase('No file uploaded!')
            );
            echo json_encode($response);
            return;
        }
    }	
	
	

}
