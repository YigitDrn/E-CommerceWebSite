const express = require('express');
const router = express.Router();
const Cart = require('../models/cart');
const Product = require('../models/product');
const auth = require('../middleware/auth');

// Tüm route'lar auth middleware'i kullanıyor
router.use(auth);

// Sepeti getir
router.get('/', async (req, res) => {
    try {
        let cart = await Cart.findOne({ user: req.user._id }).populate('items.product');
        
        if (!cart) {
            cart = {
                items: [],
                total: 0
            };
        }
        
        res.json({
            success: true,
            data: cart
        });
    } catch (error) {
        console.error('Sepet getirme hatası:', error);
        res.status(500).json({
            success: false,
            message: 'Sepet bilgileri alınırken bir hata oluştu.'
        });
    }
});

// Sepete ürün ekle
router.post('/add', async (req, res) => {
    try {
        const { productId, quantity } = req.body;
        console.log('Gelen istek:', { productId, quantity, userId: req.user._id }); // Debug için

        // Ürünü kontrol et
        const product = await Product.findById(productId);
        if (!product) {
            return res.status(404).json({
                success: false,
                message: 'Ürün bulunamadı'
            });
        }

        // Stok kontrolü
        if (product.stock < quantity) {
            return res.status(400).json({
                success: false,
                message: 'Yetersiz stok'
            });
        }

        // Kullanıcının sepetini bul veya oluştur
        let cart = await Cart.findOne({ user: req.user._id });
        
        if (!cart) {
            // Yeni sepet oluştur
            cart = new Cart({
                user: req.user._id,
                items: [{
                    product: productId,
                    quantity: quantity,
                    price: product.price
                }],
                total: product.price * quantity
            });
        } else {
            // Ürün sepette var mı kontrol et
            const existingItemIndex = cart.items.findIndex(item => 
                item.product.toString() === productId
            );
            
            if (existingItemIndex > -1) {
                // Varsa miktarı güncelle
                cart.items[existingItemIndex].quantity += quantity;
            } else {
                // Yoksa yeni ürün ekle
                cart.items.push({
                    product: productId,
                    quantity: quantity,
                    price: product.price
                });
            }

            // Toplam tutarı güncelle
            cart.total = cart.items.reduce((total, item) => {
                return total + (item.price * item.quantity);
            }, 0);
        }
        
        await cart.save();
        
        // Güncel sepeti product bilgileriyle birlikte getir
        cart = await Cart.findById(cart._id).populate('items.product');
        
        res.json({
            success: true,
            message: 'Ürün sepete eklendi',
            data: cart
        });
    } catch (error) {
        console.error('Sepete ekleme hatası:', error);
        res.status(500).json({
            success: false,
            message: 'Ürün sepete eklenirken bir hata oluştu.'
        });
    }
});

// Sepetten ürün çıkar
router.delete('/remove/:productId', async (req, res) => {
    try {
        const cart = await Cart.findOne({ user: req.user._id });
        
        if (!cart) {
            return res.status(404).json({
                success: false,
                message: 'Sepet bulunamadı'
            });
        }
        
        cart.items = cart.items.filter(item => 
            item.product.toString() !== req.params.productId
        );
        
        // Toplam tutarı güncelle
        cart.total = cart.items.reduce((total, item) => {
            return total + (item.price * item.quantity);
        }, 0);
        
        await cart.save();
        
        res.json({
            success: true,
            message: 'Ürün sepetten çıkarıldı',
            data: cart
        });
    } catch (error) {
        console.error('Sepetten çıkarma hatası:', error);
        res.status(500).json({
            success: false,
            message: 'Ürün sepetten çıkarılırken bir hata oluştu.'
        });
    }
});

module.exports = router;