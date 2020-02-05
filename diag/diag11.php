<?php
require('sector.php');

class PDF_Diag extends PDF_Sector {
	var $legends;
	var $wLegend;
	var $sum;
	var $NbVal;

	function PieChart($w, $h, $data, $format, $colors=null)//(100,35)
	{
		$this->SetFont('Courier', '', 10);
		$this->SetLegends($data,$format);

		$XPage = $this->GetX();//(90)
        $YPage = $this->GetY();//(23)
		$margin = 1;
		$hLegend = 5;
        $radius = min($w - $margin * 4 - $hLegend - $this->wLegend, $h - $margin * 2);//(352.9,68)
		$radius = floor($radius / 2);//(34)
		$XDiag = $XPage + $margin + $radius;//(125)
		$YDiag = $YPage + $margin + $radius;//(58)
		if($colors == null) {
			for($i = 0; $i < $this->NbVal; $i++) {
				$gray = $i * intval(255 / $this->NbVal);
				$colors[$i] = array($gray,$gray,$gray);
			}
		}

		//Sectors
		$this->SetLineWidth(0.2);
		$angleStart = 0;
		$angleEnd = 0;
        $i = 0;
        $radius1=30;
        $XDiag1=90;
        $YDiag1=50;
		foreach($data as $val) {
            $angle = ($val * 360) / doubleval($this->sum);//(320.85332969246,39.14667030754)
			if ($angle != 0) {
				$angleEnd = $angleStart + $angle;//(320.85332969246,360)
				$this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
                $this->Sector($XDiag1, $YDiag1, $radius1, $angleStart, $angleEnd);
                $angleStart += $angle;
            }
			$i++;
		}

		//Legends
		$this->SetFont('Courier', 'I', 10);
		$x1 = $XPage + 2 * $radius + 4 * $margin;
		$x2 = $x1 + $hLegend + $margin;
		$y1 = $YDiag - $radius + (2 * $radius - $this->NbVal*($hLegend + $margin)) / 2;
		for($i=0; $i<$this->NbVal; $i++) {
			$this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
			$this->Rect($x1, $y1, $hLegend, $hLegend, 'DF');//legends symbol
			$this->SetXY($x2,$y1);
			$this->Cell(0,$hLegend,$this->legends[$i]);//pietext
			$y1+=$hLegend + $margin;
		}
    }
    
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
        $lDiag = $lBar * ($this->NbVal + 1)-10;
        $eColumn = floor($lBar * 80 / 100);
        $this->SetLineWidth(0.2);
        $this->Rect($XDiag, $YDiag+10, $lDiag, $hDiag);

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
                $this->Rect($xval+($lval*$j), $yval+10, $lval, -$hval, 'DF');
                $j++; 
            }
            $this->Text($xval+($lval*$j)-12, $yval+15,$dt);//X-axises
            $i++; $dt+=1;
        }
        //Legends
        $this->SetFont('Courier', 'I', 10);
        $x1 = $XPage + 2 * 34 + 3 * -10;//right and left legends move
        $x2 = $x1 + 15 + $margin;
        $y1 = $YDiag - 34 + (2 * 34 - $this->NbVal*(-40 + $margin)) / 2;//down and up side legends move
        for($i = 0; $i < 1; $i++) 
        {
        $this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
        $this->Rect($xval+($lval*$j)-110, $yval+20, 5, 5, 'DF');//legends symbol
        $y1+= 5 + $margin;
        }
        
        for($i = 0; $i < 1; $i++) 
        {
        $this->SetFillColor($colors[1][0],$colors[1][1],$colors[1][2]);
        $this->Rect($xval+($lval*$j)-80, $yval+20, 5, 5, 'DF');//legends symbol
        $y1+= 5 + $margin;
        }
        $this->SetXY($xval+($lval*$j)-105, $yval+20);//bartext position
        $this->Cell(0,5,'Principal',0,1);//bartext
        $this->ln(2);
        $this->SetXY($xval+($lval*$j)-75, $yval+20);//bartext position
        $this->Cell(0,5,'Interest Rate',0,0);//bartext

        //Scales
        for ($i = 0; $i <= $nbDiv; $i++) 
        {
            $ypos = $YDiag + $hRepere * $i;
            $this->Line($XDiag, $ypos+10, $XDiag + $lDiag, $ypos+10);
            $val = ($nbDiv - $i) * $valIndRepere;
            $ypos = $YDiag + $hRepere * $i;
            $xpos = $XDiag - $margin - $this->GetStringWidth($val);
            $this->Text($xpos+1, $ypos+12 , $val);
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
?>