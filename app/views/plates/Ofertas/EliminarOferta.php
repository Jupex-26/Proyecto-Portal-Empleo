<?= $this->layout('layout');?>
<?= $this->start('header')?>
<?= $this->insert('partials/header', ['user'=>$user])?>
<?= $this->stop()?>
<?= $this->start('main') ?>
<main>
    <div class="new-oferta">
    <form action="?page=oferta&accion=eliminar&id=<?= $oferta->getId() ?>" method="POST" class="card-content card">
        
        <p>
            <strong>Nombre:</strong> <?= $oferta->getNombre() ?? '' ?>
        </p>

        <p>
            <strong>Descripción:</strong> <?= $oferta->getDescripcion() ?? '' ?>
        </p>

        <p>
            <strong>Fecha Inicio:</strong> <?= $oferta->getFechaInicio() ? $oferta->getFechaInicio()->format('Y-m-d') : '' ?>
        </p>

        <p>
            <strong>Fecha Fin:</strong> <?= $oferta->getFechaFin() ? $oferta->getFechaFin()->format('Y-m-d') : '' ?>
        </p>

        <p>
            <strong>Ciclos:</strong>
            <ul>
                <?php foreach ($oferta->getCiclos() as $ciclo): ?>
                    <li><?= $ciclo->getNombre() ?></li>
                <?php endforeach; ?>
            </ul>
        </p>

        <div class="btns-form">
            <a href="?page=oferta" class="btn">Volver</a>
            <button class="btn eliminar" name="accion" value="eliminar">Eliminar oferta</button>
        </div>

    </form>
</div>

</main>
<?= $this->stop() ?>

<?= $this->start('footer')?>
<?= $this->insert('partials/footer')?>
<?= $this->stop()?>