<?php
/*
if($photo){
	echo "si";
} else {
	echo "no";
}
*/
$number_returned = $count_alumnes;
$codi_grup = $selected_group;
$contador=0;
//print_r($all_students_in_group[1]);
$alumne =array();

foreach($all_students_in_group as $student){

if($photo){
	/* Detectar tipus d'imatge (PNG o JPG) */
	$tipus = substr($student->jpegPhoto,0,10);

	$isJPG  = strpos($tipus, 'JFIF');
	if($isJPG){
		$extensio = ".jpg";
	} else {
		$isPNG  = strpos($tipus, 'PNG');
		if($isPNG){
		$extensio = ".png";
		}
	}

	$jpeg_filename="/tmp/".$student->irisPersonalUniqueID.$extensio;
	$jpeg_file[$contador]=$student->irisPersonalUniqueID.$extensio;
	$alumne[$contador]['jpegPhoto']=$student->irisPersonalUniqueID.$extensio;
	$outjpeg = fopen($jpeg_filename, "wb");
	fwrite($outjpeg, $student->jpegPhoto);
	fclose ($outjpeg);
	$jpeg_data_size = filesize( $jpeg_filename );

	if( $jpeg_data_size < 6 ) {
		$jpeg_file[$contador]='foto.png';
		$alumne[$contador]['jpegPhoto']='foto.png';
	}

}
$alumne[$contador]['givenName']=$student->givenName;
$alumne[$contador]['sn1']=$student->sn1;
$alumne[$contador]['sn2']=$student->sn2;

$contador++;
}

//$contador = 1;
/*
echo "<pre>";
print_r($alumne);
echo "</pre>";
*/
//Crido la classe
$pdf = new FPDF();
//Defineixo els marges
$pdf->SetMargins(10,10,10);
//Obro una pàgina
$pdf->AddPage();
//$pdf->AddPage("P","A3");
//Es la posicio exacta on comença a escriure
$x=7;//10
$y=15;//24
$pdf->Image(base_url().APPPATH.'third_party/skeleton/assets/img/logo_iesebre_2010_11.jpg',$x+2,5,40,15);
//Defineixo el tipus de lletra, si és negreta (B), si és cursiva (L), si és normal en blanc
$pdf->SetFont('Arial','',10);
//$pdf->Cell(Amplada, altura, text, marc, on es comença a escriure després, alineació)
$pdf->SetXY(10,10);
$any_comencament = 2013;
$any_finalitzacio = 2014;
$date = date('d-m-Y');
$pdf->Cell(190,6,"Curs: ".$any_comencament."-".$any_finalitzacio,0,0,'R');
$pdf->ln();
$pdf->Cell(190,6,"Data: ".$date,0,0,'R');
$pdf->ln();
$pdf->Cell(190,6,utf8_decode("Pàgina: ".$pdf->PageNo()),0,0,'R');
$pdf->ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,6,"Llistat Alumnes Grup",0,0,'C');

/**/
$pdf->SetFillColor(150,150,150);
$fill=true;
//Salt de línia
$pdf->Ln(7);
$pdf->SetFont('Arial','',8);

$pdf->Cell(10,8,utf8_decode("Núm."),1,0,'C',$fill);
if($photo){
	$pdf->Cell(8,8,utf8_decode("Foto"),1,0,'L',$fill);
	$pdf->Cell(70,8,utf8_decode("Alumne/a"),1,0,'L',$fill);
	$pdf->Cell(100,8,utf8_decode("Observacions"),1,0,'C',$fill);	
} else {
	$pdf->Cell(70,8,utf8_decode("Alumne/a"),1,0,'L',$fill);
	$pdf->Cell(110,8,utf8_decode("Observacions"),1,0,'C',$fill);
}
$pdf->Ln();

//Dades
$pdf->SetFillColor(219,219,219);
$fill=false;
$pdf->SetFont('Arial','',8);
//recorrem la matriu de dades i imprimim cada camp en una casella
$x=0;
for($t=0;$t<$number_returned;$t++){

	$pdf->Cell(10,8,utf8_decode($t+1),1,0,'C',$fill);
	if($photo){
		$pdf->Cell(8,8,$pdf->Image("/tmp/".$alumne[$t]['jpegPhoto'],$pdf->GetX(),$pdf->GetY(),6),1,0,'C',$fill);
		$pdf->Cell(70,8,utf8_decode($alumne[$t]['givenName']." ".$alumne[$t]['sn1']." ".$alumne[$t]['sn2'].""),1,0,'L',$fill);
		$pdf->Cell(100,8,utf8_decode(""),1,0,'C',$fill);		
	} else {	
		$pdf->Cell(70,8,utf8_decode($alumne[$t]['givenName']." ".$alumne[$t]['sn1']." ".$alumne[$t]['sn2'].""),1,0,'L',$fill);
		$pdf->Cell(110,8,utf8_decode(""),1,0,'C',$fill);
	}	
	//$fill=!$fill;
	$pdf->Ln();
}
//enviem tot al pdf
$today = date('Y_m_d');   
//$pdf->Output();
$pdf->Output($today."_".$codi_grup.".pdf","I");
?>

