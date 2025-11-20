class Alumno{
    constructor(nombre, ap1,ap2,correo,fechaNacimiento,direccion, familia, ciclo) {
    this.nombre = nombre;
    this.ap1 = ap1;
    this.ap2=ap2;
    this.correo=correo;
    this.fechaNacimiento=fechaNacimiento;
    this.direccion=direccion;
    this.familia=familia;
    this.ciclo=ciclo;
  }
}


function saveAlumno(div,e){
    e.preventDefault();
    let form=div.querySelector('.register-alum');
    const validator=new Validator(form);
    if (validator.validateAll()){
        console.log("funciona");
        const formData=new FormData(form);
        fetch("../api/apiAlumno.php",{
            method: 'POST',
            headers: {
                'AUTHORIZATION': 'bearer '
            },
            body: formData
        })
        .then((res)=>res.json())
        .then((datos)=>{
            if (datos.success) {
                const correo=form.querySelector('#correo').value;
                const passwd=form.querySelector('#passwd').value;
                console.log("OK:", datos);
                let formgroup=div.querySelectorAll(".form-group");
                formgroup.forEach(e=>e.remove());
                div.querySelector('.btn-guardar').remove();
                let h2 = document.createElement("h2");
                h2.textContent = "Registro Completado";
                form.prepend(h2);
                let login=document.querySelector('.login-form');
                login.querySelector('.correo_login').value=correo;
                login.querySelector('.passwd_login').value=passwd;
            } else {
                console.warn("Error:", datos.message);
                alert(datos.message);
            }

        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al enviar el formulario');
        });
        
    }else{
        console.log("funciona");
        validator.showErrors();
    }
}