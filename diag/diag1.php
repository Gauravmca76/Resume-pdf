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
    
    function BarDiagram($w, $h, $data, $format, $color=null, $maxVal=0, $nbDiv=4)//(190,70)
	{
		$this->SetFont('Courier', '', 10);
		$this->SetLegends($data,$format);
		$XPage = $this->GetX();//(10.00125)
		$YPage = $this->GetY();//(23.00125)
		$margin = 2;
        //$YDiag = $YPage + $margin;//(25.00125) 
		$hDiag = floor($h - $margin * 2);//(66)
		//$XDiag = $XPage + $margin * 2 + $this->wLegend;//(75.384583)
		$lDiag = floor($w - $margin * 3 - $this->wLegend);//(122)
		if($color == null)
			$color=array(155,155,155);
		if ($maxVal == 0) {
			$maxVal = max($data);
		}
		$valIndRepere = ceil($maxVal / $nbDiv);//(300000)
		$maxVal = $valIndRepere * $nbDiv;//(1200000)
		$lRepere = floor($lDiag / $nbDiv);//(30)
		$lDiag = $lRepere * $nbDiv;//(120)
		$unit = $lDiag / $maxVal;//(0.0001)
		$hBar = floor($hDiag / ($this->NbVal + 1));//(22)
		$hDiag = $hBar * ($this->NbVal + 1);//(66)
		$eBaton = floor($hBar * 80 / 100);//(17)
       
        $XDiag11=70; $YDiag11=130;
		$this->SetLineWidth(0.2);
		$this->Rect($XDiag11, $YDiag11, $lDiag, $hDiag);//outer rect

		$this->SetFont('Courier', '', 10);
		$this->SetFillColor($color[0],$color[1],$color[2]);
		$i=0;
		foreach($data as $val) {
			//Bar
			$xval = $XDiag11;
			$lval = (int)($val * $unit);//(120,12)
			$yval = $YDiag11 + ($i + 1) * $hBar - $eBaton / 2;//(143)
            $hval = $eBaton;//(17,17)
            $this->Rect($xval, $yval, $lval, $hval, 'DF');//bar rect
        	//Legend
			$this->SetXY(0, $yval);
			$this->Cell($xval - $margin, $hval, $this->legends[$i],0,0,'R');
			$i++;
		}

		//Scales
		for ($i = 0; $i <= $nbDiv; $i++) {
			$xpos = $XDiag11 + $lRepere * $i;
			$this->Line($xpos, $YDiag11, $xpos, $YDiag11 + $hDiag);
			$val = $i * $valIndRepere;
			$xpos = $XDiag11 + $lRepere * $i - $this->GetStringWidth($val) / 2;
			$ypos = $YDiag11 + $hDiag - $margin;
			$this->Text($xpos, $ypos+6, $val);
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