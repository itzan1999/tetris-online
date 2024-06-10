<?php
// Archivo que recibe las promesas para verificar la formulario en tiempo real y para borrar un usuario y devuelve un JSON con el resultado

require_once $_SERVER['DOCUMENT_ROOT'] . "/tetris_online/config/config.php";
require_once PROJECT_ROUTE . "scripts/php/database.php";
require_once PROJECT_ROUTE . "scripts/php/functionsUser.php";


$datos = [];

if (isset($_POST['action'])) {
  $action = $_POST['action'];

  switch ($action) {
    case "existUser":
      $datos['ok'] = existUser($_POST['user']);
      break;
    case "isEmail":
      $datos['ok'] = isEmail($_POST['mail']);
      break;
    case "existEmail":
      $datos['ok'] = existEmail($_POST['mail']);
      break;
    case "testPass":
      $datos['ok'] = testPassword($_POST['pass']);
      break;
    case "checkVal":
      $datos['ok'] = valPassword($_POST['pass'], $_POST['repass']);
      break;
    case "delUser":
      $datos['ok'] =  delUser($_POST['account']);
      break;
  }
}

echo json_encode($datos);
