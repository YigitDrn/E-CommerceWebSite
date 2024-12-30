<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Ana Sayfa</a></li>
            <li class="breadcrumb-item active">Arama Sonuçları</li>
        </ol>
    </nav>

    <h2>Arama Sonuçları: "<?php echo htmlspecialchars($search_query); ?>"</h2>

    <?php if (empty($products)): ?>
        <div class="alert alert-info mt-4">
            <i class="fas fa-info-circle"></i> Aramanızla eşleşen ürün bulunamadı.
            <hr>
            <p class="mb-0">Öneriler:</p>
            <ul class="mb-0">
                <li>Farklı arama terimleri deneyin</li>
                <li>Daha genel terimler kullanın</li>
                <li>Yazım hatası olmadığından emin olun</li>
            </ul>
        </div>
    <?php else: ?>
        <p class="text-muted"><?php echo count($products); ?> ürün bulundu.</p>
        
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <a href="<?php echo base_url('shop/product/' . $product['_id']); ?>" class="text-decoration-none">
                            <img src="<?php echo base_url('assets/images/' . $product['image']); ?>" 
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
                                        onclick="addToCart(<?php echo htmlspecialchars(json_encode([
                                            'id' => $product['_id'],
                                            'name' => $product['name'],
                                            'price' => $product['price'],
                                            'image' => $product['image']
                                        ])); ?>)">
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
</div> 