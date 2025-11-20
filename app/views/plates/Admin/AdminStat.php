<?=$this->layout('Admin/AdminLayout',['page'=>$page,'user'=>$user])?>
<?=$this->start('js')?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="./assets/js/estadisticas.js"></script>
<?= $this->stop()?>
<?= $this->start('panel-main')?>

    <div class="card card-content">
        <div class="graficoAlumnos card">
             <canvas id="graficoAlumnos"></canvas>
        </div>
        <div class="graficoEmpresas card">
             <canvas id="graficoEmpresas"></canvas>
        </div>
       
    </div>

<?= $this->stop()?>