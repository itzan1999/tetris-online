import { BOARD_WIDTH, DIREC } from "./const.js";

const NUMBER_PIECES = 7;

// Propiedades de las piezas
export const PIECES_PROPERTIES = {
  O: {
    shape: [
      [1, 1],
      [1, 1]
    ],
    colors: ["#cccc00","#ffff00"]
  },
  I: {
    shape: [
      [0, 0, 0, 0],
      [1, 1, 1, 1],
      [0, 0, 0, 0],
      [0, 0, 0, 0]
    ],
    colors: ["#00cccc","#00ffff"]
  },
  T: {
    shape: [
      [0, 1, 0],
      [1, 1, 1],
      [0, 0, 0]
    ],
    colors: ["#660066","#993299"]
  },
  Z: {
    shape: [
      [1, 1, 0],
      [0, 1, 1],
      [0, 0, 0]
    ],
    colors: ["#b20000","#ff0000"]
  },
  S: {
    shape: [
      [0, 1, 1],
      [1, 1, 0],
      [0, 0, 0]
    ],
    colors: ["#00b200","#00ff00"]
  },
  L: {
    shape: [
      [0, 0, 1],
      [1, 1, 1],
      [0, 0, 0]
    ],
    colors: ["#b25800","#ff7f00"]
  },
  J: {
    shape: [
      [1, 0, 0],
      [1, 1, 1],
      [0, 0, 0]
    ],
    colors: ["#0000b2","#0000ff"]
  },
  getWithIndex: function (n) {
    return this[this.index(n)]
  },
  index: function (n) {
    return Object.keys(this)[n]
  }
}

// Clase con las propiedades basicas de las piezas
class Piece {
  // El constructor recibe el tipo de pieza que se debe crear
  constructor(nType) {
    this.position = {
      x: 0,
      y: 0
    };
    this.type = isNaN(nType) ? nType : PIECES_PROPERTIES.index(nType);
    this.shape = PIECES_PROPERTIES[this.type].shape;
    this.color = PIECES_PROPERTIES[this.type].colors;
  }

  // Metodo estatico que si no recibe parametro, genera un tipo de pieza aleatorio
  static generateType(nType = false) {
    return !nType ? Math.floor(Math.random() * NUMBER_PIECES) : nType;
  }

  // Cambia el tipo, la forma, y el color de la pieza
  cambiaPieza(newType) {
    this.type = isNaN(newType) ? newType : PIECES_PROPERTIES.index(newType);
    this.shape = PIECES_PROPERTIES[this.type].shape;
    this.color = PIECES_PROPERTIES[this.type].colors;
  }
}

// Clase que hereda de la clase Piece y añade propiedades y metodos necesarios para controlar la pieza del jugador
class PlayerPiece extends Piece {
  constructor(nType) {
    super(nType);
    // this.type = isNaN(nType) ? nType : PIECES_PROPERTIES.index(nType);
    // this.shape = PIECES_PROPERTIES[this.type].shape;
    // this.color = PIECES_PROPERTIES[this.type].colors;
    this.position.x = Math.floor(BOARD_WIDTH / 2 - this.shape[0].length / 2);
    this.state = 0;
  }

  // Genera un nuevo tipo de pieza y reinicia su posicion
  resetPiece(newType) {
    this.cambiaPieza(newType);
    this.resetPosition();
  }

  // Resetea la posicion de la pieza
  resetPosition() {
    this.position.x = Math.floor(BOARD_WIDTH / 2 - this.shape[0].length / 2);
    this.position.y = 0;
  }

  // Mueve la pieza una posicion en la direccion indicada
  move(dir) {
    switch (dir) {
      case "UP":
        this.position.y--;
        break;
      case "DOWN":
        this.position.y++; 
        break;
      case "LEFT":
        this.position.x--;
        break;
      case "RIGHT":
        this.position.x++;
        break;
    }
  }
/*
  // Mueve la pieza a la izquierda
  moveLeft() {
    this.position.x--;
  }

  // Mueve la pieza a la derecha
  moveRight() {
    this.position.x++;
  }

  // Mueve la pieza hacia arriva
  moveUp() {
    this.position.y--;
  }

  // Mueve la pieza hacia abajo
  moveDown() {
    this.position.y++;
  }
*/
  // Mueve la pieza relativamente a su posicion segun los parametros recividos
  relativeMove(x, y) {
    this.position.x += x;
    this.position.y += y;
  }

