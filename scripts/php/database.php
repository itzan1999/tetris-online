<?php

// Crea una conexion con la base de datos
function conectar() {
  require_once $_SERVER['DOCUMENT_ROOT'] . "/tetris_online/config/config.php";

  $mysqli = new mysqli(DB_HOST_NAME, DB_USER_NAME, DB_PASSWORD, DB_NAME);

  if (mysqli_connect_errno()) {
    printf(ERROR_CONNECTION_FAILED . ": %s\n", mysqli_connect_error());
    exit();
  }

  return $mysqli;
}

// Validar que el usuario no exista
function existUser($user) {
  $cnx = conectar();

  $sql = $cnx->prepare("SELECT id_user FROM users WHERE user_name LIKE ? LIMIT 1 ");
  $sql->execute([$user]);
  if ($sql->get_result()->fetch_row() > 0) {
    $cnx->close();
    return true;
  }
  $cnx->close();
  return false;
}

// Validar que el email no ha sido utilizado
function existEmail($mail) {
  $cnx = conectar();
  $sql = $cnx->prepare("SELECT id_user FROM user_data WHERE email LIKE ? LIMIT 1 ");
  $sql->execute([$mail]);
  if ($sql->get_result()->fetch_row() > 0) {
    $cnx->close();
    return true;
  }
  $cnx->close();

  return false;
}

// Obtener id de usuario mediante el user name
function getIdWithUser($user) {
  $cnx = conectar();
  $sql = $cnx->prepare("SELECT id_user FROM users WHERE user_name = ?");
  $sql->execute([$user]);
  $id = $sql->get_result()->fetch_array()[0];

  $cnx->close();

  return $id;
}

// Obtener user name mediante su ID
function getUserById($id) {
  $cnx = conectar();
  $sql = $cnx->prepare("SELECT user_name FROM users WHERE id_user = ?");
  $sql->execute([$id]);
  $user = $sql->get_result()->fetch_array()[0];

  $cnx->close();

  return $user;
}

// Obtener el ID y el usuario mediante el correo electronico
function getIdUserByEmail($email) {
  $cnx = conectar();
  $sql = $cnx->prepare("SELECT users.id_user, users.user_name, user_data.name FROM users INNER JOIN user_data ON users.id_user = user_data.id_user WHERE user_data.email LIKE ? LIMIT 1");
  $sql->execute([$email]);
  $row = $sql->get_result()->fetch_array();
  $id = $row['id_user'];
  $user = $row["user_name"];
  $name = $row["name"];
  $cnx->close();
  return ["id" => $id, "user" => $user, "name" => $name];
}

// Obtener numero de partidas de un usuario mediante su id
function getNumGamesById($id) {
  $cnx = conectar();
  $sql = $cnx->prepare("SELECT games FROM user_data WHERE id_user = ? LIMIT 1");
  $sql->execute([$id]);
  $row = $sql->get_result()->fetch_array();
  $cnx->close();
  return $row['games'];
}

// Obtener la mejor puntuacion de un usuario mediante su id
function getBestScoreById($id) {
  $cnx = conectar();
  $sql = $cnx->prepare("SELECT best_score FROM user_data WHERE id_user = ? LIMIT 1");
  $sql->execute([$id]);
  $row = $sql->get_result()->fetch_array();
  $cnx->close();
  return $row['best_score'];
}

// Obtener el mejor tiempo de un usuario mediante su id
function getBestTimeById($id) {
  $cnx = conectar();
  $sql = $cnx->prepare("SELECT best_time FROM user_data WHERE id_user = ? LIMIT 1");
  $sql->execute([$id]);
  $row = $sql->get_result()->fetch_array();
  $cnx->close();
  return $row['best_time'];
}

// Obtener el record de nivel alcanzado de un usuario mediante su id
function getBestLevelById($id) {
  $cnx = conectar();
  $sql = $cnx->prepare("SELECT best_level FROM user_data WHERE id_user = ? LIMIT 1");
  $sql->execute([$id]);
  $row = $sql->get_result()->fetch_array();
  $cnx->close();
  return $row['best_level'];
}

