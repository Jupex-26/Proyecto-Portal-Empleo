// ============================================================================
// SECCIÓN 1: CARGA INICIAL DE OFERTAS SOLICITADAS
// ============================================================================

/**
 * Carga las ofertas solicitadas por el user al cargar la página
 */
window.addEventListener('load', function() {
    const userJSON = sessionStorage.getItem('user');
    
    if (userJSON) {
        const user = JSON.parse(userJSON);
        let rol = user.rol;
        switch(rol){
            case 2:
                cargarOfertas(user.id);
                break;
            case 3:
                cargarOfertasSolicitadas(user.id);
                break;
        }
    }
});

// ============================================================================
// SECCIÓN 2: UTILIDAD PARA OBTENER TOKEN
// ============================================================================

/**
 * Obtiene el token desde sessionStorage
 * @returns {string|null} Token de autorización
 */
function obtenerToken() {
    return sessionStorage.getItem('token');
}



// ============================================================================
// SECCIÓN 3: FETCH DE DATOS
// ============================================================================

/**
 * Carga las ofertas solicitadas desde la API
 * @param {number} userId - ID del user
 */
function cargarOfertasSolicitadas(userId) {
    const token = obtenerToken();
    fetch(`../api/apiAlumno.php?menu=solicitudes&id=${userId}`, {
        method: 'GET',
        headers: {
            'AUTH': `Bearer ${token}`
        }
    })
        .then(res => res.text())
        .then(texto => JSON.parse(texto))
        .then(solicitudes => {
            pintarSolicitudes(solicitudes);
        })
        .catch(error => {
            console.error('Error al cargar ofertas:', error);
            alert('Error al cargar las ofertas solicitadas');
        });
}


/**
 * Carga las ofertas solicitadas desde la API
 * @param {number} userId - ID del user
 */
function cargarOfertas(userId) {
    const token = obtenerToken();
    fetch(`../api/apiAlumno.php?menu=ofertas&id=${userId}`, {
        method: 'GET',
        headers: {
            'AUTH': `Bearer ${token}`
        }
    })
        .then(res => res.text())
        .then(texto => JSON.parse(texto))
        .then(ofertas => {
            pintarOfertas(ofertas);
        })
        .catch(error => {
            console.error('Error al cargar ofertas:', error);
            alert('Error al cargar las ofertas');
        });
}


/**
 * Carga las ofertas solicitadas desde la API
 * @param {number} ofertaId - ID de la oferta
 */
