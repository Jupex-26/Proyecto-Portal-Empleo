<form class="container" method="post" action="?page=<?=$page?>&accion=<?=$accion??''?>" enctype="multipart/form-data">
  <input type="hidden" name='btn-accion' value="<?=$btnAction??''?>">
  <?php if(isset($accion) && $accion=='inscribir') {?>
        <div class="activate-empresa">
          <label for="activo">Activo</label>
          <input type="checkbox" name="activo" id="activo">
        </div>
      <?php } ?>  
  <div class="top-section">
      
      <div class="left flex-col">
        <input type="hidden" name="foto"  value="<?=$empresa->getFoto()?>">
        <img id="preview" src="./assets/img/<?=$empresa->getFoto()==''?'usuario.png':$empresa->getFoto()?>" alt="Foto de perfil">
        <input type="file" id="fileInput" name="foto" accept="image/*">
        <?= $validator->imprimirError('foto');?>
      </div>

      <div class="right">
        <div class="form-group flex-col">
          <label for="nombre">Nombre:<span class="error">*</span></label>
          <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre" required value="<?= $empresa->getNombre()?>">
          <?= $validator->imprimirError('nombre');?>
        </div>
        <div class="form-group flex-col">
          <label for="correo">Correo:<span class="error">*</span></label>
          <input type="email" id="correo" name="correo" placeholder="Ingresa tu correo" required value="<?= $empresa->getCorreo()?>">
          <?= $validator->imprimirError('correo');?>
        </div>
      </div>
    </div>

    <div class="bottom-section">
      <div class="form-group flex-col two-col">
        <label for="direccion">Dirección:<span class="error">*</span></label>
        <input type="text" id="direccion" name="direccion" placeholder="Ingresa tu dirección" value="<?= $empresa->getDireccion()?>">
        <?= $validator->imprimirError('direccion');?>
      </div>

      <div class="form-contacto two-col">
            <div class="form-group flex-col">
              <label for="correo-contacto">Correo de contacto:<span class="error">*</span></label>
              <input type="email" id="correo-contacto" name="correo_contacto" placeholder="Correo de contacto" value="<?= $empresa->getCorreoContacto()?>">
              <?= $validator->imprimirError('correo_contacto');?>
            </div>

            <div class="form-group flex-col">
            <label for="telefono-contacto">Teléfono de contacto:<span class="error">*</span></label>
            <input type="tel" id="telefono-contacto" name="telefono_contacto" placeholder="Teléfono de contacto" value="<?= $empresa->getTelefonoContacto()?>">
            <?= $validator->imprimirError('telefono_contacto');?>
            </div>
      </div>
      <div class="form-group flex-col two-col">
        <label for="descripcion">Descripción:<span class="error">*</span></label>
        <input type="text" id="descripcion" name="descripcion" placeholder="Ingresa una descripcion" value="<?= $empresa->getDescripcion()?>">
        <?= $validator->imprimirError('descripcion');?>
      </div>
      <div class="form-group flex-col two-col">
        <label for="passwd">Contraseña:<span class="error">*</span></label>
        <input type="password" id="passwd" name="passwd" placeholder="Contraseña">
        <?= $validator->imprimirError('passwd');?>
      </div>
      <?= $validator->imprimir()?>
      <div class="buttons-form two-col">
        <?php if ($accion == 'listado' || $accion == 'solicitudes'): ?>
          <a href="?page=<?= $page ?>&accion=<?= $accion?>" class="btn" name="action" value="cancelar">Cancelar</a>
        <?php else: ?>
            <a href="?page=<?= $page ?>" class="btn" name="action" value="cancelar">Cancelar</a>
        <?php endif; ?>
        <button type="submit" class="btn guardar" name="action" value="guardar">Guardar</button>
      </div>
    </div>
    <?php if((isset($accion)) && ($accion=='listado' || $accion=='solicitudes')):?>
    <input type="hidden" name="accion" value="editar">
    <input type="hidden" name="id" value="<?= $empresa->getId()?>">
    <?php endif; ?>
  </form>