// Obtener el numero maximo de lineas limpiadas de un usuario mediante su id
function getBestLinesById($id) {
  $cnx = conectar();
  $sql = $cnx->prepare("SELECT best_lines_clear FROM user_data WHERE id_user = ? LIMIT 1");
  $sql->execute([$id]);
  $row = $sql->get_result()->fetch_array();
  $cnx->close();
  return $row['best_lines_clear'];
}

// Obtener los datos de las partidas de un jugador concreto mediante su id ordenadas por fecha descendiente
function getGamesById($id) {
  $cnx = conectar();
  $sql = $cnx->prepare("SELECT score, max_level, lines_clear, time_game, date_game FROM games WHERE id_user = ? ORDER BY date_game DESC");
  $sql->execute([$id]);
  $rows = [];
  $result = $sql->get_result();
  while ($fila = $result->fetch_array()) {
    $rows[] = [$fila['score'], $fila['max_level'], $fila['lines_clear'], $fila['time_game'], $fila['date_game']];
  }
  $cnx->close();
  return $rows;
}

// Obtener los datos de las 100 mejores partidas almacenadas ordenadas por puntuacion descendente
function getRanking() {
  $cnx = conectar();
  $sql = $cnx->prepare("SELECT id_user, score, max_level, lines_clear, time_game, date_game FROM games ORDER BY score DESC LIMIT 100");
  $sql->execute();
  $rows = [];
  $result = $sql->get_result();
  while ($fila = $result->fetch_array()) {
    $rows[] = [getUserById($fila['id_user']), $fila['score'], $fila['max_level'], $fila['lines_clear'], $fila['time_game'], $fila['date_game']];
  }
  $cnx->close();
  return $rows;
}

// Valida el token para la activacion de la cuenta
function valToken($id, $token) {
  $msg = "";
  $cnx = conectar();
  $sql = $cnx->prepare("SELECT id_user FROM users WHERE id_user = ? AND token_user LIKE ? LIMIT 1");
  $sql->execute([$id, $token]);
  if ($sql->get_result()->fetch_row() > 0) {
    if (activateUser($id)) {
      $msg = MSG_ACCOUNT_ACTIVATED;
    } else {
      $msg = ERROR_TO_ACTIVATE_ACCOUNT;
    }
  } else {
    $msg = ERROR_UNKNOWN_USER;
  }
  $cnx->close();
  return $msg;
}

// Cambia el valor de activacion del usuario para activarlo y borra el token de activacion
function activateUser($id) {
  $cnx = conectar();
  $sql = $cnx->prepare("UPDATE users SET active_user = 1, token_user = '' WHERE id_user = ?");
  if ($sql->execute([$id])) {
    $cnx->close();
    return true;
  } else {
    $cnx->close();
    return false;
  }
}

// Registrar un nuevo usuario en la tabla USERS de la BBDD
function registrarUsu(array $datos) {
  $cnx = conectar();
  $sql = $cnx->prepare("INSERT INTO users (user_name, password, token_user, fecha_alta) VALUES (?, ?, ?, now())");
  if ($sql->execute($datos)) {
    $cnx->close();
    return true;
  }
  $cnx->close();
  return false;
}

// Registrar los datos de un nuevo usurio en la tabla USER_DATA de la BBDD
function registrarDatosUsu(array $datos) {
  $cnx = conectar();
  $sql = $cnx->prepare("INSERT INTO user_data (id_user, name, country, email) VALUES (?,?,?,?)");
  if ($sql->execute($datos)) {
    $cnx->close();
    return true;
  }
  $cnx->close();
  return false;
}

