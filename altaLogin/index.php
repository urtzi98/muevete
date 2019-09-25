<?php
	include "modelo.php";
	include "vista.php";

	session_start();

	//Soy el controlador
	if (isset($_GET["accion"])) {
		$accion = $_GET["accion"];
		$id = $_GET["id"];
	} elseif (isset($_POST["accion"])) {
			$accion = $_POST["accion"];
			$id = $_POST["id"];
	} /*else {
		$accion = "registro";
		$id = 1;
	}*/

	if ($accion == "registro") {
		switch ($id) {
			case 1 : 
				vmostrarregistro(mcogerprovincias());
			break;
			case 2 : 
				vresultadoregistro(mvalidaraltausuario());
			break;
		}
	}
	elseif ($accion == "login"){
		switch ($id) {
			case 1 : 
				vmostrarlogin();
			break;
			case 2 : 
				vresultadologin(mvalidarusuario());
			break;
		}
	}

?>
