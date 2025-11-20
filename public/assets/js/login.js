document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".login-form");

    form.addEventListener("submit", async (e) => {
        // No hacemos preventDefault: dejamos que el submit se ejecute normalmente
        const email = form.querySelector("input[name='correo_login']").value.trim();
        const pass = form.querySelector("input[name='passwd_login']").value;
        // Hacemos la consulta a la API solo para obtener token y alumno
        fetch("../api/apiLogin.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email, pass })
        })
        .then((res)=>res.json())
        .then((data)=>{
            console.log(data);
            if (data) {
                console.log("Login exitoso");
            // Guardar en storage aunque la página se recargue
            localStorage.setItem("user", JSON.stringify(data.user));
            localStorage.setItem("token", data.token);
            sessionStorage.setItem("user", JSON.stringify(data.user));
            sessionStorage.setItem("token", data.token);
            console.log(sessionStorage.getItem("token"));
        }
        else {
            alert("Error de autenticación: " + data.message);
            console.log(sessionStorage.getItem("token"));
        }
        })
        .catch((err)=>{
            console.error("Error al conectarse al servidor:", err);
        });
        
            
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
            document.querySelector('.btn-guardar').onclick=(e)=>saveAlumno(modalDiv,e);
        })
    }
    }
    
})

