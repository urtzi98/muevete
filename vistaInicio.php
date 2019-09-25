<?php

	function vmostrarmenu() {
		$cadena = file_get_contents("menu.html");
		if(isset($_SESSION["user"])){
			$cadena = str_replace("#nombre#",$_SESSION["user"],$cadena);
		}
		$trozos = explode("##anonimo##", $cadena);
		$trozos2 = explode("##registrado##",$cadena);
		$cuerpo = "";
		if (isset($_SESSION["user"])) {
			$cuerpo = $trozos2[1];
		}else{
			$cuerpo = $trozos[1];
		}
		echo $trozos[0] .$cuerpo. $trozos2[2];
	}

	function vmostrarperfil(){
		$cadena = file_get_contents("perfilusuario.html");
		$cadena = str_replace("##nombre##",$_SESSION["user"],$cadena);
		$cuerpo = "";
		if($_SESSION["user"]=="admin1234"){
			$cuerpo = $trozos[1];
		}
		$trozos = explode("##admin##", $cadena);
		echo $trozos[0].$cuerpo.$trozos[2];
	}

	function vmostrarerror(){
		echo file_get_contents("expirado.html");
	}

	function venviarComment(){
		$con = mysqli_connect("dbserver", "grupo05", "iPhahquo5a", "db_grupo05");
		$con->set_charset("utf8");
		$comment = $_POST["COMMENT"];
		$nomVehiculo = $_POST["nombreVehiculo"];
		$nomUsuario = $_POST["nombreUsuario"];
		$consulta = "INSERT INTO Comentarios (COMMENT, NOMBREVEHICULO, NAME) VALUES ('$comment', '$nomVehiculo', '$nomUsuario')";
		$con->query($consulta);
		echo "<script>window.location = 'http://webalumnos.tlm.unavarra.es:10705/final/index.php'</script>";
	}

	function vmostrarvehiculo($resultado){
		if(gettype($resultado)=="integer"){
			//Ha habido un error
		}else{
			if (isset($_GET["nombre"]) and isset($_GET["color"]) and isset($_GET["descripcion"]) and isset($_GET["precio"]) and isset($_GET["kilometros"])) {
				//Cojo las variables por url
				$nombreCoche = $_GET["nombre"];
				$color = $_GET["color"];
				$descripcion = $_GET["descripcion"];
				$precio = $_GET["precio"];
				$kilometros = $_GET["kilometros"];

				//Cargar la plantilla y poner la parte de login/usuario en $arriba
				$cadena = file_get_contents("plantillaPaginaCoche.html");
				$trozos = explode("##anonimo##", $cadena);
				$trozos2 = explode("##registrado##",$cadena);
				if (isset($_SESSION["user"])) {
					$trozos2[1] = str_replace("##nombreusuario##",$_SESSION["user"],$trozos2[1]);
					$trozos2[3] = str_replace("##nombrevehiculo##",$nombreCoche,$trozos2[3]);
					$trozos2[3] = str_replace("##nombreusu##",$_SESSION["user"],$trozos2[3]);
					$aux = $trozos2[1].$trozos2[2].$trozos2[3];
				}else{
					$aux = $trozos[1].$trozos2[2];
				}
				$arriba = $trozos[0].$aux.$trozos2[4];


				//Meter la informacion en la tabla de descripcion
				$trozos = explode("##fila##",$arriba);
				$arrayElemento = array( "Color", "Descripcion", "Precio","Kilometros");
				$arrayValor = array($color,$descripcion,$precio,$kilometros);
				$aux = "";
				for ($i=0;$i<4;$i++){
					$trozos[1] = str_replace("#elemento#",$arrayElemento[$i],$trozos[1]);
					$trozos[1] = str_replace("#valor#",$arrayValor[$i],$trozos[1]);
					$aux.=$trozos[1];
					$trozos = explode("##fila##",$arriba);
				}
				$trozos[0] = str_replace("#nombre#",$nombreCoche,$trozos[0]);
				$tabla = $trozos[0].$aux.$trozos[2];
				
				//Meter las fotos
				$aux = "";
				$aux2 = "";
				$trozos = explode("##foto##", $tabla);
				$i = 0;
				$medio = $trozos[2];
				while($fotos=$resultado->fetch_assoc()){
					$i += 1;
					$nombreFoto = $fotos["image_name"];
					$trozos[1] = str_replace("##nombrecoche##",$nombreCoche,$trozos[1]);
					$trozos[1] = str_replace("##nombrefoto##",$nombreFoto,$trozos[1]);
					$trozos[1] = str_replace("##indicepagina##",$i,$trozos[1]);
					$trozos[3] = str_replace("##indicepagina##",$i,$trozos[3]);
					$trozos[3] = str_replace("##nombrecoche##",$nombreCoche,$trozos[3]);
					$trozos[3] = str_replace("##nombrefoto##",$nombreFoto,$trozos[3]);
					$medio = str_replace("##nombrecoche##",$nombreCoche,$medio);
					$medio = str_replace("##nombrefoto##",$nombreFoto,$medio);	
					$aux .= $trozos[1];
					$aux2 .= $trozos[3];
					$trozos = explode("##foto##",$tabla);
				}
				
				$aux = str_replace("##indicetotal##", $i, $aux);
				//con $medio quitamos las fotos
				//he quitado 
				$bd = mysqli_connect("dbserver", "grupo05", "iPhahquo5a", "db_grupo05");
				$consulta = "SELECT * FROM Comentarios where NOMBREVEHICULO = '$nombreCoche'";
				$cad = explode("##comments##",$trozos[4]);
				$var = "";
				if ($resultado = $bd->query($consulta)) {
					while ($datos = $resultado->fetch_assoc()) {
						//echo $datos["NAME"];
						$cad[1] = str_replace("##usuario##",$datos["NAME"],$cad[1]);
						$cad[1] = str_replace("##comentario##",$datos["COMMENT"],$cad[1]);
						$var .= $cad[1];
						$cad = explode("##comments##",$trozos[4]);
					}
				}
				//he quitado esto $trozos[4]
				echo $trozos[0].$aux.$medio.$aux2.$cad[0].$var.$cad[2];
			}else{
				//Ha habido un error
			}
			

		}
	}

?>