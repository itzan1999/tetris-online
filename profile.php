<!DOCTYPE html>
<html lang="en">

<!-- Pagina que muestra el perfil y el historial del usuario -->

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
  require PROJECT_ROUTE . "scripts/php/functionsUser.php";
  require PROJECT_ROUTE . "scripts/php/database.php";
  require PROJECT_ROUTE . 'scripts/php/plantillas.php';

  session_start();
  $sesion = isset($_SESSION['user_id']);

  echo navBar($sesion);

  // Si no hay sesion inicada se redirige al usuario a la pagina de inicio
  if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit;
  }

  $user = $_SESSION['user_name'];
  ?>

  <br>
  <br>
  <br>
  <div id="profile_stats" class="card border-primary mb-3" style="max-width: 45rem; margin-left: 2%;">
    <div class="card-header border-primary fw-semibold">Perfil</div>
    <div class="card-body">
      <h4 id="user" class="card-title fw-bold"><?= $user ?></h4>
      <div class="card-text">
        <div class="row row-cols-1 row-cols-md-2 g-4">
          <div class="col">
            <!-- Se muestran las estadisticas del usuario -->
            <div>
              <span class="fw-bold">Partidas: </span>
              <span id="totalGames" class="fw-semibold text-secondary"><?= getNumGamesById(getIdWithUser($user)); ?></span>
            </div>
            <div>
              <span class="fw-bold">Mejor puntuaci&oacute;n: </span>
              <span id="bestScore" class="fw-semibold text-secondary"><?= getBestScoreById(getIdWithUser($user)); ?></span>
            </div>
            <div>
              <span class="fw-bold">Mejor tiempo: </span>
              <span id="bestTime" class="fw-semibold text-secondary"><?= getBestTimeById(getIdWithUser($user)); ?></span>
            </div>
          </div>
          <div class="col">
            <div>
              <span class="fw-bold">Mejor nivel: </span>
              <span id="bestLevel" class="fw-semibold text-secondary"><?= getBestLevelById(getIdWithUser($user)); ?></span>
            </div>
            <div>
              <span class="fw-bold">Maximo lineas: </span>
              <span id="bestLevel" class="fw-semibold text-secondary"><?= getBestLinesById(getIdWithUser($user)); ?></span>
            </div>

            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
              <a href="recoverPass.php"><button type="button" class="btn btn-secondary">Cambiar Contrase&ntilde;a</button></a>
              <button id="btnDelAccount" type="button" class="btn btn-danger">Borrar Cuenta</button>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="historial">
    <h4 class="fw-bold" style="margin-left: 1%;">Historial</h4>
    <div class=" cotainer text-center">
      <div class="row">
        <div class="col">
          <span class="fw-bold">#</span>
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
        // Se muestra un historial de las partidas del usuario ordenadas por fecha
        foreach (getGamesById(getIdWithUser($user)) as $game) {
          $score = $game[0];
          $level = $game[1];
          $lines = $game[2];
          $time =  $game[3];
          $date =  $game[4];

          echo <<<HTML
            <div class="game row">
              <div class="col">
                <span class="fw-semibold text-secondary">$i</span>
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


  <!-- Modal de confirmacion para borrar cuenta -->
  <div id="confirmDelModal" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="exampleModalLabel" class="modal-title fs-5">Borrar Cuenta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Seguro que desea borrar la cuenta</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button id="confirmDel" type="button" class="btn btn-danger">Borrar Cuenta</button>
        </div>
      </div>
    </div>
  </div>


  <script async src="https://cdn.jsdelivr.net/npm/es-module-shims@1/dist/es-module-shims.min.js" crossorigin="anonymous"></script>
  <script type="importmap">
    {
      "imports": {
        "@popperjs/core": "https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/esm/popper.min.js",
        "bootstrap": "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.esm.min.js"
      }
    }
  </script>

  <!-- Pequeño script que controla el boton para borrar cuenta -->
  <script type="module">
    import * as bootstrap from "./lib/Bootstrap/js/bootstrap.esm.js";

    const $btnDel = document.getElementById('btnDelAccount');
    const $btnConfirmDel = document.getElementById('confirmDel');
    const goModal = new bootstrap.Modal(document.getElementById("confirmDelModal"));
    const goModalE1 = document.getElementById('confirmDelModal');

    // Evento que se llama al hacer click en el boton de borrar cuenta, lo que mostrará el modal de confirmacion para borrar la cuenta
    $btnDel.addEventListener('click', () => {
      goModal.show();
    });

    // Evento que se llama al hacer click en el boton de borrar cuenta del modal de confirmacion
    // Al llamarse, procederá ha realizar una promesa para borrar la cuenta y partidas del usuario de la BD
    $btnConfirmDel.addEventListener('click', () => {
      goModal.hide();
      const URL = "./scripts/php/registroAjax.php";
      let user = "<?= $user ?>";
      let formData = new FormData();

      formData.append("action", "delUser");
      formData.append("account", user);

      fetch(URL, {
          method: "POST",
          body: formData
        })
        .then((response) => {
          return response.json()
        })
        .then((data) => {
          if (data.ok) {
            location.replace("index.php");
          } else {
            alert("ERROR\nNo se ha podido borrar el usuario");
          }
        });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>