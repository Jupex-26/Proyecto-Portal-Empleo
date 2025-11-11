<?= $this->layout('Admin/AdminEmpresa',['page'=>$page])?>

<?= $this->start('card-options')?>
<h1><?=$action=='editar'?'Editar Empresa':'Inscribir Empresa'?></h1>
<?= $this->stop()?>
<?= $this->start('card-content')?>
<?= $this->insert('Empresa/FormEmpresa',['page'=>$page,'accion'=>$accion,'empresa'=>$empresa,'validator'=>$validator])?>
<?= $this->stop()?>