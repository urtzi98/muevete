$(obtener_registros());

function obtener_registros(vehiculos)
{
	$.ajax({
		url : 'consulta.php',
		type : 'POST',
		dataType : 'html',
		//mismo parametro que te da la funcion
		data : { vehiculos: vehiculos },
		})

	.done(function(resultado){
		$("#tabla_resultado").html(resultado);
	})
}

$(document).on('keyup', '#busqueda', function()
{
	var valorBusqueda=$(this).val();
	if (valorBusqueda!="")
	{
		obtener_registros(valorBusqueda);
	}
	else
		{
			obtener_registros();
		}
});





