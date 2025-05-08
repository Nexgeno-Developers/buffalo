<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database('deonarmc_cattledb'); // Load the correct database
    }

    public function get_entries()
    {
        $query = $this->db->get('app_gwala');
        return $query->result();
    }

    public function get_entry($id)
    {
        $query = $this->db->get_where('app_gwala', array('id' => $id));
        return $query->row();
    }

    public function insert_entry($data)
    {
        return $this->db->insert('app_gwala', $data);
    }

    public function update_entry($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('app_gwala', $data);
    }

    public function delete_entry($id)
    {
        return $this->db->delete('app_gwala', array('id' => $id));
    }

}
