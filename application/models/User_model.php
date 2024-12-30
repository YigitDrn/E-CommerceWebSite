<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function register($data) {
        return $this->db->insert('users', $data);
    }
    
    public function login($email, $password) {
        $user = $this->db->get_where('users', ['email' => $email])->row();
        
        if ($user && password_verify($password, $user->password)) {
            unset($user->password); // Güvenlik için şifreyi session'da tutmuyoruz
            return $user;
        }
        
        return false;
    }
    
    public function get_user($id) {
        $user = $this->db->get_where('users', ['id' => $id])->row();
        if ($user) {
            unset($user->password);
        }
        return $user;
    }
    
    public function update_profile($id, $data, $current_password) {
        // Mevcut şifreyi kontrol et
        $user = $this->db->get_where('users', ['id' => $id])->row();
        
        if (!$user || !password_verify($current_password, $user->password)) {
            return false;
        }
        
        return $this->db->where('id', $id)->update('users', $data);
    }
    
    public function email_exists($email) {
        return $this->db->where('email', $email)->count_all_results('users') > 0;
    }
    
    public function update_password($email, $password) {
        $data = ['password' => password_hash($password, PASSWORD_DEFAULT)];
        return $this->db->where('email', $email)->update('users', $data);
    }
} 