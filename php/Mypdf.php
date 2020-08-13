<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	require_once dirname(__FILE__) . '/tcpdf/tcpdf_include.php';
	require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
//        $image_file = $_SESSION['emp_logo'];
//        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
/*
        $image_file = 'fondocertificado.jpg';
        $this->Image($image_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '"CORPORACIÓN MANNA\nCertificado de afiliación"', 0, false, 'C', 0, '', 0, false, 'M', 'M');
 */
       // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = K_PATH_IMAGES.'../img/logoclub.png';
        $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();        
///////////////////////////////////////////
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        // $this->Cell(0, 15, '"CORPORACIÓN MANNA\nCertificado de afiliación"', 0, false, 'C', 0, '', 0, false, 'M', 'M');

    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Pagina '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}