<?php

	function conexion() {
		$con = mysqli_connect("dbserver", "grupo05", "iPhahquo5a", "db_grupo05");
		//$con = mysqli_connect("dbserver", "grupo13", "fasdfa", "dfadf");
		return $con;
	}

	function mvalidarusuario() {
		$bd = conexion();
		
		$usuario = $_POST["usuario"];
		$password = $_POST["password"];

		$consulta = "select * from Usuario where UserID = '$usuario'";

		if ($resultado = $bd->query($consulta)) {
			if ($datos = $resultado->fetch_assoc()) {
				if ($password == $datos["password"]) {	

					$_SESSION["user"] = $usuario;
					$_SESSION["password"] = $datos["password"];
					$_SESSION["tiempo"] = time();
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


	function mcomprobarusuario() {
		$bd = conexion();

		if (!isset($_SESSION["user"])) {
			return -4;
		}

		$resta = time() - $_SESSION["tiempo"];

		if ($resta > 300) {
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

	function mcogerusuarios(){
		$bd = conexion();

		$consulta = "select * from Usuario order by Nombre";

		return $bd->query($consulta);
	}

	function mcogervehiculos(){
		$bd = conexion();

		$consulta = "select * from Vehiculo order by Nombre";

		return $bd->query($consulta);
	}

	function mcogervehiculo($nombre){
		$bd = conexion();

		$consulta = "select * from Vehiculo where Nombre = '".$nombre."'";

		return $bd->query($consulta);
	}
	//tengo que modificar alguna cosilla aqui dentro
	function mcargarcsv(){
		// conexión
		$mysqli = conexion();

		if (isset($_POST['enviar'])){
			
			$filename=$_FILES["file"]["name"];
			$info = new SplFileInfo($filename);
			$extension = pathinfo($info->getFilename(), PATHINFO_EXTENSION);

			if($extension == 'csv'){
				$filename = $_FILES['file']['tmp_name'];
				$handle = fopen($filename, "r");

				while( ($data = fgetcsv($handle, 1000, ";") ) !== FALSE ){
					if(strlen($data[0]) != 0 && strlen($data[1]) != 0 && strlen($data[2]) != 0 && strlen($data[3]) != 0 && strlen($data[4]) != 0 && strlen($data[5]) != 0){
						$q = "INSERT INTO Usuario (UserID, Contrasena, Nombre, Fecha, Correo, Provincia) VALUES (
							'$data[0]', 
							'$data[1]',
							'$data[2]',
							'$data[3]',
							'$data[4]',
							'$data[5]'
						)";

						$mysqli->query($q);
					}
				}
				fclose($handle);	
				return 1;
			}
			return -1;
		}
		return -1;

	}

	/****************************** *
	Se acualizan los datos del vehiculo en la base de datos
	1.- Campo nombre vacio --> Devuelvo -1
	2.- Campo descripcion vacio --> Devuelvo -2
	3.- Error en la base de datos --> Devuelvo -3
	4.- Actualizado correctamente
	********************************/
	function mvalidaredicion($vehiculo){
            $bd = conexion();
            $nombre = $_GET["nombre"];
            $descripcion = $_GET["descripcion"];
            $kilometros = $_GET["kilometros"]; //Que pasa cuando no hay nada puesto?
            $precio = $_GET["precio"];
            $color = $_GET	["color"];
            if(strlen($nombre)==0){
                    return -1;
            }
            if(strlen($descripcion)==0){
                    return -2;
            }

            if(strlen($precio)==0){
                    return -4;
            }
            if(strlen($color)==0){
                    return -5;
            }
            if($vehiculo!=$nombre){
                    $res = rename("imagenes/grandes".$vehiculo,"imagenes/grandes".$nombre);

                    if(rename("imagenes/".$vehiculo."/grandes","imagenes/".$nombre."/grandes") and rename("imagenes/".$vehiculo."/medianas","imagenes/".$nombre."/medianas") and rename("imagenes/".$vehiculo."/pequenas","imagenes/".$nombre."/pequenas")){
                            return -3;
                    }	
            }
            $consulta = "UPDATE Vehiculo SET Descripcion = '".$descripcion."',Nombre = '".$nombre."',Precio='".$precio."',Color='".$color."',Kilometros='".$kilometros."' WHERE Nombre = '".$vehiculo."'";

            if ($resultado = $bd->query($consulta))  {
                    return 1;
            } else {
                    //Error en la consulta	
                    return -3;
            }
		
	}

	/****************************** *
	Se suben los datos del vehiculo a la base de datos y se 
	crea una carpeta en imagenes con el nombe del coche (codificando espacios)
	1.- Campo nombre vacio --> Devuelvo -1
	2.- Campo descripcion vacio --> Devuelvo -2
	3.- Campo precio vacio --> Devuelvo -4
	4.- Campo color vacio --> Devuelvo -5
	5.- Error en la base de datos --> Devuelvo -3
	6.- Actualizado correctamente --> Devuelvo 1
	********************************/
    function mvalidaralta(){
		$nombre = $_GET["nombre"];
		$descripcion = $_GET["descripcion"];
		$kilometros = $_GET["kilometros"];
		$precio = $_GET["precio"];
		$color = $_GET	["color"];
      	if(strlen($nombre)==0){
                return -1;
        }
        if(strlen($descripcion)==0){
                return -2;
        }
        if(strlen($precio)==0){
                return -4;
        }
        if(strlen($color)==0){
                return -5;
        }
		
		if(mkdir("imagenes/".$nombre)){
			exec('chmod 0777 "imagenes/"'.$nombre.'"');
			if(mkdir("imagenes/".$nombre."/grandes") and mkdir("imagenes/".$nombre."/medianas",0777) and mkdir("imagenes/".$nombre."/pequenas",0777)){
				exec('chmod 0777 "imagenes/"'.$nombre.'"/grandes');
				exec('chmod 0777 "imagenes/"'.$nombre.'"/pequenas');
				exec('chmod 0777 "imagenes/"'.$nombre.'"/medianas');
				$bd = conexion();
				$consulta = "INSERT INTO Vehiculo (Nombre, Descripcion, Precio, Color, Kilometros) VALUES ('".$nombre."','".$descripcion."','".$precio."','".$color."','".$kilometros."')";
				if ($resultado = $bd->query($consulta))  {
				        return 1;
				} else {
				        //Error en la consulta	
				        return -3;
				}
			}else{
				//Si no se pueden crear las carpetas de dentro se borra toda la carpeta del coche
				if(file_exists("imagenes/".$nombre)){
					unlink($ruta);
				}
				return -3;
			}
		}else{
			//Si no se puede crear la carpeta de las imagenes se devuelve error y no se suben los datos
			return -3;
		}
    }


    /****************************** *
	Se borra el vehiculo de la base de datos
	1.- Campo nombre vacio --> Devuelvo -1
	2.- Campo descripcion vacio --> Devuelvo -2
	3.- Error en la base de datos --> Devuelvo -3
	4.- Actualizado correctamente
	********************************/
    function mvalidarborrado(){
        $nombre = $_POST["nombre"];


    }


    /*******************************
	Esta funcion devuelve todas las fotos correspondientes
	al vehiculo con nombre $nombre
    ********************************/
   	function mcogerfotos($nombre){
   		$bd = conexion();
   		$consulta = "SELECT * FROM images_info JOIN Vehiculo WHERE Vehiculo.Nombre = '".$nombre."' AND ID = idcoche";
   		return $bd->query($consulta);

   	}
    
   	function redimensionar($nombreFichero,$porcentaje){
		// Se obtienen las nuevas dimensiones 
		list($width, $height) = getimagesize($nombreFichero); 
		$newwidth = $width * $porcentaje; 
		$newheight = $height * $porcentaje; 

		// Cargar la imagen 
		$thumb = imagecreate($newwidth, $newheight); 
		$source = imagecreatefromjpeg($nombreFichero); 

		// Redimensionar 
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); 

		// Mostrar la nueva imagen 
		imagejpeg($thumb);
		return $thumb;
   	}

   	/****************************************************************
   	 Esta función devuelve una redimensión de la foto cuyo nombre
   	 recibe en el parametro $nombreFichero. Se pone la foto con la 
   	 anchura de $anchura pixeles y se le cambia el tamaño manteniendo
   	 la proporción original
   	 ****************************************************************/
   	function redimensionaranchura($photo,$new_width){
   		// Get the image info from the photo
		$image_info = getimagesize($photo);
		$width = $image_info[0];
		$height = $image_info[1];
		$type = $image_info[2];

		// Load the image
		switch ($type)
		{
		    case IMAGETYPE_JPEG:
		        $image = imagecreatefromjpeg($photo);
		        break;
		    case IMAGETYPE_GIF:
		        $image = imagecreatefromgif($photo);
		        break;
		    case IMAGETYPE_PNG:
		        $image = imagecreatefrompng($photo);
		        break;
		    default:
		        die('Error loading '.$photo.' - File type '.$type.' not supported');
		}

		// Create a new, resized image
		$new_height = intval(round($height / ($width / $new_width)));
		$new_image = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

		return array($new_image,$type);
   	}


    function msubirfoto(){
        $nombrecoche = $_POST["nombrecoche"];
        $idcoche= $_POST["idcoche"];
        $conn = mysqli_connect("dbserver", "grupo05", "iPhahquo5a", "db_grupo05");
        $bd = conexion();
        if (isset($_FILES)) {
        	$nombrefoto = $_FILES["file"]["name"];
        	//Comprobar que las fotos no sean demasiado pequeñas
        	$tamaño = getimagesize($nombrefoto);
        	if($tamaño[0]<600 or $tamaño[1]<600){
        		//Primero guardo la foto en la carpeta de imagenes del coche para poder cogerla pare redimensionar
	            $rutaCoche = "imagenes/".$nombrecoche;
	            $subirfoto = move_uploaded_file($_FILES["file"]["tmp_name"],$rutaCoche."/".$nombrefoto);

	            //Redimensionar imagen a los tres tamaños requeridos
	            list($grande,$tipo) = redimensionaranchura($rutaCoche."/".$nombrefoto,800);
	            $mediana = redimensionaranchura($rutaCoche."/".$nombrefoto,500)[0];
	            $pequena = redimensionaranchura($rutaCoche."/".$nombrefoto,100)[0];

	            // Save the new image over the top of the original photo
				switch ($tipo)
				{
				    case IMAGETYPE_JPEG:
				        $subir1 = imagejpeg($grande, $rutaCoche."/grandes/".$nombrefoto, 100);
				        $subir2 = imagejpeg($mediana, $rutaCoche."/medianas/".$nombrefoto, 100);
				        $subir3 = imagejpeg($pequena, $rutaCoche."/pequenas/".$nombrefoto, 100);
				        break;
				    case IMAGETYPE_GIF:
				        $subir1 = imagegif($grande, $rutaCoche."/grandes/".$nombrefoto);
				        $subir2 = imagegif($mediana, $rutaCoche."/medianas/".$nombrefoto);
				        $subir3 = imagegif($pequena, $rutaCoche."/pequenas/".$nombrefoto);         
				        break;
				    case IMAGETYPE_PNG:
				        $subir1 = imagepng($grande, $rutaCoche."/grandes/".$nombrefoto);
				        $subir2 = imagepng($mediana, $rutaCoche."/medianas/".$nombrefoto);
				        $subir3 = imagepng($pequena, $rutaCoche."/pequenas/".$nombrefoto);
				        break;
				    default:
				        die('Error saving image: '.$photo);
				}

				exec('chmod 0777 "'. $rutaCoche.'/grandes/'.$nombrefoto.'"');
				exec('chmod 0777 "'. $rutaCoche.'/medianas/'.$nombrefoto.'"');
				exec('chmod 0777 "'. $rutaCoche.'/pequenas/'.$nombrefoto.'"');

				$borrar = unlink($rutaCoche."/".$nombrefoto);
	            if ($subirfoto and $subir1 and $subir2 and $subir3 and $borrar){
	                $consulta = "INSERT INTO images_info (image_path,image_name,idcoche) VALUES ('".$ruta."','".$nombrefoto."',".$idcoche.")";
	                $bd->query($consulta);
	            }elseif(!$subirbool3){
	            	$consulta = "INSERT INTO images_info (image_path,image_name,idcoche) VALUES ('hola','problema',5)";
	                $bd->query($consulta);
	                echo "No se ha podido subir el fichero";
	            }else {
	            	$consulta = "INSERT INTO images_info (image_path,image_name,idcoche) VALUES ('adios','problema',3)";
	                $bd->query($consulta);
	                echo "No se ha podido subir el fichero"; 
	            }	
        	}else{
        		$consulta = "INSERT INTO images_info (image_path,image_name,idcoche) VALUES ('jaja','problema',1)";
	                $bd->query($consulta);
        	}
            
        }else{
        	$consulta = "INSERT INTO images_info (image_path,image_name,idcoche) VALUES ('wow','problema',2)";
	        $bd->query($consulta);
            echo "No se ha recibido la foto";
        }
    }

    /**************************************************************************************
	Borra las tres redimensions (grande,pequeña,mediana) correspondientes a la imagen de los
	archivos y de la base de datos. Si hay algun error borrando los archivos, la borra de la 
	base de datos para que no haya errores mostrando imagenes.
    ****************************************************************************************/
    function mborrarfoto(){
    	if(isset($_GET["foto"])){
	    	$bd = conexion();
	    	if(isset($_GET["foto"])){
	    		$nombrefoto = $_GET["foto"];	
	    	}else{
	    		return -1;
	    	}
	    	
	    	if(isset($_GET["num"])){
	    		$nombrecoche = $_GET["num"];	
	    	}else{
	    		return -1;
	    	}
	    	
	    	$consulta = "DELETE images_info FROM images_info JOIN Vehiculo WHERE images_info.idcoche=Vehiculo.ID
		    	 AND images_info.image_name = '".$nombrefoto."' AND Vehiculo.Nombre ='".$nombrecoche."';";
	    	$rutaCoche = "imagenes/".$nombrecoche;
	    	if(file_exists($rutaCoche."/grandes/".$nombrefoto) and file_exists($rutaCoche."/medianas/".$nombrefoto) and file_exists($rutaCoche."/pequenas/".$nombrefoto)){
	    		$bool1 = unlink($rutaCoche."/grandes/".$nombrefoto);
	    		$bool2 = unlink($rutaCoche."/medianas/".$nombrefoto);
	    		$bool3 = unlink($rutaCoche."/pequenas/".$nombrefoto);	
	    	}else{
	    		return -1;
	    	}
	    	
	    	if($bool1 and $bool2 and $bool3){
		    	if ($resultado = $bd->query($consulta))  {
	                    return 1;
	            }
	    	}
			$bd->query($consulta);
			return -1;
    	}else{
    		return -2;
    	}
    }

    /*Funcion que borra un directorio y todo lo que contenga*/
function deleteAll($str) {
	//Comprobación de que no se están borrando todos los archivos de la web
	if($str=="" or empty($str) or $str==" "){
		return false;
	}
    //Es un fichero
    if (is_file($str)) {
        //Se borra el fichero.
        return unlink($str);
    }
    //Si es un directorio.
    elseif (is_dir($str)) {
        //Lista de objetos que tiene el directorio
        $scan = glob(rtrim($str,'/').'/*');
        //Iterar la lista de ficheros/directorios.
        foreach($scan as $index=>$path) {
            //Llamada recursiva.
            deleteAll($path);
        }
        //Finalmente se elimina el directorio.
        return @rmdir($str);
    }
}

    /*Esta funcion borra la informaciond del vehiculo de la base de datos
      y de las carpetas de fotos y demás
      1.- Todo se borra correctamente --> Devuelvo 1
      2.- Hay un error en la base de datos --> Devuelvo -1
	  3.- Hay un error borrando los ficheros --> Devuelvo -2
    */
    function mborrarvehiculo($nombrecoche){
    	$bd = conexion();
    	$consulta = "DELETE FROM Vehiculo WHERE Nombre = '".$nombrecoche."'";
    	//echo $consulta;
    	if($bd->query($consulta)){
    		if(deleteAll("imagenes/".$nombrecoche)){
    			return 1;
    		}else{
    			return -2;
    		}
    	}
    	return -1;	
    	
    }

    function mborrarusuario($userid){
    	$bd = conexion();
    	$consulta = "DELETE FROM Usuario WHERE UserID = '".$userid."'";
    	if($bd->query($consulta)){
    		return 1;
    	}else{
    		return -1;
    	}
    }

?>












