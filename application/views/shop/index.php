<section class="hero-banner">
    <div class="banner-content">
        <h2>Yeni Sezon İndirimleri</h2>
        <p>Tüm ürünlerde %50'ye varan indirimler</p>
        <a href="#products" class="btn btn-primary">Alışverişe Başla</a>
    </div>
</section>

<section id="products" class="popular-products container mt-5">
    <h2>Popüler Ürünler</h2>
    
    <?php if (empty($products)): ?>
        <div class="alert alert-info">
            Henüz ürün bulunmuyor.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <a href="<?php echo base_url('shop/product/' . $product['_id']); ?>" class="text-decoration-none">
                            <img src="<?php echo $product['image']; ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo $product['name']; ?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="<?php echo base_url('shop/product/' . $product['_id']); ?>" class="text-decoration-none text-dark">
                                    <?php echo $product['name']; ?>
                                </a>
                            </h5>
                            <p class="card-text">
                                <?php echo substr($product['description'], 0, 100); ?>...
                            </p>
                            <p class="card-text">
                                <strong class="text-primary"><?php echo number_format($product['price'], 2); ?> TL</strong>
                            </p>
                            <?php if ($product['stock'] > 0): ?>
                                <button class="btn btn-primary add-to-cart"
                                        onclick="addToCart({
                                            id: '<?php echo $product['_id']; ?>',
                                            name: '<?php echo addslashes($product['name']); ?>',
                                            price: <?php echo $product['price']; ?>,
                                            quantity: 1
                                        })"
                                        data-product-id="<?php echo $product['_id']; ?>">
                                    <i class="fas fa-cart-plus"></i> Sepete Ekle
                                </button>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>
                                    Stokta Yok
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="categories container mt-5 mb-5">
    <h2>Kategoriler</h2>
    <div class="row">
        <div class="col-md-4 mb-4">
            <a href="<?php echo base_url('shop/category/elektronik'); ?>" class="text-decoration-none">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-laptop fa-3x mb-3 text-primary"></i>
                        <h3 class="card-title">Elektronik</h3>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <a href="<?php echo base_url('shop/category/giyim'); ?>" class="text-decoration-none">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-tshirt fa-3x mb-3 text-primary"></i>
                        <h3 class="card-title">Giyim</h3>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <a href="<?php echo base_url('shop/category/ev'); ?>" class="text-decoration-none">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-home fa-3x mb-3 text-primary"></i>
                        <h3 class="card-title">Ev & Yaşam</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section> 