<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Ana Sayfa</a></li>
            <li class="breadcrumb-item">
                <a href="<?php echo base_url('shop/category/' . $product['category']); ?>">
                    <?php echo ucfirst($product['category']); ?>
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $product['name']; ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo base_url('assets/images/' . $product['image']); ?>" 
                 class="img-fluid" 
                 alt="<?php echo $product['name']; ?>">
        </div>
        <div class="col-md-6">
            <h1><?php echo $product['name']; ?></h1>
            
            <div class="my-4">
                <h3 class="text-primary"><?php echo number_format($product['price'], 2); ?> TL</h3>
            </div>
            
            <div class="mb-4">
                <p><?php echo $product['description']; ?></p>
            </div>
            
            <div class="mb-4">
                <p>
                    <strong>Stok Durumu:</strong> 
                    <?php if ($product['stock'] > 0): ?>
                        <span class="text-success">Stokta var (<?php echo $product['stock']; ?> adet)</span>
                    <?php else: ?>
                        <span class="text-danger">Stokta yok</span>
                    <?php endif; ?>
                </p>
            </div>
            
            <?php if ($product['stock'] > 0): ?>
                <div class="d-flex align-items-center mb-4">
                    <div class="input-group" style="width: 150px;">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary" type="button" id="decrease-quantity">-</button>
                        </div>
                        <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="increase-quantity">+</button>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary btn-lg" onclick="addToCart(<?php echo htmlspecialchars(json_encode([
                    'id' => $product['_id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image']
                ])); ?>)">
                    <i class="fas fa-cart-plus"></i> Sepete Ekle
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#decrease-quantity').click(function() {
        var quantity = parseInt($('#quantity').val());
        if (quantity > 1) {
            $('#quantity').val(quantity - 1);
        }
    });

    $('#increase-quantity').click(function() {
        var quantity = parseInt($('#quantity').val());
        var maxStock = parseInt($('#quantity').attr('max'));
        if (quantity < maxStock) {
            $('#quantity').val(quantity + 1);
        }
    });

    $('#quantity').on('change', function() {
        var quantity = parseInt($(this).val());
        var maxStock = parseInt($(this).attr('max'));
        
        if (quantity < 1) {
            $(this).val(1);
        } else if (quantity > maxStock) {
            $(this).val(maxStock);
        }
    });
});

function addToCart(product) {
    product.quantity = parseInt($('#quantity').val());
    
    $.ajax({
        url: '<?php echo base_url("shop/add_to_cart"); ?>',
        type: 'POST',
        data: {
            id: product.id,
            quantity: product.quantity
        },
        success: function(response) {
            response = JSON.parse(response);
            if (response.success) {
                alert(response.message);
                updateCartCount();
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('Bir hata oluştu!');
        }
    });
}
</script> 