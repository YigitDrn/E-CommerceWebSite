const USER_ID = 'test-user-1';

// Sepet içeriğini yükle
async function loadCart() {
    try {
        const response = await fetch(`http://localhost:3000/api/cart/${USER_ID}`);
        const cart = await response.json();
        
        if (cart && cart.products) {
            displayCart(cart);
        }
    } catch (error) {
        console.error('Sepet yüklenirken hata:', error);
    }
}

// Sepeti görüntüle
function displayCart(cart) {
    const cartItemsContainer = document.querySelector('.cart-items');
    const summaryContainer = document.querySelector('.cart-summary');
    
    if (!cart.products.length) {
        cartItemsContainer.innerHTML = '<p>Sepetiniz boş</p>';
        return;
    }

    cartItemsContainer.innerHTML = cart.products.map(item => `
        <div class="cart-item" data-product-id="${item.productId}">
            <img src="${item.image}" alt="${item.name}">
            <div class="item-details">
                <h3>${item.name}</h3>
                <p class="item-price">${item.price.toFixed(2)} TL</p>
            </div>
            <div class="quantity-controls">
                <button class="quantity-btn" onclick="updateQuantity('${item.productId}', ${item.quantity - 1})">-</button>
                <input type="number" value="${item.quantity}" min="1" 
                       onchange="updateQuantity('${item.productId}', this.value)">
                <button class="quantity-btn" onclick="updateQuantity('${item.productId}', ${item.quantity + 1})">+</button>
            </div>
            <button class="remove-btn" onclick="updateQuantity('${item.productId}', 0)">Kaldır</button>
        </div>
    `).join('');

    // Sipariş özetini güncelle
    const subtotal = cart.totalAmount;
    const shipping = cart.products.length > 0 ? 29.90 : 0;
    const total = subtotal + shipping;

    summaryContainer.innerHTML = `
        <h2>Sipariş Özeti</h2>
        <div class="summary-item">
            <span>Ara Toplam</span>
            <span>${subtotal.toFixed(2)} TL</span>
        </div>
        <div class="summary-item">
            <span>Kargo</span>
            <span>${shipping.toFixed(2)} TL</span>
        </div>
        <hr>
        <div class="summary-item total">
            <span>Toplam</span>
            <span>${total.toFixed(2)} TL</span>
        </div>
        <button class="checkout-btn">Ödemeye Geç</button>
    `;
}

// Ürün miktarını güncelle
async function updateQuantity(productId, newQuantity) {
    try {
        const response = await fetch('http://localhost:3000/api/cart/update', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                userId: USER_ID,
                productId,
                quantity: parseInt(newQuantity)
            })
        });

        const updatedCart = await response.json();
        displayCart(updatedCart);
    } catch (error) {
        console.error('Miktar güncellenirken hata:', error);
    }
}

// Sayfa yüklendiğinde sepeti göster
window.addEventListener('load', loadCart);