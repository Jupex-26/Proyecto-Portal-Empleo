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
        <div class="card card-content">
            <h2><?= htmlspecialchars($oferta->getNombre()) ?></h2>
        </div>
    <?php endforeach; ?>
</main>
<?= $this->stop() ?>

<?= $this->start('footer')?>
<?= $this->insert('partials/footer')?>
<?= $this->stop()?>