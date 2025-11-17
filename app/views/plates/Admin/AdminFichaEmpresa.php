<?= $this->layout('Admin/AdminEmpresa',['page'=>$page, 'user'=>$user])?>

<?= $this->start('card-content')?>
<div class="container">
    <div class="top-section ficha">
            <img id="preview" src="./assets/img/<?= $empresa->getFoto() ?>" alt="Foto de perfil">
            <h2><?= $empresa->getNombre() ?></h2>
    </div>

    <div class="bottom-section">
        <div class="empresa-group">
                <label>Correo:</label>
                <p><?= $empresa->getCorreo() ?></p>
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
        <div class="empresa-group two-col">
            <label>Descripción de la Empresa:</label>
            <p><?= $empresa->getDescripcion() ?></p>
        </div>
        
<?= $this->section('bottom')?>
    </div>
</div>
<?= $this->stop()?>
