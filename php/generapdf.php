<?php
require('fpdf.php');

class PDF extends FPDF {
   function Header() {
      global $title;
      // Arial bold 15
      $this->SetFont('Arial','B',15);
      // Calculamos ancho y posición del título.
      $w = $this->GetStringWidth($title)+6;
      $this->SetX((210-$w)/2);
      // Colores de los bordes, fondo y texto
      $this->SetDrawColor(0,80,180);
      $this->SetFillColor(230,230,0);
      $this->SetTextColor(220,50,50);
      // Ancho del borde (1 mm)
      $this->SetLineWidth(1);
      // Título
      $this->Cell($w,9,$title,1,1,'C',true);
      // Salto de línea
      $this->Ln(10);
   }

   function Footer() {
      // Posición a 1,5 cm del final
      $this->SetY(-15);
      // Arial itálica 8
      $this->SetFont('Arial','I',8);
      // Color del texto en gris
      $this->SetTextColor(128);
      // Número de página
      $this->Cell(0,10,utf8_decode('Página ').$this->PageNo(),0,0,'C');
   }

   function ChapterTitle($num, $label) {
      // Arial 12
      $this->SetFont('Arial','',12);
      // // Color de fondo
      // $this->SetFillColor(200,220,255);
      // Título
      $this->Cell(0,6,utf8_decode("Sección")." $num : $label",0,1,'L',true);
      // Salto de línea
      $this->Ln(4);
   }

   function ChapterBody($file) {
      // $txt = $file;
      // Leemos el fichero
      $txt = utf8_decode(file_get_contents($file));
      // Times 12
      $this->SetFont('Times','',10);
      // Imprimimos el texto justificado
      $this->MultiCell(0,5,$txt);
      // Salto de línea
      $this->Ln();
      // Cita en itálica
      $this->SetFont('','I');
      $this->Cell(0,5,'(fin del documento)');
   }

   function PrintChapter($num, $title, $file) {
      $this->AddPage();
      // $this->ChapterTitle($num,$title);
      $this->ChapterBody($file);
   }
}