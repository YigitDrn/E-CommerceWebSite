<div class="container mt-5">
    <h2>Sepetim</h2>
    
    <?php if (empty($cart) || empty($cart['items'])): ?>
        <div class="alert alert-info">
            Sepetiniz boş. <a href="<?php echo base_url(); ?>">Alışverişe başlayın</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ürün</th>
                        <th>Fiyat</th>
                        <th>Adet</th>
                        <th>Toplam</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart['items'] as $item): ?>
                        <tr data-product-id="<?php echo $item['product']['_id']; ?>">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo base_url('assets/images/' . $item['product']['image']); ?>" 
                                         alt="<?php echo $item['product']['name']; ?>" 
                                         class="img-thumbnail mr-3" 
                                         style="width: 50px;">
                                    <span><?php echo $item['product']['name']; ?></span>
                                </div>
                            </td>
                            <td><?php echo number_format($item['price'], 2); ?> TL</td>
                            <td>
                                <input type="number" 
                                       class="form-control quantity-input" 
                                       value="<?php echo $item['quantity']; ?>" 
                                       min="1" 
                                       style="width: 80px;">
                            </td>
                            <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?> TL</td>
                            <td>
                                <button class="btn btn-danger btn-sm remove-from-cart">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Toplam:</strong></td>
                        <td><strong><?php echo number_format($cart['total'], 2); ?> TL</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="<?php echo base_url(); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Alışverişe Devam Et
            </a>
            <button class="btn btn-primary">
                Ödemeye Geç <i class="fas fa-arrow-right"></i>
            </button>
        </div>

        <script>
        $(document).ready(function() {
            // Ürün miktarını güncelle
            $('.quantity-input').change(function() {
                var $row = $(this).closest('tr');
                var productId = $row.data('product-id');
                var quantity = $(this).val();

                $.ajax({
                    url: '<?php echo base_url("shop/update_cart"); ?>',
                    type: 'POST',
                    data: {
                        id: productId,
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

            // Ürünü sepetten çıkar
            $('.remove-from-cart').click(function() {
                var $row = $(this).closest('tr');
                var productId = $row.data('product-id');

                $.ajax({
                    url: '<?php echo base_url("shop/remove_from_cart"); ?>',
                    type: 'POST',
                    data: {
                        id: productId
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
        });
        </script>
    <?php endif; ?>
</div> 