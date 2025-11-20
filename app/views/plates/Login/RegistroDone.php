<?= $this->layout('layout'); 
$this->start('main'); ?>
    <header class="sticky registro-header">
        <div class="logo">
            <img src="./assets/img/logo.png" alt="" class="registro-logo">
        </div>
        <h1>Registro de Empresas</h1>
    </header>
    <main>
        <div class="card-content registro-empresa card">
            <h2>Su Registro fue completado</h2>
            <p>Tiene que esperar a que un administrador verifique su empresa</p>
            <a href="?page=home" class="btn">Volver a la página principal</a>
        </div>
    </main> 
<?= $this->stop(); 
?>