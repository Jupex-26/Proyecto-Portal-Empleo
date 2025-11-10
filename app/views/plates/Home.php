<?php $this->layout('layout');?>
<?php $this->push('css') ?>
<link rel="stylesheet" href="./assets/css/header.css">
<link rel="stylesheet" href="./assets/css/footer.css">
<?php $this->end() ?>
<?php $this->start('header')?>
<?php $this->insert('partials/header')?>
<?php $this->stop()?>
<?php $this->start('main') ?>
<main>
    <section class="bienvenida">
      <h1>Encuentra oportunidades</h1>
      <p>En nuestro buscador de empleo reunimos a quienes buscan crecer profesionalmente con las empresas que buscan talento para transformar sus equipos. Aquí puedes explorar miles de oportunidades laborales actualizadas cada día, filtradas por área, ubicación o nivel de experiencia, y postular fácilmente con un perfil que destaque tus habilidades y trayectoria. Nuestro objetivo es que encontrar trabajo deje de ser un proceso complicado y se convierta en una experiencia sencilla, transparente y efectiva.</p>
      <p>Si eres empresa, podrás publicar tus vacantes en minutos, gestionar postulaciones desde una sola plataforma y conectar con los candidatos ideales gracias a nuestras herramientas inteligentes de selección. Tanto si estás dando el primer paso en tu carrera como si buscas el mejor talento para tu organización, este es el lugar donde las oportunidades y las personas se encuentran.</p>
    </section>
<?= $this->section('empresas') ?>
<?= $this->section('home-card')?>
<section class="contacto">
      <h2>Contáctanos</h2>
      <div class="contacto-card">
        <p>Si deseas ponerte en contacto con nosotros, estamos aquí para ayudarte. Haz clic en el botón de contacto y
          accede a nuestro formulario.</p>
        <button class="btn">Contacto</button>
      </div>
    </section>
</main>
<?php $this->stop() ?>

<?php $this->start('footer')?>
<?php $this->insert('partials/footer')?>
<?php $this->stop()?>

