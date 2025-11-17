<header class="navbar sticky">
    <div class="logo">
      <a href="?page=home"><img src="./assets/img/logo.png" alt="EmpleNow Logo"></a>
    </div>
    <nav>
      <ul>
        <li><a href="?page=oferta">Ofertas</a></li>
        <li><a href="?page=solicitud">Solicitudes</a></li>
        <li><a href="?page=notificacion">Notificaciones</a></li>
        <li><a href="?page=contacto">Contacto</a></li>
      </ul>
    </nav>

    <?php if($user):?>
    <?=$this->insert('partials/NavLogin',['user'=>$user])?>
    <?php else:?>
    <div class="perfil">
      <a href="?page=login"><img src="./assets/img/usuario.png" alt="Perfil"></a><!-- Cambiar el perfil según si tiene rol o no -->
    </div>
    <?php endif?>

  </header>