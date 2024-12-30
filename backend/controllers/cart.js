const Cart = require('../models/cart');
const Product = require('../models/product');

// Kullanıcının sepetini getir
exports.getCart = async (req, res, next) => {
    try {
        const cart = await Cart.findOne({ user: req.user._id })
            .populate('items.product', 'name price image');

        if (!cart) {
            return res.json({
                success: true,
                data: {
                    items: [],
                    total: 0
                }
            });
        }

        res.json({
            success: true,
            data: cart
        });
    } catch (error) {
        next(error);
    }
};

// Sepete ürün ekle
exports.addToCart = async (req, res, next) => {
    try {
        const { productId, quantity } = req.body;

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
            cart = await Cart.create({
                user: req.user._id,
                items: [{
                    product: productId,
                    quantity: quantity,
                    price: product.price
                }]
            });
        } else {
            // Ürün sepette var mı kontrol et
            const itemIndex = cart.items.findIndex(item => 
                item.product.toString() === productId
            );

            if (itemIndex > -1) {
                // Ürün varsa miktarı güncelle
                cart.items[itemIndex].quantity += quantity;
            } else {
                // Ürün yoksa ekle
                cart.items.push({
                    product: productId,
                    quantity: quantity,
                    price: product.price
                });
            }

            await cart.save();
        }

        res.json({
            success: true,
            message: 'Ürün sepete eklendi',
            data: cart
        });
    } catch (error) {
        next(error);
    }
};

// Sepetten ürün çıkar
exports.removeFromCart = async (req, res, next) => {
    try {
        const { productId } = req.params;

        const cart = await Cart.findOne({ user: req.user._id });
        if (!cart) {
            return res.status(404).json({
                success: false,
                message: 'Sepet bulunamadı'
            });
        }

        cart.items = cart.items.filter(item => 
            item.product.toString() !== productId
        );

        await cart.save();

        res.json({
            success: true,
            message: 'Ürün sepetten çıkarıldı',
            data: cart
        });
    } catch (error) {
        next(error);
    }
};

// Sepetteki ürün miktarını güncelle
exports.updateCartItem = async (req, res, next) => {
    try {
        const { productId } = req.params;
        const { quantity } = req.body;

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

        const cart = await Cart.findOne({ user: req.user._id });
        if (!cart) {
            return res.status(404).json({
                success: false,
                message: 'Sepet bulunamadı'
            });
        }

        const itemIndex = cart.items.findIndex(item => 
            item.product.toString() === productId
        );

        if (itemIndex === -1) {
            return res.status(404).json({
                success: false,
                message: 'Ürün sepette bulunamadı'
            });
        }

        cart.items[itemIndex].quantity = quantity;
        await cart.save();

        res.json({
            success: true,
            message: 'Ürün miktarı güncellendi',
            data: cart
        });
    } catch (error) {
        next(error);
    }
};

// Sepeti temizle
exports.clearCart = async (req, res, next) => {
    try {
        const cart = await Cart.findOne({ user: req.user._id });
        if (!cart) {
            return res.status(404).json({
                success: false,
                message: 'Sepet bulunamadı'
            });
        }

        cart.items = [];
        await cart.save();

        res.json({
            success: true,
            message: 'Sepet temizlendi',
            data: cart
        });
    } catch (error) {
        next(error);
    }
}; 