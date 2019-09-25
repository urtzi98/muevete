<?php
	function getArraySQL(){
	    //Creamos la conexión con la función anterior
	    $conexion = mysqli_connect("dbserver", "grupo05", "iPhahquo5a", "db_grupo05");

	    //generamos la consulta
	    mysqli_set_charset($conexion, "utf8"); //formato de datos utf8
	    $consulta = "select * from `Usuario`";
	    $result = mysqli_query($conexion,$consulta);

	    $rawdata = array(); //creamos un array

	    //guardamos en un array multidimensional todos los datos de la consulta
	    $i=0;
	    while($row = mysqli_fetch_array($result)){
	        $rawdata[$i] = $row;
	        $i++;
	    }
	    return $rawdata; //devolvemos el array
	}

    $myArray = getArraySQL();
    echo json_encode($myArray);
?>