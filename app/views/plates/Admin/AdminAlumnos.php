<?=$this->layout('Admin/AdminLayout',['page'=>$page, 'user'=>$user])?>
<?= $this->push('js')?>
<script src="./assets/js/alumno.js"></script>
<script src="./assets/js/tabla.js"></script>
<script src="./assets/js/modal.js"></script>
<script src="./assets/js/logica.js"></script>
<?= $this->end() ?>
<?= $this->start('panel-main')?>
    <div class="card-options">
        <button class="carga-masiva btn guardar">Carga Masiva</button>

        <button class="carga-alumno btn">Introducir Alumno</button>
    </div>
    <div class="card-content card">
        <div id="listaUsuario"></div>
    </div>
    <div id="mi-modal-personalizado" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; padding: 25px; border-radius: 8px; max-width: 400px; text-align: center;">
            <p id="modal-pregunta" style="font-weight: bold; margin-bottom: 20px;">[Aquí va el mensaje]</p>
            <button id="btn-aceptar" class="btn">Aceptar</button>
            <button id="btn-cancelar" class="btn" style="margin-left: 10px;">Cancelar</button>
        </div>
    </div>
    <div class="velo hidden"></div>
    <div class="modal hidden">
        <div class="botones choose hidden no-quitar">
            <div class="charge">
                <div class="choose-ciclo">
                    <label for="familia">Familia</label>
                    <select name="familia" id="familia" disabled>
                        <option value="familia" selected disabled>Familia</option>
                    </select>
                </div>
                <div class="choose-ciclo">
                    <label for="nivel">Nivel</label>
                    <select name="nivel" id="nivel" disabled>
                        <option value="nivel" selected disabled>Nivel</option>
                    </select>
                </div>
                <div class="choose-ciclo two-col">
                    <label for="ciclo">Ciclo</label>
                    <select name="ciclo" id="ciclo" disabled>
                        <option value="ciclo" selected disabled>Ciclo</option>
                    </select>
                </div>
                <button class="cargas btn">Carga Masiva</button>
                <input type="file" class="fichero" accept=".csv">
            </div>
        </div>
        
        <div class="botones util-btns no-quitar">
            <button class="save btn guardar">Guardar</button>
            <button class="editar btn">Editar</button>
            <button class="back btn">Volver</button>
            <button class="borrar btn eliminar">Borrar</button>
        </div>
        <div class="form-modal no-quitar"></div>
        </div>
    </div>
    
    

<?= $this->stop()?>