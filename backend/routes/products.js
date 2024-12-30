const express = require('express');
const router = express.Router();
const Product = require('../models/product');

// Tüm ürünleri getir
router.get('/', async (req, res) => {
    try {
        const products = await Product.find();
        res.json({
            success: true,
            data: products
        });
    } catch (error) {
        console.error('Ürünler getirilirken hata:', error);
        res.status(500).json({
            success: false,
            message: 'Ürünler getirilirken bir hata oluştu.'
        });
    }
});

// Kategoriye göre ürünleri getir
router.get('/category/:category', async (req, res) => {
    try {
        const products = await Product.find({ category: req.params.category });
        res.json({
            success: true,
            data: products
        });
    } catch (error) {
        console.error('Kategori ürünleri getirilirken hata:', error);
        res.status(500).json({
            success: false,
            message: 'Ürünler getirilirken bir hata oluştu.'
        });
    }
});

// Tek ürün getir
router.get('/:id', async (req, res) => {
    try {
        const product = await Product.findById(req.params.id);
        if (!product) {
            return res.status(404).json({
                success: false,
                message: 'Ürün bulunamadı.'
            });
        }
        res.json({
            success: true,
            data: product
        });
    } catch (error) {
        console.error('Ürün getirilirken hata:', error);
        res.status(500).json({
            success: false,
            message: 'Ürün getirilirken bir hata oluştu.'
        });
    }
});

// Ürün ara
router.get('/search/:query', async (req, res) => {
    try {
        const query = req.params.query;
        const products = await Product.find({
            $or: [
                { name: { $regex: query, $options: 'i' } },
                { description: { $regex: query, $options: 'i' } },
                { category: { $regex: query, $options: 'i' } }
            ]
        });
        res.json({
            success: true,
            data: products
        });
    } catch (error) {
        console.error('Ürün arama hatası:', error);
        res.status(500).json({
            success: false,
            message: 'Ürün arama sırasında bir hata oluştu.'
        });
    }
});

module.exports = router; 