// Obtener contraseña del usuario almacenada en base de datos
function getPassword($user) {
  $cnx = conectar();

  $sql = $cnx->prepare("SELECT password FROM users WHERE user_name LIKE ? LIMIT 1");
  $sql->execute([$user]);
  $sql->bind_result($pass);

  if ($sql->fetch()) {
    return $pass;
  }

  return false;
}

// Comprueba si el usuario esta activo
function isActive($user) {
  $cnx = conectar();
  $sql = $cnx->prepare("SELECT active_user FROM users WHERE user_name LIKE ? LIMIT 1");
  $sql->execute([$user]);
  $sql->bind_result($active);
  $sql->fetch();
  $sql->close();
  if ($active === 1) {
    return true;
  }
  return false;
}

// Registra un token para el cambio de contraseña
function setTokenPassword($id, $token) {
  $cnx = conectar();
  $sql = $cnx->prepare("UPDATE users SET token_password = ? WHERE id_user = ?");
  if ($sql->execute([$token, $id])) {
    $cnx->close();
    return true;
  } else {
    $cnx->close();
    return false;
  }
}

// Registrar el estado de la solicitud del cambio de contraseña
function setPasswordRequest($id, $val) {
  $cnx = conectar();
  $sql = $cnx->prepare("UPDATE users SET password_request = ? WHERE id_user = ?");
  if ($sql->execute([$val, $id])) {
    $cnx->close();
    return true;
  } else {
    $sql->close();
    return false;
  }
}

// Comprueba que el token_pass e ID recibidos concuerdan con los de la BBDD
function verifyTokenPass($id, $token) {
  $cnx = conectar();
  $sql = $cnx->prepare("SELECT id_user FROM users WHERE id_user = ? AND token_password LIKE ? LIMIT 1");
  $sql->execute([$id, $token]);
  if ($sql->get_result()->fetch_row() > 0) {
    $sql->close();
    return true;
  }
  $sql->close();
  return false;
}

// Comprueba que el usuario a solicitado un cambio de contraseña
function verifyPassRequest($id) {
  $cnx = conectar();
  $sql = $cnx->prepare("SELECT password_request FROM users WHERE id_user = ? LIMIT 1");
  $sql->execute([$id]);
  $sql->bind_result($request);
  $sql->fetch();
  $sql->close();
  if ($request === 1)
    return true;
  return false;
}

// Cambia la contraseña de un usuario especifico
function updatePassword($id, $pass) {
  $cnx = conectar();
  $sql = $cnx->prepare("UPDATE users SET password = ? WHERE id_user = ?");
  if ($sql->execute([$id, $pass])) {
    $sql->close();
    if (setTokenPassword($id, '') && setPasswordRequest($id, 0)) {
      return true;
    }
  }
  $sql->close();
  return false;
}

// Inserta datos de partida en la base de datos
function insertGame($datos) {
  $cnx = conectar();
  $sql = $cnx->prepare("INSERT INTO games (id_user, score, max_level, lines_clear, time_game, date_game) VALUES (?, ?, ?, ?, ?, now())");
  if ($sql->execute($datos)) {
    $sql->close();
    return true;
  }
  $sql->close();
  return false;
}

// Borrar partidas de un usuario
function delGamesByID($id) {
  $cnx = conectar();
  $sql = $cnx->prepare("DELETE FROM games WHERE id_user = ?");
  if ($sql->execute([$id])) {
    $sql->close();
    return true;
  }
  $sql->close();
  return false;
}

// Borrar datos de un usuario
function delDataUserByID($id) {
  $cnx = conectar();
  $sql = $cnx->prepare("DELETE FROM user_data WHERE id_user = ?");
  if ($sql->execute([$id])) {
    $sql->close();
    return true;
  }
  $sql->close();
  return false;
}

// Borrar usuario
function delUserByID($id) {
  $cnx = conectar();
  $sql = $cnx->prepare("DELETE FROM users WHERE id_user = ?");
  if ($sql->execute([$id])) {
    $sql->close();
    return true;
  }
  $sql->close();
  return false;
}
