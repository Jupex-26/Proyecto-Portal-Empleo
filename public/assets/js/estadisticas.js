// Datos de ejemplo - en producción vendrían del servidor/API
window.addEventListener('load',function(){
    const userJSON = sessionStorage.getItem('user');
    if (userJSON) {
        cargarEstadisticas();
    }
});

/**
 * Obtiene el token desde sessionStorage
 * @returns {string|null} Token de autorización
 */
function obtenerToken() {
    return sessionStorage.getItem('token');
}

/**
 * Carga los datos para las estadísticas desde la API y llama a la función para crear el gráfico.
 */
function cargarEstadisticas() {
    const token = obtenerToken();

    // Simulamos una llamada a una API que nos devuelve datos para el gráfico.
    // Deberías crear este endpoint en tu backend (ej: apiEstadisticas.php).
    fetch('../api/apiEstadisticas.php?reporte=alumnosPorCiclo', {
        headers: {
            'AUTH': token
        }
    })
    .then(res => res.json())
    .then(datos => {
        // Extraemos las etiquetas (nombres de los ciclos) y los valores (cantidad de alumnos)
        const etiquetas = datos.map(item => item.ciclo);
        const valores = datos.map(item => item.cantidad);
        
        crearGraficoAlumnosPorCiclo(etiquetas, valores);
    })
    .catch(error => {
        console.error("Error al cargar estadísticas:", error);
        // En caso de error, podrías mostrar datos de ejemplo
        const etiquetasEjemplo = ['DAW', 'DAM', 'ASIR', 'SMR'];
        const valoresEjemplo = [15, 22, 18, 25];
        crearGraficoAlumnosPorCiclo(etiquetasEjemplo, valoresEjemplo);
    });
    fetch('../api/apiEstadisticas.php?reporte=empresasPorActividad', {
        headers: {
            'AUTH': token
        }
    })
    .then(res => res.json())
    .then(datos => {
        console.log(datos);
        // Extraemos las etiquetas (nombres de los ciclos) y los valores (cantidad de alumnos)
        const etiquetas = datos.map(item => item.estado);
        const valores = datos.map(item => item.cantidad);
        console.log(etiquetas, valores);
        crearGraficoEmpresasPorActividad(etiquetas, valores);
    })
    .catch(error => {
        console.error("Error al cargar estadísticas:", error);
        // En caso de error, podrías mostrar datos de ejemplo
        const etiquetasEjemplo = ['Tecnología', 'Salud', 'Educación', 'Comercio'];
        const valoresEjemplo = [10, 15, 8, 12];
        crearGraficoEmpresasPorActividad(etiquetasEjemplo, valoresEjemplo);
    });
}

/**
 * Crea un gráfico de barras utilizando Chart.js.
 * @param {string[]} etiquetas - Un array con los nombres para el eje X.
 * @param {number[]} valores - Un array con los valores numéricos para las barras.
 */
function crearGraficoAlumnosPorCiclo(etiquetas, valores) {
    const ctx = document.getElementById('graficoAlumnos').getContext('2d');

    new Chart(ctx, {
        type: 'bar', // Tipo de gráfico: 'bar', 'line', 'pie', etc.
        data: {
            labels: etiquetas,
            datasets: [{
                label: 'Nº de Alumnos por Ciclo',
                data: valores,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(255, 159, 64, 0.5)'
                ],
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}


function crearGraficoEmpresasPorActividad(etiquetas, valores) {
    const ctx = document.getElementById('graficoEmpresas').getContext('2d');

    new Chart(ctx, {
        type: 'bar', // Tipo de gráfico: 'bar', 'line', 'pie', etc.
        data: {
            labels: etiquetas,
            datasets: [{
                label: 'Nº de Empresas por Actividad',
                data: valores,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(255, 159, 64, 0.5)'
                ],
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}