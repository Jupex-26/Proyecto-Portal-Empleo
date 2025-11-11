<?= $this->layout('layout'); 
$this->start('css'); ?>
<link rel="stylesheet" href="./assets/css/login.css">
<?= $this->stop();
$this->start('main'); ?>
    <main>
        <div class="login-container">
            <div class="login-card">
                <img src="./assets/img/logo.png" alt="EmpleNow" class="login-logo">
                <h2>Iniciar Sesión</h2>

                <form class="login-form" method="POST" action="?page=login">
                    <input type="email" name="email" placeholder="Correo electrónico" required >
                    <input type="password" name="passwd" placeholder="Contraseña" required>
                    <button type="submit" name="enviar" class="btn login-btn">Ingresar</button>
                </form>

                <div class="login-extra">
                    <p>¿No tienes cuenta?</p>
                    <div class="login-buttons">
                    <a href="?page=registerUser" class="btn user">Regístrate como Usuario</a>
                    <a href="?page=loginEmpresa" class="btn company">Regístrate como Empresa</a>
                    </div>
                    <a href="#" class="login-link">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="login-back">
                    <a href="?page=home" class="volver btn">Volver Al Inicio</a>
                </div>
            </div>
        </div>
    </main> 
<?= $this->stop(); 
?>
