<?=$this->layout('home')?>
<?= $this->start('empresas')?>
<?php foreach($empresas as $empresa): ?>

<section class="empresas">
      <h2>Empresas Más Populares</h2>
      <div class="logos">
        <div class="empresa">
          <img src="./assets/img/nter.png" alt="Nter">
          <p>Nter es una empresa especializada en servicios de consultoría tecnológica perteneciente a NWorld.</p>
        </div>
        <div class="empresa">
          <img src="./assets/img/ntt-data.png" alt="NTT DATA">
          <p>NTT DATA somos mucho más que una compañía referente en el sector tecnológico a nivel mundial.</p>
        </div>
        <div class="empresa">
          <img src="./assets/img/am-system.png" alt="AM System">
          <p>Soluciones profesionales para la gestión empresarial accesibles desde cualquier dispositivo con acceso a
            internet.</p>
        </div>
      </div>
    </section>
<?php endforeach;?>
<?= $this->stop()?>