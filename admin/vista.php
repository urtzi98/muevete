<?php
	
	function vmostrarlogin() {
		echo file_get_contents("login.html");
	}	

	function mostrarmensaje($titulo, $texto,$bien) {
		$mensaje = file_get_contents("mensaje.html");
		$mensaje = str_replace("##titulo##", $titulo, $mensaje);
		$mensaje = str_replace("##texto##", $texto, $mensaje);
		$trozos = explode("##todobien##", $mensaje);
		$aux = "";
		if($bien){
			$aux = $trozos[0].$trozos[2];
		}else{
			$aux = $trozos[0].$trozos[1]."</body></html>";
		}
		echo $aux;
	}

	function vresultadologin($resultado) {
		switch ($resultado) {
			case 1 :
				echo file_get_contents("menuadmin.html");
				break;
			case -1 : 
				mostrarmensaje("Login", "Error de acceso",0);	
				break;
			case -2 :
				mostrarmensaje("Login", "Error de acceso",0);
				break;
			case -3 :
				mostrarmensaje("Login", "Error de acceso",0);
				break;
		}
	}

	function vmostrarmenu(){
		echo file_get_contents("menuadmin.html");
	}

	function vmostrarsubirusuarios(){
		echo file_get_contents("subirUsuarios.html");
	}

	function vmostraralta() {
		echo file_get_contents("altapersona.html");
	}

	function vmostrarerror(){
		echo file_get_contents("expirado.html");
	}

	function vmostrarlistadousuarios($resultado){
		$cadena = file_get_contents("listadoPersonas.html");
		$cadena = str_replace("#tipo","usuarios",$cadena);
		$trozos = explode("##fila##", $cadena);

		$aux = "";
		$cuerpo = "";
		while ($datos = $resultado->fetch_assoc()) {
			if($datos["UserID"]=="admin1234"){

			}else{
				$aux = $trozos[1];
				$aux = str_replace("#userid#", $datos["UserID"], $aux);
				$aux = str_replace("#nombreusuario#", $datos["Nombre"], $aux);
				$aux = str_replace("#fecha#", $datos["Fecha"], $aux);
				$aux = str_replace("#correo#", $datos["Correo"], $aux);
				$aux = str_replace("#provincia#", $datos["Provincia"], $aux);
				$cuerpo .= $aux;
			}
			
		}

		echo $trozos[0] . $cuerpo . $trozos[2];
	}

	function vmostrarlistadovehiculos($resultado){
		$cadena = file_get_contents("listado.html");
		$cadena = str_replace("#tipo","vehiculos",$cadena);
		$trozos = explode("##columna##", $cadena);

		$aux = "";
		$cuerpo = "";
		while ($datos = $resultado->fetch_assoc()) {
			$aux = $trozos[1];
			$aux = str_replace("##nombrecoche##", $datos["Nombre"], $aux);
			$cuerpo .= $aux;
		}
		echo $trozos[0] . $cuerpo . $trozos[2];
	}

	function veditarvehiculo($resultado,$resultadoFotos){
		$cadena = file_get_contents("editarvehiculo.html");
		if($datos = $resultado->fetch_assoc()){
			$cadena = str_replace("##nombre##", $datos["Nombre"], $cadena);
			$cadena = str_replace("##descripcion##", $datos["Descripcion"], $cadena);
			$cadena = str_replace("##numero##", $datos["ID"], $cadena);
			$cadena = str_replace("##color##", $datos["Color"], $cadena);
			$cadena = str_replace("##precio##", $datos["Precio"], $cadena);
			$cadena = str_replace("##kilometros##", $datos["Kilometros"], $cadena);
			$trozos = explode("##foto##",$cadena);
			$aux = "";
			$aux2 = "";
			while($fotos = $resultadoFotos->fetch_assoc()){
	            $fotoLink = "imagenes/".$datos["Nombre"]."/medianas/".$fotos["image_name"];
	            $aux2 = str_replace("##path##",$fotoLink,$trozos[1]);
	            $aux2 = str_replace("##nombrefoto##",$fotos["image_name"],$aux2);
	            $aux = $aux.$aux2;				
			}
			echo $trozos[0].$aux.$trozos[2];
		}else{
			echo "Ha habido un error de conexion";
		}
		
	}
	
	function vcrearvehiculo(){
        $cadena = file_get_contents("crearvehiculo.html");
		echo $cadena;
	}

	/****************************** *
	Se cambian los datos del vehiculo en la base de datos
	1.- Campo nombre vacio --> Recibo -1
	2.- Campo descripcion vacio --> Recibo -2
	3.- Error en la base de datos --> Recibo -3
	4.- Actualizado correctamente
	********************************/
	function vresultadoedicion($resultado){
		switch($resultado){
			case 1:
				mostrarmensaje("Actualizacion de datos","Se ha actualizado correctamente el vehiculo",1);
				break;
			case -1:
				mostrarmensaje("Actualizacion de datos","No se puede dejar el campo nombre vacio",0);
				break;
			case -2:
				mostrarmensaje("Actualizacion de datos","No se puede dejar el campo nombre vacio",0);
				break;
			case -3:
				mostrarmensaje("Actualizacion de datos","Ha habido un error de conexion",0);
				break;
		}
	}

	/****************************** *
	Se cambian los datos del vehiculo en la base de datos
	1.- Campo nombre vacio --> Recibo -1
	2.- Campo descripcion vacio --> Recibo -2
	3.- Error en la base de datos --> Recibo -3
	4.- Actualizado correctamente
	********************************/
	function vresultadoalta($resultado){
		switch($resultado){
			case 1:
				mostrarmensaje("Alta de vehiculo","Se ha dado de alta correctamente el vehiculo",1);
				break;
			case -1:
				mostrarmensaje("Alta de vehiculo","No se puede dejar el campo nombre vacio",0);
				break;
			case -2:
				mostrarmensaje("Alta de vehiculo","No se puede dejar el campo descripcion vacio",0);
				break;
			case -4:
				mostrarmensaje("Alta de vehiculo","No se puede dejar el campo precio vacio",0);
				break;
			case -5:
				mostrarmensaje("Alta de vehiculo","No se puede dejar el campo color vacio",0);
				break;
			case -3:
				mostrarmensaje("Alta de vehiculo","Ha habido un error de conexion",0);
				break;
			}
			
	}

	function vborrarvehiculo(){
		$nombrecoche = $_GET["num"];
        $cadena = file_get_contents("borrarvehiculo.html");
        $cadena = str_replace("##nombre##", $nombrecoche, $cadena);
		echo $cadena;
	}

	function vresultadoborrado($resultado){
		switch($resultado){
			case 1:
				mostrarmensaje("Borrado de vehiculo","El vehiculo se ha borrado correctamente",1);
				break;
			case -1:
				mostrarmensaje("Borrado de vehiculo","No se ha podido borrar el vehiculo de la base de datos, si el error perdura contacte a su administrador",0);
				break;
			case -2:
				mostrarmensaje("Borrado de vehiculo","No se han podido borrar el los archivos del vehiculo, si el error perdura contacte a su administrador",0);
				break;
		}
	}

	function vconfirmarborradousuario($usuario){
		$cadena = file_get_contents("borrarusuario.html");
		$cadena = str_replace("##nombre##", $usuario, $cadena);
		echo $cadena;
	}

	function vresultadoborrarusuario($resultado){
		switch ($resultado) {
			case 1:
				mostrarmensaje("Borrado de usuario","El usuario se ha borrado correctamente",1);
				break;
			default:
				mostrarmensaje("Borrado de usuario", "El usuario no se ha podido borrar",0);
				break;
		}
	}


?>