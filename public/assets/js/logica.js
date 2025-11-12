window.addEventListener('load', function () {
    const listaUsuario = document.querySelector('#listaUsuario');
    fetch("./assets/plates/tabla.html")
        .then((x) => (x.text()))
        .then((plantilla) => {
            fetch("../api/apiAlumno.php?menu=listadoAlumnos")
                .then((x) => x.text())
                .then((texto) => (JSON.parse(texto)))
                .then((datos) => {
                    pintarDatos(plantilla, datos, listaUsuario);
                    ordenarTablas();
                });
        })
    
})

window.addEventListener('load', function(){
    
    let btnMasivo=document.querySelector(".carga-masiva");
    btnMasivo.onclick=function(){
        let select=document.querySelector("#familia");
        select.loadFromApi("../api/apiAlumno.php?menu=familias");
        select.addEventListener('change',(e)=>document.querySelector("#ciclo").loadFromApi("../api/apiAlumno.php?menu=ciclos&id="+e.target.selectedOptions[0].className));
        let modalDiv = document.querySelector(".modal");
        let modal = new Modal(modalDiv, document.querySelector(".velo"));
        modal.open();
        let btnCarga=document.querySelector(".cargas");
        let botones = modalDiv.querySelectorAll(".botones");
        botones[0].classList.remove("hidden");
        eliminarElementosMenosElementos(modalDiv, botones);
        document.querySelector(".back").onclick=cerrarModal(modal);
        manejarCarga(btnCarga, modalDiv);
        let borrar=document.querySelector(".borrar");
        borrar.onclick=function(){
            let botones = modalDiv.querySelectorAll(".botones");
            eliminarElementosMenosElementos(modalDiv, botones);
        }
    }
    let btnAlumno=document.querySelector(".carga-alumno");
    btnAlumno.onclick=function(){
        /* Inscribir un alumno */
    }
})



function manejarCarga(boton, modalDiv) {
    let inputFile = document.querySelector(".fichero");
    boton.onclick = () => {
        let fichero = inputFile.files[0];
        if (fichero) {
            let read = new FileReader();
            read.onload = () => {
                let datos = JSON.parse(read.result);
                editTable();
                
                fetch("./assets/plates/preenvio.html")
                    .then((x) => (x.text()))
                    .then((plantilla) => {
                        pintarTabla(plantilla, datos, modalDiv);
                        let tabla=modalDiv.querySelector("table");
                        tabla.comprobarDuplicados(tabla.querySelector(".email"));
                        seleccionarTodos(modalDiv);
                        document.querySelector(".save").onclick = () => preSave();
                    })
            }
            read.readAsText(fichero);
            
        }
    }
}

function seleccionarTodos(div){
    let tabla=div.querySelector(".editable");
    $primera=false;
    tabla.querySelector(".seleccion_total").onclick=function(){
            tabla.querySelectorAll(".seleccion input").forEach((checkbox)=>{
                if (tabla.seleccion){
                    checkbox.checked=false;
                }else{
                    checkbox.checked=true;
                }
        })
        tabla.changeSeleccion();
        
        
    }
}
function editTable(){
    let botonEditar = document.querySelector(".editar");
    botonEditar.onclick = () => {
        let tabla=document.querySelector("table.editable");
        if (!tabla.editada){
            botonEditar.innerHTML="Cancelar Edición"
            tabla.editar();
        }else{
            botonEditar.innerHTML="Editar";
            tabla.quitarEdicion();
        }
    }
}
function cerrarModal(modal){
    return function(){
        let selects=document.querySelectorAll("select");
        selects.forEach((element)=>element.toDefault())
        let botonEditar=document.querySelector(".editar");
        document.querySelector(".botones").classList.add("hidden");
        if (botonEditar.innerHTML!='Editar'){
            botonEditar.innerHTML='Editar';
        }
        modal.close();
    }
    
    
    
}


function preSave(){
    let modalDiv = document.querySelector(".modal");
    let tabla=modalDiv.querySelector("table");
                        
    if (!tabla.comprobarDuplicados(tabla.querySelector(".email"))){

        /* Validar datos y una vez validados hacer lo siguiente: */
        let familia=document.querySelector('#familia').value;
        let ciclo=document.querySelector('#ciclo').value;
        let array=tabla.obtenerSeleccionados();
        let alumnos=array.map(fila=>{
            let nombre=fila.querySelector('.nombre').innerHTML;
            let ap1=fila.querySelector('.ap1').innerHTML;
            let ap2=fila.querySelector('.ap2').innerHTML;
            let email=fila.querySelector('.email').innerHTML;
            let fechaNacimiento=fila.querySelector('.fechaNacimiento').innerHTML;
            let direccion=fila.querySelector('.direccion').innerHTML;
            return new Alumno(nombre,ap1,ap2,email,fechaNacimiento,direccion,familia,ciclo);
        })
        let json=JSON.stringify(alumnos);
        fetch('../api/apiAlumno.php',{
            method:'POST',
            headers:{
                MOCK:true,
                AUTHORIZATION: 'bearer '
            },
            body:json
        })
        .then((res)=>JSON.parse(res))
        
        tabla.desplegar(); /* para cuando se inserte en el tbody de abajo hacer q sea un desplegable de campos */
    }
}

/* function connvertirInputsEnTd(modalDiv){
    modalDiv.querySelectorAll(".duplicado").forEach((fila)=>{
            fila.querySelectorAll("input").forEach((input)=>{
                let td=input.parentElement;
                td.innerHTML=input.value;
            });
            fila.classList.remove("duplicado");
        });
    
} */

