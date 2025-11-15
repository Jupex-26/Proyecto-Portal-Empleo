<?= $this->layout('Admin/AdminEmpresa', ['page'=>$page])?>
<?= $this->push('js')?>
<?= $this->end() ?>
<?= $this->start('card-options')?>
<?= $this->insert('partials/CardOptions')?>
<?= $this->stop()?>
<?= $this->start('card-content')?>

<h1><?=$accion=='listado'?'Listado Empresas':'Solicitudes Empresas'?></h1>
<div class="buscador">
    <form action="" method="GET">
        <input type="hidden" name="page" value="<?=$page?>">
        <input type="hidden" name="accion" value="<?=$accion?>">
        <input type="hidden" name="pagina" value="1" ></input>
        <div class="content-header">
        <select name="size" id="size">
                <option <?=$size=='5'?'selected':''?>value="5">5</option>
                <option <?=$size=='10'?'selected':''?> value="10">10</option>
                <option <?=$size=='15'?'selected':''?> value="15">15</option>
                <option <?=$size=='20'?'selected':''?> value="20">20</option>
                <option <?=$size=='25'?'selected':''?> value="25">25</option>
                <option <?=$size=='30'?'selected':''?> value="30">30</option>
                <option <?=$size=='50'?'selected':''?> value="50">50</option>
            </select>
        </div>
        
        <input type="text" name="nombre_empresa" placeholder="Buscar empresa" value="<?=$nombre?>">
        
        
        <button name='filtrado'value="true"><img src="./assets/img/ver.png" alt="buscar" ></button>
    </form>
    
</div>

<?php if(count($empresas)>0){?>
<?= $this->insert('partials/ListadoEmpresas',['empresas'=>$empresas, 'paginator'=>$paginator, 'activo'=>$activo, 'accion'=>$accion,'page'=>$page])?>
<?php }else{?>
<h2>No hay Empresas</h2>
<?php } ?>
</div>
<?= $this->stop()?>