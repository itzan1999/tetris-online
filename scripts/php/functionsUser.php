<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/tetris_online/config/config.php";
// require_once PROJECT_ROUTE . 'scripts/php/database.php';

// Genera las opciones del select para todos los paises, dejando como seleccionada la que recibe por parametro
function optionsPaises($county) {
  $paises = COUNTRYS;

  foreach ($paises as $pais) {
    if ($pais === $county) {
      echo "<option value='$pais' selected>$pais</option>";
    } else {
      echo "<option value='$pais'>$pais</option>";
    }
  }
}

// Validar si hay nulos
function isNull(array $params) {
  foreach ($params as $param) {
    if (strlen(trim($param)) === 0) {
      return true;
    }
  }
  return false;
}

// Validar estructura del email
function isEmail($email) {
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return true;
  }
  return false;
}


// Valida conflejidad de la contraseña
function testPassword(string $pass) {
  if (strlen($pass) < 8) return ERROR_SHORT_PASS;
  if (strlen($pass) > 24) return ERROR_LONG_PASS;
  if (!preg_match('`[a-z]`', $pass)) return ERROR_LOWERCASE_PASS;
  if (!preg_match('`[A-Z]`', $pass)) return ERROR_UPPERCASE_PASS;
  if (!preg_match('`[0-9]`', $pass)) return ERROR_NUMBER_PASS;
  return "";
}

// Validar que la contraseña es la misma en el campo "contraseña" y "Confirmar contraseña"
function valPassword($pass, $repass) {
  if (strcmp($pass, $repass) !== 0) {
    return false;
  }
  return true;
}

// Mostrar errores en el formulario
function showErrors(array $errors) {
  if (count($errors) > 0) {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
    foreach ($errors as $error) {
      echo '<li>' . $error . '</li>';
    }
    echo '</ul>';
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
  }
}

// Generar contraseña cifrada
function generarPass($pass) {
  return password_hash($pass, PASSWORD_DEFAULT);
}

// Generar token
function generarToken() {
  return md5(uniqid(mt_rand(), false));
}

// Comprueba si el usuario se puede conectar y llama a startSession()
function login($user, $pass) {
  require_once PROJECT_ROUTE . "scripts/php/database.php";
  if ($passBD = getPassword($user)) {
    if (isActive($user)) {
      if (password_verify($pass, $passBD)) {
        startSession($user);
      } else {
        return ERROR_INVALID_LOGIN;
      }
    } else {
      return ERROR_ACCOUNT_INACTIVE;
    }
  } else {
    return ERROR_INVALID_LOGIN;
  }
}

// Crea las variables de sesion del usuario y redirecciona a la pagina principal
function startSession($user) {
  session_start();
  $_SESSION['user_id'] = getIdWithUser($user);
  $_SESSION['user_name'] = $user;
  header("Location: index.php");
  exit;
}

// Finaliza la sesion del usuario y redirecciona a la pagina principal
function endSession() {
  session_destroy();
  header("Location: index.php");
  exit;
}

// Genera un token para cambiar la contraseña
function solicitaPassword($id) {
  $token = generarToken();

  if (setTokenPassword($id, $token) && setPasswordRequest($id, 1)) {
    return $token;
  }
  return null;
}

// Valida que el token_pass y la ID recibidas sean validas y comprueba que se haya solicitado el cambio de contraseña
function valPassRequest($id, $token) {
  if (verifyTokenPass($id, $token) && verifyPassRequest($id)) return true;
  return false;
}

// Borrar cuenta de usuario
function delUser($user) {
  require_once PROJECT_ROUTE . 'scripts/php/database.php';
  $id = getIdWithUser($user);

  if (delGamesByID($id) && delDataUserByID($id) && delUserByID($id)) {
    session_start();
    session_destroy();
    return true;
  }
  return false;
}
