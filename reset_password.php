<!DOCTYPE html>
<html lang="es">

<!-- Pagina de cambio de contraseña -->

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tetris</title>
  <link rel="stylesheet" href="./css/general.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

  <?php
  require_once $_SERVER['DOCUMENT_ROOT'] . "/tetris_online/config/config.php";
  require PROJECT_ROUTE . "scripts/php/database.php";
  require PROJECT_ROUTE . "scripts/php/functionsUser.php";
  require PROJECT_ROUTE . 'scripts/php/plantillas.php';

  session_start();
  session_destroy();
  $sesion = isset($_SESSION['user_id']);

  echo navBar($sesion);

  // Se asignan a variables los valores recuperados por GET
  // Si no se han recibdo por GET se le asignan las recividas por POST
  // Sitampoco existen, se les asigna NULL
  $id = $_GET['id'] ?? $_POST['user_id'] ?? null;
  $token = $_GET['token'] ?? $_POST['token'] ?? null;

  // Si es NULL, se redirige a la pagina principal
  if ($id === null || $token === null) {
    header('Location: index.php');
    exit;
  }

  // Se valida que el token e ID recibidos corresponden a los que estan almacenados en la BD, si no, se muestra un error.
  if (!valPassRequest($id, $token)) {
    echo "<div>";
    echo ERROR_INVALID_INFO_PASS_REQUEST;
    echo "</div>";
    exit;
  }

  $errors = [];

  // Si se han recivido datos del formulario
  if (!empty($_POST)) {

    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    // Se comprueban que se han rellenados todos los campos
    if (isNull([$id, $token, $password, $repassword])) {
      $errors[] = ERROR_VOID_FIELDS;
    }

    // Que la nueva contraseña sea segura
    if ($error = testPassword($password)) {
      $errors[] = $error;
    }

    // Que la contraseña y su confirmacion sean iguales
    if (!valPassword($password, $repassword)) {
      $errors[] = ERROR_PASS_AND_CONFIR_NOT_MATCH;
    }

    // Si no hay errores, se asigna la nueva contraseña al usuario y se muestra el mensaje correspondiente
    if (count($errors) === 0) {
      $pass_hash = generarPass($password);
      if (updatePassword($id, $pass_hash)) {
        echo "<div>";
        echo MSG_PASS_CHANGED;
        echo "</div>";
        exit;
      } else {
        $errors[] = "Error al modificar la contraseña. Intentalo de nuevo.";
      }
    }
  }

  ?>

  <!-- Formulario para el cambio de contraseña -->
  <main class="form-login mx-auto pt-4">
    <h2>Cambiar contraseña</h2>
    <?php showErrors($errors);
    ?>
    <form class="row g-3" action="reset_password.php" method="post" autocomplete="off">
      <input type="hidden" name="user_id" id="user_id" value="<?= $id; ?>">
      <input type="hidden" name="token" id="token" value="<?= $token; ?>">

      <div class="form-floating">
        <input type="password" class="form-control" name="password" id="password" placeholder="Nueva contraseña" require>
        <label for="password" class="floatingInput">Nueva contraseña</label>
      </div>

      <div class="form-floating">
        <input type="password" class="form-control" name="repassword" id="repassword" placeholder="Confirmar contraseña" require>
        <label for="repassword" class="floatingInput">Confirmar contraseña</label>
      </div>

      <div class="d-grid gap-3 col-12">
        <button type="submit" class="btn btn-primary">Continuar</button>
      </div>

    </form>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>