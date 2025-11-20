// ============================================================================
// SECCIÓN 0: UTILIDAD PARA OBTENER TOKEN
// ============================================================================

/**
 * Obtiene el token desde sessionStorage
 * @returns {string|null} Token de autorización
 */
function obtenerToken() {
    return sessionStorage.getItem('token');
}

// ============================================================================
// SECCIÓN 1: INICIALIZACIÓN Y CARGA DE DATOS
// ============================================================================

/**
 * Carga inicial de la lista de alumnos al cargar la página
 * Obtiene la plantilla HTML y los datos de la API para renderizar la tabla principal
 */
window.addEventListener('load', cargarTablas)

function cargarTablas() {    
    const listaUsuario = document.querySelector('#listaUsuario');
    const token = obtenerToken();
    
    // Cargar plantilla HTML de la tabla
    fetch("./assets/plates/tabla.html")
        .then((x) => (x.text()))
        .then((plantilla) => {
            // Obtener listado de alumnos desde la API
            fetch("../api/apiAlumno.php?menu=listadoAlumnos", {
                headers: {
                    'AUTH': token
                }
            })
                .then((x) => x.text())
                .then((texto) => (JSON.parse(texto)))
                .then((datos) => {
                    pintarDatos(plantilla, datos, listaUsuario);
                    ordenarTablas();
                });
        })
}
// ============================================================================
// SECCIÓN 2: GESTIÓN DE MODALES Y BOTONES PRINCIPALES
// ============================================================================

/**
 * Configura los botones de carga masiva y carga individual de alumnos
 */


window.addEventListener('load', function () {

    // BOTÓN: Carga masiva de alumnos desde CSV
    let btnMasivo = document.querySelector(".carga-masiva");
    btnMasivo.onclick = function () {
        configurarSelects();
        // Abrir modal
        let modalDiv = document.querySelector(".modal");
        let modal = new Modal(modalDiv, document.querySelector(".velo"));
        modal.open();
        // Mostrar botones del modal
        let botones = modalDiv.querySelectorAll(".no-quitar");
        botones[0].classList.remove("hidden");
        eliminarElementosMenosElementos(modalDiv, botones);
        // Configurar botón de cerrar
        document.querySelector(".back").onclick = cerrarModal(modal);
        // Configurar carga de archivo CSV
        let btnCarga = document.querySelector(".cargas");
        manejarCarga(btnCarga, modalDiv);
        // Configurar botón de borrar contenido del modal
        let borrar = document.querySelector(".borrar");
        borrar.onclick = function () {
            let botones = modalDiv.querySelectorAll(".no-quitar");
            eliminarElementosMenosElementos(modalDiv, botones);
        }
    }
})

window.addEventListener('load',function(){
    let btnAlumno = document.querySelector(".carga-alumno");
    btnAlumno.onclick = function () {
        let modalDiv = document.querySelector(".modal");
        let modal = new Modal(modalDiv, document.querySelector(".velo"));
        let formModal = modalDiv.querySelector(".form-modal");

        modal.open();
        
        let todosLosBotones = modalDiv.querySelectorAll(".no-quitar");
        todosLosBotones.forEach(btnGroup => btnGroup.classList.add("hidden"));
        

        fetch("./assets/plates/formAlumno.html")
        .then((x)=>x.text())
        .then(x=>{
            eliminarElementosMenosElementos(modalDiv, todosLosBotones);
            formModal = modalDiv.querySelector(".form-modal");
            formModal.classList.remove("hidden");
            document.querySelectorAll('.botones').forEach(el => el.classList.add("hidden"));
            formModal.innerHTML=x;
            document.querySelector('.btn-volver').onclick=(e)=>{
                e.preventDefault(); 
                document.querySelector('.util-btns').classList.remove("hidden");
                formModal.innerHTML="";
                modal.close();
            };
            
            // 6. Configurar el botón "Guardar" del FORMULARIO
            document.querySelector('.btn-guardar').onclick=(e)=>saveAlumno(modalDiv,e);
            
            
        });
    }
})




