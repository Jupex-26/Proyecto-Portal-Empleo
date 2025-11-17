document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".login-form");

    form.addEventListener("submit", async (e) => {
        // No hacemos preventDefault: dejamos que el submit se ejecute normalmente
        const email = form.querySelector("input[name='correo_login']").value.trim();
        const pass = form.querySelector("input[name='passwd_login']").value;

        try {
            // Hacemos la consulta a la API solo para obtener token y alumno
            const res = await fetch("../api/apiLogin.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email, pass })
            });

            const data = await res.json();

            if (res.ok) {
                // Guardar en storage aunque la página se recargue
                localStorage.setItem("user", JSON.stringify(data.user));
                localStorage.setItem("token", data.token);
                sessionStorage.setItem("user", JSON.stringify(data.user));
                sessionStorage.setItem("token", data.token);
            }
        } catch (err) {
            console.error("Error al conectarse al servidor:", err);
        }
    });
});




/* Funcionamiento del registro de un usuario, para mostrar plantilla valida, y enviarlo al servidor */
window.addEventListener('load',function(){
    let btn=document.querySelector('#login-user');
    if (btn){
        btn.onclick=function(e){
        e.preventDefault();
        let modalDiv=document.querySelector('.modal');
        let veloDiv=document.querySelector('.velo');
        let modal=new Modal(modalDiv,veloDiv);
        modal.open();
        fetch("./assets/plates/formAlumno.html")
        .then((x)=>x.text())
        .then(x=>{
            modalDiv.innerHTML=x
            activarSelects();
            document.querySelector('.btn-volver').onclick=(e)=>{
                e.preventDefault();
                modalDiv.innerHTML="";
                modal.close();
            };
            document.querySelector('.btn-save').onclick=(e)=>saveAlumno(modalDiv,e);
        })
    }
    }
    
})


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
                div.querySelector('.btn-save').remove();
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

