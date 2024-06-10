<!DOCTYPE html>
<html lang="en">

<!-- Pagina de solicitud de cambio de contraseña -->

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
  require PROJECT_ROUTE . "scripts/php/functionsUser.php";
  require PROJECT_ROUTE . "scripts/php/database.php";
  require PROJECT_ROUTE . 'scripts/php/plantillas.php';

  session_start();
  $sesion = isset($_SESSION['user_id']);

  echo navBar($sesion);

  $errors = [];

  // Se comprueba que se ha enviado un formulario
  if (!empty($_POST)) {
    $email = trim($_POST['email']);

    // Se comprueba que el campo del correo no esta vacio
    if (isNull([$email])) {
      $errors[] = ERROR_VOID_FIELDS;
    }

    // Se comprueba que el correo es un correo valido
    if (!isEmail($email)) {
      $errors[] = ERROR_INVALID_EMAIL;
    }

    if (count($errors) === 0) {
      // Se comprueba que el correo introducido esta en la base de datos
      if (existEmail($email)) {
        // Se obtine la ID del usuario con dicho correo
        $datosUsu = getIdUserByEmail($email);
        // Se genera un nuevo token para el cambio de contraseña
        $token = solicitaPassword($datosUsu["id"]);

        // Si el token se ha generado correctamente se manda un correo con un link que contiene la ID y el token para el cambio de contraseña
        if ($token !== null) {
          require PROJECT_ROUTE . "scripts/php/sendEmail.php";
          $mailer = new Mailer();

          $url = SITE_URL . "reset_password.php?id=" . $datosUsu["id"] . "&token=" . $token;
          $asunto = 'Cambio de contraseña - Tetris Online';
          $cuerpo = "Hola <strong><i>{$datosUsu['name']}</i></strong><br><br>Se ha solicitado un cambio de contrase&ntilde;a en <strong>Tetris Online</strong>, si ha sido usted, pulse en el siguiente enlace: <br><a href='$url'>$url</a><br>Si <strong>NO</strong> ha sido usted, puede ignorar este correo.<br><br>Un saludo";

          if ($mailer->sendEmail($email, $asunto, $cuerpo)) {
            echo "<div>";
            echo "<p><strong>Correo enviado</strong></p>";
            echo "<p>Hemos enviado un correo electr&oacute;nico a la dirección <strong>'$email'</strong> para restablecer la contraseña</p>";
            echo "</div>";

            exit;
          }
        }
      } else {
        $errors[] = ERROR_EMAIL_NOT_FOUND;
      }
    }
  }
  ?>

  <!-- Formulario de recuperacion -->
  <main class="form-login mx-auto pt-4">
    <h3>Recuperar contrase&ntilde;a</h3>
    <?php showErrors($errors); ?>
    <form action="recoverPass.php" method="post" class="row g-3" autocomplete="off">
      <div class="form-floating">
        <input class="form-control" type="email" name="email" id="email" placeholder="Correo electrónico" required>
        <label for="email">Correo electr&oacute;nico</label>
      </div>

      <div class="d-grid gap-3 col-12">
        <button type="submit" class="btn btn-primary">Continuar</button>
      </div>

      <hr>

      <div class="col-12">
        ¿No tienes cuenta? <a href="signup.php">Registrate aquí</a>
      </div>
    </form>
  </main>
</body>

</html>