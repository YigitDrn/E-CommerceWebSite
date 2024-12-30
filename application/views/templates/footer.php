    </main>
    
    <footer class="bg-dark text-light">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h3>Hakkımızda</h3>
                    <p>AlışverişDünyası, en kaliteli ürünleri en uygun fiyatlarla sunan online alışveriş platformudur.</p>
                    <div class="social-links mt-3">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h3>Hızlı Linkler</h3>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo base_url('shop/about'); ?>" class="text-light">Hakkımızda</a></li>
                        <li><a href="<?php echo base_url('shop/contact'); ?>" class="text-light">İletişim</a></li>
                        <li><a href="<?php echo base_url('shop/faq'); ?>" class="text-light">Sıkça Sorulan Sorular</a></li>
                        <li><a href="<?php echo base_url('shop/privacy'); ?>" class="text-light">Gizlilik Politikası</a></li>
                        <li><a href="<?php echo base_url('shop/terms'); ?>" class="text-light">Kullanım Koşulları</a></li>
                    </ul>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h3>Bültenimize Katılın</h3>
                    <p>En yeni ürünler ve kampanyalardan haberdar olun.</p>
                    <form id="newsletter-form" class="mt-3">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="E-posta adresiniz">
                            <button class="btn btn-primary" type="submit">Abone Ol</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> AlışverişDünyası. Tüm hakları saklıdır.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <span class="text-muted">Güvenli Ödeme</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Sepet İşlemleri için JavaScript -->
    <script>
    function addToCart(product) {
        const token = localStorage.getItem('token');
        if (!token) {
            showToast('error', 'Lütfen önce giriş yapın');
            window.location.href = '<?php echo base_url("user/login"); ?>';
            return;
        }

        $.ajax({
            url: 'http://localhost:3001/api/cart/add',
            type: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            data: JSON.stringify({
                productId: product.id,
                quantity: product.quantity || 1
            }),
            success: function(response) {
                if (response.success) {
                    showToast('success', 'Ürün sepete eklendi!');
                    updateCartCount();
                } else {
                    showToast('error', response.message || 'Bir hata oluştu');
                }
            },
            error: function(xhr) {
                console.error('Sepete ekleme hatası:', xhr);
                showToast('error', 'Ürün sepete eklenirken bir hata oluştu!');
            }
        });
    }

    function updateCartCount() {
        const token = localStorage.getItem('token');
        if (!token) return;

        $.ajax({
            url: 'http://localhost:3001/api/cart',
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            success: function(response) {
                if (response.success && response.data) {
                    $('.cart-count').text(response.data.items ? response.data.items.length : 0);
                }
            },
            error: function(xhr) {
                console.error('Sepet sayısı güncelleme hatası:', xhr);
            }
        });
    }

    function showToast(type, message) {
        const toast = $('<div class="toast" role="alert">')
            .addClass(type === 'success' ? 'bg-success' : 'bg-danger')
            .addClass('text-white')
            .css({
                'position': 'fixed',
                'top': '20px',
                'right': '20px',
                'z-index': 1050
            });

        toast.html(`
            <div class="toast-header">
                <strong class="me-auto">${type === 'success' ? 'Başarılı' : 'Hata'}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `);

        $('body').append(toast);
        const bsToast = new bootstrap.Toast(toast[0], { delay: 3000 });
        bsToast.show();

        toast.on('hidden.bs.toast', function() {
            $(this).remove();
        });
    }

    // Newsletter form submit
    $('#newsletter-form').on('submit', function(e) {
        e.preventDefault();
        const email = $(this).find('input[type="email"]').val();
        
        $.ajax({
            url: '<?php echo base_url("shop/newsletter_subscribe"); ?>',
            type: 'POST',
            data: { email: email },
            success: function(response) {
                response = JSON.parse(response);
                showToast(response.success ? 'success' : 'error', response.message);
                if (response.success) {
                    $('#newsletter-form')[0].reset();
                }
            },
            error: function() {
                showToast('error', 'Bir hata oluştu!');
            }
        });
    });

    // Sayfa yüklendiğinde sepet sayısını güncelle
    $(document).ready(function() {
        updateCartCount();
    });
    </script>
</body>
</html> 