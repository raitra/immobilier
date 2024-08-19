<?php

namespace App\Service;

use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class ExportExcelService
{
    public function exportTableToExcel(array $data, string $excelFileName)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        foreach ($data as $row => $rowData) {
            $columnNumber = 1; // Initialiser le numéro de colonne à 1
            foreach ($rowData as $value) {
                $sheet->setCellValueByColumnAndRow($columnNumber, $row + 1, $value);
                $columnNumber++; // Incrémenter le numéro de colonne pour la prochaine itération    
            }

        }
        try {
            $writer = new Xlsx($spreadsheet);
            $writer->save($excelFileName);
            $content = file_get_contents($excelFileName);
        } catch(Exception $e) {
            exit($e->getMessage());
        }        
        header("Content-Disposition: attachment; filename=".$excelFileName);        
        unlink($excelFileName);
        exit($content);
    }
}
