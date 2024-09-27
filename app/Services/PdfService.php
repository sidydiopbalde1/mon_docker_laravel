<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfService implements PdfServiceInterface
{
    public function generatePdf(string $view, array $data, string $filePath)
    {
        $pdf = Pdf::loadView($view, $data);
        $pdf->save($filePath);
    }
}
