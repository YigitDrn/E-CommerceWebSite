<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->model('user_model');
    }

    public function register() {
        // Kullanıcı zaten giriş yapmışsa ana sayfaya yönlendir
        if ($this->session->userdata('user')) {
            redirect('/');
        }

        $data['title'] = 'Kayıt Ol';

        if ($this->input->method() === 'post') {
            // Form doğrulama kuralları
            $this->form_validation->set_rules('name', 'Ad Soyad', 'required|trim');
            $this->form_validation->set_rules('email', 'E-posta', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Şifre', 'required|min_length[6]');
            $this->form_validation->set_rules('password_confirm', 'Şifre Tekrar', 'required|matches[password]');

            if ($this->form_validation->run() === TRUE) {
                // API'ye kayıt isteği gönder
                $ch = curl_init('http://localhost:3001/api/users/register');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'password' => $this->input->post('password')
                ]));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                
                $response = curl_exec($ch);
                curl_close($ch);
                
                $result = json_decode($response, true);

                if ($result && $result['success']) {
                    $this->session->set_flashdata('success', 'Kayıt işlemi başarılı! Şimdi giriş yapabilirsiniz.');
                    redirect('user/login');
                } else {
                    $this->session->set_flashdata('error', $result['message'] ?? 'Kayıt işlemi sırasında bir hata oluştu.');
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('user/register');
        $this->load->view('templates/footer');
    }

    public function login() {
        // Kullanıcı zaten giriş yapmışsa ana sayfaya yönlendir
        if ($this->session->userdata('user')) {
            redirect('/');
        }

        $data['title'] = 'Giriş Yap';

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('email', 'E-posta', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Şifre', 'required');

            if ($this->form_validation->run() === TRUE) {
                $email = $this->input->post('email');
                $password = $this->input->post('password');

                // API'ye login isteği gönder
                $ch = curl_init('http://localhost:3001/api/users/login');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                    'email' => $email,
                    'password' => $password
                ]));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                
                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                // Debug için API yanıtını ve HTTP kodunu logla
                error_log('API Response: ' . $response);
                error_log('HTTP Code: ' . $http_code);
                
                // cURL hata kontrolü
                if ($response === false) {
                    $this->session->set_flashdata('error', 'API bağlantı hatası');
                    redirect('user/login');
                    return;
                }

                // HTTP yanıt kodu kontrolü
                if ($http_code !== 200) {
                    $this->session->set_flashdata('error', 'Giriş başarısız: Geçersiz e-posta veya şifre');
                    redirect('user/login');
                    return;
                }
                
                $result = json_decode($response, true);

                // Debug için API yanıt yapısını kontrol et
                error_log('Decoded Response: ' . print_r($result, true));

                // JSON decode hatası kontrolü
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->session->set_flashdata('error', 'API yanıt format hatası');
                    redirect('user/login');
                    return;
                }

                if ($result && isset($result['success']) && $result['success']) {
                    // Kullanıcı bilgilerini ve token'ı session'a kaydet
                    $this->session->set_userdata([
                        'user' => $result['data']['user'],
                        'token' => $result['data']['token']
                    ]);

                    // JavaScript için token'ı localStorage'a kaydet
                    echo '<script>
                        localStorage.setItem("token", "' . $result['data']['token'] . '");
                        window.location.href = "' . base_url() . '";
                    </script>';
                    return;
                } else {
                    $this->session->set_flashdata('error', $result['message'] ?? 'Giriş başarısız!');
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('user/login');
        $this->load->view('templates/footer');
    }

    public function logout() {
        // Session'dan kullanıcı bilgilerini temizle
        $this->session->unset_userdata(['user', 'token']);
        
        // JavaScript ile localStorage'dan token'ı temizle ve login sayfasına yönlendir
        echo '<script>
            localStorage.removeItem("token");
            window.location.href = "' . base_url('user/login') . '";
        </script>';
    }

    public function profile() {
        // Giriş yapmamış kullanıcıyı login sayfasına yönlendir
        if (!$this->session->userdata('user')) {
            redirect('user/login');
        }

        $data['title'] = 'Profilim';
        $data['user'] = $this->session->userdata('user');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('name', 'Ad Soyad', 'required|trim');
            $this->form_validation->set_rules('current_password', 'Mevcut Şifre', 'required');
            
            if ($this->input->post('new_password')) {
                $this->form_validation->set_rules('new_password', 'Yeni Şifre', 'required|min_length[6]');
                $this->form_validation->set_rules('new_password_confirm', 'Yeni Şifre Tekrar', 'required|matches[new_password]');
            }

            if ($this->form_validation->run() === TRUE) {
                // API'ye profil güncelleme isteği gönder
                $ch = curl_init('http://localhost:3001/api/users/update');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                    'name' => $this->input->post('name'),
                    'currentPassword' => $this->input->post('current_password'),
                    'newPassword' => $this->input->post('new_password')
                ]));
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->session->userdata('token')
                ]);
                
                $response = curl_exec($ch);
                curl_close($ch);
                
                $result = json_decode($response, true);

                if ($result && $result['success']) {
                    // Session'daki kullanıcı bilgilerini güncelle
                    $user = $this->session->userdata('user');
                    $user['name'] = $this->input->post('name');
                    $this->session->set_userdata('user', $user);
                    
                    $this->session->set_flashdata('success', 'Profil bilgileriniz güncellendi.');
                    redirect('user/profile');
                } else {
                    $this->session->set_flashdata('error', $result['message'] ?? 'Profil güncellenirken bir hata oluştu.');
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('user/profile');
        $this->load->view('templates/footer');
    }
} 