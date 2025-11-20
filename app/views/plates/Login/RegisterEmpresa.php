<?= $this->layout('layout'); 
$this->start('main'); ?>
    <header class="sticky registro-header">
        <div class="logo">
            <img src="./assets/img/logo.png" alt="" class="registro-logo">
        </div>
        
        <h1>Registro de Empresas</h1>
    </header>
    <main>
        
        
        <div class="card-content registro-empresa card">
            <?= $this->insert('Empresa/FormEmpresa',['empresa'=>$empresa,'validator'=>$validator,'page'=>$page,'accion'=>$accion])?>
        </div>
    </main> 
<?= $this->stop(); 
?>
