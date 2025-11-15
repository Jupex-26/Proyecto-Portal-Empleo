window.addEventListener('load',function(){
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
            let texto=ciclo.selectedOptions[0].value;
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
    
})



function configurarBtnEliminar(div){
    if (div.hasChildNodes()){
        let lis=div.querySelectorAll('li');
        lis.forEach(li => {
            li.querySelector('span').onclick=(e)=>e.target.parentElement.parentElement.remove();
        });

    }
    
    
    
}



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