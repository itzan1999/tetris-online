// #region Imports
import * as bootstrap from "../../../lib/Bootstrap/js/bootstrap.esm.js";

import * as CONST from "./const.js";
import Cronometro from "./cronometro.js";
import { Piece, PlayerPiece, HoldPiece, NextPieces } from "./piece.js";
import { Board } from "./board.js";
import { Canvas } from "./canvas.js";
import Sound from "./audio.js";

// #region Declaracion de constantes

// Modal
const goModal = new bootstrap.Modal(document.getElementById("gameOverModal"));
const $goModal = document.getElementById('gameOverModal');

// Audio Controls
const $musicVol = document.getElementById("music_vol");
const $fxVol = document.getElementById("fx_vol");
const $muteMusic = document.getElementById("mute_music");
const $muteMusicBtn = document.getElementById("mute_music_btn");
const $muteFx = document.getElementById("mute_fx");
const $muteFxBtn = document.getElementById("mute_fx_btn");

// Sounds
const music = new Sound(document.getElementById("main_theme"));
const move = new Sound(document.getElementById("move_fx"));
const rotate = new Sound(document.getElementById("rotate_fx"));
const fall = new Sound(document.getElementById("fall_fx"));
const softdrop = new Sound(document.getElementById("softdrop_fx"));
const harddrop = new Sound(document.getElementById("harddrop_fx"));
const collision = new Sound(document.getElementById("collision_fx"));
const hold = new Sound(document.getElementById("hold_fx"));
const single = new Sound(document.getElementById("single_fx"));
const double = new Sound(document.getElementById("double_fx"));
const triple = new Sound(document.getElementById("triple_fx"));
const tetris = new Sound(document.getElementById("tetris_fx"));

// Cronometro
const TIMER = new Cronometro();

// Canvas
const MAIN_CANVAS = new Canvas(document.getElementById("mainPanel"), CONST.BOARD_WIDTH, CONST.BOARD_HEIGHT);
const HOLD_CANVAS = new Canvas(document.getElementById("holdPiece"), CONST.HOLD_WIDTH, CONST.HOLD_HEIGHT);
const NEXT_CANVAS = new Canvas(document.getElementById("nextPieces"), CONST.NEXT_WIDTH, CONST.NEXT_HEIGHT);

// Tableros
const MAIN_BOARD = new Board(CONST.BOARD_WIDTH, CONST.BOARD_HEIGHT);
const HOLD_BOARD = new Board(CONST.HOLD_WIDTH, CONST.HOLD_HEIGHT);
const NEXT_BOARD = new Board(CONST.NEXT_WIDTH, CONST.NEXT_HEIGHT);

// Piezas
const PIECE = new PlayerPiece(Piece.generateType());
const NEXT_PIECES = new NextPieces();
const HOLD_PIECE = new HoldPiece(NEXT_PIECES.pieces[2].type);

//Marcadores
const $score = document.getElementById("score");
const $level = document.getElementById("level");
const $lines = document.getElementById("lines");

const $scoreGO = document.getElementById("scoreGO");
const $levelGO = document.getElementById("levelGO");
const $linesGO = document.getElementById("linesGO");
const $timeGO = document.getElementById("timeGO");

// Variables
let score, level, lines;

let dropCounter = 0;
let lastTime = 0;
let pause = false;

// #region Game's Functions

// Funcion que llama a todos los metodos necesarios para establecer los valores predeterminados para empezar una nueva partida
function start() {
  resetGame();

  if (!$musicVol.disabled) {
    music.play();
  }

  PIECE.cambiaPieza(Piece.generateType());

  NEXT_PIECES.resetPieces();
  NEXT_PIECES.updateBoard(NEXT_BOARD);
  
  HOLD_PIECE.cambiaPieza(NEXT_PIECES.pieces[2].type);
  HOLD_PIECE.setHold(false);
  HOLD_PIECE.setFirst(true);
  
  // HOLD_BOARD.solidifyPiece(HOLD_PIECE);
  
  HOLD_CANVAS.draw(HOLD_BOARD.board);
  NEXT_CANVAS.draw(NEXT_BOARD.board);
}

