<?= $this->layout('layout');?>
<?= $this->start('header')?>
<?= $this->insert('partials/header')?>
<?= $this->stop()?>
<?= $this->start('main') ?>
<main>
    <div class=" new-oferta">
        <form action="?page=oferta&accion=newOffer" method='POST' class="card-content card">
            <div class="buscador">
                <div>
                <select name="familia" id="familia" class="btn">
                    <option value="Familia" disabled selected>Familia</option>
                </select>
                <select name="nivel" id="nivel" class="btn">
                    <option value="nivel" disabled selected>Nivel</option>
                </select>
                <select name="ciclo" id="ciclo" class="btn">
                    <option value="ciclo" disabled selected>Ciclo</option>
                </select>
                </div>
                
            </div>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre">
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion" id="descripcion">
            </div>
            <div class="fechas">
                <div class="form-group">
                    <label for="fecha_inicio">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio">
                </div>
                <div class="form-group">
                    <label for="fecha_fin">Fecha Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin">
                </div>
            </div>
            
            <div class="save-offer">
                <a href="?page=oferta" class="btn">Volver</a>
                <button class="btn guardar" name="accion" value="crear">Crear Oferta</button>
            </div>
            
        </form>
    </div>
</main>
<?= $this->stop() ?>

<?= $this->start('footer')?>
<?= $this->insert('partials/footer')?>
<?= $this->stop()?>