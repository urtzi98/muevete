<?php
	//lo quito para probar
	include "vistaInicio.php";
	include "modelo.php";
	session_start();

	$accion = "";
	if (isset($_GET["accion"])) {
		$accion = $_GET["accion"];
	} elseif (isset($_POST["accion"])) {
		$accion = $_POST["accion"];
	} else {
		//Todo ok
		vmostrarmenu();
	}

	if($accion == "cerrarsesion"){
		session_unset();
		session_destroy();
		vmostrarmenu();
	}

	if ($accion == "verperfil") {
		if (mcomprobarusuario()==1) {
			//Todo ok
			vmostrarperfil();
		} else {
			vmostrarerror();
		}
	}
	
	if ($accion == "enviarComment") {
		venviarComment();
	}

	//conectamos a la base de datos
	$conexion = mysqli_connect("dbserver", "grupo05", "iPhahquo5a", "db_grupo05");
	//Evitamos que salgan errores por variables vacías
	error_reporting(E_ALL ^ E_NOTICE);
	//Cantidad de resultados por página (debe ser INT, no string/varchar)
	$cantidad_resultados_por_pagina = 4;

	//Comprueba si está seteado el GET de HTTP
	if (isset($_GET["pagina"])) {

		//Si el GET de HTTP SÍ es una string / cadena, procede
		if (is_string($_GET["pagina"])) {

			//Si la string es numérica, define la variable 'pagina'
			if (is_numeric($_GET["pagina"])) {
				//Si la petición desde la paginación es la página uno
				//en lugar de ir a 'index.php?pagina=1' se iría directamente a 'index.php'
				if ($_GET["pagina"] == 1) {
					header("Location: index.php");
					die();
				}else{ //Si la petición desde la paginación no es para ir a la pagina 1, va a la que sea
					$pagina = $_GET["pagina"];
				};

			} else { //Si la string no es numérica, redirige al index (por ejemplo: index.php?pagina=AAA)
				header("Location: index.php");
				die();
			};
		};

	} else { //Si el GET de HTTP no está seteado, lleva a la primera página (puede ser cambiado al index.php o lo que sea)
		$pagina = 1;
	};

	//Define el número 0 para empezar a paginar multiplicado por la cantidad de resultados por página
	$empezar_desde = ($pagina-1) * $cantidad_resultados_por_pagina;	


		//Obtiene TODO de la tabla vehiculo
		$obtener_todo_BD = "select * from Vehiculo";

		//Realiza la consulta
		$consulta_todo = mysqli_query($conexion, $obtener_todo_BD);

		//Cuenta el número total de registros o ejemplos que hay
		$total_registros = mysqli_num_rows($consulta_todo);

		//Obtiene el total de páginas existentes
		$total_paginas = ceil($total_registros / $cantidad_resultados_por_pagina); 

		//Realiza la consulta en el orden de ID ascendente (cambiar "id" por, por ejemplo, "nombre" o "edad", alfabéticamente, etc.)
		//Limitada por la cantidad de cantidad por página
		$consulta_resultados = mysqli_query($conexion, 
			"SELECT * FROM Vehiculo JOIN (SELECT * FROM images_info t1 WHERE (t1.idcoche, t1.date) = ANY(SELECT t2.idcoche, max(t2.date) FROM images_info t2 GROUP BY t2.idcoche)) img WHERE Vehiculo.ID = img.idcoche order by Vehiculo.ID asc
		limit $empezar_desde, $cantidad_resultados_por_pagina");
		//Crea un bluce 'while' y define a la variable 'datos' ($datos) como clave del array
		//que mostrará los resultados por nombre
		echo "	<section>
		";
		while($datos = mysqli_fetch_array($consulta_resultados)) {
	?>	<span class="vehiculo col-md-6">
			<p><strong><?php echo $datos['Nombre']; ?></strong> <br>
			<?php echo'		<a href="paginaCoche.php?nombre='.$datos['Nombre'].'&descripcion='.$datos['Descripcion'].'&precio='.$datos['Precio'].'&color='.$datos['Color'].'&kilometros='.$datos['Kilometros'].'&id='.$datos['ID'].'">
			<img class="img-rounded card-img-top" src="/final/admin/imagenes/'.$datos['Nombre'].'/grandes/'.$datos['image_name'].'" alt="coche '.$datos['Descripcion'].'" width="350" height="200" /> 
		</a>';?> <br>
			</p>
		</span>

	<?php
		};
		echo "</section>";
	?>

<br><!----------------------------------------------->

<?php
//Crea un bucle donde $i es igual 1, y hasta que $i sea menor o igual a X, a sumar (1, 2, 3, etc.)
//Nota: X = $total_paginas
//echo'<img src="imagenesCoches/'.$datos['Nombre'].'.jpg" />';
echo "<div style='clear: both;'> </div> <div style='fixed: bottom;' class='center'>";
for ($i=1; $i<=$total_paginas; $i++) {
	//En el bucle, muestra la paginación
	echo "<a class='btn btn-default btn-sm' href='?pagina=".$i."'>".$i."</a>";
}; 
echo "</div> ";
?>

</body>
</html>