// ============================================================================
// SECCIÓN 3: MANEJO DE CARGA DE ARCHIVOS CSV
// ============================================================================

/**
 * Gestiona la carga y procesamiento de archivos CSV
 * @param {HTMLElement} boton - Botón que ejecuta la carga
 * @param {HTMLElement} modalDiv - Elemento del modal donde se mostrará la tabla
 */
function manejarCarga(boton, modalDiv) {
    let inputFile = document.querySelector(".fichero");
    
    boton.onclick = () => {
        let fichero = inputFile.files[0];
        
        if (fichero) {
            let read = new FileReader();
            
            read.onload = () => {
                // Convertir CSV a array de objetos
                let datos = parseCSVToArray(read.result);
                
                // Configurar funcionalidad de edición de tabla
                editTable();

                // Cargar plantilla y pintar datos en tabla
                fetch("./assets/plates/preenvio.html")
                    .then((x) => (x.text()))
                    .then((plantilla) => {
                        pintarTabla(plantilla, datos, modalDiv);
                        
                        let tabla = modalDiv.querySelector("table");
                        
                        // Comprobar duplicados en correos
                        tabla.comprobarDuplicados(tabla.querySelector(".correo"));
                        
                        // Habilitar selección masiva
                        seleccionarTodos(modalDiv);
                        
                        // Configurar botón de guardar (clausura para capturar tabla)
                        document.querySelector(".save").onclick = function () {
                            preSave(tabla);
                        }
                    })
            }
            
            read.readAsText(fichero);
        }
    }
}

// ============================================================================
// SECCIÓN 4: FUNCIONALIDADES DE TABLA
// ============================================================================

/**
 * Habilita la funcionalidad de seleccionar/deseleccionar todos los checkboxes
 * @param {HTMLElement} div - Contenedor del modal con la tabla
 */
function seleccionarTodos(div) {
    let tabla = div.querySelector(".editable");
    $primera = false;
    
    tabla.querySelector(".seleccion_total").onclick = function () {
        // Alternar estado de todos los checkboxes
        tabla.querySelectorAll(".seleccion input").forEach((checkbox) => {
            if (tabla.seleccion) {
                checkbox.checked = false;
            } else {
                checkbox.checked = true;
            }
        })
        
        tabla.changeSeleccion();
    }
}

/**
 * Configura el botón de editar/cancelar edición de la tabla
 */
function editTable() {
    let botonEditar = document.querySelector(".editar");
    
    botonEditar.onclick = () => {
        let tabla = document.querySelector("table.editable");
        
        if (!tabla.editada) {
            botonEditar.innerHTML = "Cancelar Edición"
            tabla.editar();
        } else {
            botonEditar.innerHTML = "Editar";
            tabla.quitarEdicion();
        }
    }
}

// ============================================================================
// SECCIÓN 5: GESTIÓN DE MODAL
// ============================================================================

/**
 * Crea una función de clausura para cerrar el modal y resetear su estado
 * @param {Modal} modal - Instancia del modal a cerrar
 * @returns {Function} Función que cierra el modal
 */
function cerrarModal(modal) {
    return function () {
        // Resetear selects a valores por defecto
        let selects = document.querySelectorAll("select");
        selects.forEach((element) => element.toDefault())
        
        // Resetear botón de edición
        let botonEditar = document.querySelector(".editar");
        document.querySelector(".botones").classList.add("hidden");
        
        if (botonEditar.innerHTML != 'Editar') {
            botonEditar.innerHTML = 'Editar';
        }
        
        modal.close();
    }
}

// ============================================================================
// SECCIÓN 6: GUARDADO DE DATOS
// ============================================================================

/**
 * Prepara y envía los datos de alumnos seleccionados al servidor
 * @param {HTMLTableElement} tabla - Tabla con los datos de alumnos
 */
