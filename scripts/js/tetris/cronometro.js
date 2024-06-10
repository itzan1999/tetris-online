// Clase que controla el cronometro de la partida
export default class Cronometro {
  constructor() {
    this.segundo = 0;
    this.minuto = 0;
    this.hora = 0;
  }

  // Metodo que inicia el cronometro
  iniciar() {
    this.escribir();
    this.control = setInterval(() => this.escribir(), 1000);
  }

  // Metodo que para el cronometro
  parar() {
    clearInterval(this.control);
  }

  // Metodo que reinicia el cronometro
  reiniciar() {
    clearInterval(this.control);

    this.segundo = 0;
    this.minuto = 0;
    this.hora = 0;

    document.getElementById("time").textContent = "00:00:00";
  }

  // Metodo que escribe el estado actual del cronometro
  escribir() {
    this.segundo++;

    if (this.segundo > 59) {
      this.segundo = 0;
      this.minuto++;
    }

    if (this.minuto > 59) {
      this.minuto = 0;
      this.hora++;
    }

    document.getElementById("time").textContent = this.getTimer();
  }

  // Metodo que devuelve el estado actual del cronometro
  getTimer() {
    let mAux, sAux, hAux;
    
    sAux = this.segundo < 10 ? "0" + this.segundo : this.segundo;
    mAux = this.minuto < 10 ? "0" + this.minuto : this.minuto;
    hAux = this.hora < 10 ? "0" + this.hora : this.hora;

    return hAux + ":" + mAux + ":" + sAux;
  }
}