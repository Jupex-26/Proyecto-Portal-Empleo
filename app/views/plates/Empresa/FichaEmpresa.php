<?= $this->layout('layout')?>
<?= $this->start('header')?>
<?= $this->insert('partials/header',['user'=>$user??null])?>
<?= $this->stop()?>
<?= $this->start('main')?>
<div class="container">
    <div class="top-section ficha">
            <img id="preview" src="./assets/img/<?= $user->getFoto() ?>" alt="Foto de perfil">
            <h2><?= $user->getNombre() ?></h2>
    </div>

    <div class="bottom-section">
        <div class="empresa-group">
                <label>Correo:</label>
                <p><?= $user->getCorreo() ?></p>
        </div>
        <div class="empresa-group">
                <label>Contraseña:</label>
                <p>*********</p>
        </div>
        

        
        <div class="empresa-group">
            <label>Correo de contacto:</label>
            <p><?= $user->getCorreoContacto() ?></p>
        </div>

        <div class="empresa-group">
            <label>Teléfono de contacto:</label>
            <p><?= $user->getTelefonoContacto() ?></p>
        </div>
        <div class="empresa-group two-col">
            <label>Dirección:</label>
            <p><?= $user->getDireccion() ?></p>
        </div>
        <div class="empresa-group two-col">
            <label>Descripción de la Empresa:</label>
            <p><?= $user->getDescripcion() ?></p>
        </div>
        <p class="two-col card"><strong>Para cambiar datos pongase en contacto con soporte</strong></p>
    </div>  
</div>
<?= $this->stop()?>
<?= $this->start('footer')?>
<?= $this->insert('partials/footer')?>
<?= $this->stop()?>