function preSave(tabla) {
    let botonEditar = document.querySelector(".editar");
    botonEditar.innerHTML = "Editar";
    tabla.quitarEdicion();
    
    /* Validar datos y una vez validados hacer lo siguiente: */
    
    // Obtener valores de familia y ciclo
    let familia = document.querySelector('#familia').value;
    let ciclo = document.querySelector('#ciclo').value;

    // Obtener filas seleccionadas (excluye duplicados y headers)
    let array = tabla.obtenerSeleccionados();
    let filasValidas = array.filter(fila => !fila.classList.contains('seccion-header'));
    
    // Mapear filas a objetos Alumno
    let alumnos = filasValidas.map(fila => {
        console.log(fila);
        let nombre = fila.querySelector('.nombre').innerHTML;
        let ap1 = fila.querySelector('.ap1').innerHTML;
        let ap2 = fila.querySelector('.ap2').innerHTML;
        let correo = fila.querySelector('.correo').innerHTML;
        let fechaNacimiento = fila.querySelector('.fechaNacimiento').innerHTML;
        let direccion = fila.querySelector('.direccion').innerHTML;
        
        return new Alumno(nombre, ap1, ap2, correo, fechaNacimiento, direccion, familia, ciclo);
    })
    
    // Preparar JSON para envío
    let json = JSON.stringify({ familia, ciclo, alumnos });
    const token = obtenerToken();
    
    // Enviar datos al servidor
    fetch('../api/apiAlumno.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'MOCK': true,
            'AUTH': token
        },
        body: json
    })
    .then((res) => res.json())
    .then((datos) => {
        // Mover filas subidas a la sección de "Alumnos Subidos"
        tabla.moveSubidos(datos, filasValidas);
        tabla.desplegar();
    })

    // Desplegar tabla para mostrar secciones colapsables
    tabla.desplegar();
}



// ============================================================================
// SECCIÓN 7: RENDERIZADO DE DATOS EN HTML
// ============================================================================

/**
 * Pinta la tabla de pre-envío con los datos del CSV
 * @param {string} plantilla - HTML de la plantilla de la tabla
 * @param {Array<Object>} datos - Array de objetos con datos de alumnos
 * @param {HTMLElement} elemento - Elemento donde se insertará la tabla
 */
function pintarTabla(plantilla, datos, elemento) {
    // Limpiar contenido previo del modal
    let botones = elemento.querySelectorAll(".no-quitar");
    eliminarElementosMenosElementos(elemento, botones);
    
    // Crear contenedor temporal
    let contenedor = document.createElement("div");
    contenedor.innerHTML = plantilla;
    console.log(contenedor);
    
    // Obtener referencias a la estructura de la tabla
    let padreInfo = contenedor.querySelector(".nombre").parentElement;
    let abueloInfo = padreInfo.parentElement;

    // Clonar y rellenar filas con datos
    let size = datos.length;
    for (let i = 0; i < size; i++) {
        let nuevo = padreInfo.cloneNode(true);
        nuevo.querySelector(".nombre").innerHTML = datos[i].nombre;
        nuevo.querySelector(".ap1").innerHTML = datos[i].ap1;
        nuevo.querySelector(".ap2").innerHTML = datos[i].ap2;
        nuevo.querySelector(".correo").innerHTML = datos[i].correo;
        nuevo.querySelector(".fechaNacimiento").innerHTML = datos[i].fechaNacimiento;
        nuevo.querySelector(".direccion").innerHTML = datos[i].direccion;
        abueloInfo.appendChild(nuevo);
    }
    
    // Eliminar fila plantilla
    padreInfo.remove();
    
    // Insertar contenido en el elemento destino
    while (contenedor.children.length > 0) {
        elemento.appendChild(contenedor.children[0]);
    }
}

/**
 * Pinta la tabla principal con el listado de alumnos existentes
 * @param {string} plantilla - HTML de la plantilla de la tabla
 * @param {Array<Object>} datos - Array de objetos con datos de alumnos
 * @param {HTMLElement} elemento - Elemento donde se insertará la tabla
 */
