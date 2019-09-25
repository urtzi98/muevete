<?php
	$con = mysqli_connect("dbserver", "grupo05", "iPhahquo5a", "db_grupo05");
	$con->set_charset("utf8");
	$comment = $_POST["COMMENT"];
	$nomVehiculo = $_POST["nombreVehiculo"];
	$nomUsuario = $_POST["nombreUsuario"];
	$consulta = "INSERT INTO Comentarios (COMMENT, NOMBREVEHICULO, NAME) VALUES ('$comment', '$nomVehiculo', '$nomUsuario')";
	$con->query($consulta)
	echo "<script>window.location = 'http://webalumnos.tlm.unavarra.es:10705/final/index.php'</script>";
?>