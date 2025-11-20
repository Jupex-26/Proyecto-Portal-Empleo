<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EmpleNow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jockey+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/home.css">
    <link rel="icon" type="image/x-icon" href="./assets/img/logo.png"> 
    <?= $this->section('css')?>
    
    <script src="./assets/js/validator.js"></script>
    <script src="./assets/js/modal.js"></script>
    <script src="./assets/js/select.js"></script>
    <script src="./assets/js/alumno.js"></script>
    <script src="./assets/js/script.js"></script>
    <?= $this->section('js')?>
</head>
<body>
    <?= $this->section('header')?>
    <?= $this->section('main')?>
    <?= $this->section('footer')?>
</body>
</html>