<?= $this->layout('Admin/AdminEmpresa',['page'=>$page,'user'=>$user])?>

<?= $this->start('card-options')?>
<h1><?=$btnAction=='editar'?'Editar Empresa':'Inscribir Empresa'?></h1>
<?= $this->stop()?>
<?= $this->start('card-content')?>
<?= $this->insert('Empresa/FormEmpresa',['page'=>$page,'accion'=>$accion,'empresa'=>$empresa,'validator'=>$validator, 'btnAction'=>$btnAction])?>
<?= $this->stop()?>