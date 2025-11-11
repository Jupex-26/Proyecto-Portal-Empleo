<?= $this->layout('Admin/AdminEmpresa',['page'=>$page])?>

<?= $this->start('card-content')?>
<div class="container">
    <div class="top-section">
            <img id="preview" src="./assets/img/<?= $empresa->getFoto() ?>" alt="Foto de perfil">
            <h2><?= $empresa->getNombre() ?></h2>
    </div>

    <div class="bottom-section">
        <div class="empresa-group">
                <label>Correo:</label>
                <p><?= $empresa->getEmail() ?></p>
        </div>
        <div class="empresa-group">
            <label>Dirección:</label>
            <p><?= $empresa->getDireccion() ?></p>
        </div>

        
        <div class="empresa-group">
            <label>Correo de contacto:</label>
            <p><?= $empresa->getCorreoContacto() ?></p>
        </div>

        <div class="empresa-group">
            <label>Teléfono de contacto:</label>
            <p><?= $empresa->getTelefonoContacto() ?></p>
        </div>
        <div class="empresa-group last-dato-empresa">
            <label>Descripción de la Empresa:</label>
            <p><?= $empresa->getDescripcion() ?></p>
        </div>
        
<?= $this->section('bottom')?>
    </div>
</div>
<?= $this->stop()?>
