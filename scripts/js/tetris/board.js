/** @format */

import { BOARD_WIDTH } from "./const.js";

// Clase que controla el estado de los tableros (Tablero de Juego, Hold y Next)
export class Board {
  // El constructor recibe el ancho y alto del tablero
  constructor(width, height) {
    this.board = this.#createBoard(width, height);
  }

  // Metodo privado que crea el array que representa el tablero.
  #createBoard(width, height) {
    return Array(height)
      .fill()
      .map(() => Array(width).fill("0"));
  }

  // Asigna a todas las posiciones del tablero un "0"
  clear() {
    this.board.forEach((row) => row.fill("0"));
  }

  // Metodo que recibe una pieza, y comprueba si dicha pieza esta colisionando con los bordes del tablero u otra pieza.
  // Devuelve TRUE en caso de que exista la colision
  checkCollision(piece) {
    return piece.shape.find((row, y) => {
      return row.find((value, x) => {
        return value !== 0 && this.board[y + piece.position.y]?.[x + piece.position.x] !== "0";
      });
    });
  }

  // Metodo que recibe una pieza, y comprueba si dicha pieza esta colisionando con otra pieza del tablero
  // Devuelve TRUE en caso de que exista la colision
  collisionWithPieces(piece) {
    return piece.shape.find((row, y) => {
      return row.find((value, x) => {
        let boardVal = this.board[y + piece.position.y][x + piece.position.x];
        return value !== "0" && boardVal && boardVal !== "0";
      });
    });
  }

  // Metodo que recibe una pieza, y asigna a cada posicion del tablero, correspondientes a la posicion de la pieza, el caracter correspondiente al tipo de pieza
  // De forma que la pieza queda integrada en el tablero
  // Tipos de pieza: I, L, J, O, S, Z, T
  solidifyPiece(piece) {
    piece.shape.forEach((row, y) => {
      if (row.some((val) => (val == 1))) {
        row.forEach((value, x) => {
          const newY = y + piece.position.y;
          const newX = x + piece.position.x;
  
          if (value === 1) {
            this.board[newY][newX] = piece.type;
          }
        });
      }
    });
  }

  // Metodo privado que recorre el tablero y busca que lineas estan completas
  // Devuelve un array que contiene los numeros correspondientes a las filas completas
  #findRowsToRemove() {
    let rows = [];

    this.board.forEach((row, y) => {
      if (row.every((value) => value !== "0")) {
        rows.push(y);
      }
    });

    return rows;
  }

  // Metodo que elimina las filas completas del tablero
  // Devuelve el numero de filas eliminadas
  removeRows() {
    // LLama al metodo findRowsToRemove() para localizar las filas a eliminar
    let rows = this.#findRowsToRemove();

    // Recorre el array de las filas completas, y borra la fila correspondiete del tablero y añade una fila vacia ("0") en la parte superior del tablero
    rows.forEach((y) => {
      const newRow = Array(BOARD_WIDTH).fill("0");
      this.board.splice(y, 1);
      this.board.unshift(newRow);
    });

    return rows.length;
  }
}
