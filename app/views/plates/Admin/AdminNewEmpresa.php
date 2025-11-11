<?= $this->layout('Admin/AdminEmpresa',['page'=>$page])?>

<?= $this->start('card-options')?>
<h1>Inscribir Empresa</h1>
<?= $this->stop()?>
<?= $this->start('card-content')?>
<?= $this->insert('Empresa/NewEmpresa',['validator'=>$validator, 'page'=>$page,'accion'=>$accion])?>
<?= $this->stop()?>