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
                <p><?= $empresa->getCorreo(); ?></p>
                <form action="?page=<?=$page?>&accion=<?=$accion?>" method="POST">
                    <button name="btn-accion" class="editar" value="editar"><img src="./assets/img/editar.png" alt="editar"></button>
                    <button name="btn-accion" class="ver" value="ver"><img src="./assets/img/ver.png" alt="ver"></button>
                    <button name="btn-accion" class="remove" value="eliminar"><img src="./assets/img/borrar.png" alt="eliminar"></button>
                    <input type="hidden" name="id" value="<?= $empresa->getId()?>">
                
                <?php if(!$activo){?>
                    <button name="btn-accion" class="aceptar" value="aceptar"><img src="./assets/img/aceptar.png" alt="aceptar"></button>
                <?php } ?>
                </form>
                
            </div>
        <?php endforeach; ?>
    </div>
    <div class="pagination">
        <?= $paginator ?>
    </div>