/* function mostrarDuplicados() {
    let modalDiv = document.querySelector(".modal");
    let duplicado = comprobarDuplicados(modalDiv.querySelector("tbody"));
    if (duplicado) {
        modalDiv.querySelectorAll(".duplicado").forEach((fila) => {
        let array=Array.from(fila.children);
        array.forEach((td)=>{
            let clase=td.className;
            let input=document.createElement("input");
            input.value=td.innerHTML;
            td.innerHTML="";
            input.className=clase;
            input.name=clase;
            td.appendChild(input);
        })
    });
    }
    return duplicado;
} */

/* function insertarEnTobdy(array){
    let tbody=document.querySelector("tbody");
    let size=array.length;
    for (let i=0;i<size;i++){
        let tr=document.createElement("tr");
        tr.onclick=function(){
            let modal=new Modal(document.querySelector(".modal"),document.querySelector(".velo"));
            modal.open();
        };
        for (let valor of Object.values(array[i])) {
            let td = document.createElement("td");
            td.textContent = valor;
            tr.appendChild(td);
        }
        tbody.appendChild(tr);
    }
}  */
function enviarDatos(datos) {
    fetch("datos/insertarAlumnos.json", {
        method: "POST",
        body: JSON.stringify(datos)
    })
        .then((respuesta) => (respuesta.text()))
        .then((respuesta) => {
            let respuestaJSON = JSON.parse(respuesta);
            if (respuestaJSON.respuesta) {
                alert("Datos insertados correctamente");
            } else {
                alert("Error al insertar los datos");
            }
        })
}

function pintarTabla(plantilla, datos, elemento) {
    let botones = elemento.querySelectorAll(".botones");
    eliminarElementosMenosElementos(elemento, botones);
    let contenedor = document.createElement("div");
    contenedor.innerHTML = plantilla;
    console.log(contenedor);
    let padreInfo = contenedor.querySelector(".nombre").parentElement;
    let abueloInfo = padreInfo.parentElement;
    
    let size = datos.length;
    for (let i = 0; i < size; i++) {
        let nuevo = padreInfo.cloneNode(true);
        nuevo.querySelector(".nombre").innerHTML = datos[i].nombre;
        nuevo.querySelector(".ap1").innerHTML = datos[i].ap1;
        nuevo.querySelector(".ap2").innerHTML = datos[i].ap2;
        nuevo.querySelector(".email").innerHTML = datos[i].email;
        nuevo.querySelector(".fechaNacimiento").innerHTML=datos[i].fechaNacimiento;
        nuevo.querySelector(".direccion").innerHTML=datos[i].direccion;
        abueloInfo.appendChild(nuevo);
    }
    padreInfo.remove();
    while (contenedor.children.length > 0) {

        elemento.appendChild(contenedor.children[0]);
    }

}


function pintarDatos(plantilla, datos, elemento) {
    let botones = elemento.querySelectorAll(".botones");
    eliminarElementosMenosElementos(elemento, botones);
    let contenedor = document.createElement("div");
    contenedor.innerHTML = plantilla;
    let padreInfo = contenedor.querySelector(".id").parentElement;
    let abueloInfo = padreInfo.parentElement;
    let size = datos.length;
    for (let i = 0; i < size; i++) {
        let nuevo = padreInfo.cloneNode(true);
        nuevo.querySelector(".id").innerHTML = datos[i].id;
        nuevo.querySelector(".nombre").innerHTML = datos[i].nombre;
        nuevo.querySelector(".ap1").innerHTML = datos[i].ap1;
        nuevo.querySelector(".ap2").innerHTML = datos[i].ap2;
        nuevo.querySelector(".email").innerHTML = datos[i].email;
        nuevo.onclick = () => modalAlumno();
        abueloInfo.appendChild(nuevo);
    }
    padreInfo.remove();
    while (contenedor.children.length > 0) {

        elemento.appendChild(contenedor.children[0]);
    }

}

function modalAlumno() {
    const modalDiv = document.querySelector(".modal");
    let botones = modalDiv.querySelectorAll(".botones");
    eliminarElementosMenosElementos(modalDiv, botones);
    let modal = new Modal(modalDiv, document.querySelector(".velo"));
    modal.open();
    let back=document.querySelector(".back");
    back.addEventListener('click',cerrarModal(modal));
    fetch("./assets/plates/datosAlumno.html")
        .then((x) => (x.text()))
        .then((plantilla) => {
            fetch("datos/alumno12.json")
                .then((x) => (x.text()))
                .then((texto) => JSON.parse(texto))
                .then((datos) => {
                    pintarAlumno(plantilla, datos, modalDiv)
                })
        })

}


function pintarAlumno(plantilla, datos, elemento) {
    let contenedor = document.createElement("div");
    contenedor.innerHTML = plantilla;
    contenedor.querySelector("#id").value = datos.ID;
    contenedor.querySelector("#nombre").value = datos.Nombre;
    contenedor.querySelector("#ap1").value = datos.Ap1;
    contenedor.querySelector("#ap2").value = datos.Ap2;
    contenedor.querySelector("#email").value = datos.email;
    contenedor.querySelector("#direccion").value = datos.direccion;
    while (contenedor.children.length > 0) {

        elemento.appendChild(contenedor.children[0]);
    }

}

function eliminarElementosMenosElementos(div, elementos) {
    let array = Array.from(div.children);
    let elementosPermitidos=Array.from(elementos);
    array.forEach((x) => {
        if (!elementosPermitidos.includes(x)) {
            x.remove();
        }

    });

}
