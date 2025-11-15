<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>EmpleNow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jockey+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/home.css">
    <script src="./assets/js/select.js"></script>
    <link rel="icon" type="image/x-icon" href="./assets/img/logo.png"> 
    <?= $this->section('css')?>
    <?= $this->section('js')?>
    <script src="./assets/js/script.js"></script>
</head>
<body>
    <?= $this->section('header')?>
    <?= $this->section('main')?>
    <?= $this->section('footer')?>
</body>
</html>