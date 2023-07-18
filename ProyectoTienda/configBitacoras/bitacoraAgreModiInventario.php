<?php
//llamamos a la configuracion de tablas dinamicas que se basa en la libreria fpdf
require('../config/fpdfTablaDinamica.php');
class PDF extends PDF_MC_Table{
    //cabecera de pagina
    function Header(){
        //Se determina la fuente que será: Arial bold 15
        $this->SetFont('Arial','B',12);
        //Ajustar numero a conveniencia para distribuir el espacio a la izquierda para centrar
        $this->Cell(55);
        //Titulo que ira en cada hoja
        $this->Cell(80,10,utf8_decode('Reporte de agreación o modificación de inventario'),0,0,'C');
        //Salto de linea
        $this->Ln(15);
    
        //para generar la celda, primero usaremos los parametros
            //La primera es el ancho de la celda, el segundo es alto
            //El tercer parametro es el texto que se le pondra
            //El cuarto parametro es el borde, el quinto si queremos darle un salto de linea
            //El sexto parametro es como queremos que este el texto, en este caso C es centrado
            //Y el ultimo parametro es el relleno de la celda
        $this->Cell(15, 6, utf8_decode('ID'),1 , 0, 'C', 0);
        $this->Cell(40, 6, utf8_decode('Usuario'),1 , 0, 'C', 0);
        $this->Cell(50, 6, utf8_decode('Fecha'),1 , 0, 'C', 0);
        $this->Cell(85, 6, utf8_decode('Descripción'),1 , 1, 'C', 0);
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
$consulta = "SELECT * FROM bitaagregainv";
$resultado = mysqli_query( $conexion, $consulta ) or die ( "Algo ha ido mal en la consulta a la base de datos");

//Instanciación de la clase heredada PDF_MC_Table del archivo de configuracion
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',9);
//definimos los anchos de las columnas
$pdf->SetWidths(Array(15,40,50,85));
//definimos un alto predeterminado
$pdf->SetLineHeight(5.5);
//definimos las alineaciones de los textos
$pdf->SetAligns(Array('C','C','C','J'));

//Aqui vamos a hacer el recorrido de los datos recuperados
while($columna = $resultado->fetch_assoc()){ 
    $pdf->Row(Array(
        $columna['idBitaInv'],
        $columna['nombreUsuario'],
        $columna['fecha'],
        $columna['descripcion']
    ));
}

$pdf->Output();
?>