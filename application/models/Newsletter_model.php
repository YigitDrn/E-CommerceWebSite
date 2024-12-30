<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletter_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function subscribe($email) {
        // E-posta adresi zaten var mı kontrol et
        $query = $this->db->get_where('newsletter', ['email' => $email]);
        
        if ($query->num_rows() > 0) {
            return false;
        }
        
        // Yeni kayıt ekle
        $data = [
            'email' => $email,
            'created_at' => date('Y-m-d H:i:s'),
            'status' => 1
        ];
        
        return $this->db->insert('newsletter', $data);
    }
    
    public function unsubscribe($email) {
        return $this->db->where('email', $email)
                       ->update('newsletter', ['status' => 0]);
    }
    
    public function get_all_subscribers($status = 1) {
        return $this->db->where('status', $status)
                       ->get('newsletter')
                       ->result();
    }
} 