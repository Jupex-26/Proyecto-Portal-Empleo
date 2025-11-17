<?=$this->layout('layout')?>
<?= $this->push('css')?>
<link rel="stylesheet" href="./assets/css/admin.css">
<?= $this->end() ?>
<?= $this->start('header')?>
<?= $this->insert('partials/HeaderAdmin', ['user'=>$user])?>
<?= $this->stop()?>
<?= $this->start('main')?>
<div class="panel">
    <aside class="sidebar">
    <?= $this->insert('partials/AsideAdmin', ['page'=>$page])?>
    </aside>
    <main>
    <?= $this->section('panel-main')?>
    </main>
</div>
<?= $this->stop()?>