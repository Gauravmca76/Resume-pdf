<?php
require('diag1.php');
session_start();
$p=$_SESSION['top'];
$ir=$_SESSION['toir'];
$pdf = new PDF_Diag();
$pdf->AddPage();
//$data = array('Principal' => 7000, 'Interest' => 3900, 'EMI' => 1100);
//$data = array('Principal' => 17230, 'Interest' => 3900);
$data = array('Principal' => $p, 'Interest' => $ir);

//Pie chart
$pdf->SetFont('Arial', 'BIU', 12);
$pdf->Cell(0, 5, '1 - Pie chart', 0, 1);
$pdf->Ln(8);

$pdf->SetFont('Arial', '', 10);
$valX = round($pdf->GetX());
$valY = round($pdf->GetY());
$pdf->Cell(30, 5, 'Principal Amount:');
$pdf->Cell(15, 5, $data['Principal'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Intrest Rate:');
$pdf->Cell(15, 5, $data['Interest'], 0, 0, 'R');
$pdf->Ln();
//$pdf->Cell(30, 5, 'EMI Amount:');
//$pdf->Cell(15, 5, $data['EMI'], 0, 0, 'R');
$pdf->Ln();
$pdf->Ln(8);

$pdf->SetXY(90, $valY);
$col1=array(100,100,255);
$col2=array(255,100,100);
$col3=array(255,255,100);
$pdf->PieChart(100, 35, $data, '%l (%p)', array($col1,$col2,$col3));
$pdf->SetXY($valX, $valY + 40);

//Bar diagram
$pdf->ln(50);
$pdf->SetFont('Arial', 'BIU', 12);
$pdf->Cell(0, 5, '2 - Bar diagram', 0, 1);
$pdf->Ln(2);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->BarDiagram(190, 70, $data, '%l : %v (%p)', array(255,175,100));
$pdf->SetXY($valX, $valY + 80);

$pdf->Output();
?>
