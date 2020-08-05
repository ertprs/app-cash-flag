<?php
	//*** Para generar el pdf ***
	function reg_pdf($registro){
		$this->load->library('pdf');
		$pdf = new PDF('P', 'mm', 'letter', true, 'UTF‐8', false);
		$pdf->SetTitle('CERTIFICADO DE AFILIACIÓN');
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_MAIN));
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(true,PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setPrintHeader(false);
		$pdf->AddPage();
		// get the current page break margin
		$bMargin = $pdf->getBreakMargin();
		// get current auto-page-break mode
		$auto_page_break = $pdf->getAutoPageBreak();
		// disable auto-page-break
		$pdf->SetAutoPageBreak(false, 0);
		// set bacground image
		$img_file = K_PATH_IMAGES.'fondocertificado.jpg';
		$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
		// restore auto-page-break status
		$pdf->SetAutoPageBreak($auto_page_break, $bMargin);
		// set the starting point for the page content
		$pdf->setPageMark();		
		$pdf->Writehtml('
			<h4 align="center">DATOS DEL ENROLADOR</h4>
			', true, false, true, false, '');
		$pdf->Output('Certificado_'.trim($registro['tit_codigo_largo']).'.pdf', 'I');
	}
?>
