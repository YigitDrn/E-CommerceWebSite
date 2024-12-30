<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Ana Sayfa</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo ucfirst($category); ?></li>
        </ol>
    </nav>

    <h2><?php echo ucfirst($category); ?> Ürünleri</h2>

    <?php if (empty($products)): ?>
        <div class="alert alert-info">
            Bu kategoride henüz ürün bulunmuyor.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo base_url('assets/images/' . $product['image']); ?>" 
                             class="card-img-top" 
                             alt="<?php echo $product['name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['name']; ?></h5>
                            <p class="card-text">
                                <?php echo substr($product['description'], 0, 100); ?>...
                            </p>
                            <p class="card-text">
                                <strong class="text-primary"><?php echo number_format($product['price'], 2); ?> TL</strong>
                            </p>
                            <button class="btn btn-primary add-to-cart"
                                    onclick="addToCart(<?php echo htmlspecialchars(json_encode([
                                        'id' => $product['_id'],
                                        'name' => $product['name'],
                                        'price' => $product['price'],
                                        'image' => $product['image']
                                    ])); ?>)">
                                <i class="fas fa-cart-plus"></i> Sepete Ekle
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div> 