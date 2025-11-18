let stream=null;

function conectarcamara(){
    const div=document.querySelector('.connect-cam');
    div.classList.remove('hidden');
    const video=document.getElementById("video");
    const constraints={
        audio:true,
        video:{
            width:600,height:400
        }
    };
    async function init(){
        try {
            stream=await navigator.mediaDevices.getUserMedia(constraints);
            video.srcObject=stream;
            /* handleSuccess(stream); */
        } catch (error) {
            console.error("Error al acceder a la cámara:", error);
        }
    }
    init();

}


async function guardarImg(){
    const canvas = document.getElementById("canvas"); // Asumiendo que tu canvas tiene el id="canvas"
    const video = document.getElementById("video"); // Ya usado en conectarcamara()
    const snap = document.getElementById("snap"); // Botón para tomar la foto
    const resnap = document.getElementById("resnap"); // Botón para volver a tomar la foto
    const ventana = document.querySelector(".ventana-de-captura"); // O el selector correcto para tu "ventana"
    console.log("guardando imagen");
    let context=canvas.getContext("2d");
    let recorte=document.querySelector("#recorte");

    let ancho=recorte.offsetWidth;
    let alto=recorte.offsetHeight;
    let izq=recorte.offsetLeft;
    let top=recorte.offsetTop;
    snap.addEventListener('click',async function(){
        ventana.classList.add("hidden");
        snap.classList.add("hidden");
        resnap.classList.remove("hidden");
        context.drawImage(video,izq,top,ancho,alto,0,0,ancho,alto);
    
        let imageBlob=await new Promise(resolve=>canvas.toBlob(resolve,'image/png'));
        let form=new FormData();
        form.append("foto",imageBlob,"fotico.png");
        fetch("php/guardar-img.php",{
            method:"POST",
            body:form
        }).then((a)=>a.text()).then((texto)=>console.log(texto));
    })
    resnap.addEventListener('click',function(){
        ventana.classList.remove("hidden");
        snap.classList.remove("hidden");
        resnap.classList.add("hidden");
        context.clearRect(0, 0, canvas.width, canvas.height);

    })

}

function apagarCamara() {
    const div=document.querySelector('.connect-cam');
    div.classList.add('hidden');
    // Verifica si hay un stream activo
    if (stream) {
        // Obtiene todas las pistas de medios (video y audio)
        let tracks = stream.getTracks(); 

        // Itera sobre cada pista y la detiene
        tracks.forEach(track => {
            track.stop();
        });

        // Opcionalmente, puedes anular la referencia al stream
        stream = null; 

        console.log("Cámara apagada y stream detenido.");
    } else {
        console.log("No hay un stream de cámara activo para detener.");
    }
}