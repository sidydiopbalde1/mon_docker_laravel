<?php
namespace App\Services;

interface PdfServiceInterface 
{
    
public function generatePdf(string $view, array $data, string $pdfPath);
}