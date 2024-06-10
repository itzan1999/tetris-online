<!DOCTYPE html>
<html lang="en">
<!-- Pagina de login -->

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tetris</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/general.css">
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

  $errors = [];

  // Si se ha enviado el formulario, se comprueba que los campos no esten vacios y se llama a la funcion que se encarga del login
  if (!empty($_POST)) {
    $user = trim($_POST['user']);
    $password = trim($_POST['password']);

    if (isNull([$user, $password])) {
      $errors[] = ERROR_VOID_FIELDS;
    }

    if (count($errors) === 0) {
      $errors[] = login($user, $password);
    }
  }
  ?>

  <main class="form-login mx-auto pt-4">
    <h2>Iniciar Sesion</h2>
    <?php showErrors($errors); ?>
    <form class="row g-3" action="login.php" method="post" autocomplete="off">

      <div class="form-floating">
        <input type="text" class="form-control" name="user" id="txt_user" placeholder="Usuario" required>
        <label for="txt_user" class="floatingInput">Usuario</label>
      </div>

      <div class="form-floating">
        <input type="password" class="form-control" name="password" id="txt_password" placeholder="Contraseña" required>
        <label for="txt_password" class="floatingInput">Contraseña</label>
      </div>

      <div class="col-12">
        <a href="recoverPass.php">¿Olvidaste tu contraseña?</a>
      </div>

      <div class="d-grid gap-3 col-12">
        <button type="submit" class="btn btn-primary">Iniciar</button>
      </div>

      <hr>

      <div class="col-12">
        ¿No tienes cuenta? <a href="signup.php">Registrate aquí</a>
      </div>
    </form>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>