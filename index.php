<!DOCTYPE html>
<html lang="es">
<!-- Pagina principal -->

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tetris</title>
  <link rel="stylesheet" href="./css/general.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <style>
  main {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin-top: 75px;
  }
  </style>
</head>

<body>
  <?php

  require_once $_SERVER['DOCUMENT_ROOT'] . "/tetris_online/config/config.php";
  require PROJECT_ROUTE . "scripts/php/functionsUser.php";
  require PROJECT_ROUTE . "scripts/php/plantillas.php";

  session_start();
  // Se almacena en una variable si hay sesion iniciada
  $sesion = false;

  // Si se ha solicitado el cierre de sesion en la URL, se cierra la sesion.
  if (isset($_SESSION['user_id'])) {
    $sesion = true;
    if (isset($_GET['logout']) && $_GET["logout"] === "1") {
      endSession();
    }
  }

  echo navBar($sesion);
  ?>

  <main>
    <img src="assets/img/tetris_logo.png" alt="Logo" height="25%" width="25%"><br>
    <p class="fs-2">Bienvenido a <span class="fw-bold">Tetris Online</span></p>
    <p class="fs-5">No se requiere de una cuenta para jugar.</p>
    <p class="fs-5"> Para que se guarden tus puntuaciones y participar en el Ranking deberas crear una cuenta.</p>
  </main>


  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</body>

</html>