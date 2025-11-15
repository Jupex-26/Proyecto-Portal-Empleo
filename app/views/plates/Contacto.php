<?= $this->layout('layout')?>
<?= $this->start('header')?>
<?= $this->insert('partials/header')?>
<?= $this->stop()?>
<?= $this->start('main')?>
    <main class="contacto-form">
        <h1>Contacto</h1>
        <div class="form-container">
            <form class="contact-form">
                <div class="form-header">
                    <span class="icon">📧</span>
                    <p>Completa el siguiente formulario y te responderemos lo antes posible</p>
                </div>

                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Tu nombre">

                <div class="apellidos">
                    <div>
                    <label for="apellido1">Apellido 1</label>
                    <input type="text" id="apellido1" name="apellido1" placeholder="Primer apellido">
                    </div>
                    <div>
                    <label for="apellido2">Apellido 2</label>
                    <input type="text" id="apellido2" name="apellido2" placeholder="Segundo apellido">
                    </div>
                </div>

                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" placeholder="correo@ejemplo.com">

                <label for="asunto">Asunto</label>
                <input type="text" id="asunto" name="asunto" placeholder="Motivo del mensaje">

                <label for="mensaje">Mensaje</label>
                <textarea id="mensaje" name="mensaje" rows="5" placeholder="Escribe tu mensaje aquí..."></textarea>

                <button type="submit" class="btn-enviar btn">Enviar</button>
            </form>
        </div>
  </main>
<?= $this->stop()?>
<?= $this->start('footer')?>
<?= $this->insert('partials/footer')?>
<?= $this->stop()?>