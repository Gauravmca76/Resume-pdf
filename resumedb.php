<?php
require('C:\xampp\htdocs\pdf\fpdf\fpdf.php');
$con=mysqli_connect("localhost","root","");
if(!$con)
{
    die('Could not connect: '.mysqli_error());
}
mysqli_select_db($con,"resume");
$sql="SELECT * FROM tb_resume";
$result=mysqli_query($con,$sql);
class PDF extends FPDF
{
function Footer()
{
$this->setFont('Times','B',12);
$this->cell(190,10,"I hereby declare that the above information given by me is true in all respects.",0,1);
$this->cell(190,2,"Date- ",0,1);
$this->cell(130,10,"Place- Mumbai",0,0);
$this->cell(30,10,"GAURAV SINGH",0,0);
}
}
$pdf=new PDF();
$pdf->AddPage();
if(mysqli_num_rows($result) > 0)
{
while($row=mysqli_fetch_array($result))
{
$pdf->setFont('Times','BU',15);
$pdf->cell(71,10,'',0,0);
$pdf->cell(59,5,'RESUME',0,0);
$pdf->cell(59,5,'',0,1);
$pdf->ln(10);
$pdf->setFont('Times','B',12);
$pdf->cell(15,5,"Name: ",0,0);
$pdf->setFont('Times','',12);
$pdf->cell(20,5,$row['name'],0,1);//name
$pdf->Image($row['cv_image'],145,25,30,35,'JPG');//image
$pdf->ln(2);
$pdf->setFont('Times','B',12);
$pdf->cell(25,5,"Contact No:",0,0);
$pdf->setFont('Times','',12);
$pdf->cell(20,5,$row['contact'],0,1);//contact
$pdf->ln(2);
$pdf->setFont('Times','B',12);
$pdf->cell(20,5,"Address:",0,0);
$pdf->setFont('Times','',12);
$pdf->cell(10,5,$row['add1'],0,1);//address1
$pdf->ln(2);
$pdf->cell(50,5,$row['add2'],0,1);//address2
$pdf->ln(2);
$pdf->cell(50,5,$row['add3'],0,1);//address3
$pdf->ln(2);
$pdf->setFont('Times','B',12);
$pdf->cell(15,5,"E-mail:",0,0);
$pdf->setFont('Times','',12);
$pdf->cell(10,5,$row['email'],0,1);//email
$pdf->ln(2);
$pdf->setFont('Times','',12);
$pdf->cell(130,5,"*******************************************************************************",0,1);
$pdf->ln(2);
$pdf->setFont('Times','BU',12);
$pdf->setFillColor(169,169,169);
$pdf->cell(190,10,"Objective:- ",0,1,'',true);
$pdf->ln(2);
$pdf->setFont('Times','B',12);
$pdf->cell(120,10,$row['objective'],0,1);//objective
$pdf->ln(2);
$pdf->setFont('Times','BU',12);
$pdf->setFillColor(169,169,169);
$pdf->cell(190,5,"Personal Details:- ",0,1,'',true);$pdf->ln(4);
$pdf->setFont('Times','B',12);
$pdf->cell(30,5,"Date Of Birth:- ",0,0,'L');
$pdf->cell(10,5,$row['dob'],0,1);//DOB
$pdf->ln(2);
$pdf->cell(30,5,"Marital Status:- ",0,0,'L');
$pdf->cell(20,5,$row['martial'],0,1);//maritial
$pdf->ln(2);
$pdf->cell(18,5,"Gender:- ",0,0,'L');
$pdf->cell(10,5,$row['gender'],0,1);//gender
$pdf->ln(2);
$pdf->cell(36,5,"Language Knows:- ",0,0,'L');
$pdf->cell(10,5,$row['languages'],0,1);//languages
$pdf->ln(2);
$pdf->cell(20,5,"Hobbies:- ",0,0,'L');
$pdf->cell(10,5,$row['hobbies'],0,1);//hobbie
$pdf->ln(2);
$pdf->setFont('Times','BU',12);
$pdf->setFillColor(169,169,169);
$pdf->cell(190,10,"Education Qualification:- ",0,1,'',true);
$pdf->ln(3);
$pdf->setFont('Times','B',12);
$pdf->cell(30,10,"Examination",1,0,'C');
$pdf->cell(40,10,"University/Board",1,0,'C');
$pdf->cell(35,10,"Institute",1,0,'C');
$pdf->cell(50,10,"Year of Passing",1,0,'C');
$pdf->cell(40,10,"Aggregate in %",1,1,'C');

$pdf->cell(30,10,$row['examination'],1,0,'C');
$pdf->cell(40,10,$row['university'],1,0,'C');
$pdf->cell(35,10,$row['institute'],1,0,'C');
$pdf->cell(50,10,$row['year_of_passing'],1,0,'C');
$pdf->cell(40,10,$row['aggregates'],1,1,'C');
while($row1=mysqli_fetch_assoc($result))
{
    $pdf->cell(30,10,$row1['examination'],1,0,'C');
    $pdf->cell(40,10,$row1['university'],1,0,'C');
    $pdf->cell(35,10,$row1['institute'],1,0,'C');
    $pdf->cell(50,10,$row1['year_of_passing'],1,0,'C');
    $pdf->cell(40,10,$row1['aggregates'],1,1,'C');
}
$pdf->ln(4);
$pdf->setFont('Times','BU',12);
$pdf->setFillColor(169,169,169);
$pdf->cell(190,10,"Key Skills:- ",0,1,'',true);
$pdf->ln(2);
$pdf->setFont('Times','B',12);
$pdf->cell(190,10,$row['skills'],0,1);//skills
$pdf->ln(1);
$pdf->setFont('Times','BU',12);
$pdf->setFillColor(169,169,169);
$pdf->cell(190,10,"WORK EXPERIENCE:- ",0,1,'',true);
$pdf->ln(1);
$pdf->setFont('Times','B',12);
$pdf->Image('arraow.png',10,246,5,5,'PNG');
$pdf->cell(10,10," ",0,0);
$pdf->cell(10,10,$row['work'],0,1);//work
$pdf->AliasNbPages();
}
}
$pdf->output();
?>