function cargarSolicitudes(ofertaId) {
    const token = obtenerToken();
    fetch(`../api/apiAlumno.php?menu=solicitudesOferta&id=${ofertaId}`, {
        method: 'GET',
        headers: {
            'AUTH': `Bearer ${token}`
        }
    })
    .then(res => res.text())
    .then(texto => JSON.parse(texto))
    .then(data => {
        const alumnos = data.alumnos;

        // Seleccionamos la card correspondiente
        const card = document.querySelector(`.oferta-card button[data-oferta-id="${ofertaId}"]`).closest('.oferta-card');

        // Evitamos duplicar el carrusel si ya existe
        if (!card.querySelector('.carousel-wrapper')) {

            // Contenedor principal del carrusel con botones
            const wrapper = document.createElement('div');
            wrapper.classList.add('carousel-wrapper');

            // Botón previo
            const prevBtn = document.createElement('button');
            prevBtn.classList.add('carousel-btn', 'prev','btn');
            prevBtn.textContent = '◀';
            wrapper.appendChild(prevBtn);

            // Contenedor de items
            const carousel = document.createElement('div');
            carousel.classList.add('carousel');

            if (alumnos.length === 0) {
                carousel.innerHTML = `<p>No hay solicitudes aún.</p>`;
            } else {
                alumnos.forEach(alumno => {
                    const item = document.createElement('div');
                    item.classList.add('carousel-item','card','card-content', 'flex-col');
                    /* Insertar luego:  */
                    item.innerHTML = `
                        <div>
                            <img src="./assets/img/${alumno.foto}" alt="foto alumno"></img>
                            <div class="flex-col">
                            <p><strong>${alumno.nombre} ${alumno.ap1}</strong></p>
                            <p>${alumno.correo}</p>
                            </div>
                        </div>
                        
                        <p>${alumno.descripcion}</p>
                        <div class="car-btns">
                            <img src="./assets/img/corazon.png" class="like icono"></img>
                            <img src="./assets/img/borrar.png" class='descartar icono'></img>
                            <img src="./assets/img/ver.png" class='ver-cv icono'></img>
                        </div>

                    `;
            
                    carousel.appendChild(item);
                });
            }

            wrapper.appendChild(carousel);

            // Botón siguiente
            const nextBtn = document.createElement('button');
            nextBtn.classList.add('carousel-btn', 'next', 'btn');
            nextBtn.textContent = '▶';
            wrapper.appendChild(nextBtn);

            // Insertamos el carrusel después del contenido de la card
            card.parentElement.appendChild(wrapper);

            // Funcionalidad de scroll
            const scrollAmount = 220; // ancho aproximado de cada item + gap
            prevBtn.addEventListener('click', () => {
                carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
            nextBtn.addEventListener('click', () => {
                carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });

            /* configurar Like Eliminar y Ver CV(hacer update a la api con like y eliminar, like:Estado:'INTERESADO', eliminar:'DENEGADO',
            ver cv hago un modal y como ya tengo el cv no hace falta nada mas) */
        }
    })
    .catch(error => {
        console.error('Error al cargar solicitudes:', error);
        alert('Error al cargar las solicitudes');
    });
}


// ============================================================================
// SECCIÓN 4: RENDERIZADO DE DATOS
// ============================================================================

/**
 * Pinta las ofertas en el contenedor
 * @param {Array} solicitudes - Array de objetos {solicitud_id, estado, oferta}
 */
function pintarSolicitudes(solicitudes) {
    const contenedor = document.querySelector('.ofertas');
    
    // Si no hay ofertas
    if (!solicitudes || solicitudes.length === 0) {
        contenedor.innerHTML = `
            <div class="card-content card">
                <h2>No hay Ofertas</h2>
            </div>
        `;
        return;
    }
    
    // Limpiar contenedor
    contenedor.innerHTML = '';
    // Pintar cada oferta
    solicitudes.forEach(item => {
        const { solicitud_id, estado, oferta } = item;
        
        const card = document.createElement('div');
        card.className = 'card-content card oferta-card';
        
        // Construir lista de ciclos
        let ciclosHTML = '';
        if (oferta.ciclos && oferta.ciclos.length > 0) {
            ciclosHTML = oferta.ciclos
                .map(ciclo => `<li>${ciclo.nombre || ciclo}</li>`)
                .join('');
        }
        
        // Formatear fechas
        const fechaInicio = formatearFecha(oferta.fecha_inicio || oferta.fechaInicio);
        const fechaFin = formatearFecha(oferta.fecha_fin || oferta.fechaFin);
        
        card.innerHTML = `
            <img src="./assets/img/${oferta.foto || 'usuario.png'}" alt="logo-empresa" srcset="">
            <h2>${oferta.nombre || oferta.titulo}</h2>
            <p>${oferta.descripcion || ''}</p>
            <ul>
                ${ciclosHTML}
            </ul>
            
            <div class="ofertas-fechas flex-col">
                <p>Fecha Inicio</p>
                <p>${fechaInicio}</p>
            </div>
            <div class="ofertas-fechas flex-col">
                <p>Fecha Fin</p>
                <p>${fechaFin}</p>
            </div>
            
            <form class="oferta-btns">
                <button type="button" class="btn eliminar" data-solicitud-id="${oferta.id}">Renunciar</button>
            </form>
        `;
        
        contenedor.appendChild(card);
    });
    
    // Configurar listeners después de pintar
    configurarListeners();
}

function pintarOfertas(ofertas){
    const contenedor = document.querySelector('.ofertas');
    
    // Si no hay ofertas
    if (!ofertas || ofertas.length === 0) {
        contenedor.innerHTML = `
            <div class="card-content card">
                <h2>No hay Ofertas</h2>
            </div>
        `;
        return;
    }
    
    // Limpiar contenedor
    contenedor.innerHTML = '';
    // Pintar cada oferta
    ofertas.forEach(oferta => {
        let div=document.createElement('div');
        const card = document.createElement('div');
        card.className = 'card-content card oferta-card';
        
        // Construir lista de ciclos
        let ciclosHTML = '';
        if (oferta.ciclos && oferta.ciclos.length > 0) {
            ciclosHTML = oferta.ciclos
                .map(ciclo => `<li>${ciclo.nombre || ciclo}</li>`)
                .join('');
        }
        
        // Formatear fechas
        const fechaInicio = formatearFecha(oferta.fecha_inicio || oferta.fechaInicio);
        const fechaFin = formatearFecha(oferta.fecha_fin || oferta.fechaFin);
        
        card.innerHTML = `
            <img src="./assets/img/${oferta.foto}" alt="logo-empresa" srcset="">
            <h2>${oferta.nombre || oferta.titulo}</h2>
            <p>${oferta.descripcion || ''}</p>
            <ul>
                ${ciclosHTML}
            </ul>
            
            <div class="ofertas-fechas flex-col">
                <p>Fecha Inicio</p>
                <p>${fechaInicio}</p>
            </div>
            <div class="ofertas-fechas flex-col">
                <p>Fecha Fin</p>
                <p>${fechaFin}</p>
            </div>
            
            <form class="oferta-btns">
                <button type="button" class="btn guardar" data-oferta-id="${oferta.id}">Ver Solicitudes</button>
            </form>
        `;
        div.appendChild(card)
        contenedor.appendChild(div);
    });
    
    // Configurar listeners después de pintar
    configurarListeners(); 

}

// ============================================================================
// SECCIÓN 5: EVENTOS
// ============================================================================

/**
 * Configura los listeners de los botones
 */
function configurarListeners() {
    const botonesRenunciar = document.querySelectorAll('.oferta-btns button[data-solicitud-id]');
    const botonesSolicitud=document.querySelectorAll('.oferta-btns button[data-oferta-id]');
    
    botonesSolicitud.forEach(btn => {
        btn.addEventListener('click', (function() {
            let primero=true;
            return function(){
                if (primero){
                    primero=false;
                    const ofertaId = btn.dataset.ofertaId;
                    cargarSolicitudes(ofertaId);
                }else{
                    primero=true;
                    let car=btn.parentElement.parentElement.parentElement.querySelector('.carousel-wrapper');
                    if (car) car.remove();
                }
            }
        })());
    });
    botonesRenunciar.forEach(btn => {
        btn.onclick = function(e) {
            e.preventDefault();
            
            const solicitudId = btn.dataset.solicitudId;
            
            if (!confirm('¿Estás seguro de que deseas renunciar a esta oferta?')) {
                return;
            }
            
            const token = obtenerToken();
            
            // DELETE: Eliminar solicitud
            fetch('../api/apiAlumno.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'AUTH': `Bearer ${token}`,
                    'ACCION':'solicitud'
                },
                body: JSON.stringify({ id: solicitudId })
            })
            .then(res => res.text())
            .then(texto => JSON.parse(texto))
            .then(datos => {
                if (datos.success) {
                    alert('Has renunciado a la oferta');
                    
                    // Eliminar card del DOM
                    btn.closest('.oferta-card').remove();
                    
                    // Verificar si quedan ofertas
                    const contenedor = document.querySelector('.ofertas');
                    if (contenedor.children.length === 0) {
                        contenedor.innerHTML = `
                            <div class="card-content card">
                                <h2>No hay Ofertas</h2>
                            </div>
                        `;
                    }
                } else {
                    alert(datos.message || 'Error al renunciar');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            });
        }
    });
}

// ============================================================================
// SECCIÓN 6: UTILIDADES
// ============================================================================

/**
 * Formatea una fecha a formato dd-mm-yyyy
 * @param {string} fecha - Fecha en formato ISO o similar
 * @returns {string} Fecha formateada
 */
function formatearFecha(fecha) {
    if (!fecha) return 'N/A';
    
    const date = new Date(fecha);
    const dia = String(date.getDate()).padStart(2, '0');
    const mes = String(date.getMonth() + 1).padStart(2, '0');
    const anio = date.getFullYear();
    
    return `${dia}-${mes}-${anio}`;
}