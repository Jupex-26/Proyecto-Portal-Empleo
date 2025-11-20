// ============================================================================
// SECCIÓN 1: ORDENAMIENTO DE TABLAS
// ============================================================================

/**
 * Inicializa la funcionalidad de ordenamiento para todas las tablas con clase "ordenable"
 * Permite ordenar columnas de forma ascendente/descendente al hacer click en los encabezados
 */
function ordenarTablas() {
    let tablas = document.querySelectorAll("table.ordenable");
    let sizeTable = tablas.length;
    
    // Iterar sobre todas las tablas ordenables
    for (let x = 0; x < sizeTable; x++) {
        let tabla = tablas[x];
        let ths = tabla.querySelectorAll("th");
        let size = ths.length;
        
        // Configurar evento click en cada encabezado
        for (let i = 0; i < size; i++) {
            ths[i].onclick = function() {
                // Alternar dirección de ordenamiento (ascendente/descendente)
                if (ths[i].children[0].classList.contains("row-up")) {
                    ths[i].orden = 1;  // Orden descendente
                    ths[i].children[0].classList.remove("row-up");
                    ths[i].children[0].classList.add("row-down");
                } else if (ths[i].children[0].classList.contains("row-down")) {
                    ths[i].orden = -1;  // Orden ascendente
                    ths[i].children[0].classList.add("row-up");
                    ths[i].children[0].classList.remove("row-down");
                }
                
                // Determinar tipo de ordenamiento según clase del th
                let tipo = "";
                if (ths[i].classList.contains("lexico")) {
                    tipo = "lexico";  // Ordenamiento alfabético
                } else if (ths[i].classList.contains("numero")) {
                    tipo = "numero";  // Ordenamiento numérico
                }
                
                // Ejecutar ordenamiento
                tabla.ordenar({column: i, type: tipo, orden: ths[i].orden});
            }
        }
    }
}

/**
 * Ordena las filas de una tabla según columna, tipo y dirección especificados
 * @param {Object} props - Propiedades del ordenamiento
 * @param {number} props.column - Índice de la columna a ordenar
 * @param {string} props.type - Tipo de ordenamiento ("lexico" o "numero")
 * @param {number} props.orden - Dirección del ordenamiento (1 o -1)
 */
HTMLTableElement.prototype.ordenar = function(props) {
    let tbody = this.tBodies[0];
    let trs = Array.from(tbody.rows);
    let orden = props.orden;
    let col = props.column;
    let typ = props.type;
    
    // Aplicar función de ordenamiento según el tipo
    switch(typ) {
        case "lexico":
            trs.sort(ordenTrsTexto(col, orden));
            break;
        case "numero":
            trs.sort(ordenTrsNumero(col, orden));
            break;
    }
    
    // Re-insertar filas ordenadas en el tbody
    for (let i = 0; i < trs.length; i++) {
        tbody.appendChild(trs[i]);
    }
};

/**
 * Genera función de comparación para ordenamiento léxico/alfabético
 * @param {number} col - Índice de columna
 * @param {number} orden - Dirección (1 o -1)
 * @returns {Function} Función comparadora para Array.sort()
 */
function ordenTrsTexto(col, orden) {
    return function(a, b) {
        return orden * (a.cells[col].innerText.localeCompare(b.cells[col].innerText));
    }
}

/**
 * Genera función de comparación para ordenamiento numérico
 * @param {number} col - Índice de columna
 * @param {number} orden - Dirección (1 o -1)
 * @returns {Function} Función comparadora para Array.sort()
 */
function ordenTrsNumero(col, orden) {
    return function(a, b) {
        return orden * (a.cells[col].innerText - b.cells[col].innerText);
    }
}

// ============================================================================
// SECCIÓN 2: EDICIÓN DE FILAS INDIVIDUALES
// ============================================================================

/**
 * Convierte las celdas de una fila en campos editables (inputs)
 * Guarda el valor original en data-attributes para poder cancelar cambios
 */
