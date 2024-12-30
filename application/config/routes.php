<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'shop';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Shop routes
$route['products'] = 'shop/products';
$route['categories'] = 'shop/categories';
$route['cart'] = 'shop/cart';
$route['contact'] = 'shop/contact'; 