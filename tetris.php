<!DOCTYPE html>
<html lang="es">

<!-- Pagina del tetris -->

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tetris</title>
  <link rel="stylesheet" href="./css/general.css">
  <link rel="stylesheet" href="./css/tetris.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
  <?php
  require_once $_SERVER['DOCUMENT_ROOT'] . "/tetris_online/config/config.php";
  require PROJECT_ROUTE . 'scripts/php/plantillas.php';

  session_start();
  $sesion = isset($_SESSION['user_id']);

  echo navBar($sesion);
  ?>
  <div class="game card text-bg-dark border-dark border-2 p-3">
    <div class="main container mx-auto">

      <!-- Marcadores -->
      <div class="scores card text-bg-dark border-dark border-2">
        <div class="scores card-body">
          <div class="element">
            <span class="fw-bold fs-5">Score: </span><span class="fs-4 fw-bold text-info" id="score">0</span><br>
          </div>
          <div class="element">
            <span class="fw-bold fs-5">Level: </span><span class="fs-4 fw-bold text-info" id="level">1</span><br>
          </div>
          <div class="element">
            <span class="fw-bold fs-5">Lines: </span><span class="fs-4 fw-bold text-info" id="lines">0</span><br>
          </div>
          <div class="element">
            <span class="fw-bold fs-5">Time: </span><span class="fs-4 fw-bold text-info" id="time">00:00</span><br>
          </div>
        </div>
      </div>


      <!-- Tablero de juego -->
      <canvas class="main-panel" id="mainPanel"></canvas>


      <!-- Panel lateral -->
      <div class="lateral card text-bg-dark border-dark border-2">

        <!-- Tablero de la pieza guardada -->
        <div class="element hold">
          <span class="fw-bold">Guardar Pieza (C)</span>
          <canvas class="panel-hold" id="holdPiece"></canvas>
        </div>

        <!-- Tablero de las proximas piezas -->
        <div class="element next">
          <span class="fw-bold">Siguientes Piezas</span>
          <canvas class="panel-next" id="nextPieces"></canvas>
        </div>

        <!-- Controles del audio -->
        <div class="element audioControls">
          <!-- Control de la musica -->
          <span class="fw-bold">Music Volumen:</span>
          <div class="control">
            <input type="range" name="music_vol" id="music_vol" min="0.0" max="2.0" value="1.0" step="0.01">
            <button class="btn" id="mute_music" value="0"><img id="mute_music_btn" src="assets/icons/volume-up-fill.svg" height="26" width="26"></img></button><br>
          </div>

          <!-- Control de los efectos de sonido -->
          <span class="fw-bold">FX Volumen:</span>
          <div class="control">
            <input type="range" name="fx_vol" id="fx_vol" min="0" max="2" value="1" step="0.01">
            <button class="btn" id="mute_fx" value="0"><img id="mute_fx_btn" src="assets/icons/volume-up-fill.svg" height="26" width="26"></img></button>
          </div>
        </div>

      </div>

    </div>
  </div>


  <!-- Fuentes de sonido -->
  <audio id="main_theme" src="assets/sounds/music/Original Tetris theme.mp3"></audio>
  <audio id="move_fx" src="assets/sounds/fx/move.wav"></audio>
  <audio id="rotate_fx" src="assets/sounds/fx/rotate.wav"></audio>
  <audio id="fall_fx" src="assets/sounds/fx/fall.wav"></audio>
  <audio id="softdrop_fx" src="assets/sounds/fx/softdrop.wav"></audio>
  <audio id="harddrop_fx" src="assets/sounds/fx/harddrop.wav"></audio>
  <audio id="collision_fx" src="assets/sounds/fx/collision.wav"></audio>
  <audio id="hold_fx" src="assets/sounds/fx/hold.wav"></audio>
  <audio id="single_fx" src="assets/sounds/fx/single.wav"></audio>
  <audio id="double_fx" src="assets/sounds/fx/double.wav"></audio>
  <audio id="triple_fx" src="assets/sounds/fx/triple.wav"></audio>
  <audio id="tetris_fx" src="assets/sounds/fx/tetris.wav"></audio>


  <!-- GameOver Modal -->
  <div class="modal fade" id="gameOverModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">GameOver</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <span class="fw-bold">Tiempo: </span><span id="timeGO" class="text-secondary fw-semibold"></span><br>
          <span class="fw-bold">Nivel: </span><span id="levelGO" class="text-secondary fw-semibold"></span><br>
          <span class="fw-bold">Lineas: </span><span id="linesGO" class="text-secondary fw-semibold"></span><br>
          <span class="fw-bold">Puntuacion: </span><span id="scoreGO" class="text-secondary fw-semibold"></span><br>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
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

  <!-- Script que almacena la variable de sesion en un SessionStorage -->
  <script type="text/javascript">
    sessionStorage.setItem("user_name", "<?php if (isset($_SESSION['user_name'])) echo $_SESSION['user_name']; ?>");
  </script>


  <!-- Script princial de la logica del tetris -->
  <script src="scripts/js/tetris/game.js" type="module"></script>


  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>