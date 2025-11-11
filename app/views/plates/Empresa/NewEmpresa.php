<form class="container" method="post" action="?page=<?=$page?>&accion=<?=$accion?>" enctype="multipart/form-data">
    <div class="top-section">
      <div class="left">
        <img id="preview" src="./assets/img/usuario.png" alt="Foto de perfil">
        <input type="file" id="fileInput" name="foto" accept="image/*" required>
        <?= $validator->imprimirError('imagen');?>
      </div>

      <div class="right">
        <div class="form-group">
          <label for="nombre">Nombre:</label>
          <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre" required>
          <?= $validator->imprimirError('nombre');?>
        </div>
        <div class="form-group">
          <label for="correo">Correo:</label>
          <input type="email" id="correo" name="correo" placeholder="Ingresa tu correo" required >
          <?= $validator->imprimirError('correo');?>
        </div>
      </div>
    </div>

    <div class="bottom-section">
      <div class="form-group">
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" placeholder="Ingresa tu dirección" required>
        <?= $validator->imprimirError('direccion');?>
      </div>

      <div class="form-contacto">
            <div class="form-group">
              <label for="correo-contacto">Correo de contacto:</label>
              <input type="email" id="correo-contacto" name="correo_contacto" placeholder="Correo de contacto" required>
              <?= $validator->imprimirError('correo_contacto');?>
            </div>

            <div class="form-group">
            <label for="telefono-contacto">Teléfono de contacto:</label>
            <input type="tel" id="telefono-contacto" name="telefono_contacto" placeholder="Teléfono de contacto" required>
            <?= $validator->imprimirError('telefono_contacto');?>
            </div>
      </div>
      <div class="form-group">
        <label for="descripcion">Descripción:</label>
        <input id="descripcion" name="descripcion" placeholder="Ingresa una descripcion" required>
        <?= $validator->imprimirError('descripcion');?>
      </div>
      <?= $validator->imprimir()?>
      <div class="buttons-form">
        <button type="submit" class="btn" name="action" value="cancelar">Cancelar</button>
        <button type="submit" class="btn guardar" name="action" value="guardar">Guardar</button>
      </div>
    </div>
  </form>