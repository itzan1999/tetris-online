<!DOCTYPE html>
<html lang="en">

<!-- Pagina de registro -->

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tetris</title>
  <link rel="stylesheet" href="./css/general.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="css/styles.css">
</head>

<body>
  <?php
  require_once $_SERVER['DOCUMENT_ROOT'] . "/tetris_online/config/config.php";
  require_once PROJECT_ROUTE . "scripts/php/database.php";
  require_once PROJECT_ROUTE . "scripts/php/functionsUser.php";
  require PROJECT_ROUTE . 'scripts/php/plantillas.php';

  session_start();
  $sesion = isset($_SESSION['user_id']);

  echo navBar($sesion);

  $errors = [];

  if (!empty($_POST)) {
    $user = trim($_POST['user']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $country = trim($_POST['country']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (isNull([$user, $name, $email, $country, $password, $repassword])) {
      $errors[] = ERROR_VOID_FIELDS;
    }

    if (!isEmail($email)) {
      $errors[] = ERROR_INVALID_EMAIL;
    }

    if ($error = testPassword($password)) {
      $errors[] = $error;
    }

    if (!valPassword($password, $repassword)) {
      $errors[] = ERROR_PASS_AND_CONFIR_NOT_MATCH;
    }

    if (existUser($user)) {
      $errors[] = "El usuario $user ya existe";
    }

    if (existEmail($email)) {
      $errors[] = "El email $email ya esta siendo utilizado";
    }

    if (count($errors) === 0) {
      $passCifrada = generarPass($password);
      $token = generarToken();
      if (registrarUsu([$user, $passCifrada, $token])) {
        if (registrarDatosUsu([getIdWithUser($user), $name, $country, $email])) {
          // Usuario añadido a la BD
          // Enviar correo de confirmacion
          require PROJECT_ROUTE . "scripts/php/sendEmail.php";

          $id = getIdWithUser($user); // TODO Encriptar ID del usuario para pasarla en la URL
          $url = SITE_URL . "activeUser.php?id=$id&token=$token";
          $asunto = 'Activar cuenta - Tetris Online';
          $cuerpo = "Hola <strong><i>$name</i></strong><br><br>Bienvenido a <strong>Tetris Online</strong><br><br>Para finalizar el proceso de registro, es necesario que pulses en el siguiente link: <a href='$url'>Activar cuenta</a>";

          $mailer = new Mailer();

          if ($mailer->sendEmail($email, $asunto, $cuerpo)) {
            echo "<div>Para terminar el proceso de registro, active la cuenta desde el correo electronico enviado a '$email'</div>";
            exit;
          }
        } else {
          $errors[] = ERROR_ADD_USER_DATA;
        }
      } else {
        $errors[] = ERROR_ADD_USER;
      }
    }
  }
  ?>

  <!-- Formulario de registro -->
  <main>
    <div class="container">

      <h2>Datos del usuario</h2>
      <?php showErrors($errors); ?>

      <form class="row g-3" action="signup.php" method="post" autocomplete="off">
        <div class="col-md-6">
          <label for="txt_user"><span class="text-danger">*</span> Nombre de usuario</label>
          <input type="text" name="user" id="txt_user" class="form-control" <?php if ($errors) echo "value='$user'" ?> required>
          <span id="valUser" class="text-danger"></span>
        </div>
        <div class="col-md-6">
          <label for="txt_name"><span class="text-danger">*</span> Nombre</label>
          <input type="text" name="name" id="txt_name" class="form-control" <?php if ($errors) echo "value='$name'" ?> required>
        </div>
        <div class="col-md-6">
          <label for="txt_email"><span class="text-danger">*</span> Email</label>
          <input type="email" name="email" id="txt_email" class="form-control" <?php if ($errors) echo "value='$email'" ?> required>
          <span id="valIsEmail" class="text-danger"></span>
          <span id="valEmail" class="text-danger"></span>
        </div>
        <div class="col-md-6">
          <label for="slt_country"><span class="text-danger">*</span> Pais</label>
          <select class="form-control" name="country" id="slt_country">
            <option selected>Selecciona un pais...</option>
            <?php
            // Rellena las opciones del select con todos los paises
            ($errors) ? optionsPaises($country) : optionsPaises(null);
            ?>
          </select>
        </div>
        <div class="col-md-6">
          <label for="txt_password"><span class="text-danger">*</span> Contraseña</label>
          <input type="password" name="password" id="txt_password" class="form-control" <?php if ($errors) echo "value='$password'" ?> required>
          <span id="testPass" class="text-danger"></span>
          <span id="valPass" class="text-danger"></span>
        </div>
        <div class="col-md-6">
          <label for="txt_confirm_password"><span class="text-danger">*</span> Confirmar contraseña</label>
          <input type="password" name="repassword" id="txt_confirm_password" class="form-control" <?php if ($errors) echo "value='$repassword'" ?> required>
          <span id="valRepass" class="text-danger"></span>
        </div>

        <i><b>Nota:</b> Los campos con asterisco son obligatorios</i>

        <div class="col-12">
          <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
      </form>
    </div>
  </main>


  <script type="module" src="scripts/js/valFormRegister.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</body>

</html>