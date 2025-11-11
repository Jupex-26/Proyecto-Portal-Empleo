<?= $this->layout('Admin/AdminEmpresa', ['page'=>$page])?>
<?= $this->push('js')?>
<script src="./assets/js/listado.js"></script>
<?php $this->end() ?>
<?= $this->start('card-options')?>
<?= $this->insert('partials/CardOptions')?>
<?= $this->stop()?>
<?= $this->start('card-content')?>
<h1><?=$accion=='listado'?'Listado Empresas':'Solicitudes Empresas'?></h1>
<?php if(count($empresas)>0){?>
<?= $this->insert('partials/ListadoEmpresas',['empresas'=>$empresas, 'paginator'=>$paginator, 'activo'=>$activo, 'accion'=>$accion])?>
<?php }else{?>
<h2>No hay Empresas</h2>
<?php } ?>
</div>
<?= $this->stop()?>