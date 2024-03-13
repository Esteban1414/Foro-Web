<?php

require_once "../../config/app.php";
require_once "../../autoload.php";

use user\controllers\userController;

if (isset($_POST['user_module'])) {

	$insUsuario = new userController();

	if ($_POST['user_module'] == "admin_registration") {
		echo $insUsuario->registerUser("admin");
	}

	if ($_POST['user_module'] == "user_registration") {
		echo $insUsuario->registerUser("user");
	}

	// if($_POST['modulo_usuario']=="eliminar"){
	// 	echo $insUsuario->eliminarUsuarioControlador();
	// }

	if ($_POST['user_module'] == "update") {
		echo $insUsuario->updateUser();
	}

	if ($_POST['user_module'] == "report_user") {
		echo $insUsuario->reportUser();
	}
	if ($_POST['user_module'] == "suspend_user") {
		echo $insUsuario->suspendUser();
	}
	// if($_POST['modulo_usuario']=="eliminarFoto"){
	// 	echo $insUsuario->eliminarFotoUsuarioControlador();
	// }

	//Se accede a este archivo
} else {
	header("Location: " . APP_URL . "user/login");
}
