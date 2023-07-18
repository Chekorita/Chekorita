<?php
//Para llamar la libreria de fpsf.php
require('../config/fpdf/fpdf.php');

class PDF extends FPDF{
//Cabeza de pagina
function Header(){
    //Se determina la fuente que será: Arial bold 15
    $this->SetFont('Arial','B',12);
    //Ajustar numero a conveniencia para distribuir el espacio a la izquierda para centrar
    $this->Cell(55);
    //Titulo que ira en cada hoja
    $this->Cell(80,10,utf8_decode('Reporte de inicio de sesión'),0,0,'C');
    //Salto de linea
    $this->Ln(15);

    //para generar la celda, primero usaremos los parametros
        //La primera es el ancho de la celda, el segundo es alto
        //El tercer parametro es el texto que se le pondra
        //El cuarto parametro es el borde, el quinto si queremos darle un salto de linea
        //El sexto parametro es como queremos que este el texto, en este caso C es centrado
        //Y el ultimo parametro es el relleno de la celda
    $this->Cell(30, 6, utf8_decode('ID'),1 , 0, 'C', 0);
    $this->Cell(90, 6, utf8_decode('Usuario'),1 , 0, 'C', 0);
    $this->Cell(70, 6, utf8_decode('Fecha'),1 , 1, 'C', 0);
}

//Pie de pagina
function Footer(){
    //Posición a 1.5 cm de la parte inferior
    $this->SetY(-15);
    //Se determina la fuente que será: Arial italic 8
    $this->SetFont('Arial','I',8);
    //Se establece para que se de el numero de pagina
    $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
}
}

//Hacemos la consulta 
include('../config/usuarioLectura.php');
$consulta = "SELECT * FROM bitainisesion";
$resultado = mysqli_query( $conexion, $consulta ) or die ( "Algo ha ido mal en la consulta a la base de datos");
//Instanciación de la clase heredada PDF


$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',9);


//Aqui vamos a hacer el recorrido de los datos recuperados
while($columna = $resultado->fetch_assoc()){ 
    $pdf->Cell(30, 7, utf8_decode($columna['idBitaSesion']),1 , 0, 'C', 0);
    $pdf->Cell(90, 7, utf8_decode($columna['nombreUsuario']),1 , 0, 'C', 0);
    //para esta ultima si se le pone salto de linea para que la siguiente fila aparezca abajo
    $pdf->Cell(70, 7, utf8_decode($columna['fecha']),1 , 1, 'C', 0);
}

$pdf->Output();
?>