// Funcion que se llama a si misma para crear el GameLoop, es la encargada de hacer que las piezas caigan automaticamente
function update(time = 0) {
  const deltaTime = time - lastTime;
  
  lastTime = time;
  dropCounter += deltaTime;
  
  if (dropCounter > 1000 - (level * 20) && !pause) {
    // PIECE.moveDown();
    PIECE.move(CONST.DIREC.DOWN);
    dropCounter = 0;
    if (MAIN_BOARD.checkCollision(PIECE)) {
      // PIECE.moveUp();
      PIECE.move(CONST.DIREC.UP);

      collision.play();

      next();

    } else {
      fall.play();
    }
  }
  
  MAIN_CANVAS.draw(MAIN_BOARD.board, PIECE);
  window.requestAnimationFrame(update);
}

// Funcion que realiza las operaciones necesarias para generar la siguiente pieza
function next() {
  // Añade la pieza colocada al tablero
  MAIN_BOARD.solidifyPiece(PIECE);
  // Reinicia la pieza del jugador
  PIECE.resetPiece(NEXT_PIECES.getNext());

  // Restablece el estado de la pieza guardada para poder volver a guardar una nueva
  HOLD_PIECE.setHold(false);

  // Actualiza el tablero de las siguientes piezas
  NEXT_PIECES.updateBoard(NEXT_BOARD);
  // Dibuja el tablero de las siguientes piezas
  NEXT_CANVAS.draw(NEXT_BOARD.board);

  // Elimina las lineas completas y actualiza los marcadores
  scores(MAIN_BOARD.removeRows());

  // Comprueba si hay habido GameOver
  if (MAIN_BOARD.checkCollision(PIECE)) {
    gameOver();
  }
}

// #region EndGame's Functions
// Funcion que reseta todos los parametros al final de una partida
function resetGame() {
  // Oculatar modal del GameOver
  goModal.hide();
  // Reiniciar cronometro
  TIMER.reiniciar();
  // Reiniciar musica
  music.reset();

  // Reiniciar marcadores
  level = 1;
  lines = 0;
  score = 0;
  
  $level.textContent = level;
  $lines.textContent = lines;
  $score.textContent = score;
  
  // Limpiar tableros
  MAIN_BOARD.clear();
  HOLD_BOARD.clear();
  NEXT_BOARD.clear();
  
  // Iniciar cronometro
  TIMER.iniciar();
}

// Funcion que muestra el modal del GameOver y pausa el juego
function gameOver() {
  TIMER.parar();
  pause = true;

  $timeGO.textContent = TIMER.getTimer();
  $levelGO.textContent = level;
  $linesGO.textContent = lines;
  $scoreGO.textContent = score;

  goModal.show();
}

// Funcion que realiza una promesa para almacenar los resultados de la partida en la base de datos
function saveInDatabase() {
  const URL = "scripts/php/saveGameAjax.php";
  let user = sessionStorage.getItem("user_name");
  let formData = new FormData();
  let datos = [user, score, level, lines, TIMER.getTimer()];

  formData.append("action", "insertGame");
  formData.append("datos", datos);

  fetch(URL, {
    method: "POST",
    body: formData
  })
    .then((response) => response.json())
    .then((data) => {
      if (!data.ok) {
        alert("Para guardar la partida, debe iniciar sesion");
      }
    });
}


