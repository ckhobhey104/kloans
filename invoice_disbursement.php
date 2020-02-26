<?php
session_start();
include_once("../../fpdf/fpdf.php");
if($_GET["disbursal_date"] && $_GET["loan_principal"]){
	$pdf = new FPDF('L','mm',array(110,120));
	$pdf->SetFillColor(230,230,230);
	$pdf->AddPage();

	//SET FONT
	$pdf->SetFont("Arial","B",13);
	$pdf->Cell(86,7,"Grow Loans Money Lending Services",0,1,"C");
	$pdf->SetFont("Arial","I",11);
	$pdf->Cell(86,7,"Kasoa Adjacent Odupong Kpehe School",0,1,"C");
	$pdf->SetFont("Arial","U",10);
	$pdf->Cell(86,7,"0249514575 or 0209130317",0,1,"C");
	$pdf->SetFont("Arial",null,8);
	$pdf->Cell(25,6,"Disbursal Date",0,0);
	$pdf->Cell(20,6,":".$_GET["disbursal_date"],0,1);
	$pdf->Cell(25,6,"Client",0,0);
	$pdf->Cell(25,6,":".$_GET["name_of_client"],0,1);
	$pdf->Cell(25,6,"Loan Purpose:",0,0);
	$pdf->Cell(25,6,"".$_GET["loan_purpose"],0,1);
	$pdf->Cell(25,6,"Loan Amount",0,0);
	$pdf->Cell(25,6,":".$_GET["loan_principal"],0,1);
	$pdf->Cell(25,6,"Loan Term",0,0);
	$pdf->Cell(25,6,":".$_GET["loan_term"]." Months",0,1);
	$pdf->Cell(25,6,"Interest Rate",0,0);
	$pdf->Cell(25,6,":".$_GET["loan_interest"]." %",0,1);
	$pdf->Cell(25,6,"Loan Purpose",0,0);
	$pdf->Cell(20,6,":".$_GET["loan_purpose"],0,1);
	$pdf->Cell(25,6,"Payment Interval",0,0);
	$pdf->Cell(25,6,":".$_GET["interval"],0,1);
	$pdf->Cell(30,6,"Start Repayment On",0,0);
	$pdf->Cell(20,6,":".$_GET["start_payment"],0,1);






	


	$pdf->Output("../../PDF_INVOICE/LOAN_DISBURSEMENT/LOAN_".$_GET["name_of_client"]."_".$_GET["disbursal_date"].".pdf","F");
	$pdf->Output();

} 
