<?php
// * Configuracion de la Base de Datos
define('DB_HOST_NAME', 'localhost');
define('DB_USER_NAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'tetris');

// * Datos para envio de correo electronico
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_USER', 'tetrisonline.tfg.asir@gmail.com');
define('MAIL_PASS', 'yilhjzpmmkbizgdx');
define('MAIL_PORT', '587');

// * Configuracion del sistema
define('SITE_URL', 'http://localhost/tetris_online/');
define('NAME_PROJECT', 'tetris_online');
define('PROJECT_ROUTE', $_SERVER['DOCUMENT_ROOT'] . '/' . NAME_PROJECT . '/');

// * TOKENS
define("KEY_TOKEN", "SDF-s3*34.2Dk");



// ! Mensajes de Error
define('ERROR_INCORRECT_PASS', 'La contrase&ntilde;a no es correcta');
define('ERROR_ACCOUNT_INACTIVE', 'La cuenta no ha sido activada');
define('ERROR_INVALID_USER', 'Usuario no valido');
define('ERROR_INVALID_LOGIN', 'El usuario y/o contraseña son incorrectos');
define('ERROR_SHORT_PASS', 'La contraseña debe tener al menos 8 caracteres');
define('ERROR_LONG_PASS', 'La contraseña no pude tener más de 24 caracteres');
define('ERROR_LOWERCASE_PASS', 'La contraseña debe tener una min&uacute;scula');
define('ERROR_UPPERCASE_PASS', 'La contraseña debe tener una may&uacute;scula');
define('ERROR_NUMBER_PASS', 'La contraseña debe tener un número');
define('ERROR_TO_ACTIVATE_ACCOUNT', 'Error al activar cuenta');
define('ERROR_UNKNOWN_USER', 'No existe el registro del usuario');
define('ERROR_CONNECTION_FAILED', 'Conexion fallida');
define('ERROR_USER_NOT_AVAILABLE', 'Usuario no disponible');
define('ERROR_EMAIL_NOT_AVAILABLE', 'Email no disponible');
define('ERROR_INVALID_EMAIL', 'El email no es valido');
define('ERROR_PASS_AND_CONFIR_NOT_MATCH', 'La contraseña y la confirmacion no coinciden');
define('ERROR_VOID_FIELDS', 'Debe rellenar todos los campos');
define('ERROR_ADD_USER_DATA', 'Error al registrar los datos del usuario');
define('ERROR_ADD_USER', 'Error al registrar usuario');
define('ERROR_EMAIL_NOT_FOUND', 'No existe una cuenta asociada a esta direcci&oacute;n de correo');
define('ERROR_INVALID_INFO_PASS_REQUEST', 'No se pudo verificar la informacion');
define('ERROR_TO_CHANGE_PASSWORD', 'Error al modificar la contrase&ntilde;a. Intentalo de nuevo.');


// ? Datos hardcode
define('COUNTRYS', array("Afganistán", "Albania", "Alemania", "Andorra", "Angola", "Antigua y Barbuda", "Arabia Saudita", "Argelia", "Argentina", "Armenia", "Australia", "Austria", "Azerbaiyán", "Bahamas", "Bangladés", "Barbados", "Baréin", "Bélgica", "Belice", "Benín", "Bielorrusia", "Birmania", "Bolivia", "Bosnia y Herzegovina", "Botsuana", "Brasil", "Brunéi", "Bulgaria", "Burkina Faso", "Burundi", "Bután", "Cabo Verde", "Camboya", "Camerún", "Canadá", "Catar", "Chad", "Chile", "China", "Chipre", "Ciudad del Vaticano", "Colombia", "Comoras", "Corea del Norte", "Corea del Sur", "Costa de Marfil", "Costa Rica", "Croacia", "Cuba", "Dinamarca", "Dominica", "Ecuador", "Egipto", "El Salvador", "Emiratos Árabes Unidos", "Eritrea", "Eslovaquia", "Eslovenia", "España", "Estados Unidos", "Estonia", "Etiopía", "Filipinas", "Finlandia", "Fiyi", "Francia", "Gabón", "Gambia", "Georgia", "Ghana", "Granada", "Grecia", "Guatemala", "Guyana", "Guinea", "Guinea ecuatorial", "Guinea-Bisáu", "Haití", "Honduras", "Hungría", "India", "Indonesia", "Irak", "Irán", "Irlanda", "Islandia", "Islas Marshall", "Islas Salomón", "Israel", "Italia", "Jamaica", "Japón", "Jordania", "Kazajistán", "Kenia", "Kirguistán", "Kiribati", "Kuwait", "Laos", "Lesoto", "Letonia", "Líbano", "Liberia", "Libia", "Liechtenstein", "Lituania", "Luxemburgo", "Madagascar", "Malasia", "Malaui", "Maldivas", "Malí", "Malta", "Marruecos", "Mauricio", "Mauritania", "México", "Micronesia", "Moldavia", "Mónaco", "Mongolia", "Montenegro", "Mozambique", "Namibia", "Nauru", "Nepal", "Nicaragua", "Níger", "Nigeria", "Noruega", "Nueva Zelanda", "Omán", "Países Bajos", "Pakistán", "Palaos", "Palestina", "Panamá", "Papúa Nueva Guinea", "Paraguay", "Perú", "Polonia", "Portugal", "Reino Unido", "República Centroafricana", "República Checa", "República de Macedonia", "República del Congo", "República Democrática del Congo", "República Dominicana", "República Sudafricana", "Ruanda", "Rumanía", "Rusia", "Samoa", "San Cristóbal y Nieves", "San Marino", "San Vicente y las Granadinas", "Santa Lucía", "Santo Tomé y Príncipe", "Senegal", "Serbia", "Seychelles", "Sierra Leona", "Singapur", "Siria", "Somalia", "Sri Lanka", "Suazilandia", "Sudán", "Sudán del Sur", "Suecia", "Suiza", "Surinam", "Tailandia", "Tanzania", "Tayikistán", "Timor Oriental", "Togo", "Tonga", "Trinidad y Tobago", "Túnez", "Turkmenistán", "Turquía", "Tuvalu", "Ucrania", "Uganda", "Uruguay", "Uzbekistán", "Vanuatu", "Venezuela", "Vietnam", "Yemen", "Yibuti", "Zambia", "Zimbabue"));

// ? Mensajes
define('MSG_ACCOUNT_ACTIVATED', 'Cuenta activada');
define('MSG_PASS_CHANGED', 'Contrase&ntilde;a modificada');
