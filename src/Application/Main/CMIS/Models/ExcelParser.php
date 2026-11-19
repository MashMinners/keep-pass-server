<?php

namespace Application\Main\CMIS\Models;

use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelParser
{
    /**
     * Максимально базовый метод который парсит файлы форматов ods, xls, xlsx
     * @param array $files
     * @param $startRow
     * @return array
     */
    public function parseExcel(array $files, string $startRow) : array{
        $result = [];
        foreach ($files AS $file){
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $rows = $sheet->rangeToArray(
                "$startRow:$highestColumn$highestRow", // Диапазон
                NULL,                          // Значение для пустых ячеек
                TRUE,                          // Вычислять формулы
                TRUE,                          // Форматировать значения (даты, проценты)
                TRUE                           // Использовать индексы строк/столбцов в массиве
            );
            $result = array_merge($result, $rows);
        }
        return $result;
    }

}