// #region Eventos
// Evento que controla los inputs por teclado para controlar la pieza en juego
document.addEventListener("keydown", event => {
  // Si se mantiene presionada la tecla solo se lanza el evento una vez
  if (event.repeat || pause) return;

  // Mover a la izquierda
  if (event.key === CONST.CONTROLS.LEFT) {
    //PIECE.moveLeft();
    PIECE.move(CONST.DIREC.LEFT);
    if (MAIN_BOARD.checkCollision(PIECE)) {
      // PIECE.moveRight();
      PIECE.move(CONST.DIREC.RIGHT);
      collision.play();
    } else {
      move.play();
    }
  }
  
  // Mover a la derecha
  if (event.key === CONST.CONTROLS.RIGHT) {
    // PIECE.moveRight();
    PIECE.move(CONST.DIREC.RIGHT);
    if (MAIN_BOARD.checkCollision(PIECE)) {
      // PIECE.moveLeft();
      PIECE.move(CONST.DIREC.LEFT);
      collision.play();
    } else {
      move.play();
    }
  }

  // Mover hacia abajo
  if (event.key === CONST.CONTROLS.DOWN) {
    // PIECE.moveDown();
    PIECE.move(CONST.DIREC.DOWN);
    if (MAIN_BOARD.checkCollision(PIECE)) {
      // PIECE.moveUp();
      PIECE.move(CONST.DIREC.UP);

      next();
      collision.play();
    } else {
      softdrop.play();
    }
  }

  // Rotar pieza horario
  if (event.key === CONST.CONTROLS.ROTATE) {
    const relMovs = PIECE.type !== "I"
      ? CONST.relMoveGeneral[PIECE.state]
      : CONST.relMoveI[PIECE.state];

    let rVal = true;
    
    PIECE.rotatePiece();

    if (MAIN_BOARD.checkCollision(PIECE)) {
      rVal = false;
      for (let i = 0; i < relMovs.length && !rVal; i++) {
        PIECE.relativeMove(relMovs[i][0], relMovs[i][1]);
        if (!MAIN_BOARD.checkCollision(PIECE)) {
          rVal = true;
          break;
        } else {
          PIECE.relativeMove(-relMovs[i][0], -relMovs[i][1]);
        }
      }
    }
    
    if (!rVal) {
      PIECE.invertRotate();
      collision.play();
    } else {
      dropCounter -= dropCounter / 4;
      rotate.play();
    }
  }

  // Rotar pieza antihorario
  if (event.key.toLowerCase() === CONST.CONTROLS.ROTATE_INV) {
    let relState = PIECE.state - 1;
    if (relState < 0) relState = 3;
    const relMovs = PIECE.type !== "I"
      ? CONST.relMoveGeneral[relState]
      : CONST.relMoveI[relState];

    let rVal = true;
    
    PIECE.invertRotate();

    if (MAIN_BOARD.checkCollision(PIECE)) {
      rVal = false;
      for (let i = 0; i < relMovs.length; i++) {
        PIECE.relativeMove(-relMovs[i][0], -relMovs[i][1]);
        if (!MAIN_BOARD.checkCollision(PIECE)) {
          rVal = true;
          break;
        } else {
          PIECE.relativeMove(relMovs[i][0], relMovs[i][1]);
        }
      }
    }

    if (!rVal) {
      PIECE.rotatePiece();
      collision.play();
    } else {
      dropCounter -= dropCounter / 4;
      rotate.play();
    }
  }

  // Guardar pieza
  if (event.key.toLowerCase() === CONST.CONTROLS.HOLD) {
    if (!HOLD_PIECE.getHold()) {
      const type = PIECE.type;
      
      hold.play();

      HOLD_PIECE.setHold(true);
      
      if (HOLD_PIECE.getFirst()) {
        PIECE.resetPiece(NEXT_PIECES.getNext());
        NEXT_PIECES.updateBoard(NEXT_BOARD);
        NEXT_CANVAS.draw(NEXT_BOARD.board)
        
        HOLD_PIECE.setFirst(false);

      } else {
        PIECE.resetPiece(HOLD_PIECE.type);
      }

      HOLD_PIECE.hold(type)

      HOLD_BOARD.clear();
      HOLD_BOARD.solidifyPiece(HOLD_PIECE);

      HOLD_CANVAS.draw(HOLD_BOARD.board);

    }
  }

  // Hard drop
  if (event.key === CONST.CONTROLS.HARD_DROP) {
    PIECE.hardDrop(MAIN_BOARD);
    harddrop.play();
    next();
  }

});

// Reiniciar partidad y guardar datos en la BD (si hay sesion iniciada) al cerrar el modal de GameOver
$goModal.addEventListener('hidden.bs.modal', () => {
  if (sessionStorage.getItem('user_name')) {
    saveInDatabase();
  }
  start();
  pause = false;
});

// Cuando la musica termine la reinicia
music.element.addEventListener("ended", () => {
  music.reset();
  music.play();
});

// Cuando se pierde el foco en la ventana, el juego se pausa
window.addEventListener("blur", () => {
  music.pause();
  TIMER.parar();
  pause = true;
});

// El juego se reanuda al volver el foco a la pantalla
window.addEventListener("focus", () => {
  if (!($musicVol.disabled)) music.play();
  TIMER.iniciar();
  pause = false;
});

// Controles del volumen de la musica
$musicVol.addEventListener("input", () => {
  if ($muteMusic.value === "0") {
    music.gainNode.gain.value = $musicVol.value;
  }
}, false);

