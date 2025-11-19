<?= $this->layout('layout');?>
<?= $this->start('header')?>
<?= $this->insert('partials/header',['user'=>$user])?>
<?= $this->stop()?>
<?= $this->push('js')?>
<script src="./assets/js/solicitud.js"></script>
<?= $this->stop()?>
<?= $this->start('main') ?>
<main>
    
    <div class="ofertas"></div>
    <div class="velo hidden"></div>
    <div class="modal hidden">
        <iframe src="" frameborder="0" class="cv"></iframe>
        <button class="close-modal btn">Cerrar</button>
    </div>
</main>
<?= $this->stop() ?>

<?= $this->start('footer')?>
<?= $this->insert('partials/footer')?>
<?= $this->stop()?>