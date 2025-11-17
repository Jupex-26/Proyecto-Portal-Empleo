<?=$this->layout('Admin/AdminLayout',['page'=>$page,'user'=>$user])?>
<?= $this->start('panel-main')?>
<section class="card-options">
<?= $this->section('card-options')?>
</section>
<section class="card-content card">
<?= $this->section('card-content') ?>
</section>
<?= $this->stop()?>