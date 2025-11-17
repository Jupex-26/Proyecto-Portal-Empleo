<?= $this->layout('layout');?>
<?= $this->start('header')?>
<?= $this->insert('partials/header',['user'=>$user])?>
<?= $this->stop()?>
<?= $this->start('main') ?>
<main>
    
    <?php if ($user->getRol()=='2'):?>
        <?= $this->insert('partials/FiltroEmpresa')?>
        <?= $this->insert('Ofertas/ListadoOfertasEmpresa',['empresa'=>$user]);?>
    <?php elseif($user->getRol()=='3'): ?>
        <?= $this->insert('partials/FiltroAlumno')?>
        <?= $this->insert('Ofertas/ListadoOfertasAlumno',['ofertas'=>$ofertas,'user'=>$user]);?>
    <?php endif;?>

</main>
<?= $this->stop() ?>

<?= $this->start('footer')?>
<?= $this->insert('partials/footer')?>
<?= $this->stop()?>