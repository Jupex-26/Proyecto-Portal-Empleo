/* Añadir métodos al type String */

/* Método para comprobar si un string es fecha */
String.prototype.esFecha=function(){
    let partes=this.split("/");
    let valido=false;
    if(partes.length==3){
        let f=new Date(partes[2],partes[1]-1,partes[0]);
        if (f!="Invalid Date"){
            if (f.getFullYear()==partes[2]&& f.getMonth()==partes[1]-1&& f.getDate()==partes[0]){
                valido=true;
            }
        }
    }
    return valido;
}

/* Método para comprobar DNI */
String.prototype.esDni=function(){
    let regexp_dni=/^(\d{1,8})([TRWAGMYFPDXBNJZSQVHLCKE])$/i;
    const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
    let valido=false;
    let partes=regexp_dni.exec(this);
    if (partes){
        let letra=letras[partes[1]%23];
        valido=letra==partes[2];
        
    }
    return valido;
}