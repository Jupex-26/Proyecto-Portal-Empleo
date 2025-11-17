<?= $this->layout('Admin/AdminFichaEmpresa',['page'=>$page, 'empresa'=>$empresa, 'user'=>$user])?>
<?= $this->start('card-options')?>
<h1>Ficha Empresa</h1>
<?= $this->stop()?>
<?= $this->start('bottom')?>
<div class="btn-actions two-col">
  <form method="post" action="?page=empresas&accion=<?=$accion?>">
    <div class="buttons-form">
      <button type="submit" class="btn" name="action" value="cancelar">Volver</button>
    </div>
  </form>
</div>
<?= $this->stop()?>