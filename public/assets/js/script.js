window.addEventListener('load',function(){
    activarSelects();
})

window.addEventListener('load',function(){

    const userIcon = document.getElementById("userIcon");
    const dropdownMenu = document.getElementById("dropdownMenu");
    if (userIcon){
        userIcon.addEventListener("click", () => {
        dropdownMenu.classList.toggle("show");
    });

    document.addEventListener("click", (e) => {
        if (!userIcon.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.remove("show");
        }
    });
    }
})




