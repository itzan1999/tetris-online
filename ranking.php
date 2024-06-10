<!DOCTYPE html>
<html lang="es">

<!-- Pagina del ranking de partidas de todos los usuarios registrados -->

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tetris</title>
  <link rel="stylesheet" href="./css/general.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

  <?php
  require_once $_SERVER['DOCUMENT_ROOT'] . "/tetris_online/config/config.php";
  require_once PROJECT_ROUTE . "scripts/php/database.php";
  require_once PROJECT_ROUTE . "scripts/php/plantillas.php";

  session_start();
  $sesion = isset($_SESSION['user_id']);

  echo navBar($sesion);

  ?>
  <div class="ranking">
    <h4 class="fw-bold" style="margin-left: 1%;">Ranking</h4>
    <div class=" cotainer text-center">
      <div class="row">
        <div class="col">
          <span class="fw-bold">#</span>
        </div>
        <div class="col">
          <span class="fw-bold">Usuario</span>
        </div>
        <div class="col">
          <span class="fw-bold">Puntuaci&oacute;n</span>
        </div>
        <div class="col">
          <span class="fw-bold">Lineas</span>
        </div>
        <div class="col">
          <span class="fw-bold">Tiempo</span>
        </div>
        <div class="col">
          <span class="fw-bold">Nivel</span>
        </div>
        <div class="col">
          <span class="fw-bold">Fecha</span>
        </div>
      </div>
      <hr class="border border-primary border-1 opacity-50">
      <div id="history">

        <?php
        $i = 1;
        // Se muestran las 100 partidas con merjor puntuacion almacenadas en la BD ordenadas por puntucion descendente
        foreach (getRanking() as $game) {
          $user = $game[0];
          $score = $game[1];
          $level = $game[2];
          $lines = $game[3];
          $time =  $game[4];
          $date =  $game[5];

          echo <<<HTML
            <div class="game row">
              <div class="col">
                <span class="fw-semibold text-secondary">$i</span>
              </div>
              <div class="col">
                <span id="user" class="fw-semibold text-secondary">$user</span>
              </div>
              <div class="col">
                <span id="score" class="fw-semibold text-secondary">$score</span>
              </div>
              <div class="col">
                <span id="lines" class="fw-semibold text-secondary">$lines</span>
              </div>
              <div class="col">
                <span id="time" class="fw-semibold text-secondary">$time</span>
              </div>
              <div class="col">
                <span id="level" class="fw-semibold text-secondary">$level</span>
              </div>
              <div class="col">
                <span id="date" class="fw-semibold text-secondary">$date</span>
              </div>
            </div>
            <hr class="border border-primary border-1 opacity-50">
          HTML;
          $i++;
        }
        ?>
      </div>
    </div>
  </div>



  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>