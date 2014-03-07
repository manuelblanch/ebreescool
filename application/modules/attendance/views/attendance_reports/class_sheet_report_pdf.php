<?php
$number_returned = $count_alumnes;
$codi_grup = $selected_group;
$contador=0;
//print_r($all_students_in_group[1]);
$alumne =array();

foreach($all_students_in_group as $student){

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
$alumne[$contador]['givenName']=$student->givenName;
$alumne[$contador]['sn1']=$student->sn1;
$alumne[$contador]['sn2']=$student->sn2;

$outjpeg = fopen($jpeg_filename, "wb");
fwrite($outjpeg, $student->jpegPhoto);
fclose ($outjpeg);
$jpeg_data_size = filesize( $jpeg_filename );


	if( $jpeg_data_size < 6 ) {
						//echo "jpegPhoto $jpeg_file[$contador] contains errors<br />";
						//echo '<a href="javascript:deleteJpegPhoto();" style="color:red; font-size: 75%">Delete Photo</a>';
						//$jpeg_filename="/tmp/foto.jpg";
						$jpeg_file[$contador]='foto.png';
						$alumne[$contador]['jpegPhoto']='foto.png';
						//$outjpeg = fopen($jpeg_filename, "wb");
						//fwrite($outjpeg, $student->jpegPhoto);
						//fclose ($outjpeg);
						//continue;
					}

 //Convert image to image without Alpha Channel: TODO: Try to use TCPDF instead of FPDF		
		            //We need imagemagick installed on server
		            //http://acacha.org/mediawiki/index.php/Imagemagick#Eliminar_el_canal_Alfa
		            //convert 201011-406.png -background white -flatten +matte 201011-406_no.png
		            //$cmd="/usr/bin/convert $jpeg_filename -background white -flatten +matte /tmp/$jpeg_file[$contador]";
		            //$cmd="/usr/bin/convert $jpeg_filename -background white -flatten +matte /tmp/$alumne[$contador]['jpegPhoto']";
		            //echo "$cmd"."</br>";
		            //exec($cmd);
$contador++;
}

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
$pdf->Cell(190,6,"FOTOGRAFIES DELS ALUMNES",0,0,'C');
$pdf->Ln(5);
$pdf->Cell(120,0,"Grup: ".utf8_decode($codi_grup." (".$codi_grup.")"),0,0,'T');
//$pdf->Cell(120,0,utf8_decode("En aquest grup hi ha ".$number_returned." Alumnes"),0,0,'T');
//Salt de línia
$pdf->Ln(7);

$pdf->SetFont('Arial','',10);
//$pos = strpos($all_students_in_group[1]->dn,',');
//$dn = substr($all_students_in_group[1]->dn,($pos+1),strlen($all_students_in_group[1]->dn));
/*
echo "<pre>";
print_r($alumne);
echo "<br /></pre>";
*/
//Dades
$pdf->SetFillColor(219,219,219);
$fill=false;
$pdf->SetFont('Arial','',8);
//recorrem la matriu de dades i imprimim cada camp en una casella
$z=0;
$pc=0;
$test=0;
//echo count($alumne);

for($j=0; $j<$number_returned;$j++){


	if(($z%6)==0) {
		$pdf->Ln();
		for($test=$pc;$test<$z=$j;$test++){

			$nom_alumne = $alumne[$test]['sn1'].", ".$alumne[$test]['givenName']." ";

			if(strlen($nom_alumne)>18){
				$pdf->SetFont('Arial','',6);
			} else if (strlen($nom_alumne)>22) {
				$pdf->SetFont('Arial','',5);
			} else {
				$pdf->SetFont('Arial','',7);
			}

			$pdf->Cell(28.75,8, utf8_decode($nom_alumne),1,0,'L',$fill);
			$pc++;
		}	
		$pdf->Ln();

	if($z==30){
		$pdf->AddPage();
	}

	}
			$pdf->Cell(28.75,38.5,$pdf->Image("/tmp/".$alumne[$j]['jpegPhoto'],$pdf->GetX(),$pdf->GetY(),28.75),1,0,'C',$fill);					
			$z++;
	}

if($z==$number_returned){
	$pdf->Ln();
		for($test=$pc;$test<$z=$j;$test++){

			$nom_alumne = $alumne[$test]['sn1'].", ".$alumne[$test]['givenName']." ";

			if(strlen($nom_alumne)>18){
				$pdf->SetFont('Arial','',6);
			} else if (strlen($nom_alumne)>22) {
				$pdf->SetFont('Arial','',5);
			} else {
				$pdf->SetFont('Arial','',7);
			}

			$pdf->Cell(28.75,8, utf8_decode($nom_alumne),1,0,'L',$fill);
			$pc++;
		}



}

//enviem tot al pdf
$today = date('Y_m_d');   
//$pdf->Output();
$pdf->Output("Llençol_".$today."_".$codi_grup.".pdf","I");

/**/
//enviem tot al pdf
//$pdf->Output();

?>
