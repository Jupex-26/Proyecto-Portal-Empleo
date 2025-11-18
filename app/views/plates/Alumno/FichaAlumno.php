<?= $this->layout('layout')?>
<?= $this->start('js')?>
<script src="./assets/js/camara.js"></script>
<script src="./assets/js/perfil.js"></script>
<?= $this->stop()?>
<?= $this->start('header')?>
<?= $this->insert('partials/header',['user'=>$user??null])?>
<?= $this->stop()?>
<?= $this->start('main')?>
<div class="container">
         <div class="perfil-header">
            <h1>Perfil de Usuario</h1>
        </div>

        <div class="card card-content">
            <div class="mode-toggle">
                <button class="btn" id="toggleMode">✏️ Editar Perfil</button>
            </div>

            <form id="profileForm" class="view-mode">
                <div class="photo-section">
                    <div class="photo-container">
                        <img id="photoPreview" class="photo-preview hidden" alt="Foto de perfil">
                        <div id="photoPlaceholder" class="photo-placeholder">👤</div>
                    </div>
                    <div class="file-input-wrapper">
                        <label class="file-input-button">
                            📷 Cambiar Foto
                        </label>
                        <div id="fotoName" class="file-name"></div>
                    </div>
                </div>

                <div class="perfil-form">
                    <div class="form-group">
                        <label for="nombre">Nombre *</label>
                        <input type="text" id="nombre" name="nombre" required disabled>
                    </div>

                    <div class="form-group">
                        <label for="ap1">Primer Apellido *</label>
                        <input type="text" id="ap1" name="ap1" required disabled>
                    </div>

                    <div class="form-group">
                        <label for="ap2">Segundo Apellido</label>
                        <input type="text" id="ap2" name="ap2" disabled>
                    </div>

                    <div class="form-group">
                        <label for="correo">Correo Electrónico *</label>
                        <input type="email" id="correo" name="correo" required disabled>
                    </div>

                    <div class="form-group">
                        <label for="fechaNacimiento">Fecha de Nacimiento</label>
                        <input type="date" id="fechaNacimiento" name="fechaNacimiento" disabled>
                    </div>

                    <div class="hidden form-group full-width password-fields">
                        <h3 class="password-title">Cambiar Contraseña</h3>
                        <div class="password-grid">
                            <div class="form-group">
                                <label for="passwdActual">Contraseña Actual</label>
                                <input type="password" id="passwdActual" name="passwdActual" placeholder="••••••••">
                            </div>

                            <div class="form-group">
                                <label for="passwdNueva">Contraseña Nueva</label>
                                <input type="password" id="passwdNueva" name="passwdNueva" placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion" disabled>
                    </div>

                    <div class="form-group full-width">
                        <label>Ciclos Formativos</label>
                        
                        <div class="add-ciclo-section hidden">
                            <div class="form-group">
                                <label for="familia">Familia Profesional</label>
                                <select id="familia" class="btn" disabled>
                                    <option value="">-- Selecciona familia --</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="nivel">Nivel</label>
                                <select id="nivel" class="btn" disabled>
                                    <option value="">-- Selecciona nivel --</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="ciclo">Ciclo</label>
                                <select id="ciclo" class="btn" disabled>
                                    <option value="">-- Selecciona ciclo --</option>
                                </select>
                            </div>
                        </div>

                        
                        <button type="button" class="btn hidden add-ciclo">+ Agregar Ciclo</button>
                        
                        <ul id="ciclosContainer" class="total-ciclos"></ul>
                    </div>

                    <div class="form-group full-width">
                        <label for="cvInput">Curriculum Vitae (PDF)</label>
                        <div class="cv-section">
                            <div class="file-input-wrapper">
                                <label class="file-input-button" for="cvInput">
                                    📄 Subir CV
                                </label>
                                <input type="file" id="cvInput" name="cv" accept=".pdf">
                                <div id="cvName" class="file-name"></div>
                            </div>
                            <div id="cvLinkContainer"></div>
                        </div>
                    </div>
                </div>

                <div class="btns-form">
                    <button type="button" class="btn" id="btnCancelar">Cancelar</button>
                    <button type="submit" class="btn guardar">Guardar Cambios</button>
                </div>
            </form>
        </div>
        <div class="velo hidden"></div>
        <div class="modal hidden">
            <input type="file" id="fotoInput" name="foto" accept="image/*">
            <div class="hidden connect-cam">
                <div id="ventana">
                    <div id="rec"></div>
                    <div id="rec3"></div>
                    <div id="rec4"></div>
                    <div id="rec2"></div>
                    <video id="video" playsinline autoplay></video>
                    <div id="recorte"></div>
                </div>
                <canvas id="canvas" width="358" height="238"></canvas>
            </div>

            
            <div class="botones">
                <button id="cerrarBtn" class="btn">Cerrar</button>
                <button id="resnap" class="hidden btn">Volver a Capturar</button>
                <button id="snap" class="btn">Capturar</button>
                <button class="conectar btn">Conectar cámara</button>
                <button id="saveImg" class="btn guardar">Guardar Imagen</button>
            </div>
        </div>
    </div>
<?= $this->stop()?>
<?= $this->start('footer')?>
<?= $this->insert('partials/footer')?>
<?= $this->stop()?>