// Controles del volumen de los efectos de sonido
$fxVol.addEventListener("input", () => {
  move.gainNode.gain.value = $fxVol.value;
  rotate.gainNode.gain.value = $fxVol.value;
  fall.gainNode.gain.value = $fxVol.value;
  softdrop.gainNode.gain.value = $fxVol.value;
  harddrop.gainNode.gain.value = $fxVol.value;
  collision.gainNode.gain.value = $fxVol.value;
  hold.gainNode.gain.value = $fxVol.value;
  single.gainNode.gain.value = $fxVol.value;
  double.gainNode.gain.value = $fxVol.value;
  triple.gainNode.gain.value = $fxVol.value;
  tetris.gainNode.gain.value = $fxVol.value;
}, false);

// Silenciar la musica
$muteMusic.addEventListener("click", () => {
  if ($muteMusic.value === '0') {
    $muteMusic.value = 1;
    music.pause();
    $musicVol.disabled = true;
    $muteMusicBtn.src = "assets/icons/volume-mute-fill.svg";
  } else if ($muteMusic.value === '1') {
    $muteMusic.value = 0;
    music.play();
    $musicVol.disabled = false;
    $muteMusicBtn.src = "assets/icons/volume-up-fill.svg";
  }
});

// Silenciar los efectos de sonido
$muteFx.addEventListener("click", () => {
  if ($muteFx.value === "0") {
    $muteFx.value = 1;
    move.gainNode.gain.value = 0;
    rotate.gainNode.gain.value = 0;
    fall.gainNode.gain.value = 0;
    softdrop.gainNode.gain.value = 0;
    harddrop.gainNode.gain.value = 0;
    collision.gainNode.gain.value = 0;
    hold.gainNode.gain.value = 0;
    single.gainNode.gain.value = 0;
    double.gainNode.gain.value = 0;
    triple.gainNode.gain.value = 0;
    tetris.gainNode.gain.value = 0;
    $fxVol.disabled = true;
    $muteFxBtn.src = "assets/icons/volume-mute-fill.svg";
  } else if ($muteFx.value === "1") {
    $muteFx.value = 0;
    move.gainNode.gain.value = $fxVol.value;
    rotate.gainNode.gain.value = $fxVol.value;
    fall.gainNode.gain.value = $fxVol.value;
    softdrop.gainNode.gain.value = $fxVol.value;
    harddrop.gainNode.gain.value = $fxVol.value;
    collision.gainNode.gain.value = $fxVol.value;
    hold.gainNode.gain.value = $fxVol.value;
    single.gainNode.gain.value = $fxVol.value;
    double.gainNode.gain.value = $fxVol.value;
    triple.gainNode.gain.value = $fxVol.value;
    tetris.gainNode.gain.value = $fxVol.value;
    $fxVol.disabled = false;
    $muteFxBtn.src = "assets/icons/volume-up-fill.svg";
  }
});

// Redimensiona los canvas al redimensionar la ventana
addEventListener("resize", (event) => {
  if (event.repeat) return;
  MAIN_CANVAS.reSize(MAIN_BOARD.board);
  HOLD_CANVAS.reSize(HOLD_BOARD.board);
  NEXT_CANVAS.reSize(NEXT_BOARD.board);
}, false);


// #region Marcadores
// Funcion que llama a las funciones necesarias para actualizar los marcadores
function scores(linesClear) {
  if (linesClear) {
    sumScore(linesClear);
    sumLines(linesClear);
    changeDifficult();
  }
}

// Suma los puntos correspondientes al numero de lineas limpiadas a la vez
function sumScore(linesClear) {
  switch (linesClear) {
    case 1:
      score += CONST.SCORE_BASE * level * 1;
      single.play();
      break;
    case 2:
      score += CONST.SCORE_BASE * level * 2;
      double.play();
      break;
    case 3:
      score += CONST.SCORE_BASE * level * 4;
      triple.play();
      break;
    case 4:
      score += CONST.SCORE_BASE * level * 8;
      tetris.play();
      break;
  }

  $score.textContent = score;
}

// Suma el numero de lineas limpiadas
function sumLines(linesClear) {
  lines += linesClear;
  $lines.textContent = lines;
}

// Si se cumple la condicion aumenta el nivel de dificultad
function changeDifficult() {
  if (lines >= level * CONST.MULTI_LINES_FOR_LEVEL) {
    level++;
    $level.textContent = level;
  }
}


start();
update();