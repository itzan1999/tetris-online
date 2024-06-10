// Clase que controla los archivos de audio
export default class Sound {
  // El constructor recibe los elementos <audio> del HTML
  constructor(audio) {
    this.AudioContext = window.AudioContext;
    this.audioCtx = new AudioContext();

    
    this.element = audio;
    this.track = this.audioCtx.createMediaElementSource(audio);
    this.gainNode = this.audioCtx.createGain();

    this.track.connect(this.gainNode).connect(this.audioCtx.destination);
  }

  // Reanuda la pista de audio
  resume() {
    if (this.audioCtx.state === "suspended") {
      this.audioCtx.resume();
    }
  }

  // Inicia la pista de audio
  play() {
    this.resume();
    this.element.play();
  }

  // Pausa la pista de audio
  pause() {
    this.resume();
    this.element.pause();
  }

  // Reinicia la pista de audio
  reset() {
    this.element.load();
  }
}

