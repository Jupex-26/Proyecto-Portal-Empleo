<form class="container" method="post" action="?page=<?=$page?>&accion=<?=$accion?>" enctype="multipart/form-data">
  <?php if($accion=='inscribir') {?>
        <div>
          <label for="activo">Activo</label>
          <input type="checkbox" name="activo" id="">
        </div>
      <?php } ?>  
  <div class="top-section">
      
      <div class="left">
        <img id="preview" src="./assets/img/<?=$empresa->getFoto()==''?'usuario.png':$empresa->getFoto()?>" alt="Foto de perfil">
        <input type="file" id="fileInput" name="foto" accept="image/*">
        <?= $validator->imprimirError('imagen');?>
      </div>

      <div class="right">
        <div class="form-group">
          <label for="nombre">Nombre:</label>
          <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre" required value="<?= $empresa->getNombre()?>">
          <?= $validator->imprimirError('nombre');?>
        </div>
        <div class="form-group">
          <label for="correo">Correo:</label>
          <input type="email" id="correo" name="correo" placeholder="Ingresa tu correo" required value="<?= $empresa->getCorreo()?>">
          <?= $validator->imprimirError('correo');?>
        </div>
      </div>
    </div>

    <div class="bottom-section">
      <div class="form-group">
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" placeholder="Ingresa tu dirección" value="<?= $empresa->getDireccion()?>">
        <?= $validator->imprimirError('direccion');?>
      </div>

      <div class="form-contacto">
            <div class="form-group">
              <label for="correo-contacto">Correo de contacto:</label>
              <input type="email" id="correo-contacto" name="correo_contacto" placeholder="Correo de contacto" value="<?= $empresa->getCorreoContacto()?>">
              <?= $validator->imprimirError('correo_contacto');?>
            </div>

            <div class="form-group">
            <label for="telefono-contacto">Teléfono de contacto:</label>
            <input type="tel" id="telefono-contacto" name="telefono_contacto" placeholder="Teléfono de contacto" value="<?= $empresa->getTelefonoContacto()?>">
            <?= $validator->imprimirError('telefono_contacto');?>
            </div>
      </div>
      <div class="form-group">
        <label for="descripcion">Descripción:</label>
        <input id="descripcion" name="descripcion" placeholder="Ingresa una descripcion" value="<?= $empresa->getDescripcion()?>">
        <?= $validator->imprimirError('descripcion');?>
      </div>
      <?= $validator->imprimir()?>
      <div class="buttons-form">
        <a href="?page=<?=$page?>" class="btn" name="action" value="cancelar">Cancelar</a>
        <button type="submit" class="btn guardar" name="action" value="guardar">Guardar</button>
      </div>
    </div>
    <?php if($accion=='listado') {?>
    <input type="hidden" name="accion" value="editar">
    <input type="hidden" name="id" value="<?= $empresa->getId()?>">
    <?php } ?>
  </form>