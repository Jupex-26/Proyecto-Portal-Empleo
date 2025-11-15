<?= $this->layout('Admin/AdminFichaEmpresa',['page'=>$page, 'empresa'=>$empresa])?>
<?= $this->start('card-options')?>
<h1>Eliminar Empresa</h1>
<?= $this->stop()?>
<?= $this->start('bottom')?>
<div class="btn-actions two-col">
  <p class="error card">¿Estás seguro de eliminar esta empresa?</p>
  <form method="post" action="?page=empresas&accion=<?=$accion?>">
    <input type="hidden" name="id" value="<?= $empresa->getId() ?>">
    <input type="hidden" name="accion" value="eliminar">
    <div class="buttons-form">
      <button type="submit" class="btn" name="action" value="cancelar">Cancelar</button>
      <button type="submit" class="btn eliminar" name="action" value="eliminar">Eliminar</button>
        <input type="hidden" name='btn-accion' value="<?=$btnAction?>">
    </div>
  </form>
</div>
<?= $this->stop()?>