HTMLTableRowElement.prototype.editar = function() {
    let celdas = this.querySelectorAll("td"); /* this.cells; sirve igual */
    let size = celdas.length;
    
    for (let i = 0; i < size; i++) {
        // Eliminar mensajes de error previos
        let p = this.querySelector('.error');
        if (p) p.remove();
        
        // Solo editar celdas que contienen texto plano (no inputs u otros elementos)
        if (celdas[i].innerHTML == celdas[i].innerText) {
            // Guardar valor original en data-attribute
            celdas[i].setAttribute('data-' + celdas[i].className, celdas[i].innerHTML);
            
            let texto = celdas[i].innerText;
            
            // Crear input con el valor actual
            let input = document.createElement("input");
            input.type = "text";
            input.value = texto;
            
            // Reemplazar contenido por input
            celdas[i].innerHTML = "";
            celdas[i].appendChild(input);
            
            // Guardar valor anterior para poder cancelar
            celdas[i].valorAnterior = texto;
        }
    }
}

/**
 * Guarda los cambios realizados en una fila editable
 * Convierte los inputs de vuelta a texto
 */
HTMLTableRowElement.prototype.guardar = function() {
    let inputs = this.querySelectorAll("input[type=text]");
    let size = inputs.length;
    this.classList.remove('duplicado');
    for (let i = 0; i < size; i++) {
        // Reemplazar input por su valor
        inputs[i].parentElement.innerHTML = inputs[i].value;
    }
}

/**
 * Cancela la edición de una fila
 * Restaura los valores originales
 */
HTMLTableRowElement.prototype.cancelar = function() {
    let inputs = this.querySelectorAll("input[type=text]");
    let size = inputs.length;
    
    for (let i = 0; i < size; i++) {
        // Restaurar valor anterior
        inputs[i].parentElement.innerHTML = inputs[i].parentElement.valorAnterior;
    }
}

/**
 * Elimina una fila de la tabla
 */
HTMLTableRowElement.prototype.delete = function() {
    this.remove();
}

// Propiedad para indicar si una fila está en modo edición
HTMLTableRowElement.prototype.editada = false;

// ============================================================================
// SECCIÓN 3: EDICIÓN DE TABLA COMPLETA
// ============================================================================

/**
 * Activa el modo de edición para toda la tabla
 * Añade columna con botones de editar/guardar/cancelar/borrar para cada fila
 */
HTMLTableElement.prototype.editar = function() {
    this.editada = true;
    let tbody = this.querySelector('.request');
    let tr_thead = this.querySelector('thead').querySelector('tr');
    let trs = Array.from(tbody.rows);
    let size = trs.length;
    
    // Añadir encabezado de columna "Editar"
    let th = document.createElement("th");
    th.classList = "edit";
    th.innerHTML = 'Editar';
    tr_thead.appendChild(th);
    
    // Añadir celda con botones de acción a cada fila
    for (let i = 0; i < size; i++) {
        // Saltar filas de encabezado de sección
        if (!trs[i].classList.contains('seccion-header')) {
            let td = trs[i].insertCell();
            td.classList = "edit";
            
            // BOTÓN: Cancelar edición (inicialmente oculto)
            let btnCancel = document.createElement("span");
            btnCancel.classList = "btn-cancel";
            btnCancel.style.display = "none";
            td.appendChild(btnCancel);
            
            // BOTÓN: Guardar cambios (inicialmente oculto)
            let btnSave = document.createElement("span");
            btnSave.classList = "btn-save";
            btnSave.style.display = "none";
            td.appendChild(btnSave);
            
            // BOTÓN: Borrar fila (visible por defecto)
            let btnBorrar = document.createElement("span");
            btnBorrar.classList = "btn-borrar";
            btnBorrar.style.display = "inline-block";
            td.appendChild(btnBorrar);
            
            btnBorrar.onclick = function() {
                this.parentElement.parentElement.delete();
            }
            
            // BOTÓN: Editar fila (visible por defecto)
            let btnEdit = document.createElement("span");
            btnEdit.classList = "btn-editar";
            btnEdit.style.display = "inline-block";
            td.appendChild(btnEdit);
            
            // Al hacer click en editar: mostrar guardar/cancelar, ocultar editar/borrar
            btnEdit.onclick = function() {
                this.style.display = "none";
                btnBorrar.style.display = "none";
                btnSave.style.display = "inline-block";
                btnCancel.style.display = "inline-block";
                this.parentElement.parentElement.editar();
            }
            
            // Al hacer click en cancelar: restaurar y volver a mostrar editar/borrar
            btnCancel.onclick = function() {
                this.parentElement.parentElement.cancelar();
                this.style.display = "none";
                btnSave.style.display = "none";
                btnEdit.style.display = "inline-block";
                btnBorrar.style.display = "inline-block";
            }
            
            // Al hacer click en guardar: guardar cambios y volver a mostrar editar/borrar
            btnSave.onclick = function() {
                /* let bool=validarErrores(this.parentElement.parentElement); */
                
                    this.parentElement.parentElement.guardar();
                    this.style.display = "none";
                    btnCancel.style.display = "none";
                    btnEdit.style.display = "inline-block";
                    btnBorrar.style.display = "inline-block";
                
            }
        }
    }
};

