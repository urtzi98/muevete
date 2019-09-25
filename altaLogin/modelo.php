<?php

	function conexion() {
		$con = mysqli_connect("dbserver", "grupo05", "iPhahquo5a", "db_grupo05");
		$con->set_charset("utf8");
		//$con = mysqli_connect("dbserver", "grupo13", "fasdfa", "dfadf");
		return $con;
	}

	function mcogerprovincias(){
		$bd = conexion();
		$consulta = "SELECT * FROM Provincias";
		return $bd->query($consulta);

	}

	/**************************************
	Registramos un usuario en la base de datos
	1.- Contraseñas diferentes --> Devuelvo: -1
	2.- Usuario ya registrado --> Devuelvo: -2
	3.- Campo nombre vacio --> Devuelvo: -3
	4.- Campo usuario vacio --> Devuelvo: -4
	5.- Campo password vacio --> Devuelvo: -5
	6.- Error con la base de datos --> Devuelvo: -6
	7.- Campo correo vacio --> Devuelvo -7
	8.- Campo fecha vacio --> Devuelvo -8
	9.- Campo provincia --> Devuelvo -9
	10.- Guardado correctamente --> Devuelvo: 1
	(Nota: guardamos la contraseña encriptada md5)
	******************************************/

	function mvalidaraltausuario() {
		$bd = conexion();

		$nombre = $_POST["nombre"];
		$user = $_POST["user"];
		$password = $_POST["password"];
		$password2 = $_POST["password2"];
		$correo = $_POST["correo"];
		$fecha = $_POST["fecha"];
		$provincia = $_POST["provincia"];
		if (strlen($nombre) == 0) {
			return -3;
		}

		if (strlen($user) == 0) {
			return -4;
		}
		
		if (strlen($correo) == 0) {
			return -7;
		}
		
		if (strlen($fecha) == 0) {
			return -8;
		}

		if (strlen($provincia) == 0) {
			return -9;
		}
		if ($provincia==0){
			return -9;
		}
		

		if ((strlen($password) ==0) || (strlen($password2) == 0)) {
			return -5;
		}

		if ($password != $password2) {
			return -1;
		}

		$consulta = "select * from Usuario where UserID = '$user'";
		if ($resultado = $bd->query($consulta))  {
			if ($datos = $resultado->fetch_assoc()) {
				return -2;
			}
		} else {
			if (strlen($resultado) != 0){
				//Error en la consulta			
				return -6;
			}			
		}

		//Añadimos el usuario
		$consulta = "insert into Usuario (Nombre, UserID, Contrasena, Fecha, Correo, Provincia) values ('$nombre', '$user', '$password','$fecha','$correo','$provincia')";
		if ($bd->query($consulta)) {
			$_SESSION["user"] = $user;
			$_SESSION["password"] = $password;
			echo $datos["Contrasena"];
			$_SESSION["tiempo"] = time();
			return 1;
		} else {
			//Error al añadirlo a la BD
			return -6;
		}

	}


	/**************************************
	Logeamos un usuario en la base de datos
	1.- Contraseña incorrecta--> Devuelvo -1
	2.- Usuario y contraseña incorrecta --> Devuelvo -2
	3.- Error en la base de datos --> Devuelvo -3
	3.- Logeado correctamente --> Devuelvo 1
	*****************************************/
	function mvalidarusuario() {
		$bd = conexion();
		
		$usuario = $_POST["usuario"];
		$password = $_POST["password"];

		$consulta = "select * from Usuario where UserID = '$usuario'";

		if ($resultado = $bd->query($consulta)) {
			if ($datos = $resultado->fetch_assoc()) {
				if ($password == $datos["Contrasena"]) {
					//Usuario y contraseña correcta
					$_SESSION["user"] = $usuario;
					$_SESSION["password"] = $datos["Contrasena"];
					$_SESSION["tiempo"] = time();
					return 1;	
				} else {
					//echo "No coinciden las contraseñas";					
					return -1;
				}
			} else {
				//Usuario y contraseña incorrecta
				return -2;
			}
		} else {
			//echo "error base de datos";			
			return -3;
		}

	}

?>










