<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - AlışverişDünyası</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
    
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="<?php echo base_url(); ?>">
                    <h1 class="mb-0">AlışverişDünyası</h1>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo base_url(); ?>">Ana Sayfa</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                Kategoriler
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo base_url('shop/category/elektronik'); ?>">Elektronik</a></li>
                                <li><a class="dropdown-item" href="<?php echo base_url('shop/category/giyim'); ?>">Giyim</a></li>
                                <li><a class="dropdown-item" href="<?php echo base_url('shop/category/ev'); ?>">Ev & Yaşam</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo base_url('shop/contact'); ?>">İletişim</a>
                        </li>
                    </ul>
                    
                    <div class="d-flex align-items-center">
                        <div class="nav-icons me-3">
                            <a href="#" class="icon-btn" data-bs-toggle="modal" data-bs-target="#searchModal">
                                <i class="fas fa-search"></i>
                            </a>
                            <a href="<?php echo base_url('shop/cart'); ?>" class="icon-btn cart-btn">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="cart-count">0</span>
                            </a>
                        </div>
                        
                        <?php if ($this->session->userdata('user')): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user"></i> <?php echo $this->session->userdata('user')['name']; ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="<?php echo base_url('user/profile'); ?>">Profilim</a></li>
                                    <li><a class="dropdown-item" href="<?php echo base_url('shop/orders'); ?>">Siparişlerim</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo base_url('user/logout'); ?>">Çıkış Yap</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('user/login'); ?>">
                                    <i class="fas fa-sign-in-alt"></i> Giriş Yap
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url('user/register'); ?>">
                                    <i class="fas fa-user-plus"></i> Kayıt Ol
                                </a>
                            </li>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Arama Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ürün Ara</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo base_url('shop/search'); ?>" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="Ürün adı veya kategori...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Ara
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <main> 