/* TO-DO */
function validarErrores(tr){
    let inputs = tr.querySelectorAll("input[type=text]");
    let form = document.createElement('form');
    form.append(...inputs);
    console.log(form);
    let size = inputs.length;
    let valido=true;
    
    for (let i = 0; i < size; i++) {
        let clase=inputs[i].parentElement.className;
        let valor=inputs[i].value;
        let validator=new Validator();
        switch(clase){
            case "nombre":
                validator.validate("nombre",valor,true,50);
                break;
            case "ap1":
                validator.validate("apellido",valor,true,50);
                break;
            case "ap2":
                validator.validate("apellido",valor,false,50);
                break;
            case "direccion":
                validator.validate("direccion",valor,true,100);
                break;
            case "correo":
                validator.validate("email",valor,true,100);
                break;
            case "fechaNacimiento":
                validator.validate("fechaNacimiento",valor,true,null);
                break;
        }
    }
    return valido;
}
/**
 * Desactiva el modo de edición de la tabla
 * Cancela todos los cambios pendientes y elimina la columna de botones
 */
HTMLTableElement.prototype.quitarEdicion = function() {
    this.editada = false;
    
    // Cancelar edición en todas las filas
    let trs = Array.from(this.querySelector('.request').children);
    trs.map((tr) => tr.cancelar());
    
    // Eliminar columna de edición
    let array = document.querySelectorAll(".edit");
    array.forEach(element => {
        element.remove();
    });
}

// ============================================================================
// SECCIÓN 4: SELECCIÓN DE FILAS
// ============================================================================

// Propiedad para rastrear estado de selección masiva
HTMLTableElement.prototype.seleccion = false;

/**
 * Alterna el estado de selección masiva de la tabla
 */
HTMLTableElement.prototype.changeSeleccion = function() {
    if (this.seleccion) {
        this.seleccion = false;
    } else {
        this.seleccion = true;
    }
}

/**
 * Obtiene todas las filas seleccionadas (con checkbox marcado)
 * Excluye filas duplicadas
 * @returns {Array<HTMLTableRowElement>} Array de filas seleccionadas y válidas
 */
HTMLTableElement.prototype.obtenerSeleccionados = function() {
    let tbody = this.querySelector('.request');
    let checkboxes = tbody.querySelectorAll('input[type="checkbox"]:checked');
    
    if (checkboxes.length > 0) {
        // Convertir checkboxes a filas y filtrar duplicadas
        let seleccionados = Array.from(checkboxes)
            .map(checkbox => checkbox.parentElement.parentElement)
            .filter(fila => !fila.classList.contains('duplicado'));
        
        return seleccionados;
    } else {
        return [];
    }
}

