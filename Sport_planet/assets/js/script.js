const express = require('express');
const bcrypt = require('bcrypt');
const User = require('./models/User');

const router = express.Router();

router.post('/login', async (req, res) => {
    const { email, contraseña } = req.body;

    try {
        const user = await User.findOne({ email });
        if (!user) {
            return res.status(400).json({ message: 'Correo o contraseña incorrectos' });
        }

        const isPasswordValid = await bcrypt.compare(contraseña, user.contraseña);
        if (!isPasswordValid) {
            return res.status(400).json({ message: 'Correo o contraseña incorrectos' });
        }

        res.status(200).json({ message: 'Inicio de sesión exitoso', user });
    } catch (error) {
        res.status(500).json({ message: 'Error en el inicio de sesión', error });
    }
});

module.exports = router;