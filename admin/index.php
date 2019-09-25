<?php

	session_start();

	include "modelo.php";
	include "vista.php";

	$accion = "nada";
	if(mcomprobarusuario() != 1){
		//Si se ha cerrado la sesion
		vmostrarerror();
	}else{
	
		if (isset($_GET["accion"])) {
			$accion = $_GET["accion"];
			$id = $_GET["id"];
			$vehiculo = $_GET["num"];
		} elseif (isset($_POST["accion"])) {
			$accion = $_POST["accion"];
			$id = $_POST["id"];
			$vehiculo = $_POST["num"];
		} else{
			//Todo ok
			vmostrarmenu();
		} 
                
		if ($accion == "usuarios") {
			vmostrarlistadousuarios(mcogerusuarios());
		}

		if ($accion == "vehiculos") {
			vmostrarlistadovehiculos(mcogervehiculos());
		}

		if ($accion == "editarvehiculo"){
			
			switch ($id) {
				case 1:
					veditarvehiculo(mcogervehiculo($vehiculo),mcogerfotos($vehiculo));
					break;
				case 2:
					vresultadoedicion(mvalidaredicion($vehiculo));
					break;
				case 3:
					if(mborrarfoto()==-1){
						echo 'Ha habido un error<br>';
					}
					veditarvehiculo(mcogervehiculo($vehiculo),mcogerfotos($vehiculo));
					break;
			}
		}

		if ($accion == "borrarusuario"){
			if($id != 2){
				vconfirmarborradousuario($vehiculo);
			}else{
				vresultadoborrarusuario(mborrarusuario($vehiculo));
			}
		}
		
		if ($accion == "crearvehiculo"){
			switch ($id) {
				case 1:
					vcrearvehiculo();
					break;
				case 2:
					vresultadoalta(mvalidaralta());
					break;
			}
         }
	    
        if ($accion == "borrarvehiculo"){
            switch ($id) {
                case 1:
                    vborrarvehiculo();
                    break;
                case 2:
                    vresultadoborrado(mborrarvehiculo($vehiculo));
                    break;
            }
        }
        if ($accion == "subirfoto"){
            msubirfoto();
            veditarvehiculo(mcogervehiculo($vehiculo),mcogerfotos($vehiculo));
        }

        if ($accion == "cargarUsuarios"){
        	switch ($id) {
        		case 1:
                    vmostrarsubirusuarios();
                    break;
                case 2:
                    mcargarcsv();
                    break;
        	}
        }
	}
?>