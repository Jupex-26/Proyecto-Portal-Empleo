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