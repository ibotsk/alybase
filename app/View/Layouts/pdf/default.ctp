<?php
 
//header('Content-Disposition: attachment; filename="export.pdf"');
$pdf->AddPage();
/*$fpdf->AddFont('custom', '', '590e0df9a67dbf0ae1f3349949da62a1_arial.php');
$fpdf->AddFont('custom', 'B', '590e0df9a67dbf0ae1f3349949da62a1_arial.php');
$fpdf->AddFont('custom', 'BI', '590e0df9a67dbf0ae1f3349949da62a1_arial.php');
$fpdf->AddFont('custom', 'I', '590e0df9a67dbf0ae1f3349949da62a1_arial.php');*/
$pdf->SetFont('helvetica','',11);
$pdf->WriteHTML(utf8_decode($content_for_layout));
//echo $content_for_layout;
$pdf->Output();
