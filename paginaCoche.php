<?php
	include "vistaInicio.php";
	include "modelo.php";
	session_start();
	$id = -1;
	if (isset($_POST["id"])){
		$id = $_POST["id"];
	}elseif (isset($_GET["id"])){
		$id = $_GET["id"];
	}

	if (isset($_POST["accion"])) {
		$accion = $_POST["accion"];
		if ($accion == "enviarComment") {
			venviarComment();
		}
	}
	vmostrarvehiculo(mcogerfotosvehiculo($id));

	
	//falta meterle hacia donde redirecciona el boton

?>