
    <nav>
        <a href="?page=home" class="nav-item <?= $page=='home'?'active':""?>">
            <img src="./assets/img/panel.png" alt="Dashboard">
            <span>Dashboard</span>
        </a>
        <a href="?page=users" class="nav-item <?= $page=='users'?'active':""?>">
            <img src="./assets/img/usuarios.png" alt="Usuarios">
            <span>Usuarios</span>
        </a>
        <a href="?page=empresas" class="nav-item <?= $page=='empresas'?'active':""?>">
            <img src="./assets/img/empresas.png" alt="Empresas">
            <span>Empresas</span>
        </a>
        <a href="?page=stats" class="nav-item <?= $page=='stats'?'active':""?>">
            <img src="./assets/img/estadisticas.png" alt="Estadísticas">
            <span>Estadísticas</span>
        </a>
    </nav>
