<?php
// Genera la barra de navegacion de la pagina web, la cual varia si hay sesion iniciada o no
// Devuelve un string con los elementos HTML de dicha barra de navegacion
function navBar($sesion) {

  $nav = <<< HTML
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark p-2 fixed-top" data-bs-theme="dark">
      <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="index.php">Tetris Online</a>
        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbarNav">
          <div class="navbar-collapse collapse d-flex justify-content-between">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link bg-success rounded text-white fw-bolder" href="tetris.php">Jugar</a>
              </li>
              <li class="nav-item">
                <a class="nav-link rounded text-warning fw-bolder" href="ranking.php">Ranking</a>
              </li>
  HTML;

  if ($sesion) {
    $nav .= <<< HTML
      <li class="nav-item">
        <a class="nav-link fw-semibold text-light" href="profile.php">Perfil</a>
      </li>
    HTML;
  }

  $nav .= '</ul><ul class="navbar-nav">';

  if ($sesion) {
    $nav .= <<< HTML
        <li class="nav-item">
          <a class="nav-link fw-normal" href="index.php?logout=1">Cerrar Sesion</a>
        </li>
      HTML;
  } else {
    $nav .= <<< HTML
      <li class="nav-item">
        <a class="bg-primary text-white rounded fw-bold nav-link fw-normal me-2" href="login.php">Iniciar Sesion</a>
      </li>
      <li class="nav-item">
        <a class="bg-secondary text-white rounded nav-link fw-normal" href="signup.php">Registrarse</a>
      </li>
      HTML;
  }
  $nav .= <<< HTML
            </ul>
          </div>
        </div>
      </div>
    </nav>
  HTML;
  return $nav;
}
