// ============================================================================
// UTILIDAD: OBTENER TOKEN
// ============================================================================

/**
 * Obtiene el token desde sessionStorage
 * @returns {string|null} Token de autorización
 */
function obtenerToken() {
    return sessionStorage.getItem('token');
}

// ============================================================================
// EXTENSIÓN DE HTMLSelectElement CON AUTENTICACIÓN
// ============================================================================

HTMLSelectElement.prototype.loadFromApi = function(ruta) {
    this.disabled = false;
    let first = this.querySelector("option");
    this.innerHTML = "";
    if (first) this.appendChild(first);
    
    const token = obtenerToken();
    
    fetch(ruta, {
        method: 'GET',
        headers: {
            'AUTH': token
        }
    })
    .then((res) => res.text())
    .then((res) => JSON.parse(res))
    .then((texto) => {
        texto.forEach(element => {
            let option = document.createElement("option");
            option.classList.add(element.id ?? element.nombre);
            option.value = element.id ?? element.nombre;
            option.innerHTML = element.nombre + " <input type='hidden' value='" + element.id + "''>";
            this.appendChild(option);
        });
    });
    
    if (this.dataset.default === "true") return; /* Si alguien está intentando cargar recursos cuando he cancelado toda acción no intento cargar nada */
}

HTMLSelectElement.prototype.toDefault = function() {
    let first = this.querySelector("option");
    const copia = first.cloneNode(true); 
    this.innerHTML = "";
    this.appendChild(copia);
    this.disabled = true; /* Vuelvo a los valores por defecto */
    this.dataset.default = "true"; /* Indico una propiedad por si alguien cancela antes de cargar recursos */
}

/**
 * Configura selects en cascada: Familia -> Nivel -> Ciclo
 */

/**
 * Carga las familias profesionales en el select
 * @param {string} selectorId - ID del select (ej: "#familia")
 * @returns {HTMLElement} - Elemento select cargado
 */
function cargarFamilias(selectorId = "#familia") {
    const select = document.querySelector(selectorId);
    if (!select) {
        return null;
    }
    select.loadFromApi("../api/apiAlumno.php?menu=familias");
    return select;
}

/**
 * Configura la cascada Nivel -> Ciclo cuando cambia el nivel
 * @param {HTMLElement} selectNivel - Select de nivel
 * @param {HTMLElement} selectCiclo - Select de ciclo
 * @param {string} idFamily - ID de la familia seleccionada
 */
function configurarCascadaNivelCiclo(selectNivel, selectCiclo, idFamily) {
    selectNivel.onchange = function(e) {
        const nivelSeleccionado = e.target.selectedOptions[0].className;
        selectCiclo.loadFromApi(`../api/apiAlumno.php?menu=ciclos&id=${idFamily}&nivel=${nivelSeleccionado}`);
    };
}

/**
 * Configura la cascada Familia -> Nivel cuando cambia la familia
 * @param {HTMLElement} selectFamilia - Select de familia
 * @param {HTMLElement} selectNivel - Select de nivel
 * @param {HTMLElement} selectCiclo - Select de ciclo
 */
function configurarCascadaFamiliaNivel(selectFamilia, selectNivel, selectCiclo) {
    let idFamily;
    
    selectFamilia.onchange = function(e) {
        idFamily = e.target.selectedOptions[0].className;
        
        // Resetear selects dependientes
        selectNivel.toDefault();
        selectCiclo.toDefault();
        
        // Cargar niveles según familia
        selectNivel.loadFromApi(`../api/apiAlumno.php?menu=niveles&id=${idFamily}`);
        
        // Configurar cascada nivel->ciclo
        configurarCascadaNivelCiclo(selectNivel, selectCiclo, idFamily);
    };
}

/**
 * Configura todos los selects en cascada: Familia -> Nivel -> Ciclo
 * Esta es la función "orquestadora" que usa las funciones anteriores
 */
function configurarSelects() {
    // 1. Cargar familias
    const selectFamilia = cargarFamilias("#familia");
    
    // 2. Obtener referencias a los otros selects
    const selectNivel = document.querySelector("#nivel");
    const selectCiclo = document.querySelector("#ciclo");
    
    // 3. Configurar cascada familia->nivel->ciclo
    if (selectNivel && selectCiclo) {
        configurarCascadaFamiliaNivel(selectFamilia, selectNivel, selectCiclo);
    }
}



