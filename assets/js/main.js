// Token kontrolü
function isLoggedIn() {
    return localStorage.getItem('token') !== null;
}

// Sepete ürün ekleme
async function addToCart(productId) {
    if (!isLoggedIn()) {
        showNotification('Lütfen önce giriş yapın', 'error');
        window.location.href = '/deneme/user/login';
        return;
    }

    if (!productId) {
        console.error('Ürün ID\'si bulunamadı:', productId);
        showNotification('Geçersiz ürün ID\'si', 'error');
        return;
    }

    // Debug için
    console.log('Adding to cart:', { productId });

    try {
        const response = await fetch('/deneme/shop/add_to_cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify({
                id: productId,
                quantity: 1
            })
        });

        // Debug için ham yanıtı yazdır
        const rawResponse = await response.text();
        console.log('Raw API Response:', rawResponse);

        let result;
        try {
            result = JSON.parse(rawResponse);
        } catch (e) {
            console.error('JSON parse error:', e);
            showNotification('API yanıtı işlenirken hata oluştu', 'error');
            return;
        }

        console.log('Parsed API Response:', result);
        
        if (result.success) {
            updateCartCount();
            showNotification('Ürün sepete eklendi!', 'success');
        } else {
            showNotification(result.message || 'Bir hata oluştu', 'error');
        }
    } catch (error) {
        console.error('Sepete ekleme hatası:', error);
        showNotification('Bir hata oluştu!', 'error');
    }
}

// Sepet sayısını güncelle
async function updateCartCount() {
    if (!isLoggedIn()) return;

    try {
        const response = await fetch('/deneme/shop/get_cart_count', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });
        const count = await response.text();
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            cartCount.textContent = count;
        }
    } catch (error) {
        console.error('Sepet sayısı güncellenirken hata:', error);
    }
}

// Bildirim göster
function showNotification(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type === 'success' ? 'bg-success' : 'bg-danger'} text-white`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.style.position = 'fixed';
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.zIndex = '1050';

    toast.innerHTML = `
        <div class="toast-header">
            <strong class="me-auto">${type === 'success' ? 'Başarılı' : 'Hata'}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    `;

    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
    bsToast.show();

    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
});

function displayProducts(products) {
    console.log('Starting displayProducts with:', products);
    
    const productsContainer = document.getElementById('products-container');
    if (!productsContainer) {
        console.error('Products container not found!');
        return;
    }
    
    productsContainer.innerHTML = '';

    if (!Array.isArray(products) || products.length === 0) {
        productsContainer.innerHTML = '<div class="col-12"><div class="alert alert-info">Henüz ürün bulunmuyor.</div></div>';
        return;
    }

    products.forEach(product => {
        console.log('Processing product:', product);

        const productCard = document.createElement('div');
        productCard.className = 'col-md-4 mb-4';
        
        // Resim yolunu kontrol et
        let imageUrl = product.image;
        if (!imageUrl) {
            imageUrl = '/assets/images/default-product.jpg';
        } else if (!imageUrl.startsWith('http') && !imageUrl.startsWith('/assets/')) {
            imageUrl = `/assets/images/${imageUrl}`;
        }

        // Ürün ID'sini kontrol et
        const productId = product._id || product.id;
        console.log('Product ID:', productId);

        productCard.innerHTML = `
            <div class="card h-100">
                <img src="${imageUrl}" class="card-img-top" alt="${product.name}" onerror="this.src='/assets/images/default-product.jpg'">
                <div class="card-body">
                    <h5 class="card-title">${product.name}</h5>
                    <p class="card-text">${product.description}</p>
                    <p class="card-text"><strong>Fiyat: ${product.price.toLocaleString('tr-TR')} TL</strong></p>
                    <button class="btn btn-primary" onclick="addToCart('${productId}')">Sepete Ekle</button>
                </div>
            </div>
        `;
        productsContainer.appendChild(productCard);
    });
}