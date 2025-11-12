function ordenarTablas(){
    let tablas=document.querySelectorAll("table.ordenable");
    let sizeTable=tablas.length;
    for (let x=0;x<sizeTable;x++){
        let tabla=tablas[x];
        let ths=tabla.querySelectorAll("th");
        let size=ths.length;
        for (let i=0;i<size;i++){
            ths[i].onclick=function(){
                if (ths[i].children[0].classList.contains("row-up")){
                    ths[i].orden=1;
                    ths[i].children[0].classList.remove("row-up");
                    ths[i].children[0].classList.add("row-down");
                }else if (ths[i].children[0].classList.contains("row-down")){
                    ths[i].orden=-1;
                    ths[i].children[0].classList.add("row-up");
                    ths[i].children[0].classList.remove("row-down");
                }
                let tipo="";
                if (ths[i].classList.contains("lexico")){
                    tipo="lexico";
                }else if(ths[i].classList.contains("numero")){
                    
                    tipo="numero";
                }
                tabla.ordenar({column:i,type:tipo,orden:ths[i].orden});
            }
        }
    }
}

HTMLTableElement.prototype.ordenar=function(props){
    let tbody=this.tBodies[0];
    let trs=Array.from(tbody.rows);
    let orden=props.orden;
    let col=props.column;
    let typ=props.type;
    switch(typ){
        case "lexico":
            trs.sort(ordenTrsTexto(col,orden));
            break;
        case "numero":
            trs.sort(ordenTrsNumero(col,orden));
            break;
    }
    for (let i=0;i<trs.length;i++){
        tbody.appendChild(trs[i]);
    }
};


function ordenTrsTexto(col,orden){
    return function(a,b){
        return orden*(a.cells[col].innerText.localeCompare(b.cells[col].innerText));
    }
}

function ordenTrsNumero(col,orden){
    return function(a,b){
        return orden*(a.cells[col].innerText-b.cells[col].innerText);
    }
}


HTMLTableRowElement.prototype.editar=function(){
    let celdas=this.querySelectorAll("td"); /* this.cells; sirve igual */
    let size=celdas.length;
    for (let i=0;i<size;i++){
        if (celdas[i].innerHTML==celdas[i].innerText){ /* innerText y textContent hace lo mismo */
            let texto=celdas[i].innerText;
            let input=document.createElement("input");
            input.type="text";
            input.value=texto;
            celdas[i].innerHTML="";
            celdas[i].appendChild(input);
            celdas[i].valorAnterior=texto;
        }
    }
}

HTMLTableElement.prototype.editar=function(){
    /* añadir celdas */
    this.editada=true;
    let trs=this.rows;
    let size=trs.length;
    for (let i=0;i<size;i++){
        
        if (trs[i].parentElement.nodeName.toUpperCase()=="THEAD"){
            let th=document.createElement("th");
            th.classList="edit";
            trs[i].appendChild(th);
        }else if(trs[i].parentElement.nodeName.toUpperCase()=="TBODY"){
            let td=trs[i].insertCell();
            td.classList="edit";
            let btnCancel=document.createElement("span");
            btnCancel.classList="btn-cancel";
            btnCancel.style.display="none";
            td.appendChild(btnCancel);
            let btnSave=document.createElement("span");
            btnSave.classList="btn-save";
            btnSave.style.display="none";
            td.appendChild(btnSave);
            let btnBorrar=document.createElement("span");
            btnBorrar.classList="btn-borrar";
            btnBorrar.style.display="inline-block";
            td.appendChild(btnBorrar);
            btnBorrar.onclick=function(){
                this.parentElement.parentElement.delete();
            }
            let btnEdit=document.createElement("span");
            btnEdit.classList="btn-editar";
            btnEdit.style.display="inline-block";
            td.appendChild(btnEdit);
            btnEdit.onclick=function(){
                this.style.display="none";
                btnBorrar.style.display="none";
                btnSave.style.display="inline-block";
                btnCancel.style.display="inline-block";
                this.parentElement.parentElement.editar();
            }
            btnCancel.onclick=function(){
                this.parentElement.parentElement.cancelar();
                this.style.display="none";
                btnSave.style.display="none";
                btnEdit.style.display="inline-block";
                btnBorrar.style.display="inline-block";
            }
            btnSave.onclick=function(){
                this.parentElement.parentElement.guardar();
                this.style.display="none";
                btnCancel.style.display="none";
                btnEdit.style.display="inline-block";
                btnBorrar.style.display="inline-block";
            }
        }
    }

};


HTMLTableRowElement.prototype.editada=false;

HTMLTableRowElement.prototype.delete=function(){
    this.remove();
}


HTMLTableRowElement.prototype.guardar=function(){
    let inputs=this.querySelectorAll("input[type=text]");
    let size=inputs.length;
    for (let i=0;i<size;i++){
        inputs[i].parentElement.innerHTML=inputs[i].value;
    }
}
HTMLTableRowElement.prototype.cancelar=function(){
    let inputs=this.querySelectorAll("input[type=text]");
    let size=inputs.length;
    for (let i=0;i<size;i++){
        inputs[i].parentElement.innerHTML=inputs[i].parentElement.valorAnterior;
    }
}


HTMLTableElement.prototype.quitarEdicion=function(){
    this.editada=false;
        let array=document.querySelectorAll(".edit");
        array.forEach(element => {
            element.remove();
        });
}

HTMLTableElement.prototype.seleccion=false;
HTMLTableElement.prototype.changeSeleccion=function(){
    if (this.seleccion){
        this.seleccion=false;
    }else{
        this.seleccion=true;
    }
}
HTMLTableElement.prototype.comprobarDuplicados=function(campo){
    let tbody=campo.parentElement.parentElement;
    let clase="."+campo.classList[0];
    /* Paso un filtro para no comprobar los tr con la clase seccion-header */
    let filas = Array.from(tbody.children).filter(child => 
        child.tagName === 'TR' && !child.classList.contains('seccion-header')
    );
    let nombres = new Set();
    let valido = false;
    filas.forEach((fila) => {
        let nombre = fila.querySelector(clase).innerHTML;
        if (nombres.has(nombre)) {
            fila.classList.add("duplicado");
            valido = true;
        } else {
            nombres.add(nombre);
        }
    });
    return valido;
}

HTMLTableElement.prototype.desplegar = function() {
    let tbody = this.querySelector('.response');
    let desplegar = tbody.querySelector('.seccion-header');
    
    desplegar.onclick = function() {
        Array.from(tbody.children).forEach((fila) => {
            if (fila !== desplegar) {
                fila.classList.toggle('oculto');
            }
        });
    }
}

HTMLTableElement.prototype.obtenerSeleccionados=function(){
    let tbody = this.querySelector('.request');
    let checkboxes=tbody.querySelectorAll('input[type="checkbox"]:checked');
    if (checkboxes.length>0){
        let seleccionados=Array.from(checkboxes).filter(checkbox=>{
            let fila=checkbox.parentElement.parentElement;
            return !fila.classList.contains('duplicado');
        });
        return seleccionados;
    }else{
        return[];
    }
    

}
