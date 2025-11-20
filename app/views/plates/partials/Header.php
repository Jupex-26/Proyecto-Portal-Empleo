<header class="sticky">
    <!-- El checkbox y el icono de hamburguesa ahora están aquí -->
    <input class="burger-check hidden" type="checkbox" id="burger-check"/>
    <label for="burger-check" class="burger">
      
        <span></span>
        <span></span>
        <span></span>
    </label>
    
    <!-- La barra de navegación está separada -->
    <div class="navbar">
        <div class="logo">
            <a href="?page=home"><img src="./assets/img/logo.png" alt="EmpleNow Logo"></a>
        </div>
        <nav>
            <ul>
                <li><a href="?page=oferta">Ofertas</a></li>
                <li><a href="?page=solicitud">Solicitudes</a></li>
                <li><a href="?page=contacto">Contacto</a></li>
            </ul>
        </nav>
        <?php if($user):?>
            <?=$this->insert('partials/NavLogin',['user'=>$user])?>
        <?php else:?>
            <div class="perfil">
                <a href="?page=login"><img src="./assets/img/usuario.png" alt="Perfil"></a>
            </div>
        <?php endif?>
    </div>
</header>