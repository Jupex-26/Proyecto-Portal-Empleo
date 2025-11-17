<div class="ofertas">
<?php if(empty($ofertas)):?>
    <div class="card-content card">
        <h2>No hay Ofertas</h2>
    </div>
<?php else:?>
  <?php foreach ($ofertas as $oferta): ?>
    
        <div class="card-content card oferta-card">
                <img src="./assets/img/<?=$oferta->getFoto()??''?>" alt="logo-empresa" srcset="">
                <h2><?= $oferta->getNombre() ?></h2>
                <p><?=$oferta->getDescripcion()?></p>
                <ul>
                <?php foreach($oferta->getCiclos() as $ciclo):?>
                    
                        <li><?= $ciclo->getNombre()?></li>
                    
                <?php endforeach;?>
                </ul>
                
                    <div class="ofertas-fechas flex-col">
                        <p>Fecha Inicio</p>
                        <p><?=$oferta->getFechaInicio()->format('d-m-Y')?></p>
                    </div>
                    <div class="ofertas-fechas flex-col">
                        <p>Fecha Fin</p>
                        <p><?=$oferta->getFechaFin()->format('d-m-Y')?></p>
                    </div>
                    <?php if($user->getCv()==''):?>
                        
                        <div class="oferta-btns">
                        <p class="error">Tiene que insertar un CV antes</p>
                        </div>
                    <?php else: ?>
                        <form class="oferta-btns" method="GET" action="?page=oferta">
                            <input type="hidden" name="page" value="oferta">
                            <?php 
                                $ids = array_map(fn($s) => $s->getOfertaId(), $user->getSolicitudes());
                                if (in_array($oferta->getId(),$ids)):?>
                                <button class="btn eliminar" name="accion" value="renunciar">Renunciar</button>
                                
                            <?php else:?>
                                <button class="btn guardar" name="accion" value="postular">Postularse</button>
                                
                            <?php endif;?>
                            <input type="hidden" name="id" value="<?=$oferta->getId()?>">
                            
                        </form>
                    <?php endif; ?>
            </div>
        
    <?php endforeach; ?>
<?php endif?>
</div>