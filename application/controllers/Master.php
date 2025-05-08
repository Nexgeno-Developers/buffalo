<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master extends CI_Controller {

    function __construct()
    {
        parent::__construct();
    $this->load->database();
    $this->load->library('session');

    /*LOADING ALL THE MODELS HERE*/
    $this->load->model('Crud_model',     'crud_model');
    $this->load->model('User_model',     'user_model');
    $this->load->model('Settings_model', 'settings_model');
    $this->load->model('Payment_model',  'payment_model');
    $this->load->model('Email_model',    'email_model');
    $this->load->model('Addon_model',    'addon_model');
    $this->load->model('Frontend_model', 'frontend_model');
    $this->load->model('Deonar_model', 'deonar_model');

    /*cache control*/
    $this->output->set_header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
    $this->output->set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    $this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
    $this->output->set_header("Pragma: no-cache");

    /*SET DEFAULT TIMEZONE*/
    timezone();

    /*LOAD EXTERNAL LIBRARIES*/
    $this->load->library('pdf');

    if($this->session->userdata('superadmin_login') != 1){
      redirect(site_url('login'), 'refresh');
    }
        $this->load->model('Master_model', 'master_model');

    }

    public function index()
    {
        $page_data['entries'] = $this->master_model->get_entries();
        //$this->load->view('/backend/superadmin/master/master_view', $data);
        
            $page_data['folder_name']  = 'master';
            $page_data['page_name']  = 'master_view';
            $page_data['page_title'] = 'Manage Master Users';
            $this->load->view('backend/index', $page_data);        
        
    }

    public function create()
    {
        // Retrieve form input data
        $data['applicant_name'] = $this->input->post('applicant_name');
        $data['certificate_no'] = $this->input->post('certificate_no');

    
        // Check if the file upload is attempted
        if ($_FILES['certificate_pdf']['name'] != "") {
            // File upload is attempted
            $pdfname2 = time();
            $randomString = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            
            // Create the PDF name
            $pdfname = $randomString;
            $fileName = str_replace(' ', '_', $_FILES['certificate_pdf']['name']);
            $data['certificate_pdf'] = $pdfname . '_' . $pdfname2.'_'. $fileName;
    
            // Define upload path
            $path = 'uploads/master_pdf/' . $data['certificate_pdf'];
    
            // Check for file upload errors
            if ($_FILES['certificate_pdf']['error'] !== UPLOAD_ERR_OK) {
                // File upload failed
                //echo 'File upload failed with error code: ' . $_FILES['certificate_pdf']['error'];
            } else {
                // Attempt to move uploaded file to the upload path
                if (move_uploaded_file($_FILES['certificate_pdf']['tmp_name'], $path)) {
                    // File moved successfully
                    //echo 'File uploaded successfully';
                } else {
                    // File move failed
                    //echo 'File move failed';
                }
            }
        } else {
            // No file uploaded
            //echo 'No file uploaded';
        }
        
        // Attempt to insert data into the database
        if ($this->db->insert('app_gwala', $data)) {
            // Data inserted successfully
    			 $response = array(
    				 'status' => true,
    				 'notification' => get_phrase('Agent_added_successfully')
    			 );
        } else {
            // Insertion failed
    			 $response = array(
    				 'status' => false,
    				 'notification' => get_phrase('Agent_Not_added_successfully')
    			 );
        }
        echo json_encode($response);	
    }

    public function edit($id)
    {
        $this->load->model('master_model');
        $data = $this->master_model->get_entry($id);
        $this->load->view('/backend/superadmin/master/master_edit_form', array('data' => $data));
    }


    
    public function update()
{
    
    // Retrieve form input data
    $id = $this->input->post('id'); // Corrected
    $old_data = $this->db->get_where('app_gwala', ['id' => $id])->row();
    $data['applicant_name'] = $this->input->post('applicant_name');
    $data['certificate_no'] = $this->input->post('certificate_no');

    // Check if the file upload is attempted
    if ($_FILES['certificate_pdf']['name'] != "") {
        $pdfname2 = time();
        $randomString = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
        
        // Create the PDF name
        $pdfname = $randomString;
        $fileName = str_replace(' ', '_', $_FILES['certificate_pdf']['name']);
        $data['certificate_pdf'] = $pdfname . '_' . $pdfname2.'_'. $fileName;
        
        
        $path = 'uploads/master_pdf/' . $data['certificate_pdf'];
        
        
        if (move_uploaded_file($_FILES['certificate_pdf']['tmp_name'], $path)) {
            // File moved successfully
            //echo 'File uploaded successfully';
        } else {
            // File move failed
            //echo 'File move failed';
        }

    } else {
        $data['certificate_pdf'] = $old_data->certificate_pdf;
        // No file uploaded
        //echo 'No file uploaded';
    }

    // Attempt to update data in the database
   $sucess = $this->db->where('id', $id)->update('app_gwala', $data);
    if ($sucess) {
        // Data updated successfully
        $response = array(
            'status' => true,
            'notification' => get_phrase('Agent_updated_successfully')
        );
    } else {
        // Update failed
        $response = array(
            'status' => false,
            'notification' => get_phrase('Agent_not_updated_successfully')
        );
    }
    echo json_encode($response);    
}



    public function delete($id)
    {
        $this->master_model->delete_entry($id);
        $response = array(
         'status' => true,
         'notification' => get_phrase('Agent_Delete_successfully')
        );
        echo json_encode($response);
    }

}
