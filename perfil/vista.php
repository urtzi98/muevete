<?php

	function vmostrarperfil(){
		$cadena = file_get_contents("perfilusuario.html");
		$cadena = str_replace("##nombre##",$_SESSION["user"],$cadena);
		$trozos = explode("##admin##", $cadena);

		$cuerpo = "";
		if(isset($_SESSION["user"])){
			if ($_SESSION["user"]=="admin1234"){
				$cuerpo = $trozos[1];
			}	
		}
		
		echo $trozos[0] . $cuerpo . $trozos[2];
	}

	

	function vmostrarerrorexpirar(){
		echo file_get_contents("expirado.html");
	}

?>