<?php

require('sector.php');
session_start();
class PDF_Diag extends PDF_Sector {
    var $legends;
    var $wLegend;
    var $sum;
    var $NbVal;
    

    function ColumnChart($w, $h, $data, $format, $color=null, $maxVal=0, $nbDiv=4)
    {


        $colors[0][0] = 155;
        $colors[0][1] = 75;
        $colors[0][2] = 155;


        $colors[1][0] = 0;
        $colors[1][1] = 155;
        $colors[1][2] = 0;


        $colors[2][0] = 75;
        $colors[2][1] = 155;
        $colors[2][2] = 255;


        $colors[3][0] = 75;
        $colors[3][1] = 0;
        $colors[3][2] = 155;

        $this->SetFont('Courier', '', 10);
        $this->SetLegends($data,$format);


        $XPage = $this->GetX();
        $YPage = $this->GetY();
        $margin = 2; 


        $YDiag = $YPage + $margin;


        $hDiag = floor($h - $margin * 2);


        $XDiag = $XPage + $margin;


        $lDiag = floor($w - $margin * 3 - $this->wLegend);

        if($color == null)
            $color=array(155,155,155);
        if ($maxVal == 0) 
        {
            foreach($data as $val)
            {
                if(max($val) > $maxVal)
                {
                    $maxVal = max($val);
                }
            }
        }


        $valIndRepere = ceil($maxVal / $nbDiv);

        $maxVal = $valIndRepere * $nbDiv;

        $hRepere = floor($hDiag / $nbDiv);

        $hDiag = $hRepere * $nbDiv;

        $unit = $hDiag / $maxVal;
        $lBar = floor($lDiag / ($this->NbVal + 1));
        $lDiag = $lBar * ($this->NbVal + 1);
        $eColumn = floor($lBar * 80 / 100);
        $this->SetLineWidth(0.2);
        $this->Rect($XDiag, $YDiag, $lDiag, $hDiag);

        $this->SetFont('Courier', '', 10);
        $this->SetFillColor($color[0],$color[1],$color[2]);
        $i=0; $dt=2020; 
        foreach($data as $val) 
        {
            //Column
            $yval = $YDiag + $hDiag;
            $xval = $XDiag + ($i + 1) * $lBar - $eColumn/2;
            $lval = floor($eColumn/(count($val)));
            $j=0;  
            foreach($val as $v)
            {
                $hval = (int)($v * $unit);
                $this->SetFillColor($colors[$j][0], $colors[$j][1], $colors[$j][2]);
                $this->Rect($xval+($lval*$j), $yval, $lval, -$hval, 'DF');
                $j++; 
            }
            $this->Text($xval+($lval*$j)-10, $yval+5,$dt);//X-axises
            $i++; $dt+=1;
        }
        //Legends
        $this->SetFont('Courier', 'I', 10);
        $x1 = $XPage + 2 * 34 + 3 * 13;//right and left legends move
        $x2 = $x1 + 15 + $margin;
        $y1 = $YDiag - 34 + (2 * 34 - $this->NbVal*(-10 + $margin)) / 2;//down side legends move
        for($i = 0; $i < 2; $i++) 
        {
        $this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
        $this->Rect($x1, $y1, 5, 5, 'DF');//legends symbol
        $y1+= 5 + $margin;
        }
        $this->SetXY($x1+8,$y1-15);
        $this->Cell(0,5,'Principal',0,1);//bartext
        $this->ln(2);
        $this->SetXY($x1+8,$y1-7);
        $this->Cell(0,5,'Interest Rate',0,0);//bartext

        //Scales
        for ($i = 0; $i <= $nbDiv; $i++) 
        {
            $ypos = $YDiag + $hRepere * $i;
            $this->Line($XDiag, $ypos, $XDiag + $lDiag, $ypos);
            $val = ($nbDiv - $i) * $valIndRepere;
            $ypos = $YDiag + $hRepere * $i;
            $xpos = $XDiag - $margin - $this->GetStringWidth($val);
            $this->Text($xpos, $ypos, $val);
        }
        
    }

    function SetLegends($data, $format)
	{
		$this->legends=array();
		$this->wLegend=0;
		$this->sum=array_sum($data);
		$this->NbVal=count($data);
		foreach($data as $l=>$val)
		{
			$p=sprintf('%.0f',$val).'';
			$legend=str_replace(array('%l','%v','%p'),array($l,$val,$p),$format);
			$this->legends[]=$legend;
			$this->wLegend=max($this->GetStringWidth($legend),$this->wLegend);
		}
	}
}


$pdf = new PDF_Diag();
$pdf->AddPage();

$l=0;
for($i = 0; $i < $_SESSION['ti']; $i++)
{
    $data[$i]=array($_SESSION['ttp'][$l],$_SESSION['ir'][$l]);
    $l++;
}

// Column chart
$pdf->SetFont('Arial', 'BIU', 12);
$pdf->Cell(210, 5, 'Chart Title', 0, 1, 'C');
$pdf->Ln(8);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->ColumnChart(110, 100, $data,null, array(255,175,100));
$pdf->Output();
?>