<?php 

	function conexion() {
		$con = mysqli_connect("dbserver", "grupo05", "iPhahquo5a", "db_grupo05");
		//$con = mysqli_connect("dbserver", "grupo13", "fasdfa", "dfadf");
		return $con;
	}


	//Esta funcion comprueba si hay que cerrar la sesión por inactividad y si la contraseña es correcta
	//devuelve 1 si todo está bien, y numeros negativos en caso contrario
	function mcomprobarusuario() {
		$bd = conexion();

		if (!isset($_SESSION["user"])) {
			echo "cerrada";
			return -4;
		}

		$resta = time() - $_SESSION["tiempo"];
		echo "El número de segundos es : " . $resta . "<br>";

		if ($resta > 1000) {
			echo "Se ha cerrado su sesión por inactividad";
			session_unset();
			session_destroy();
			return -6;
		}

		$_SESSION["tiempo"] = time();

		$consulta = "select * from Usuario where UserID = '" . $_SESSION["user"] . "'";
		if ($resultado = $bd->query($consulta)) {
			if ($datos = $resultado->fetch_assoc()) {
				if ($_SESSION["password"] == $datos["Contrasena"]) {	
					return 1;	
				} else {
					return -3;
				}
			} else {
				return -2;
			}
		} else {
			return -1;
		}		
	}

	function mcogerfotosvehiculo($id){
		$bd = conexion();
		$consulta = "SELECT * FROM images_info WHERE idcoche=".$id;
		if($resultado = $bd->query($consulta)){
			return $resultado;
		}else{
			return -1;
		}
	}
 ?>