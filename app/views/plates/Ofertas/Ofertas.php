<?= $this->layout('layout');?>
<?= $this->start('header')?>
<?= $this->insert('partials/header')?>
<?= $this->stop()?>
<?= $this->start('main') ?>
<main>
<?php if ($user->getRol()=='2'):?>
    <?= $this->insert('partials/FiltroEmpresa')?>
<?php endif; ?>
  <?php foreach ($user->getOfertas() as $oferta): ?>
        <div class="ofertas">
            <div class="card-content card oferta-card">
                <img src="./assets/img/<?=$user->getFoto()?>" alt="logo-empresa" srcset="">
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
                
                <form class="oferta-btns" method="POST" action="?page=oferta">
                    <input type="hidden" name="id" value="<?=$oferta->getId()?>">
                    <button class="guardar btn" name="accion" value="editar">Editar</button>
                    <button class="btn eliminar" name="accion" value="eliminar">Eliminar</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</main>
<?= $this->stop() ?>

<?= $this->start('footer')?>
<?= $this->insert('partials/footer')?>
<?= $this->stop()?>