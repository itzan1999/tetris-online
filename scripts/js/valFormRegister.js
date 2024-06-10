/** @format */

// Archivo que valida el tiempo real el formulario de registro

import { ERROR_USER_NOT_AVAILABLE, ERROR_INVALID_EMAIL, ERROR_EMAIL_NOT_AVAILABLE, ERROR_PASS_AND_CONFIR_NOT_MATCH } from "./consts.js";


const $txtUser = document.getElementById("txt_user");
const $txtEmail = document.getElementById("txt_email");
const $txtPass = document.getElementById("txt_password");
const $txtRepass = document.getElementById("txt_confirm_password");
const URL = "scripts/php/registroAjax.php";

//Eventos que se llaman al perder el foco de los elementos del formulario, y llaman a las funciones correspondientes para dicho campo
$txtUser.addEventListener(
  "blur",
  function () {
    existUser($txtUser.value);
  },
  false
);

$txtEmail.addEventListener(
  "blur",
  function () {
    isEmail($txtEmail.value);
    existEmail($txtEmail.value);
  },
  false
);

$txtPass.addEventListener(
  "blur",
  function () {
    testPass($txtPass.value);
    valPass($txtPass.value, $txtRepass.value);
  },
  false
);

$txtRepass.addEventListener(
  "blur",
  function () {
    valPass($txtPass.value, $txtRepass.value);
  },
  false
);

// Funcion que lanza una promesa para comprobar si existe el usuario
function existUser(user) {
  let formData = new FormData();
  formData.append("action", "existUser");
  formData.append("user", user);

  fetch(URL, {
    method: "POST",
    body: formData
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.ok) {
        document.getElementById("valUser").innerHTML = ERROR_USER_NOT_AVAILABLE;
      } else {
        document.getElementById("valUser").innerHTML = "";
      }
    });
}

// Funcion que lanza una promesa para comprobar si el email es valido
function isEmail(mail) {
  let formData = new FormData();
  formData.append("action", "isEmail");
  formData.append("mail", mail);

  fetch(URL, {
    method: "POST",
    body: formData
  })
    .then((response) => response.json())
    .then((data) => {
      if (!data.ok) {
        document.getElementById("valIsEmail").innerHTML = ERROR_INVALID_EMAIL;
      } else {
        document.getElementById("valIsEmail").innerHTML = "";
      }
    });
}

// Funcion que lanza una promesa para comprobar si existe el email
function existEmail(mail) {
  let formData = new FormData();
  formData.append("action", "existEmail");
  formData.append("mail", mail);

  fetch(URL, {
    method: "POST",
    body: formData
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.ok) {
        document.getElementById("valEmail").innerHTML = ERROR_EMAIL_NOT_AVAILABLE;
      } else {
        document.getElementById("valEmail").innerHTML = "";
      }
    });
}

// Funcion que lanza una promesa para comprobar si la contraseña es segura
function testPass(pass) {
  let formData = new FormData();
  formData.append("action", "testPass");
  formData.append("pass", pass);

  fetch(URL, {
    method: "POST",
    body: formData
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.ok) {
        document.getElementById("testPass").innerHTML = `${data.ok}`;
      } else {
        document.getElementById("testPass").innerHTML = "";
      }
    });
}

// Funcion que lanza una promesa para comprobar si la contraseña coincide con la confirmacion
function valPass(pass, repass) {
  let formData = new FormData();
  formData.append("action", "checkVal");
  formData.append("pass", pass);
  formData.append("repass", repass);

  fetch(URL, {
    method: "POST",
    body: formData
  })
    .then((response) => response.json())
    .then((data) => {
      if (!data.ok) {
        document.getElementById("valRepass").innerHTML = ERROR_PASS_AND_CONFIR_NOT_MATCH;
      } else {
        document.getElementById("valRepass").innerHTML = "";
      }
    });
}
