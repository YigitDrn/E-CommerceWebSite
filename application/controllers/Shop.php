<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {

    private $api_url = 'http://localhost:3001/api';

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
    }

    private function call_api($endpoint, $method = 'GET', $data = null) {
        $ch = curl_init($this->api_url . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method === 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        // Token varsa ekle
        if ($this->session->userdata('token')) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->session->userdata('token')
            ]);
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 404) {
            return null;
        }
        
        return json_decode($response, true);
    }

    public function index() {
        $response = $this->call_api('/products');
        
        // Debug için log
        error_log('API Response: ' . print_r($response, true));
        
        $data['title'] = 'E-Ticaret Mağazası';
        $data['products'] = $response['data'] ?? [];
        
        // Debug için log
        error_log('Products: ' . print_r($data['products'], true));
        
        $this->load->view('templates/header', $data);
        $this->load->view('shop/index', $data);
        $this->load->view('templates/footer');
    }

    public function product($id) {
        $response = $this->call_api('/products/' . $id);
        
        if (!$response || !$response['success']) {
            show_404();
        }
        
        $data['title'] = $response['data']['name'];
        $data['product'] = $response['data'];
        
        $this->load->view('templates/header', $data);
        $this->load->view('shop/product', $data);
        $this->load->view('templates/footer');
    }

    public function category($category) {
        $response = $this->call_api('/products/category/' . urlencode($category));
        
        $data['title'] = ucfirst($category);
        $data['products'] = $response['data'] ?? [];
        $data['category'] = $category;
        
        $this->load->view('templates/header', $data);
        $this->load->view('shop/category', $data);
        $this->load->view('templates/footer');
    }

    public function search() {
        $query = $this->input->get('q');
        
        if (empty($query)) {
            redirect('/');
        }
        
        $response = $this->call_api('/products/search/' . urlencode($query));
        
        $data['title'] = 'Arama Sonuçları: ' . $query;
        $data['products'] = $response['data'] ?? [];
        $data['search_query'] = $query;
        
        $this->load->view('templates/header', $data);
        $this->load->view('shop/search', $data);
        $this->load->view('templates/footer');
    }

    public function cart() {
        if (!$this->session->userdata('user')) {
            redirect('user/login');
        }

        $response = $this->call_api('/cart');
        
        $data['title'] = 'Sepetim';
        $data['cart'] = $response['data'] ?? null;
        
        $this->load->view('templates/header', $data);
        $this->load->view('shop/cart', $data);
        $this->load->view('templates/footer');
    }

    public function add_to_cart() {
        if (!$this->session->userdata('user')) {
            echo json_encode(['success' => false, 'message' => 'Lütfen önce giriş yapın']);
            return;
        }

        if ($this->input->is_ajax_request()) {
            $product_id = $this->input->post('id');
            $quantity = $this->input->post('quantity', true);

            $response = $this->call_api('/cart/add', 'POST', [
                'productId' => $product_id,
                'quantity' => $quantity
            ]);

            echo json_encode($response);
        }
    }

    public function remove_from_cart() {
        if ($this->input->is_ajax_request()) {
            $product_id = $this->input->post('id');
            
            $response = $this->call_api('/cart/remove/' . $product_id, 'DELETE');
            
            echo json_encode($response);
        }
    }

    public function update_cart() {
        if ($this->input->is_ajax_request()) {
            $product_id = $this->input->post('id');
            $quantity = $this->input->post('quantity');
            
            $response = $this->call_api('/cart/update/' . $product_id, 'PUT', [
                'quantity' => $quantity
            ]);
            
            echo json_encode($response);
        }
    }

    public function newsletter_subscribe() {
        if ($this->input->is_ajax_request()) {
            $email = $this->input->post('email');
            
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'E-posta', 'required|valid_email');
            
            if ($this->form_validation->run() === FALSE) {
                echo json_encode(['success' => false, 'message' => 'Geçerli bir e-posta adresi giriniz.']);
                return;
            }
            
            $this->load->model('newsletter_model');
            if ($this->newsletter_model->subscribe($email)) {
                echo json_encode(['success' => true, 'message' => 'Bültenimize başarıyla abone oldunuz.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Bu e-posta adresi zaten kayıtlı.']);
            }
        }
    }

    public function get_cart_count() {
        if (!$this->session->userdata('token')) {
            echo '0';
            return;
        }

        // API'den sepet bilgisini al
        $ch = curl_init('http://localhost:3001/api/cart');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->session->userdata('token')
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $result = json_decode($response, true);
        
        if ($result && $result['success'] && isset($result['data']['items'])) {
            echo count($result['data']['items']);
        } else {
            echo '0';
        }
    }
} 