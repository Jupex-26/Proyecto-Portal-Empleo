

// Datos de ejemplo - en producción vendrían del servidor/API
window.addEventListener('load',function(){
    const userJSON = sessionStorage.getItem('user');
    const token = sessionStorage.getItem('token');
    if (userJSON) {
        const datosUsuario = JSON.parse(userJSON);
        configurarToggleEdicion();
        cargarDatos(datosUsuario, token);
    }
})
    

// ============================================================================
// GESTIÓN DE CICLOS
// ============================================================================




/**
 * Carga los ciclos existentes del usuario
 */
function cargarCiclosExistentes(datosUsuario) {
    const div = document.querySelector('#ciclosContainer');
    div.innerHTML = ''; // Limpiar primero
    
    datosUsuario.ciclos.forEach(ciclo => {
        insertarEnDiv(ciclo.nombre, ciclo.id, div);
        div.querySelector('input[value="' + ciclo.id + '"]').parentElement.querySelector('span').remove(); // Deshabilitar input hidden

    });
}

// ============================================================================
// GESTIÓN DE DATOS DEL FORMULARIO
// ============================================================================

function cargarDatos(datosUsuario, token) {
    fetch('../api/apiAlumno.php?menu=alumno&id=' + datosUsuario.id,{ 
        method: 'GET',
        headers: {
            'AUTH':'Bearer ' + token
        }
    })
    .then(response => response.json())
    .then(data => {
        rellenarFormulario(data)
        configurarListenersPerfil(data);
        configurarGuardarCambios(data, token);
    });
    
}




function rellenarFormulario(data) {
    document.getElementById('nombre').value = data.nombre;
    document.getElementById('correo').value = data.correo;
    document.getElementById('direccion').value = data.direccion;
    document.getElementById('ap1').value = data.ap1;
    document.getElementById('ap2').value = data.ap2;
    document.getElementById('fechaNacimiento').value = data.fechaNacimiento.date.substring(0, 10);
    
    cargarCiclosExistentes(data);
    
    if (data.foto) {
        mostrarFoto(data.foto);
    }
    if (data.cv) {
        mostrarEnlaceCV(data.cv);
    } 
}



function mostrarFoto(src) {
    const preview = document.getElementById('photoPreview');
    const placeholder = document.getElementById('photoPlaceholder');
    
    preview.src = './assets/img/'+ src;
    preview.classList.remove('hidden');

    placeholder.classList.add('hidden');
}


 function mostrarEnlaceCV(cvData) {
    const container = document.getElementById('cvLinkContainer');
    if (cvData) {
        container.innerHTML = `
            <button type="button" data-cv="../../cvs/${cvData}" class="cv-link">
                📄 Ver CV actual
            </button>
        `;
    } else {
        container.innerHTML = '';
    }
} 


// ============================================================================
// EVENT LISTENERS
// ============================================================================



// Función para toggle entre modo vista y edición
function configurarToggleEdicion() {
    const toggleBtn = document.getElementById('toggleMode');
    const form = document.getElementById('profileForm');
    const inputs = form.querySelectorAll('input:not([type="file"]):not([type="hidden"]), select');
    const passwordFields = document.querySelector('.password-fields');
    const addCicloSection = document.querySelector('.add-ciclo-section');
    const btnAgregarCiclo = document.querySelector('.add-ciclo');
    
    let modoEdicion = false;

    toggleBtn.addEventListener('click', function() {
        modoEdicion = !modoEdicion;
        
        if (modoEdicion) {
            // Cambiar a modo EDICIÓN
            toggleBtn.textContent = '👁️ Ver Perfil';
            
            // Habilitar inputs
            inputs.forEach(input => {
                input.disabled = false;
            });
            
            // Mostrar campos de contraseña
            passwordFields.classList.remove('hidden');
            
            // Mostrar sección de agregar ciclos
            addCicloSection.classList.remove('hidden');
            btnAgregarCiclo.classList.remove('hidden');
            
            // Agregar botones de borrar a ciclos existentes
            const ciclosContainer = document.getElementById('ciclosContainer');
            const ciclos = ciclosContainer.querySelectorAll('li');
            
            ciclos.forEach(li => {
                if (!li.querySelector('span')) {
                    let borrar = document.createElement("span");
                    borrar.innerHTML = "<img src='./assets/img/borrar.png' alt='Borrar'>";
                    borrar.onclick = (e) => e.target.closest('li').remove();
                    li.appendChild(borrar);
                } else {
                    li.querySelector('span').classList.remove('hidden');
                }
            });
            

            
        } else {
            // Cambiar a modo VISTA
            toggleBtn.textContent = '✏️ Editar Perfil';
            
            // Deshabilitar inputs
            inputs.forEach(input => {
                input.disabled = true;
            });
            
            // Ocultar campos de contraseña
            passwordFields.classList.add('hidden');
            
            // Ocultar sección de agregar ciclos
            addCicloSection.classList.add('hidden');
            btnAgregarCiclo.classList.add('hidden');
            
            // Ocultar botones de borrar ciclos
            const ciclosContainer = document.getElementById('ciclosContainer');
            const botonesBorrar = ciclosContainer.querySelectorAll('li span');
            
            botonesBorrar.forEach(boton => {
                boton.classList.add('hidden');
            });
        }
    });
}


