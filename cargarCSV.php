<?php
	// conexión
	$mysqli = mysqli_connect("dbserver", "grupo05", "iPhahquo5a", "db_grupo05");

	if (isset($_POST['enviar'])){
		
		$filename=$_FILES["file"]["name"];
		$info = new SplFileInfo($filename);
		$extension = pathinfo($info->getFilename(), PATHINFO_EXTENSION);

		if($extension == 'csv'){
			$filename = $_FILES['file']['tmp_name'];
			$handle = fopen($filename, "r");
			echo $handle;
			while( ($data = fgetcsv($handle, 1000, ";") ) !== FALSE ){
				//if(strlen($data[0]) != 0 && strlen($data[1]) != 0 && strlen($data[2]) != 0 && strlen($data[3]) != 0 && strlen($data[4]) != 0 && strlen($data[5]) != 0){
					$q = "insert into Usuario (UserID, Contrasena, Nombre, Fecha, Correo, Provincia) values (
						'$data[0]', 
						'$data[1]',
						'$data[2]',
						'$data[3]',
						'$data[4]',
						'$data[5]'
					)";
					$mysqli->query($q);
					echo "Hola";
				//}
			}
			echo "Todo subido correctamente<br>";
			echo '<a href="/final/index.php" class="btn btn-success">Menu principal</a>';
			fclose($handle);	
		}
	}
?>