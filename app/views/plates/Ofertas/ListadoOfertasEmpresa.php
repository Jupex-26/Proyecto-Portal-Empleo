<div class="ofertas">
<?php if(empty($empresa->getOfertas())):?>
    <div class="card-content card">
        <h2>No hay Ofertas</h2>
    </div>
<?php else:?>
  <?php foreach ($empresa->getOfertas() as $oferta): ?>
    
        <div class="card-content card oferta-card">
                <img src="./assets/img/<?=$empresa->getFoto()?>" alt="logo-empresa" srcset="">
                <div class="oferta-texto">
                    <h2><?= $oferta->getNombre() ?></h2>
                    <p><?=$oferta->getDescripcion()?></p>
                    <ul>
                    <?php foreach($oferta->getCiclos() as $ciclo):?>
                        
                            <li><?= $ciclo->getNombre()?></li>
                        
                    <?php endforeach;?>
                    </ul>
                        <div class="ofertas-fechas">
                            <div class="flex-col">
                                <p>Fecha Inicio</p>
                                <p><?=$oferta->getFechaInicio()->format('d-m-Y')?></p>
                            </div>
                        
                            <div class="flex-col">
                                <p>Fecha Fin</p>
                                <p><?=$oferta->getFechaFin()->format('d-m-Y')?></p>
                            </div>
                        </div>
                </div>
                    <form class="oferta-btns" method="GET" action="?page=oferta">
                        <input type="hidden" name="page" value="oferta">
                        <button class="guardar btn" name="accion" value="editar">Editar</button>
                        <button class="btn eliminar" name="accion" value="eliminar">Eliminar</button>
                        <input type="hidden" name="id" value="<?=$oferta->getId()?>">
                    </form>
            </div>
        
    <?php endforeach; ?>
<?php endif?>
</div>