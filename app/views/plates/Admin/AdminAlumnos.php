<?=$this->layout('Admin/AdminLayout',['page'=>$page])?>
<?= $this->push('js')?>
<script src="./assets/js/alumno.js"></script>
<script src="./assets/js/validador.js"></script>
<script src="./assets/js/tabla.js"></script>
<script src="./assets/js/select.js"></script>
<script src="./assets/js/modal.js"></script>
<script src="./assets/js/logica.js"></script>
<?= $this->end() ?>
<?= $this->start('panel-main')?>
    <div class="card">
        <button class="carga-masiva btn guardar">Carga Masiva</button>

        <button class="carga-alumno btn">Introducir Alumno</button>
    </div>
    <div class="velo hidden"></div>
    <div class="modal hidden">
        <div class="botones choose hidden">
            <div class="charge">
                <div class="choose-ciclo">
                    <label for="familia">Familia</label>
                    <select name="familia" id="familia" disabled>
                        <option value="familia" selected disabled>Familia</option>
                    </select>
                </div>
                <div class="choose-ciclo">
                    <label for="ciclo">Ciclo</label>
                    <select name="ciclo" id="ciclo" disabled>
                        <option value="ciclo" selected disabled>Ciclo</option>
                    </select>
                </div>
                <button class="cargas btn">Carga Masiva</button>
                <input type="file" class="fichero">
            </div>
        </div>
        
        <div class="botones util-btns">
            <button class="save btn guardar">Guardar</button>
            <button class="editar btn">Editar</button>
            <button class="back btn">Volver</button>
            <button class="borrar btn eliminar">Borrar</button>
        </div>
    </div>
    <div class="card-content">
        <div id="listaUsuario"></div>
    </div>
    

<?= $this->stop()?>