function pintarDatos(plantilla, datos, elemento) {
    // Limpiar contenido previo
    let botones = elemento.querySelectorAll(".no-quitar");
    eliminarElementosMenosElementos(elemento, botones);
    
    // Crear contenedor temporal
    let contenedor = document.createElement("div");
    contenedor.innerHTML = plantilla;
    
    // Obtener referencias a la estructura de la tabla
    let padreInfo = contenedor.querySelector(".id").parentElement;
    let abueloInfo = padreInfo.parentElement;
    
    // Clonar y rellenar filas con datos
    let size = datos.length;
    for (let i = 0; i < size; i++) {
        let nuevo = padreInfo.cloneNode(true);
        nuevo.querySelector(".id").innerHTML = datos[i].id;
        nuevo.querySelector(".nombre").innerHTML = datos[i].nombre;
        nuevo.querySelector(".ap1").innerHTML = datos[i].ap1;
        nuevo.querySelector(".ap2").innerHTML = datos[i].ap2;
        nuevo.querySelector(".correo").innerHTML = datos[i].correo;
        
        // Evento click para abrir modal de detalle del alumno
        nuevo.onclick = () => modalAlumno(nuevo);
        
        abueloInfo.appendChild(nuevo);
    }
    
    // Eliminar fila plantilla
    padreInfo.remove();
    
    // Insertar contenido en el elemento destino
    while (contenedor.children.length > 0) {
        elemento.appendChild(contenedor.children[0]);
    }
}

/**
 * Abre un modal con los datos detallados de un alumno
 */
