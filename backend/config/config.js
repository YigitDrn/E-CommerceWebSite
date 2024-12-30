module.exports = {
    port: process.env.PORT || 3001,
    mongoURL: process.env.MONGO_URL || 'mongodb+srv://yigitd543:yigitduran00@cluster0.uwfyo.mongodb.net/ecommerce',
    jwtSecret: process.env.JWT_SECRET || 'your-secret-key',
    jwtExpiration: '24h',
    saltRounds: 10
}; 