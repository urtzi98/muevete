<?php
	//lo quito para probar
	include "vista.php";
	include "modelo.php";
	session_start();

	$accion = "";
	if (isset($_GET["accion"])) {
		$accion = $_GET["accion"];
	} elseif (isset($_POST["accion"])) {
		$accion = $_POST["accion"];
	} elseif (mcomprobarusuario()==1) {
		//Todo ok
		vmostrarperfil();
	} else {
		vmostrarerrorexpirar();
	}
	
	if($accion=="borrar"){
		echo "se borrará esta cuenta";
		vmostrarperfil();
	}
	if($accion=="cerrarsesion"){
		session_destroy();
		echo "sesion cerrada correctamente<br>";
		echo '<a href="/final/index.php" class="btn btn-info">Volver</a>';
	}
	
	
?>
	