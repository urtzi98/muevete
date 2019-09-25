<?php
require('fpdf/mysql_table.php');

class PDF extends PDF_MySQL_Table
{
	function Header()
	{
	    // Title
	    $this->SetFont('Arial','',18);
	    $this->Cell(0,6,'Listado Vehiculos',0,1,'C');
	    $this->Ln(10);
	    // Ensure table header is printed
	    parent::Header();
	}
}


	$bd = mysqli_connect("dbserver", "grupo05", "iPhahquo5a", "db_grupo05");

	$pdf = new PDF();
	$pdf->AddPage();
	// First table: output all columns
	$pdf->Table($bd,'select ID,Nombre,Color,Precio,Kilometros from Vehiculo order by Nombre');
	$pdf->AddPage();
	$pdf->Output();
	
?>