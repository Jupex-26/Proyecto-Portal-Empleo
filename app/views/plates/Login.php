<?= $this->layout('layout'); 
$this->start('main'); ?>
    <main>
        <div class="login-container">
            <div class="login-card flex-col">
                <img src="./assets/img/logo.png" alt="EmpleNow" class="login-logo">
                <h2>Iniciar Sesión</h2>
                
                <form class="login-form flex-col" method="POST" action="?page=login">
                    
                    <input type="email" name="correo_login" placeholder="Correo electrónico" required value="<?=$correo??''?>" >
                    <input type="password" name="passwd_login" placeholder="Contraseña" required>
                    <button type="submit" name="accion" class="btn login-btn" value="login">Ingresar</button>
                    <?php if ($validator->imprimirError('correo_login')): ?>
                        <p class="error card"><?= $validator->imprimirError('correo_login') ?></p>
                    <?php endif; ?>

                </form>
                
                <div class="login-extra">
                    <p>¿No tienes cuenta?</p>
                    <div class="login-buttons">
                        <form action="index.php" method="GET">
                            <input type="hidden" name="page" value="login">
                            <button class="btn user login-btn" name="accion" value="registroAlumno">Regístrate como Usuario</button>
                            <button class="btn company login-btn" name="accion" value="registroEmpresa">Regístrate como Empresa</button>
                        </form>
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
