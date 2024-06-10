<?php
// Archivo que recibe la promesa para guardar los datos de la partida en la base de datos y devuelve un JSON con los resultados

require_once $_SERVER['DOCUMENT_ROOT'] . "/tetris_online/config/config.php";
require_once PROJECT_ROUTE . "scripts/php/database.php";

session_start();

$val = [];

if (isset($_POST['action'])) {
  $action = $_POST['action'];
  if ($action === "insertGame" && isset($_SESSION['user_name'])) {
    $datos = explode(',', $_POST['datos']);
    $datos[0] = getIdWithUser($_SESSION['user_name']);
    $val['ok'] = insertGame($datos);
  }
}

echo json_encode($val);
