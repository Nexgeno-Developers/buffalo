<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

require FCPATH . 'vendor/autoload.php';

class Qrcodegenerator extends CI_Controller {
	protected $theme;
	protected $active_school_id;

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
	public function generate_qrcode($qr_digit, $book_no) 
	{
        $options = new QROptions(
          [
            'eccLevel' => QRCode::ECC_L,
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,//OUTPUT_IMAGE_JPG
            'version' => 3,
          ]
        );
        $qrcode = (new QRCode($options))->render($qr_digit);
        
        //$this->save_qrcode($qrcode, $qr_digit); //save
        
        echo '<img src="'.$qrcode.'" alt="QR Code" width="150px" height="150px"><p style="margin: 0;position: absolute;left: 23px;font-size: 16px;margin-top: -13px;"><b>BN'.$book_no.'-'.$qr_digit.'</b></p><br>';
	}
	
	/*function save_qrcode($qrcode, $qr_digit)
	{
        $data = $qrcode;
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        $path = 'uploads/generated-qrcodes/'.$qr_digit.'.jpg';
        file_put_contents($path, $data);
        $this->add_text_to_qrcode($path); //add text
	}*/
	
	/*function add_text_to_qrcode($path)
	{
        header('Content-type: image/jpeg');
        
        $image = imagecreatefromjpeg($path);
        
        $textcolor = imagecolorallocate($image, 255, 255, 255);
        
        $font_file = 'myfont.ttf';
        
        $custom_text = "Watermark Text";
        
        imagettftext($image, 225, 0, 3450, 3000, $textcolor, $font_file, $custom_text);
        
        imagejpeg($image);
        
        imagedestroy($image); // for clearing memory 
	}*/ 
	
	function create_bulk_qrimage()
	{
	    
	   // echo 'Not Allowed';
	   // exit();
	    
	    $start   = trim(preg_replace('/\s+/', '', $this->input->get('start')));
	    $end     = trim(preg_replace('/\s+/', '', $this->input->get('end')));
	    $book_no = trim(preg_replace('/\s+/', '', $this->input->get('bookno')));
	    $qr_digit= $this->input->get('qr_digit') ? trim(preg_replace('/\s+/', '', $this->input->get('qr_digit'))) : 0;
	    
        if (strpos(strval($start), '0') === 0 || strpos(strval($end), '0') === 0) {
            echo "Number should not starts with zero";exit;
        }	    

	    if(!empty($start) && !empty($end) && !empty($book_no))
	    {
            for ($x = $start; $x <= $end; $x++)
            {
                $qrdigit = str_pad($x, $qr_digit, '0', STR_PAD_LEFT); 
                $this->generate_qrcode($qrdigit, $book_no);
            }	        
	    }
	    else
	    {
	        echo 'pllease fill all data';
	    }
	}
	
	
	/* ----------- tag id print 2024 --------------------------- */
	
	
	public function generate_tag_qrcode($qr_digit, $certificate) 
	{
        $options = new QROptions(
          [
            'eccLevel' => QRCode::ECC_L,
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,//OUTPUT_IMAGE_JPG
            'version' => 3,
          ]
        );
        $qrcode = (new QRCode($options))->render($qr_digit);
        
        
        echo '<img src="'.$qrcode.'" alt="QR Code" width="150px" height="150px"><p style="margin: 0;position: absolute;left: 23px;font-size: 12px;margin-top: -13px;"><b>Tag:'.$qr_digit.'</b></p><br><p style="margin-left: 16px;font-size: 12px; margin-top: 2px;"><b>Cert:'.$certificate.'</b></p></div>';
	}
	
	
	function create_bulk_tag_qr()
	{
	    
	   $vyapari_id = $this->input->get('vyapari_id');
       $receipt_no = $this->input->get('receipt_no');
 
	    
	   //$data = $this->db->select('qrcode')->from('app_qrcode')->where('vyapari_id', $vyapari_id)->where('receipt_no', $receipt_no)->order_by('qrcode', 'asc')->get()->result();
	   $data = $this->db->select(['app_qrcode.qrcode', 'cattle_pre_booking.certificate_no'])
                 ->from('app_qrcode')
                 ->join('cattle_pre_booking', 'app_qrcode.qrcode = cattle_pre_booking.tag_no')
                 ->where('app_qrcode.vyapari_id', $vyapari_id)
                 ->where('app_qrcode.receipt_no', $receipt_no)
                 ->order_by('cattle_pre_booking.certificate_no', 'asc')
                 ->get()
                 ->result();
	   
	    
	    if(count($data) > 0 || !empty($data) || $data != null){
	        
	        $error_msg = '';
	        
	        foreach($data as $row){
	            
	            //$tag_no = $this->db->select(['certificate_no','tag_no'])->from('cattle_pre_booking')->where('tag_no', $row->qrcode)->where('receipt_no', $receipt_no)->get()->row();
	            
	            if($row->qrcode || !empty($row->qrcode)){
	                
	                $qrdigit = $row->qrcode;
	                $certificate = $row->certificate_no;
	                
	                $this->generate_tag_qrcode($qrdigit, $certificate);
	                
	            } else {

	              $error_msg .= '<br><br><p style="margin: 0; position: absolute; left: 23px; font-size: 16px;"><b>Tag No of this <br>Qrcode No: '.$row->qrcode.'<br>Not Present</b></p><br><br>';
	                
	            }
	            
	        }
	        
	        echo $error_msg;
	        
	    } else {
	        echo "DATA Not Present";
	    }
	    
	    
	}
	
	/* ----------- tag id print 2024 ---------------------------- */
	
	/* ----------- professional courier ------------------ */
	
	
	
	public function professional_courier_generate_qrcode($qr_digit, $book_no) 
	{
        $options = new QROptions(
          [
            'eccLevel' => QRCode::ECC_L,
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,//OUTPUT_IMAGE_JPG
            'version' => 3,
          ]
        );
        $qrcode = (new QRCode($options))->render($qr_digit);
        
        //$this->save_qrcode($qrcode, $qr_digit); //save
        
        echo '<p style="margin-left:60px; margin-top:200px; position: relative; font-size: 16px;"><b>'.$qr_digit.' - BN:'.$book_no.'</b></p><img style="margin-left:60px; " src="'.$qrcode.'" alt="QR Code" width="50px" height="50px"><br>';
	}
	
	
	
	
	
	function professional_courier_qrimage()
	{
	    $start   = trim(preg_replace('/\s+/', '', $this->input->get('start')));
	    $end     = trim(preg_replace('/\s+/', '', $this->input->get('end')));
	    $book_no = trim(preg_replace('/\s+/', '', $this->input->get('bookno')));

	    if(!empty($start) && !empty($end) && !empty($book_no))
	    {
            for ($x = $start; $x <= $end; $x++)
            {
                $qrdigit = str_pad($x, 7, '0', STR_PAD_LEFT); 
                $this->professional_courier_generate_qrcode($qrdigit, $book_no);
            }	        
	    }
	    else
	    {
	        echo 'pllease fill all data';
	    }

	}
	
	
	
}