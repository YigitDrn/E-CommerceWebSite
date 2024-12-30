const jwt = require('jsonwebtoken');
const User = require('../models/user');

module.exports = async (req, res, next) => {
    try {
        // Token'ı al
        const token = req.header('Authorization')?.replace('Bearer ', '');
        console.log('Gelen token:', token); // Debug için
        
        if (!token) {
            console.log('Token bulunamadı'); // Debug için
            return res.status(401).json({
                success: false,
                message: 'Yetkilendirme token\'ı bulunamadı'
            });
        }

        // Token'ı doğrula
        const decoded = jwt.verify(token, 'your-secret-key');
        console.log('Çözümlenen token:', decoded); // Debug için
        
        // Kullanıcıyı bul
        const user = await User.findById(decoded.userId);
        console.log('Bulunan kullanıcı:', user ? user._id : 'Kullanıcı bulunamadı'); // Debug için
        
        if (!user) {
            return res.status(401).json({
                success: false,
                message: 'Kullanıcı bulunamadı'
            });
        }

        // Kullanıcıyı request'e ekle
        req.user = user;
        next();
    } catch (error) {
        console.error('Auth hatası:', error);
        if (error.name === 'JsonWebTokenError') {
            return res.status(401).json({
                success: false,
                message: 'Geçersiz token'
            });
        }
        if (error.name === 'TokenExpiredError') {
            return res.status(401).json({
                success: false,
                message: 'Token süresi dolmuş'
            });
        }
        res.status(401).json({
            success: false,
            message: 'Yetkilendirme hatası'
        });
    }
}; 