function modalAlumno(tr) {
    const modalDiv = document.querySelector(".modal");
    const token = obtenerToken();
    
    // Limpiar modal
    let botones = modalDiv.querySelectorAll(".no-quitar");
    eliminarElementosMenosElementos(modalDiv, botones);
    
    // Abrir modal
    let modal = new Modal(modalDiv, document.querySelector(".velo"));
    modal.open();
    
    // Configurar botón cerrar
    let back = document.querySelector(".back");
    back.addEventListener('click', cerrarModal(modal));
    
    // Cargar plantilla y datos del alumno
    fetch("./assets/plates/datosAlumno.html")
        .then((x) => (x.text()))
        .then((plantilla) => {
            let id = tr.querySelector('.id').innerHTML;
            fetch("../api/apiAlumno.php?menu=alumno&id=" + id, {
                headers: {
                    'AUTH': token
                }
            })
                .then((x) => (x.text()))
                .then((texto) => JSON.parse(texto))
                .then((datos) => {
                    pintarAlumno(plantilla, datos, modalDiv);
                    let utils=document.querySelector('.util-btns');
                    utils.classList.add("hidden");
                    let cancelar=document.querySelector('.cancelar-modal');
                    cancelar.onclick=(e)=>{
                        e.preventDefault();
                        let botones = modalDiv.querySelectorAll(".no-quitar");
                        eliminarElementosMenosElementos(modalDiv, botones);
                        utils.classList.remove("hidden");
                        modal.close();
                    }
                    let update=document.querySelector('.update-alum');
                    update.onclick=(e)=>{
                        e.preventDefault();
                        let form=modalDiv.querySelector('.alumno');
                        const validator=new Validator(form);
                        if(validator.validateAll(false)){
                            fetch("../api/apiAlumno.php",{
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'AUTH': token,
                                    'ACCION':'ADMINEDIT'
                                },
                                body: JSON.stringify({
                                    id: form.querySelector('#id').value,
                                    nombre: form.querySelector('#nombre').value,
                                    ap1: form.querySelector('#ap1').value,
                                    ap2: form.querySelector('#ap2').value,
                                    correo: form.querySelector('#correo').value,
                                    fechaNacimiento: form.querySelector('#fechaNacimiento').value,
                                    direccion: form.querySelector('#direccion').value,
                                    passwd: form.querySelector('#passwd').value
                                })
                            })
                            .then((res)=>res.json())
                            .then((datos)=>{
                                if (datos.success) {
                                    let botones = modalDiv.querySelectorAll(".no-quitar");
                                    eliminarElementosMenosElementos(modalDiv, botones);
                                    utils.classList.remove("hidden");
                                    modal.close();
                                    console.log("OK:", datos);
                                    cargarTablas();
                                } else {
                                    let mensajeError = datos.error;
                                    let p=document.createElement('p');
                                    p.classList.add('error');
                                    p.innerHTML=mensajeError;
                                    form.appendChild(p);
                                    console.log(form);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error al actualizar el alumno');
                            });
                        }else{
                            validator.showErrors();
                        }
                    }
                    let eliminar=document.querySelector('.delete-alum');
                    eliminar.onclick=async (e)=>{
                        e.preventDefault();
                        let confirmar= confirm("¿Estás seguro de que deseas eliminar este alumno?");
                        if (confirmar){
                            fetch("../api/apiAlumno.php",{
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'AUTH': token,
                                    'ACCION':'alumno'
                                },
                                body: JSON.stringify({
                                    id: tr.querySelector('.id').innerHTML
                                })
                            })
                            .then((res)=>res.json())
                            .then((datos)=>{
                                if (datos.success) {
                                    let botones = modalDiv.querySelectorAll(".no-quitar");
                                    eliminarElementosMenosElementos(modalDiv, botones);
                                    utils.classList.remove("hidden");
                                    modal.close();
                                    console.log("OK:", datos);
                                    cargarTablas();
                                    
                                } else {
                                    console.log("Error:", datos.message);
                                    alert(datos.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error al eliminar el alumno');
                            });
                        }else{
                            return;
                        }
                    }
                    
                })
        })
}

/**
 * Pinta los datos de un alumno en el formulario del modal
 * @param {string} plantilla - HTML de la plantilla del formulario
 * @param {Object} datos - Objeto con datos del alumno
 * @param {HTMLElement} elemento - Elemento donde se insertará el formulario
 */
function pintarAlumno(plantilla, datos, elemento) {
    let contenedor = document.createElement("div");
    console.log(datos);
    contenedor.innerHTML = plantilla;
    
    // Rellenar campos del formulario
    contenedor.querySelector("#fotoAlumno").src = './assets/img/' + datos.foto;
    contenedor.querySelector("#id").value = datos.id;
    contenedor.querySelector("#nombre").value = datos.nombre;
    contenedor.querySelector("#ap1").value = datos.ap1;
    contenedor.querySelector("#ap2").value = datos.ap2;
    contenedor.querySelector("#correo").value = datos.correo;
    contenedor.querySelector("#direccion").value = datos.direccion;
    contenedor.querySelector("#fechaNacimiento").value = datos.fechaNacimiento.date.split(' ')[0];
    
    // Insertar formulario en el modal
    while (contenedor.children.length > 0) {
        elemento.appendChild(contenedor.children[0]);
    }
}

// ============================================================================
// SECCIÓN 8: UTILIDADES
// ============================================================================

/**
 * Elimina todos los hijos de un div excepto los especificados
 * @param {HTMLElement} div - Elemento padre
 * @param {NodeList|Array} elementos - Elementos que NO se deben eliminar
 */
function eliminarElementosMenosElementos(div, elementos) {
    let array = Array.from(div.children);
    let elementosPermitidos = Array.from(elementos);
    
    array.forEach((x) => {
        if (!elementosPermitidos.includes(x)) {
            x.remove();
        }
    });
}

/**
 * Convierte un CSV en un array de objetos
 * @param {string} csvText - Contenido del CSV (read.result)
 * @param {string} separator - Separador de columnas, por defecto ";"
 * @returns {Array<Object>} Array de objetos con propiedades según la cabecera
 */
function parseCSVToArray(csvText, separator = ';') {
    // Separar filas
    const rows = csvText.trim().split('\n');

    // Obtener cabeceras
    const headers = rows.shift().split(separator).map(h => h.trim());

    // Convertir cada fila en objeto
    const data = rows.map(row => {
        const values = row.split(separator).map(v => v.trim());
        const obj = {};
        headers.forEach((header, index) => {
            obj[header] = values[index] || null; // null si no hay valor
        });
        return obj;
    });

    return data;
}

