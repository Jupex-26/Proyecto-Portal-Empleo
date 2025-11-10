HTMLSelectElement.prototype.loadFromApi=function(ruta){
    
    this.disabled=false;
    let first=this.querySelector("option");
    this.innerHTML="";
    
    fetch(ruta)
    .then((res)=>res.text())
    .then((res)=>JSON.parse(res))
    .then((texto)=>{
        this.appendChild(first);
        texto.forEach(element => {
            let option=document.createElement("option");
            option.classList.add(element.id);
            option.value=element.nombre;
            option.textContent=element.nombre;
            this.appendChild(option);
        });
    })
    if (this.dataset.default === "true") return; /* Si alguien está intentando cargar recursos cuando he cancelado toda acción no intento cargar nada */
}
HTMLSelectElement.prototype.toDefault=function(){
    let first=this.querySelector("option");
    const copia = first.cloneNode(true); 
    this.innerHTML="";
    this.appendChild(copia);
    this.disabled=true; /* Vuelvo a los valores por defecto */
    this.dataset.default = "true"; /* Indico una propiedad por si alguien cancela antes de cargar recursos */
}
