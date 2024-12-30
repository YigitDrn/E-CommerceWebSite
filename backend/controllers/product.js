const Product = require('../models/product');

// Tüm ürünleri getir
exports.getAllProducts = async (req, res, next) => {
    try {
        const products = await Product.find().sort('-createdAt');
        res.json({
            success: true,
            count: products.length,
            data: products
        });
    } catch (error) {
        next(error);
    }
};

// Tek ürün getir
exports.getProduct = async (req, res, next) => {
    try {
        const product = await Product.findById(req.params.id);
        if (!product) {
            return res.status(404).json({
                success: false,
                message: 'Ürün bulunamadı'
            });
        }
        res.json({
            success: true,
            data: product
        });
    } catch (error) {
        next(error);
    }
};

// Yeni ürün ekle
exports.createProduct = async (req, res, next) => {
    try {
        const product = await Product.create(req.body);
        res.status(201).json({
            success: true,
            data: product
        });
    } catch (error) {
        next(error);
    }
};

// Ürün güncelle
exports.updateProduct = async (req, res, next) => {
    try {
        const product = await Product.findByIdAndUpdate(
            req.params.id,
            req.body,
            {
                new: true,
                runValidators: true
            }
        );

        if (!product) {
            return res.status(404).json({
                success: false,
                message: 'Ürün bulunamadı'
            });
        }

        res.json({
            success: true,
            data: product
        });
    } catch (error) {
        next(error);
    }
};

// Ürün sil
exports.deleteProduct = async (req, res, next) => {
    try {
        const product = await Product.findByIdAndDelete(req.params.id);

        if (!product) {
            return res.status(404).json({
                success: false,
                message: 'Ürün bulunamadı'
            });
        }

        res.json({
            success: true,
            message: 'Ürün başarıyla silindi'
        });
    } catch (error) {
        next(error);
    }
};

// Kategoriye göre ürün getir
exports.getProductsByCategory = async (req, res, next) => {
    try {
        const products = await Product.find({ category: req.params.category });
        res.json({
            success: true,
            count: products.length,
            data: products
        });
    } catch (error) {
        next(error);
    }
};

// Ürün ara
exports.searchProducts = async (req, res, next) => {
    try {
        const searchQuery = req.params.query;
        const products = await Product.find({
            $text: { $search: searchQuery }
        }).sort('-createdAt');

        res.json({
            success: true,
            count: products.length,
            data: products
        });
    } catch (error) {
        next(error);
    }
}; 