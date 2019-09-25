<?php
/////// CONEXIÓN A LA BASE DE DATOS /////////

$conexion = mysqli_connect("dbserver", "grupo05", "iPhahquo5a", "db_grupo05");
if ($conexion -> connect_errno)
{
	die("Fallo la conexion:(".$conexion -> mysqli_connect_errno().")".$conexion-> mysqli_connect_error());
}

//////////////// VALORES INICIALES ///////////////////////

$tabla="";
$query="SELECT * FROM Vehiculo ORDER BY ID";
//echo $query;

///////// LO QUE OCURRE AL TECLEAR SOBRE EL INPUT DE BUSQUEDA ////////////
if(isset($_POST['vehiculos']))
{
	$q=$conexion->real_escape_string($_POST['vehiculos']);
	$query="SELECT * FROM Vehiculo WHERE 
		ID LIKE '%".$q."%' OR
		Nombre LIKE '%".$q."%' OR
		Color LIKE '%".$q."%' OR
		Descripcion LIKE '%".$q."%' OR
		Precio LIKE '%".$q."%' OR
		Kilometros LIKE '%".$q."%'";
}

$buscarVehiculos=$conexion->query($query);
if ($buscarVehiculos->num_rows > 0)
{
	$tabla.= 
	'<table class="table">
		<tr class="bg-primary">
			<td>ID COCHE</td>
			<td>NOMBRE</td>
			<td>COLOR</td>
			<td>DESCRIPCION</td>
			<td>PRECIO</td>
			<td>KILOMETROS</td>
			<td>PAGINA WEB</td>
		</tr>';

	while($filaVehiculos= $buscarVehiculos->fetch_assoc())
	{
		$tabla.=
		'<tr>
			<td>'.$filaVehiculos['ID'].'</td>
			<td>'.$filaVehiculos['Nombre'].'</td>
			<td>'.$filaVehiculos['Color'].'</td>
			<td>'.$filaVehiculos['Descripcion'].'</td>
			<td>'.$filaVehiculos['Precio'].'</td>
			<td>'.$filaVehiculos['Kilometros'].'</td>
			<td><a class="btn btn-primary" href="http://webalumnos.tlm.unavarra.es:10705/final/paginaCoche.php?nombre='.$filaVehiculos['Nombre'].'&descripcion='.$filaVehiculos['Descripcion'].'&color='.$filaVehiculos['Color'].'&precio='.$filaVehiculos['Precio'].'&kilometros='.$filaVehiculos['Kilometros'].'&id='.$filaVehiculos['ID'].'">Link
				</a>
			</td></tr>';
	}

	$tabla.='</table>';
} else
	{
		$tabla="No se encontraron coincidencias con sus criterios de búsqueda.";
	}


echo $tabla;
?>