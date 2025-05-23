<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

function app_log($data)
{
    $CI =& get_instance();

    $insert['user_id']     = $CI->session->userdata('user_id');
    $insert['name']        = $data['name'];
    $insert['description'] = $data['description'];
    $insert['timestamp']   = date('Y-m-d H:i:s');

    $CI->db->insert('app_log', $insert);
}

function get_all_states()
{
    $CI =& get_instance();
    $states = $CI->db->get('state')->result_array();
    return $states;
}

function get_locality_by_state($state_id)
{
    $CI =& get_instance();
    $localities = $CI->db->where('state_id', $state_id)->get('locality')->result_array();
    return $localities;
}

function vyapari_id($id)
{
   return 'V2024-'.$id;
}

function compressImage($source, $destination, $quality) 
{
	// Get image info
	$imgInfo = getimagesize($source);
	$mime = $imgInfo['mime'];
	
	// Create a new image from file
	switch($mime){
		case 'image/jpeg':
			$image = imagecreatefromjpeg($source);
			break;
		case 'image/png':
			$image = imagecreatefrompng($source);
			break;
		case 'image/gif':
			$image = imagecreatefromgif($source);
			break;
		default:
			$image = imagecreatefromjpeg($source);
	}
	// Save image
    imagejpeg($image, $destination, $quality);
}


function access($action)
{
    $CI = & get_instance();
    $role = $CI->session->userdata('role_type');
    
    if($role == 'superadmin')
    {
        return true;
    }
    else
    {
        if($action == 'registration_button') //registration of vyapari
        {
            if($role == 'inward')
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        elseif($action == 'printid_button') //print id of vyapari
        {
            if($role == 'inward')
            {
                return true;
            }
            else
            {
                return false;
            }        
        }
        elseif($action == 'allocate_pass_button') //print id of vyapari
        {
            if($role == 'inward')
            {
                return true;
            }
            else
            {
                return false;
            }        
        }
        elseif($action == 'allocate_pandol') //print id of vyapari
        {
            if($role == 'inward')
            {
                return true;
            }
            else
            {
                return false;
            }        
        }
        elseif($action == 'manage_pass_button') //block /unblock pass of vyapari
        {
            if($role == 'inward')
            {
                return true;
            }
            else
            {
                return false;
            }         
        }
        elseif($action == 'manage_user_button') //user activate
        {
            if($role == 'admin1' || $role == 'gate_manager')
            {
                return true;
            }
            else
            {
                return false;
            }         
        }
        elseif($action == 'gate_edit_button') //user activate
        {
            if($role == 'gate_manager')
            {
                return false;
            }
            else
            {
                return false;
            }         
        }        
        elseif($action == 'manage_vyapari') //vyapari page
        {
            if($role == 'admin' || $role == 'inward' || $role == 'outward' || $role == 'registration' || $role == 'bmc')
            {
                return true;
            }
            else
            {
                return false;
            }         
        } 
        elseif($action == 'exit_verification') //exit page
        {
            if($role == 'outward')
            {
                return true;
            }
            else
            {
                return false;
            }         
        }  
        elseif($action == 'manage_admins') //manage admin page
        {
            if($role == 'admin' || $role == 'bmc' || $role == 'gate_manager')
            {
                return true;
            }
            else
            {
                return false;
            }         
        } 
        elseif($action == 'reports') //report page
        {
            if($role == 'doctor' || $role == 'bmc' || $role == 'admin' || $role == 'outward' || $role == 'inward') //$role == 'admin'
            {
                return true;
            }
            else
            {
                return false;
            }         
        } 
        elseif($action == 'pass_inward_report')
        {
            if($role == 'bmc' || $role == 'admin' || $role == 'outward' || $role == 'inward')
            {
                return true;
            }
            else
            {
                return false;
            }         
        }   
        elseif($action == 'pass_outward_report')
        {
            if($role == 'bmc' || $role == 'admin' || $role == 'outward' || $role == 'inward')
            {
                return true;
            }
            else
            {
                return false;
            }         
        } 
        elseif($action == 'pass_block_report')
        {
            if($role == 'bmc' || $role == 'admin' || $role == 'outward' || $role == 'inward')
            {
                return true;
            }
            else
            {
                return false;
            }         
        }  
        elseif($action == 'pandol_info_report')
        {
            if($role == 'doctor' || $role == 'bmc' || $role == 'admin' || $role == 'inward')
            {
                return true;
            }
            else
            {
                return false;
            }         
        }
        elseif($action == 'cattle_prebooking')
        {
            if($role == 'pre_booking')
            {
                return true;
            }elseif($role == 'doctor'){
                return true;
            }
            else
            {
                return false;
            } 
        }
        elseif($action == 'cattle_prebooking_delete')
        {
            if($role == 'superadmin')
            {
                return true;
            }
            else
            {
                return false;
            } 
        }
    }
}

function old_vyapari_check($param){
    $CI      = & get_instance();
	$vyapari = $CI->input->post();
    
    $old_vyapari['name']      = $vyapari["name"];
	$old_vyapari['phone']     = $vyapari["phone"];
	$old_vyapari['aadhar_no'] = $vyapari["aadhar_no"];
	$old_vyapari['address']   = $vyapari["address"];
	$old_vyapari['state']     = $vyapari["state"];
	$old_vyapari['locality']  = $vyapari["locality"];
	
	$vapari_data = $CI->db->where('aadhar_no',$old_vyapari['aadhar_no'])->get('vyapari_detail')->row();
	
	if (empty($vapari_data)) {
       $CI->db->insert('vyapari_detail', $old_vyapari);
    } else {
       $CI->db->where('aadhar_no', $old_vyapari['aadhar_no'])->update('vyapari_detail', $old_vyapari);
    }
}

	function get_type_by_id($table, $sid, $id, $field){
		$CI = & get_instance();
		$CI->db->select($field);
		$CI->db->where($sid, $id);
		$res = $CI->db->get($table)->row();
		if($res)
			return $res->$field;
	}
	
	function get_cattle_count($slaughterType, $sex, $date = ""){
		$CI = & get_instance();
		$CI->benchmark->mark('query_start');

		$CI->db->select('COUNT(AQ.qrcode) AS total', false);
		$CI->db->from('app_qrcode AS AQ');
		$CI->db->join('cattle_pre_booking AS CPB', 'CPB.tag_no = AQ.qrcode', 'inner');

		if ($slaughterType) {
			$CI->db->where('AQ.slaughtering_type', $slaughterType);
		}

		if ($sex) {
			$CI->db->where('CPB.cattle_sex', $sex);
		}

		if ($date) {
			$CI->db->where('AQ.timestamp >=', date('Y-m-d 00:00:00', strtotime($date)));
			$CI->db->where('AQ.timestamp <=', date('Y-m-d 23:59:59', strtotime($date)));
		}

		$result = $CI->db->get()->row_array();
		return $result['total'];        
	}