  // Rota la pieza en sentido horario
  // La primera columna del array pasa a ser la primera fila, pero invertida
  // La segunda columna del array pasa a ser la segunda fila, pero invertida
  // Y asi con todas las columnas
  rotatePiece() {
    const ROTATED = [];

    for (let i = 0; i < this.shape[0].length; i++) {
      const row = [];

      for (let j = this.shape.length - 1; j >= 0; j--) {
        row.push(this.shape[j][i]);
      }

      ROTATED.push(row);
    }

    this.shape = ROTATED;
    this.state++;
    if (this.state === 4) this.state = 0;
  }

  // Rota la pieza en sentido antihorario
  invertRotate() {
    const ROTATED = [];

    for (let i = this.shape[0].length - 1; i >= 0; i--) {
      const row = [];

      for (let j = 0; j < this.shape.length; j++) {
        row.push(this.shape[j][i]);
      }

      ROTATED.push(row);
    }

    this.shape = ROTATED;
    this.state--;
    if (this.state === -1) this.state = 3;
  }

  // Mueve la pieza hacia abajo hasta que colisione con algo
  hardDrop(board) {
    let col = board.checkCollision(this);
    while (!col) {
      // this.moveDown();
      this.move(DIREC.DOWN);
      col = board.checkCollision(this);
    }
    // this.moveUp();
    this.move(DIREC.UP)
  }
}


//Clase encargada de gestionar la pieza que se guarda para usar más adelante
class HoldPiece extends Piece {
  #hold;
  #first;
  constructor(nType) {
    super(nType);
    this.position.x = 1;
    this.position.y = 1;
    // Propiedad que controla si se ha guardado una pieza en este turno
    this.#hold = false;
    // Propiedad que controla si se ha guardado alguna pieza en esta partida
    this.#first = true;
  }
  
  // Devuelve el valor de la propiedad hold
  getHold() {
    return this.#hold;
  }

  // Devuelve el valor de la propiedad First
  getFirst() {
    return this.#first;
  }

  // Asigna un valor recivido por parametro a la propiedad "hold"
  setHold(hold) {
    this.#hold = hold;
  }

  // Asigna un valor recivido por parametro a la propiedad "first"
  setFirst(first) {
    this.#first = first;
  }

  // Metodo que se llama al almacenar una pieza, cambia el tipo de pieza almacenada a la nueva
  hold(type) {
    this.cambiaPieza(type);
  }
}

// Clase que gestiona las siguientes piezas que se jugaran, contine un array de objetos de tipo Piece
class NextPieces {
  constructor() {
    this.pieces = [];
    while (this.pieces.length < 3) {
      this.addPiece()
    }
  }

  // Añade una nueva pieza y si hay mas de 3, borra la ultima
  addPiece() {
    this.pieces.unshift(new Piece(Piece.generateType()));
    if (this.pieces.length > 3) this.#delOldPiece();
    this.#validPosition();
  }

  // Borra la ultima pieza
  #delOldPiece() {
    this.pieces.pop();
  }

  // Genera 3 nuevas piezas
  resetPieces() {
    for (let i = 0; i < 3; i++) {
      this.addPiece();
    }
  }

  // Coloca las piezas en la posicion adecuaada segun su orden de aparicion
  #validPosition() {
    let y = 7;
    this.pieces.forEach(piece => {
      piece.position.x = 1;
      piece.position.y = y;
      y -= 3;
    });
  }

  // Devuelve el tipo de la pieza siguiente
  getNext() {
    let next = this.pieces[2].type;
    this.addPiece();
    return next;
  }

  // Actualiza el tablero de las piezas siguientes, recibe como parametro el propio board.
  updateBoard(board) {
    board.clear();
    for (let i = 0; i < this.pieces.length; i++) {
      board.solidifyPiece(this.pieces[i]);
    }
  }

}

export {Piece, PlayerPiece, HoldPiece, NextPieces}