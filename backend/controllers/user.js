const User = require('../models/user');
const jwt = require('jsonwebtoken');
const config = require('../config/config');

// JWT token oluştur
const generateToken = (user) => {
    return jwt.sign(
        { id: user._id, email: user.email, role: user.role },
        config.jwtSecret,
        { expiresIn: config.jwtExpiration }
    );
};

// Kayıt ol
exports.register = async (req, res, next) => {
    try {
        const { name, email, password } = req.body;

        // E-posta kontrolü
        const existingUser = await User.findOne({ email });
        if (existingUser) {
            return res.status(400).json({
                success: false,
                message: 'Bu e-posta adresi zaten kullanılıyor'
            });
        }

        // Yeni kullanıcı oluştur
        const user = await User.create({
            name,
            email,
            password
        });

        // Token oluştur
        const token = generateToken(user);

        res.status(201).json({
            success: true,
            message: 'Kayıt başarılı',
            data: {
                user,
                token
            }
        });
    } catch (error) {
        next(error);
    }
};

// Giriş yap
exports.login = async (req, res, next) => {
    try {
        const { email, password } = req.body;

        // E-posta ve şifre kontrolü
        if (!email || !password) {
            return res.status(400).json({
                success: false,
                message: 'Lütfen e-posta ve şifre giriniz'
            });
        }

        // Kullanıcıyı bul
        const user = await User.findOne({ email }).select('+password');
        if (!user) {
            return res.status(401).json({
                success: false,
                message: 'Geçersiz e-posta veya şifre'
            });
        }

        // Şifre kontrolü
        const isMatch = await user.comparePassword(password);
        if (!isMatch) {
            return res.status(401).json({
                success: false,
                message: 'Geçersiz e-posta veya şifre'
            });
        }

        // Token oluştur
        const token = generateToken(user);

        res.json({
            success: true,
            message: 'Giriş başarılı',
            data: {
                user,
                token
            }
        });
    } catch (error) {
        next(error);
    }
};

// Profil bilgilerini getir
exports.getProfile = async (req, res, next) => {
    try {
        const user = await User.findById(req.user.id);
        res.json({
            success: true,
            data: user
        });
    } catch (error) {
        next(error);
    }
};

// Profil güncelle
exports.updateProfile = async (req, res, next) => {
    try {
        const { name, email, currentPassword, newPassword } = req.body;
        const user = await User.findById(req.user.id).select('+password');

        // Şifre değişikliği varsa
        if (newPassword) {
            // Mevcut şifre kontrolü
            const isMatch = await user.comparePassword(currentPassword);
            if (!isMatch) {
                return res.status(401).json({
                    success: false,
                    message: 'Mevcut şifre hatalı'
                });
            }
            user.password = newPassword;
        }

        // E-posta değişikliği varsa
        if (email && email !== user.email) {
            const existingUser = await User.findOne({ email });
            if (existingUser) {
                return res.status(400).json({
                    success: false,
                    message: 'Bu e-posta adresi zaten kullanılıyor'
                });
            }
            user.email = email;
        }

        // İsim güncelleme
        if (name) {
            user.name = name;
        }

        await user.save();

        res.json({
            success: true,
            message: 'Profil güncellendi',
            data: user
        });
    } catch (error) {
        next(error);
    }
};

// Şifre sıfırlama isteği
exports.forgotPassword = async (req, res, next) => {
    try {
        const { email } = req.body;
        const user = await User.findOne({ email });

        if (!user) {
            return res.status(404).json({
                success: false,
                message: 'Bu e-posta adresiyle kayıtlı kullanıcı bulunamadı'
            });
        }

        // Şifre sıfırlama token'ı oluştur
        const resetToken = jwt.sign(
            { id: user._id },
            config.jwtSecret,
            { expiresIn: '1h' }
        );

        // TODO: E-posta gönderme işlemi
        // Şimdilik sadece token döndürüyoruz
        res.json({
            success: true,
            message: 'Şifre sıfırlama bağlantısı e-posta adresinize gönderildi',
            data: { resetToken }
        });
    } catch (error) {
        next(error);
    }
};

// Şifre sıfırlama
exports.resetPassword = async (req, res, next) => {
    try {
        const { token, newPassword } = req.body;

        // Token'ı doğrula
        const decoded = jwt.verify(token, config.jwtSecret);
        const user = await User.findById(decoded.id);

        if (!user) {
            return res.status(400).json({
                success: false,
                message: 'Geçersiz veya süresi dolmuş token'
            });
        }

        // Yeni şifreyi kaydet
        user.password = newPassword;
        await user.save();

        res.json({
            success: true,
            message: 'Şifreniz başarıyla güncellendi'
        });
    } catch (error) {
        if (error.name === 'JsonWebTokenError') {
            return res.status(400).json({
                success: false,
                message: 'Geçersiz token'
            });
        }
        next(error);
    }
}; 