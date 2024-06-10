<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tetris</title>
  <link rel="stylesheet" href="./css/general.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>

  <?php
  // Archivo al cual se accede mediante el link enviado por correo y que se encarga de activar el usuario
  require_once $_SERVER['DOCUMENT_ROOT'] . "/tetris_online/config/config.php";
  require PROJECT_ROUTE . 'scripts/php/database.php';
  require PROJECT_ROUTE . 'scripts/php/plantillas.php';

  session_start();
  $sesion = isset($_SESSION['user_id']);

  echo navBar($sesion);

  $id = isset($_GET['id']) ? $_GET['id'] : '';
  $token = isset($_GET['token']) ? $_GET['token'] : '';

  // Si la URL no contiene la ID y el token se redirige a la pagina principal
  if ($id === '' || $token === '') {
    header('Location: ' . PROJECT_ROUTE . 'index.php');
    exit;
  }

  // Se valida que el token coincide con la ID y se muestra el mensaje correspondiente
  echo ("<div>" . valToken($id, $token) . "</div>");

  ?>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>