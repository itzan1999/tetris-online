import { BLOCK_SIZE, CANVAS_BACKGROUND_1, CANVAS_BACKGROUND_2 } from "./const.js";
import { PIECES_PROPERTIES } from "./piece.js";

// Clase que controla todas las operaciones relacionadas con los canvas
export class Canvas {
  #widthGrid;
  #heighthGrid;
  // El constructor recive el elemento <canvas> HTML, el ancho y alto del canvas
  constructor(canvas, width, height) {
    this.canvas = canvas;
    this.ctx = canvas.getContext("2d");
    this.canvas.width = width * BLOCK_SIZE;
    this.canvas.height = height * BLOCK_SIZE;
    this.ctx.scale(BLOCK_SIZE, BLOCK_SIZE);
    this.#widthGrid = width;
    this.#heighthGrid = height;
  }

  // Metodo que llama a los demas metodos implicados en el dibujado del tablero.
  // Recibe como parametro el tablero y una pieza
  // La pieza es opcional
  draw(board, piece) {
    this.#drawBackground(board);
    this.#drawStade(board);
    if (piece) this.#drawPiece(piece);
    this.#drawGrid();
  }

  // Metodo que dibuja la cuadricula de fondo en las posiciones del tablero que no tengan piezas ("0")
  // Recibe como parametro el array del tablero correspondiente
  #drawBackground(board) {
    let i = 0;
    board.forEach((row, y) => {
      row.forEach((value, x) => {
        this.ctx.fillStyle = (i % 2 === 0) ? CANVAS_BACKGROUND_1 : CANVAS_BACKGROUND_2;
        this.ctx.fillRect(x, y, 1, 1);
        i++;
      })
      i--;
    })
  }

  // Metodo que dibuja el estado actual de tablero (las piezas que estan ya colocadas)
  // Recibe como parametro el array del tablero correspondiente
  #drawStade(board) {
    board.forEach((row, y) => {
      row.forEach((type, x) => {
        if (type !== "0") {
          this.ctx.fillStyle = this.#getGradiente(x, y, type);
          this.ctx.fillRect(x, y, 1, 1);
        }
      })
    })
  }

  // Metodo que dibuja la pieza que se esta jugando (la que controla el jugador).
  // Recibe como parametro la pieza que se juega
  #drawPiece(piece) {
    // Recorre la forma de la pieza jugada y le suma la posicion de la pieza a la posicion de la forma para hayar la posicion del bloque
    piece.shape.forEach((row, y) => {
      row.forEach((value, x) => {
        if (value === 1) {
          let relX = piece.position.x + x;
          let relY = piece.position.y + y;

          this.ctx.fillStyle = this.#getGradiente(relX, relY, piece.type);
          this.ctx.fillRect(relX, relY, 1, 1);
        }
      })
    });
  }

  // Metodo que dibuja la cuadricula en el tablero
  #drawGrid() {
    this.ctx.strokeStyle = CANVAS_BACKGROUND_1;
    this.ctx.lineWidth = 0.05;

    for (let i = 0; i <= this.canvas.width; i++) {
      this.ctx.beginPath();
      this.ctx.moveTo(0, i);
      this.ctx.lineTo(this.canvas.width, i);
      this.ctx.stroke();
      
      this.ctx.beginPath();
      this.ctx.moveTo(i, 0);
      this.ctx.lineTo(i, this.canvas.width);
      this.ctx.stroke();
    }
  }

  // Metodo que crea el gradiente para los bloques que tienen una pieza
  // Medio bloque diagonalmente sera de un color y el otro medio de otro color
  // Devuelve el gradiente ya creado
  #getGradiente(x, y, type) {
    let grad = this.ctx.createLinearGradient(x, y + 1, x + 1, y);
    
    grad.addColorStop(0, PIECES_PROPERTIES[type].colors[0]);
    grad.addColorStop(0.5, PIECES_PROPERTIES[type].colors[0]);
    grad.addColorStop(0.5, PIECES_PROPERTIES[type].colors[1]);
    grad.addColorStop(1, PIECES_PROPERTIES[type].colors[1]);
    
    return grad;
  }

  // Metodo de devuelve el ancho del canvas
  getWidth() {
    return this.#widthGrid;
  }

  // Metodo que redimensiona el canvas
  // Se llama cuando de redimensiona la pantalla
  reSize(board) {
    this.canvas.width = this.#widthGrid * BLOCK_SIZE;
    this.canvas.height = this.#heighthGrid * BLOCK_SIZE;
    this.ctx = this.canvas.getContext("2d");
    this.ctx.scale(BLOCK_SIZE, BLOCK_SIZE);
    this.draw(board);
  }
}