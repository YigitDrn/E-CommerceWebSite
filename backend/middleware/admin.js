module.exports = (req, res, next) => {
    // Auth middleware'inden gelen kullanıcı bilgisini kontrol et
    if (!req.user) {
        return res.status(401).json({
            success: false,
            message: 'Yetkilendirme gerekli'
        });
    }

    // Admin rolünü kontrol et
    if (req.user.role !== 'admin') {
        return res.status(403).json({
            success: false,
            message: 'Bu işlem için admin yetkisi gerekli'
        });
    }

    next();
}; 