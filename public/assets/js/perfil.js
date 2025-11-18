

// Datos de ejemplo - en producción vendrían del servidor/API
window.addEventListener('load',function(){
    const userJSON = sessionStorage.getItem('user');
    const token = sessionStorage.getItem('token');
    if (userJSON) {
        const datosUsuario = JSON.parse(userJSON);
        
        console.log(datosUsuario);
        cargarDatos(datosUsuario, token);
    }
})
    

// ============================================================================
// GESTIÓN DE CICLOS
// ============================================================================


/**
 * Agrega un ciclo seleccionado al listado
 */
function agregarCiclo() {
    const selectCiclo = document.querySelector('#ciclo');
    const selectedOption = selectCiclo.selectedOptions[0];
    
    if (!selectedOption || !selectedOption.value) {
        alert('Por favor, selecciona un ciclo');
        return;
    }
    
    const id = selectedOption.className;
    const nombre = selectedOption.value;
    const div = document.querySelector('#ciclosContainer');
    
    insertarEnDiv(nombre, id, div);
}

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
        configurarListenersPerfil();
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
        mostrarEnlaceCV();
    } 
}

/* function toggleEditMode() {
    editMode = !editMode;
    const form = document.getElementById('profileForm');
    const toggleBtn = document.getElementById('toggleMode');
    const passwordFields = document.querySelector('.password-fields');
    const addCicloSection = document.querySelector('.add-ciclo-section');
    const btnAgregarCiclo = document.querySelector('.btn-agregar-ciclo');
    
    if (editMode) {
        form.classList.remove('view-mode');
        toggleBtn.textContent = '👁️ Ver Perfil';
        passwordFields.classList.remove('hidden');
        addCicloSection.classList.remove('hidden');
        btnAgregarCiclo.classList.remove('hidden');
    } else {
        form.classList.add('view-mode');
        toggleBtn.textContent = '✏️ Editar Perfil';
        passwordFields.classList.add('hidden');
        addCicloSection.classList.add('hidden');
        btnAgregarCiclo.classList.add('hidden');
    }
} */

/* function handlePhotoChange(event) {
    const file = event.target.files[0];
    if (file) {
        document.getElementById('fotoName').textContent = 'Seleccionado: ' + file.name;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            fotosData.preview = e.target.result;
            fotosData.ruta = file.name;
            mostrarFoto(e.target.result);
        };
        reader.readAsDataURL(file);
    }
} */

function mostrarFoto(src) {
    const preview = document.getElementById('photoPreview');
    const placeholder = document.getElementById('photoPlaceholder');
    
    preview.src = './assets/img/'+ src;
    preview.classList.remove('hidden');

    placeholder.classList.add('hidden');
}

/* function handleCVChange(event) {
    const file = event.target.files[0];
    if (file) {
        cvData.nombre = file.name;
        cvData.ruta = file.name;
        document.getElementById('cvName').textContent = 'Seleccionado: ' + file.name;
        mostrarEnlaceCV();
    }
} */

/* function mostrarEnlaceCV() {
    const container = document.getElementById('cvLinkContainer');
    if (cvData.ruta) {
        container.innerHTML = `
            <a href="${cvData.ruta}" target="_blank" class="cv-link">
                📄 Ver CV actual: ${cvData.nombre}
            </a>
        `;
    } else {
        container.innerHTML = '';
    }
} */

/* function cancelar() {
    cargarDatos();
    if (editMode) {
        toggleEditMode();
    }
} */

// ============================================================================
// EVENT LISTENERS
// ============================================================================





function configurarListenersPerfil() {
    const changeImg=document.querySelector('.file-input-button');
    configurarCambiarImg(changeImg);
    
}




function configurarCambiarImg(changeImg) {
    let modalDiv=document.querySelector('.modal');
    let overlayDiv=document.querySelector('.velo');
    let modal=new Modal(modalDiv,overlayDiv);
    changeImg.addEventListener('click',function(e){
        e.preventDefault();
        modal.open();
        console.log(modalDiv);
        console.log(changeImg);
        let conect=modalDiv.querySelector('.conectar');
        const cerrarBtn=modalDiv.querySelector('#cerrarBtn').onclick=()=>{
            modal.close();
            apagarCamara();
        };
        conect.addEventListener('click', function() {
            conectarcamara();
            const save=modalDiv.querySelector('#saveImg');
            save.onclick=()=>{
                console.log('guardar');
                guardarImg();
            }
        });
    });
}
/* 





document.addEventListener('DOMContentLoaded', function() {
   
    
    // Configurar los selects en cascada
    configurarSelects();
    
    // Event listener para toggle edit mode
    document.getElementById('toggleMode').addEventListener('click', toggleEditMode);
    
    // Event listener para agregar ciclo
    document.querySelector('.btn-agregar-ciclo').addEventListener('click', agregarCiclo);
    
    // Event listener para cancelar
    document.getElementById('btnCancelar').addEventListener('click', cancelar);
    
    // Event listener para cambio de foto
    document.getElementById('fotoInput').addEventListener('change', handlePhotoChange);
    
    // Event listener para cambio de CV
    document.getElementById('cvInput').addEventListener('change', handleCVChange);
    
    // Event listener para submit del formulario
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Aquí se enviaría el formulario al servidor
        // El FormData incluirá automáticamente todos los inputs hidden de ciclos
        const formData = new FormData(this);
        
        console.log('Datos del formulario:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        
        alert('Perfil actualizado correctamente');
        
        if (editMode) {
            toggleEditMode();
        }
    });
}); */