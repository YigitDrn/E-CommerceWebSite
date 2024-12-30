// Token kontrolü
function isLoggedIn() {
    const token = localStorage.getItem('token');
    console.log('Token kontrolü:', token); // Debug için
    return token !== null;
}

// Sepete ürün ekleme
async function addToCart(product) {
    if (!isLoggedIn()) {
        showNotification('Lütfen önce giriş yapın', 'error');
        window.location.href = '/deneme/user/login';
        return;
    }

    try {
        const token = localStorage.getItem('token');
        console.log('Sepete eklenecek ürün:', product); // Debug için
        console.log('Kullanılan token:', token); // Debug için

        const response = await fetch('http://localhost:3001/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                productId: product.id,
                quantity: product.quantity || 1
            })
        });

        console.log('API Yanıt Status:', response.status); // Debug için

        const result = await response.json();
        console.log('API Yanıt:', result); // Debug için

        if (result.success) {
            updateCartCount();
            showNotification('Ürün sepete eklendi!', 'success');
        } else {
            showNotification(result.message || 'Bir hata oluştu', 'error');
        }
    } catch (error) {
        console.error('Sepete ekleme hatası:', error);
        showNotification('Ürün sepete eklenirken bir hata oluştu!', 'error');
    }
}

// Sepet sayısını güncelle
async function updateCartCount() {
    if (!isLoggedIn()) return;

    try {
        const token = localStorage.getItem('token');
        console.log('Sepet sayısı güncellenirken kullanılan token:', token); // Debug için

        const response = await fetch('http://localhost:3001/api/cart', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        console.log('Sepet API Yanıt Status:', response.status); // Debug için

        const result = await response.json();
        console.log('Sepet API Yanıt:', result); // Debug için

        if (result.success && result.data) {
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = result.data.items ? result.data.items.length : 0;
            }
        }
    } catch (error) {
        console.error('Sepet sayısı güncellenirken hata:', error);
    }
}

// Bildirim göster
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} notification`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '1050';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', () => {
    console.log('Sayfa yüklendiğinde token:', localStorage.getItem('token')); // Debug için
    updateCartCount();
});