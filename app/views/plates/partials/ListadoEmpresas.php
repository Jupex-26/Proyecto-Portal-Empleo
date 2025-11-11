<div class="content-header">
    <select name="size" id="size">
    <option value="5" data-size="5">5</option>
    <option selected value="10" data-size="10">10</option>
    <option value="15" data-size="15">15</option>
    <option value="20" data-size="20">20</option>
    <option value="25" data-size="25">25</option>
    <option value="30" data-size="30">30</option>
    <option value="50" data-size="50">50</option>
</select>

<a id="link-size" href="?page=<?=$page?>&accion=<?=$accion?>&size=10&pagina=1">Actualizar</a>
</div>
<div class="content-listado">
    
    <div class="header-listado">
        <p>ID</p>
        <p>Nombre</p>
        <p>Correo</p>
        <p>Acción</p>
    </div>
    <div class="listado">
        <?php foreach($empresas as $empresa): ?>
            <div class="listado-empresa">
                <p><?= $empresa->getId(); ?></p>
                <p><?= $empresa->getNombre(); ?></p>
                <p><?= $empresa->getEmail(); ?></p>
                <form action="?page=<?=$page?>&accion=<?=$accion?>" method="POST">
                    <button name="accion" class="editar" value="editar"><img src="./assets/img/editar.png" alt="editar"></button>
                    <button name="accion" class="ver" value="ver"><img src="./assets/img/ver.png" alt="ver"></button>
                    <button name="accion" class="remove" value="eliminar"><img src="./assets/img/borrar.png" alt="eliminar"></button>
                    <input type="hidden" name="id" value="<?= $empresa->getId()?>">
                
                <?php if(!$activo){?>
                    <button name="accion" class="aceptar" value="aceptar"><img src="./assets/img/aceptar.png" alt="aceptar"></button>
                <?php } ?>
                </form>
                
            </div>
        <?php endforeach; ?>
    </div>
    <div class="pagination">
        <?= $paginator ?>
    </div>