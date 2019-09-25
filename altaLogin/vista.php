<?php

	function mostrarmensaje($titulo, $texto) {
		$mensaje = file_get_contents("mensaje.html");
		$mensaje = str_replace("##texto##", $texto, $mensaje);
		$mensaje = str_replace("##titulo##", $titulo, $mensaje);
		$trozos = explode("##opcion##", $mensaje);

		$cuerpo = "";
		if(isset($_SESSION["user"])){
			if ($_SESSION["user"]=="admin1234"){
				$cuerpo = $trozos[1];
			}	
		}
		
		echo $trozos[0] . $cuerpo . $trozos[2];
	}


	function vmostrarregistro($resultado) {
		$cadena = file_get_contents("registro.html");
		$trozos = explode("##opciondesplegable##", $cadena);
		$cuerpo = "";
		while($datos=$resultado->fetch_assoc()){
			 $trozos[1] = str_replace("##provincia##", $datos["provincia"], $trozos[1]);
			 $trozos[1] = str_replace("##idprovincia##", $datos["id_provincia"], $trozos[1]);
			 $cuerpo .= $trozos[1];
			$trozos = explode("##opciondesplegable##", $cadena);
		}
		echo $trozos[0].$cuerpo.$trozos[2];
	}

	function vmostrarlogin() {
		echo file_get_contents("login.html");
	}

	/**************************************
	Registramos un usuario en la base de datos
	1.- Contraseñas diferentes --> Devuelvo: -1
	2.- Usuario ya registrado --> Devuelvo: -2
	3.- Campo nombre vacio --> Devuelvo: -3
	4.- Campo usuario vacio --> Devuelvo: -4
	5.- Campo password vacio --> Devuelvo: -5
	6.- Error con la base de datos --> Devuelvo: -6
	7.- Campo correo vacio --> Devuelvo: -7
	8.- Campo fecha vacio --> Devuelvo: -8
	9.- Campo provincia vacio --> Devuelvo: -9
	10.- Guardado correctamente --> Devuelvo: 1
	(Nota: guardamos la contraseña encriptada md5)
	******************************************/
	function vresultadoregistro($resultado) {
		switch($resultado) {
			case 1 :
				mostrarmensaje("Registro de usuario", "Se ha dado de alta correctamente el usuario");
			break;
			case -1 :
				mostrarmensaje("Registro de usuario", "Las contraseñas deben ser iguales");
			break;
			case -2 :
				mostrarmensaje("Registro de usuario", "El usuario ya existe en la base de datos");
			break;
			case -3 :
				mostrarmensaje("Registro de usuario", "No se puede dejar el campo nombre vacio");
			break;
			case -4 :
				mostrarmensaje("Registro de usuario", "No se puede dejar el campo usuario vacio");
			break;
			case -5 :
				mostrarmensaje("Registro de usuario", "No se pueden dejar los campos de contraseña vacíos");
			break;
			case -6 :
				mostrarmensaje("Registro de usuario", "Se ha producido un error en la base de datos");
			break;
			case -7 :
				mostrarmensaje("Registro de usuario", "No se puede dejar el campo del correo vacio");
			break;
			case -8 :
				mostrarmensaje("Registro de usuario", "No se puede dejar el campo fecha vacio");
			break;
			case -9 :
				mostrarmensaje("Registro de usuario", "No se puede dejar el campo provincia vacio");
			break;
		}
	}

	function vresultadologin($resultado){
		switch($resultado) {
			case 1 :
				echo "<script>window.location = 'http://webalumnos.tlm.unavarra.es:10705/final/index.php'</script>";
			break;
			case -1 :
				mostrarmensaje("Log-in de usuario", "Contraseña incorrecta");
			break;
			case -2 :
				mostrarmensaje("Log-in de usuario", "Usuario y Contraseña incorrecta");
			break;
			case -3 :
				mostrarmensaje("Log-in de usuario", "Se ha producido un error en la base de datos");
			break;
		}
	}

?>