// ============================================================================
// SECCIÓN 5: VALIDACIÓN Y DETECCIÓN DE DUPLICADOS
// ============================================================================

/**
 * Comprueba si hay valores duplicados en una columna específica
 * Marca las filas duplicadas con clase "duplicado" y añade mensaje de error
 * @param {HTMLElement} campo - Celda de referencia para determinar qué columna verificar
 * @returns {boolean} true si hay duplicados, false en caso contrario
 */
HTMLTableElement.prototype.comprobarDuplicados = function(campo) {
    let tbody = campo.parentElement.parentElement;
    let clase = "." + campo.classList[0];
    
    // Filtrar solo filas de datos (excluir encabezados de sección)
    let filas = Array.from(tbody.children).filter(child => 
        child.tagName === 'TR' && !child.classList.contains('seccion-header')
    );
    
    let nombres = new Set();  // Set para detectar duplicados
    let valido = false;
    
    filas.forEach((fila) => {
        let td = fila.querySelector(clase);
        let nombre = td.innerHTML;
        
        // Si el valor ya existe, marcar como duplicado
        if (nombres.has(nombre)) {
            fila.classList.add("duplicado");
            td.innerHTML += "<p class='error'>Este campo está duplicado</p>";
            valido = true;
        } else {
            nombres.add(nombre);
        }
    });
    
    return valido;
}

// ============================================================================
// SECCIÓN 6: GESTIÓN DE FILAS SUBIDAS (RESPONSE)
// ============================================================================

/**
 * Mueve las filas válidas desde la sección "request" a la sección "response"
 * Verifica contra el servidor si los correos ya existen
 * @param {Array<string>} datos - Array de correos que ya existen en el servidor
 * @param {Array<HTMLTableRowElement>} datosSubidos - Filas a procesar
 */
HTMLTableElement.prototype.moveSubidos = function(datos, datosSubidos) {
    let response = this.querySelector('.response');
    let trs = Array.from(datosSubidos);
    let total_subidos=datosSubidos.length-datos.length;
    let header=response.querySelector('.seccion-header');
    let conteo=header.querySelector('.conteo');
    conteo.innerHTML="("+total_subidos+")";
    // Procesar en orden inverso para mantener el orden visual correcto
    trs.reverse().forEach((tr) => {
        let correo = tr.querySelector('.correo');
        
        // Verificar si el correo ya existe en el servidor
        if (datos.includes(correo.innerHTML)) {
            correo.innerHTML += "<p class='error'>Este correo ya existe</p>";
            tr.classList.add('duplicado');
        } 
        // Si no es duplicado, mover a la sección de "Subidos"
        else if (!tr.classList.contains('duplicado')) {
            // Deshabilitar checkbox de filas subidas exitosamente
            tr.querySelector('input[type="checkbox"]').disabled = true;
            response.appendChild(tr);
        }
    })
}

/**
 * Añade funcionalidad de colapsar/expandir a la sección "response"
 * Permite ocultar/mostrar las filas de alumnos ya subidos
 */
HTMLTableElement.prototype.desplegar = function() {
    let tbody = this.querySelector('.response');
    let desplegar = tbody.querySelector('.seccion-header');
    
    desplegar.onclick = function() {
        let span = desplegar.querySelector('span');
        
        // Alternar icono de flecha
        if (span.classList.contains("row-up")) {
            span.classList.remove("row-up");
            span.classList.add("row-down");
        } else if (span.classList.contains("row-down")) {
            span.classList.add("row-up");
            span.classList.remove("row-down");
        }
        
        // Alternar visibilidad de las filas (excepto el encabezado)
        Array.from(tbody.children).forEach((fila) => {
            if (fila !== desplegar) {
                fila.classList.toggle('hidden');
            }
        });
    }
}
