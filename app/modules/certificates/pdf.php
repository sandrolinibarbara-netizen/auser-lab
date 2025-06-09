<?php
//dati esempio
$user = $parsed['course']['nome'] . ' ' . $parsed['course']['cognome'];
$course = $parsed['course']['corso'];
$upperCourse = strtoupper($course);
$lessonsNumber = $parsed['course']['lezioni'];
$lessonsLength = $parsed['course']['lunghezza_lezione'];
$start = $parsed['course']['data_inizio'];
$end = $parsed['course']['data_fine'];
$location = 'via Brescia, 207 - c/o Ex Portineria Cremona Solidale - 26100 Cremona';
$teacherNumber = count($parsed['course']['teachers']) == 1 ? 'Docente' : 'Docenti';
$teachers = '';

foreach ($parsed['course']['teachers'] as $key => $teacher) {
    $teachers .= $teacher;
    if($key != count($parsed['course']['teachers'])-1){
        $teachers .= ', ';
    }
}

$organizer = 'Auser Università Popolare di Cremona';
$where = 'Cremona';
$date = new DateTime();
$formattedDate = $date->format("d/m/Y");

//Posizioni e dimensioni degli elementi
$customSize = array(252, 178);
//Intestazione
$certificateSize = ['w' => 0, 'h' => 0];
$certificatePosition = ['x' => 16, 'y' => 24];
$whoSize = ['w' => 120, 'h' => 0];
$whoPosition = ['x' => 73.5, 'y' => 38];
$whatSize = ['w' => 210, 'h' => 0];
$whatPosition = ['x' => 22, 'shortTitleY' => 66, 'longTitleY' => 60];
//Contenuto
$contentSize = ['w' => 0, 'h' => 0];
$contentPosition = ['x' => 24, 'y' => 82];

// create new PDF document
$pdf = new TCPDF('L', PDF_UNIT, $customSize, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nebbia');
$pdf->SetTitle('Attestato');
$pdf->SetSubject("Attestato di $user di partecipazione al corso $course");
$pdf->SetKeywords("attestato, $course");

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set margins
$pdf->SetMargins(0, 0, 0);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->SetAutoPageBreak(false, 0);

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);
$pdf->setCellHeightRatio(1);

$pdf->AddPage();

//Immagini e cornice
$pdf->Image('../assets/certificates/frame.png',0,0,$customSize['0'],$customSize['1']);
$pdf->Image('../assets/certificates/auser_logo.png',11,10);
$pdf->Image('../assets/certificates/course_logo.png',202,14);
$pdf->Image('../assets/certificates/signature.png',182,118);

//Contenuto HTML
$certificate = "<h2>ATTESTATO DI PARTECIPAZIONE</h2>";

$who = "<p>Si attesta che $user<br>HA FREQUENTATO IL</p>";

$what = "<h1>CORSO DI \"$upperCourse\"</h1>";

$whatLength = $pdf->GetStringWidth($what);

$content = '<div style="font-size:14px;"><p>Monte ore: '.$lessonsNumber." lezioni da $lessonsLength ore, dal $start al $end</p>
<p>Sede del corso: $location</p>
<p>$teacherNumber: $teachers</p>
<p>Soggetto organizzatore del corso: $organizer</p>
<p></p>
<p>Data e luogo: $where $formattedDate</p></div>";

//Inserimento del contenuto HTML nelle celle
$pdf->setCellHeightRatio(1.75);
$pdf->writeHTMLCell($certificateSize['w'],$certificateSize['h'],$certificatePosition['x'],$certificatePosition['y'],$certificate,0,0,0,1,'C',1);
$pdf->writeHTMLCell($whoSize['w'],$whoSize['h'],$whoPosition['x'],$whoPosition['y'],$who,0,0,0,1,'C',1);
$pdf->setCellHeightRatio(1.25);
$pdf->writeHTMLCell($whatSize['w'],$whatSize['h'],$whatPosition['x'],$whatLength > 164.9 ? $whatPosition['longTitleY'] : $whatPosition['shortTitleY'],$what,0,0,0,1,'C',1);

$pdf->setCellHeightRatio(0.95);
$pdf->writeHTMLCell($contentSize['w'],$contentSize['h'],$contentPosition['x'],$contentPosition['y'],$content,0,0,0,1,'L',1);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_001.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
