// Archivo que contiene una serie de constantes que se utilizan en distintos archivos del proyecto

// Tamaño del tablero de JUEGO en bloques
export const BOARD_WIDTH = 10;
export const BOARD_HEIGHT = 20;

// Tamaño del tablero de NEXT en bloques
export const NEXT_WIDTH = 6;
export const NEXT_HEIGHT = 10;

// Tamaño del tablero de HOLD en bloques
export const HOLD_WIDTH = 6;
export const HOLD_HEIGHT = 4;

// Porcentaje del tamaño de la pantalla que tomara para el tamaño del bloque
const BLOCK_PORCENT = 0.035;
// Tamaño del bloque
export let BLOCK_SIZE = reSizeBlock();

// Funcion que calcula el tamaño correspondiente al bloque del tablero, teniendo en cuenta el tamaño y forma de la ventana.
// Devuelve el tamaño del bloque.
export function reSizeBlock() {
  let sizeL, sizeq;
  if (innerHeight > innerWidth) {
    sizeL = innerWidth;
    sizeq = innerHeight;
  } else {
    sizeL = innerHeight;
    sizeq = innerWidth;
  }
  return (sizeL * BLOCK_PORCENT * BOARD_HEIGHT < sizeq - (sizeL * BLOCK_PORCENT))
    ? sizeL * BLOCK_PORCENT
    : sizeq - (sizeL * BLOCK_PORCENT);
}

// Evento que se llama al redimensionar la ventana
// Al ser invocado, actualiza el tamaño del bloque.
addEventListener("resize", () => {
  BLOCK_SIZE = reSizeBlock();
}, false);

// Puntuacion base por linea
export const SCORE_BASE = 100;
// Multiplicador del nivel que deben alcanzar las lineas limpiadas para aumentar el nivel
export const MULTI_LINES_FOR_LEVEL = 10;

// Colores del fondo del tablero
export const CANVAS_BACKGROUND_1 = "#000";
export const CANVAS_BACKGROUND_2 = "#444";

// Direcciones de movimiento
export const DIREC = {
  UP: "UP",
  DOWN: "DOWN",
  LEFT: "LEFT",
  RIGHT: "RIGHT"
}

// Teclas del teclado usadas para los controles del tetris
export const CONTROLS = {
  LEFT: "ArrowLeft",
  RIGHT: "ArrowRight",
  DOWN: "ArrowDown",
  ROTATE: "ArrowUp",
  ROTATE_INV: "z",
  HOLD: "c",
  HARD_DROP: " "
}

// Movimientos relativos para probar rotaciones para piezas basicas
export const relMoveGeneral = [
  [
    [-1, 0],
    [-1, 1],
    [0, -2],
    [-1, -2]
  ],
  [
    [1, 0],
    [1, -1],
    [0, 2],
    [1, 2]
  ],
  [
    [1, 0],
    [1, 1],
    [0, -2],
    [1, -2]
  ],
  [
    [-1, 0],
    [-1, -1],
    [0, 2],
    [-1, 2]
  ]
];

// Movimientos relativos para probar rotaciones para pieza I
export const relMoveI = [
  [
    [-2, 0],
    [1, 0],
    [-2, -1],
    [1, 2]
  ],
  [
    [-1, 0],
    [2, 0],
    [-1, 2],
    [2, -1]
  ],
  [
    [2, 0],
    [-1, 0],
    [2, 1],
    [-1, -2]
  ],
  [
    [1, 0],
    [-2, 0],
    [1, -2],
    [-2, 1]
  ]
];