function configurarListenersPerfil(data) {
    const changeImg = document.querySelector('.file-input-button');
    configurarCambiarImg(changeImg, data);
    
    // Listener para cuando se selecciona una foto con el input (está en la modal)
    const fotoInput = document.getElementById('fotoInput');
    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                
                // Solo guardar el archivo, sin actualizar preview
                data.fotoFile = file;
            }
        });
    }
    
    // Listener para mostrar nombre del CV seleccionado
    const cvInput = document.getElementById('cvInput');
    if (cvInput) {
        cvInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const cvName = document.getElementById('cvName');
            if (file) {
                cvName.textContent = file.name;
            }
        });
    }
    let cvlink = document.querySelector('.cv-link');
    cvlink.onclick = function(e) { 
        e.preventDefault();
        let modalDiv = document.querySelector('.modal-cv');
        let overlayDiv = document.querySelector('.velo');
        let modal = new Modal(modalDiv, overlayDiv);
        modal.open();
        let iframe= modalDiv.querySelector('iframe');
        iframe.classList.remove('hidden');
        iframe.src=cvlink.getAttribute('data-cv');
        console.log(iframe);
        let cerrarBtn = modalDiv.querySelector('#cerrarCv');
        cerrarBtn.onclick = () => {
            iframe.classList.add('hidden');
            iframe.src="";
            modal.close();
        };
    }
}

function configurarCambiarImg(changeImg, data) {
    let modalDiv = document.querySelector('.modal');
    let overlayDiv = document.querySelector('.velo');
    let modal = new Modal(modalDiv, overlayDiv);
    
    changeImg.addEventListener('click', function(e) {
        e.preventDefault();
        modal.open();
        
        let conect = modalDiv.querySelector('.conectar');
        const cerrarBtn = modalDiv.querySelector('#cerrarBtn');
        
        cerrarBtn.onclick = () => {
            modal.close();
            apagarCamara();
        };
        
        let save = modalDiv.querySelector('#saveImg');
        
        save.onclick = () => {
            const foto = window.fotoCapturada;
            if (foto) {
                // Solo guardar el blob, sin actualizar preview
                data.foto = foto;
            }
            modal.close();
            apagarCamara();
        };
        
        // Si conecta la cámara
        conect.onclick = () => {
            conectarcamara();
        };
    });
}

// Función para guardar cambios
function configurarGuardarCambios(datosUsuario, token) {
    const form = document.getElementById('profileForm');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Crear objeto con todos los datos
        const datos = {
            id: datosUsuario.id,
            nombre: document.getElementById('nombre').value,
            ap1: document.getElementById('ap1').value,
            ap2: document.getElementById('ap2').value,
            correo: document.getElementById('correo').value,
            fechaNacimiento: document.getElementById('fechaNacimiento').value,
            direccion: document.getElementById('direccion').value
        };
        
        // Agregar contraseñas si se proporcionaron
        const passwdActual = document.getElementById('passwdActual').value;
        const passwdNueva = document.getElementById('passwdNueva').value;
        
        if (passwdActual) {
            datos.passwdActual = passwdActual;
        }
        if (passwdNueva) {
            datos.passwdNueva = passwdNueva;
        }
        
        // Agregar ciclos
        const ciclosContainer = document.getElementById('ciclosContainer');
        const ciclos = [];
        const ciclosLi = ciclosContainer.querySelectorAll('li');
        
        ciclosLi.forEach(li => {
            const id = li.querySelector('input.id').value;
            const nombre = li.querySelector('input[name*="[nombre]"]').value;
            
            ciclos.push({
                id: id,
                nombre: nombre
            });
        });
        
        datos.ciclos = ciclos;
        
        // Función auxiliar para procesar la foto
        const procesarFoto = () => {
            return new Promise((resolve) => {
                const fotoInput = document.getElementById('fotoInput');
                
                // Si se seleccionó una foto desde el input de archivo
                if (fotoInput && fotoInput.files && fotoInput.files[0]) {
                    const reader = new FileReader();
                    reader.readAsDataURL(fotoInput.files[0]);
                    reader.onloadend = function() {
                        datos.foto = reader.result;
                        resolve();
                    };
                }
                // Si hay foto capturada con la cámara (blob)
                else if (datosUsuario.foto && datosUsuario.foto instanceof Blob) {
                    const reader = new FileReader();
                    reader.readAsDataURL(datosUsuario.foto);
                    reader.onloadend = function() {
                        datos.foto = reader.result;
                        resolve();
                    };
                } 
                // Si hay referencia guardada de input (fotoFile)
                else if (datosUsuario.fotoFile && datosUsuario.fotoFile instanceof File) {
                    const reader = new FileReader();
                    reader.readAsDataURL(datosUsuario.fotoFile);
                    reader.onloadend = function() {
                        datos.foto = reader.result;
                        resolve();
                    };
                }
                else {
                    // NO enviar nada - mantener la foto actual en BD
                    resolve();
                }
            });
        };
        
        // Función auxiliar para procesar el CV
        const procesarCV = () => {
            return new Promise((resolve) => {
                const cvInput = document.getElementById('cvInput');
                if (cvInput && cvInput.files && cvInput.files[0]) {
                    const cvReader = new FileReader();
                    cvReader.readAsDataURL(cvInput.files[0]);
                    cvReader.onloadend = function() {
                        datos.cv = cvReader.result;
                        resolve();
                    };
                } else {
                    resolve();
                }
            });
        };
        
        // Procesar foto y CV en paralelo
        await Promise.all([procesarFoto(), procesarCV()]);
        
        
        // Enviar los datos
        enviarFetch(datos, token);
    });
}

function enviarFetch(datos, token) {
    fetch('../api/apiAlumno.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'MOCK': false,
            'AUTH': token,
            'ACCION':'ALUMNO'
        },
        body: JSON.stringify(datos)
    })
    .then((res) => res.json())
    .then((resultado) => {
        if (resultado.success) {
            alert('✅ Perfil actualizado correctamente');
            location.reload();
        } else {
            alert('❌ Error: ' + resultado.error);
        }
    })
    .catch((error) => {
        console.error('Error al guardar:', error);
        alert('❌ Error al guardar los cambios');
    });
}