/**
 * Inserta un nuevo ciclo en el contenedor especificado si no existe previamente.
 * Crea un elemento <li> con inputs ocultos para el id y nombre del ciclo,
 * siguiendo el patrón de nombres ciclos[index][campo] para su procesamiento en el servidor.
 * También añade un botón de eliminar con funcionalidad para remover el elemento.
 * 
 * @param {string} texto - El nombre del ciclo que se mostrará en la lista
 * @param {string} id - El identificador único del ciclo
 * @param {HTMLElement} div - El contenedor DOM donde se insertará el nuevo elemento
 * @returns {void}
 */
function insertarEnDiv(texto,id,div){
    let bool=comprobarExistencia(id,div);
    if (!bool){
        let index = div.querySelectorAll('li').length;
        let li=document.createElement('li');
        let inputid=document.createElement('input');
        let inputname=document.createElement('input');
        inputid.type='hidden';
        inputname.type='hidden';
        inputid.value=id;
        inputname.value=texto;
        inputid.name = `ciclos[${index}][id]`;
        inputname.name = `ciclos[${index}][nombre]`;
        inputid.className='id';
        li.innerHTML=texto;
        let borrar=document.createElement("span");
        borrar.innerHTML="<img src='./assets/img/borrar.png'>";
        borrar.onclick=(e)=>e.target.parentElement.parentElement.remove();
        li.appendChild(inputid);
        li.appendChild(inputname);
        li.appendChild(borrar);
        div.appendChild(li);
    }
    
}

/**
 * Verifica si un ciclo con el id especificado ya existe en el contenedor.
 * Recorre todos los inputs con clase 'id' dentro del div y compara sus valores
 * con el id proporcionado utilizando un Set para la búsqueda.
 * 
 * @param {string} id - El identificador del ciclo a buscar
 * @param {HTMLElement} div - El contenedor DOM donde se buscará el ciclo
 * @returns {boolean} - Retorna true si el ciclo ya existe, false en caso contrario
 */
function comprobarExistencia(id,div){
    let inputs=div.querySelectorAll(".id");
    let ids=Array.from(inputs).map((div)=>div.value);
    let array=new Set(ids);
    let bool=false;
    if (array.has(id)){
        bool=true;
    }
    return bool;
}

/**
 * Configura el evento onclick para todos los botones de eliminar existentes en el contenedor.
 * Busca todos los elementos <li> dentro del div y asigna a cada botón (span) la funcionalidad
 * de eliminar su elemento padre. Útil para reactivar la funcionalidad en elementos
 * cargados desde el servidor o que ya existían en el DOM.
 * 
 * @param {HTMLElement} div - El contenedor DOM que contiene los elementos con botones de eliminar
 * @returns {void}
 */
function configurarBtnEliminar(div){
    if (div.hasChildNodes()){
        let lis=div.querySelectorAll('li');
        lis.forEach(li => {
            li.querySelector('span').onclick=(e)=>e.target.parentElement.parentElement.remove();
        });

    }
}


/**
 * Función principal que inicializa y coordina el sistema de gestión de ciclos.
 * Configura los selectores, activa los botones de eliminar para elementos existentes,
 * y asigna el evento al botón de agregar ciclo. Valida que se haya seleccionado un ciclo
 * válido antes de insertarlo y muestra mensajes de error cuando corresponde.
 * Utiliza encadenamiento opcional (?.) para eliminar mensajes de error previos de forma segura.
 * 
 * @returns {void}
 */
function activarSelects(){
    configurarSelects();
    const add_ciclo=document.querySelector(".add-ciclo");
    const total_ciclos=document.querySelector(".total-ciclos");
    if (add_ciclo){
        configurarBtnEliminar(total_ciclos);
        add_ciclo.onclick=function(e){
        e.preventDefault();
        document.querySelector('p.error')?.remove(); /* esto hace que se elimine si exite se llama encadenamiento opcional, sirve para acceder a una propiedad o método solo si el objeto existe*/
        const ciclo=document.querySelector('#ciclo');
        if (ciclo.disabled==false && ciclo.selectedOptions[0].disabled==false){
            let texto=ciclo.selectedOptions[0].textContent;
            let id=ciclo.selectedOptions[0].className;
            insertarEnDiv(texto,id,total_ciclos);
            console.log(ciclo.selectedOptions[0]);
        }else{
            let p=document.createElement('p');
            p.className="error";
            p.innerHTML="Tiene que elegir un ciclo";
            add_ciclo.insertAdjacentElement("afterend",p);
        }
    }
    }
    
}