<?= $this->layout('layout');?>
<?= $this->start('header')?>
<?= $this->insert('partials/header')?>
<?= $this->stop()?>
<?= $this->start('main') ?>
<main>
    <div class=" new-oferta">
        <form action="?page=oferta&accion=newOffer" method='POST' class="card-content card">
            <div class="form-group">
                <label for="nombre">Nombre:<span class="error">*</span></label>
                <input type="text" name="nombre" id="nombre" value="<?= $oferta->getNombre()??''?>" placeholder="Inserte el título de la oferta">
                <?=$validator->imprimirError('nombre')?>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:<span class="error">*</span></label>
                <input type="text" name="descripcion" id="descripcion" value="<?= $oferta->getDescripcion()??''?>" placeholder="Inserte una descripción">
                <?=$validator->imprimirError('descripcion')?>
            </div>
            <div class="fechas">
                <div class="form-group">
                    <label for="fecha_inicio">Fecha Inicio<span class="error">*</span></label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?= $oferta->getFechaInicio()?$oferta->getFechaInicio()->format('Y-m-d'):''?>">
                    <?=$validator->imprimirError('fecha_inicio')?>
                </div>
                <div class="form-group">
                    <label for="fecha_fin">Fecha Fin<span class="error">*</span></label>
                    <input type="date" name="fecha_fin" id="fecha_fin" value="<?= $oferta->getFechaFin()?$oferta->getFechaFin()->format('Y-m-d'):''?>">
                    <?=$validator->imprimirError('fecha_fin')?>
                </div>
            </div>
            <div class="buscador">
                <div>
                <select name="familia" id="familia" class="btn">
                    <option value="Familia" disabled selected>Familia</option>
                </select>
                <select name="nivel" id="nivel" class="btn" disabled>
                    <option value="nivel" disabled selected>Nivel</option>
                </select>
                <select name="ciclo" id="ciclo" class="btn" disabled>
                    <option value="ciclo" disabled selected>Ciclo</option>
                </select>
                <button class="btn guardar add-ciclo">Agregar</button>
                </div>
            </div>

            <div class="ciclos">
                <div class="card card-content">
                    <ul class='total-ciclos'>
                        <?php foreach($oferta->getCiclos() as $index => $ciclo):?>
                            <li><?= $ciclo->getNombre()?> 
                                <input type="hidden" value="<?=$ciclo->getId()?>" name="ciclos[<?=$index?>][id]"><input type="hidden" value="<?=$ciclo->getNombre()?>" name="ciclos[<?=$index?>][nombre]">
                                <span><img src="./assets/img/borrar.png"></span></li>
                        <?php endforeach?>
                    </ul>
                </div>
                <?= $validator->imprimirError('ciclos')?>
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