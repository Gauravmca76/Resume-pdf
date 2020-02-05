<html>
<head>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style type="text/css">
    table#emi{
        border:1px solid #d4d4d4;
        margin:0 auto;
        font-family:'Cantora One', sans-serif;
        font-size:14px;
    }
    table#emi td{
        padding:5px;
    }
    table#emi tr:nth-child(even){
        background:#E4E4E4;
        border:1px solid #D4D4D4;
        border-left:0;
        border-right:0;
    }
    table#emi tr td:nth-last-child(1){
        background:#D7E4FF;
    }
    table#emi input{
        margin-bottom:5px !important;
        margin-top:5px;
    }
    #result td{
        padding:5px;
    }
    table#result{
        width:477px;
        border:1px solid #d4d4d4;
        margin:0 auto;
        margin-top:10px;
        display:none;
        font-family:'Cantora One', sans-serif;
        font-size:14px;
    }
    table#result tr:nth-child(even){
        background:#E4E4E4;
        border:1px solid #D4D4D4;
    }
    table#result tr td:nth-last-child(1){
        width:213px;
    }
span.err{
        color:#F00;
        font-weight:bold;
    }
</style>
</head>
<body>
<form name="loandata" method="post" action="finalemical.php">
    <table id="emi" width="100%">
        <tr>
            <td colspan="3">
                <b>
                    Enter Loan Information:
                </b>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td width="48%">
                Amount of the loan (any currency):
                <span class="err">*</span>
            </td>
            <td>
                <input type="text" name="principal" size="12" >
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                Annual percentage rate of interest: 
                <span class="err">*</span>
            </td>
            <td>
                <input type="text" name="interest" size="12">
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                Repayment period in years: 
                <span class="err">*</span>
            </td>
            <td>
                <input type="text" name="years" size="12">
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <input type="submit" value="Compute"  name="EMI_submit" id="EMI_submit" class="btn btn-primary">
            </td>
        </tr>
    </table>
</form>
<form method="post" action="phpchart.php"><input type="submit" value="Show Bar Graph"  name="EMI_submit" class="btn btn-primary"></form>
<form method="post" action="phppie.php"><input type="submit" value="Show Pie Graph"  name="EMI_submit" class="btn btn-primary"></form>
<form method="post" action="ex1.php"><input type="submit" value="Show Graph PDF"  name="EMI_submit" class="btn btn-primary"></form>
<!-- <form method="post" action="stackbar.php"><input type="submit" value="Show Stack Bar"  name="EMI_submit" class="btn btn-primary"></form> -->
</body>
</html>
<?php
error_reporting(0);
session_start();
$l=0;
if(isset($_POST['EMI_submit']))
{
$rate = $_POST['interest']/100/12;
$principle = $_POST['principal'];
$time = $_POST['years']*12;
$t=$time/12;
$x= pow(1+$rate,$time);
$monthly = ($principle*$x*$rate)/($x-1);
$monthly = round($monthly);
$total_interest_rate=0;
$total_principal=0;
$abc=0;
$xyz=0;
$current_month = 1;
$current_year  = 1;
print("<table cellpadding='5' cellspacing='0' bgcolor='#eeeeee' border='1' width='100%'>");
$legend  = "\t<tr valign='top' bgcolor='#cccccc'>\n";
$legend .= "\t\t<td><b>Month</b></td>\n";
$legend .= "\t\t<td><b>Interest Paid</b></td>\n";
$legend .= "\t\t<td><b>Beginning Balance</b></td>\n";
$legend .= "\t\t<td><b>Principal Paid</b></td>\n";
$legend .= "\t\t<td><b>Monthely Paid</b></td>\n";
$legend .= "\t\t<td><b>Remaing Balance</b></td>\n";
$legend .= "\t</tr>\n";
echo $legend;
while($current_month <= $time)
{
global $rate,$principle,$monthly,$r,$p,$e,$total_interest_rate,$total_principal,$_SESSION,$t,$abc,$xyz;
$r = $principle * $rate;
$p = round($monthly-$r);
$e = round($principle-$p);
//2nd last row
if($current_month==$time-1)
{
$_SESSION['tl']=$e;
}
//last row
if($current_month==$time)
{
$p=$_SESSION['tl'];
$e=$p-$p;
}
$total_interest_rate=$total_interest_rate+$r;//total interest year wise
$total_principal=$total_principal+$p;//total principal year wise    
print("\t<tr valign='top' bgcolor='#eeeeee'>");
print("\t\t<td>".$current_month."</td>");
print("\t\t<td>".number_format(round($r))."</td>");
print("\t\t<td>".number_format($principle)."</td>");
print("\t\t<td>".number_format($p)."</td>");
print("\t\t<td>".number_format($monthly)."</td>");
print("\t\t<td>".number_format($e)."</td>");
print("</tr>");
($current_month % 12) ? $show_legend = FALSE : $show_legend = TRUE;
if($show_legend)
{
print("\t<tr valign='top' bgcolor='#ffffcc'>\n");
print("\t\t<td colspan='6'><b>Totals for year " . $current_year . "</td>\n");
print("\t</tr>\n");
$total_spend_year = $total_principal + $total_interest_rate;//total amount year wise
print("\t<tr valign='top' bgcolor='$cccccc'>\n");
print("\t\t<td>&nbsp;</td>");
print("\t\t<td colspan='5'>\n");
print("\t\tYou will Spend ".number_format($total_spend_year)." in year ".$current_year."<br>\n");   
print("\t\tTotal Interest Rate in ".$current_year." year is ".number_format($total_interest_rate)."<br>\n"); 
print("\t\tTotal Principal in ".$current_year." year is ".number_format($total_principal)."\n"); 
$_SESSION['ttp'][$l]=(round($total_principal));
$_SESSION['ir'][$l]=(round($total_interest_rate));
$l++;//counter variable
print("\t\t</td>\n");
print("\t</tr>");
print("\t<tr valign='top' bgcolor='$ffffff'>\n");
print("\t\t<td colspan='6'>&nbsp;<br><br></td>\n");
print("\t</tr>\n");
$current_year++;
if($current_month < $time)
{
echo $legend;
}
$_SESSION['ti']=$t;
$_SESSION['tp']=round($total_principal);
$_SESSION['tir']=round($total_interest_rate);
}//if close
$abc+=$_SESSION['tp'];
$xyz+=$_SESSION['tir'];
$_SESSION['top']=round($abc);
$_SESSION['toir']=round($xyz);
if($current_month % 12 == 0)
{
$total_interest_rate=0;
$total_principal=0;
}
$principle=$e;
$current_month++;
}//while close
print("</table>");
}
?>