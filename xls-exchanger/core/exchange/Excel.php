<?php

namespace XlsExchanger\Exchange;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use XlsExchanger\Log\Logger;

class Excel
{
    /**
     * Создает xlsx файл с товарами из заказа
     *
     * @param  string $inputJson
     * @param  string $outputPath
     * @return bool
     */
    public function createFromJson(string $inputJson, string $outputPath): bool
    {
        $inputArr = json_decode($inputJson, true);
        $products = $this->processInputArr($inputArr);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
        $spreadsheet->getDefaultStyle()->getFont()->setSize('11');

        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setTitle('Товары в заказе');
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);

        $columns = [
            'A' => 'id',
            'B' => 'ШК',
            'C' => 'Название',
            'D' => 'Кол-во',
            'E' => 'Сумма'
        ];

        // заполнение строки с заголовками
        $row = 1;
        foreach ($columns as $key => $value) {
            $sheet->getColumnDimension($key)->setAutoSize(true);
            $sheet->setCellValue($key . (string)$row, $value);
        }

        // заполнение строк с товарами (начиная со второй строки)
        $row = 2;
        foreach ($products as $product) {

            $col = 1;
            foreach ($product as $value) {
                $sheet->setCellValueByColumnAndRow($col, $row, $value);
                $col++;
            }

            $row++;
        }

        try {
            $writer = new Xlsx($spreadsheet);
            $writer->save($outputPath);
        } catch (Exception $e) {
            Logger::log(Logger::ERROR, $e->getMessage(), true);
            return false;
        }

        return true;
    }

    /**
     * Обрабатывает входящий массив и возвращает массив валидных продуктов
     *
     * @param  array $inputArr
     * @return array
     */
    private function processInputArr(array $inputArr): array
    {
        if (!empty($inputArr['items'])) {

            $validProducts = [];
            foreach ($inputArr['items'] as $item) {
                // проверяем штрих-код на валидность EAN13
                if ($this->isValidBarcode($item['item']['barcode'])) {
                    $validProducts[] = [
                        'id' => $item['item']['id'],
                        'barcode' => $item['item']['barcode'],
                        'name' => $item['item']['name'],
                        'quantity' => $item['quantity'],
                        'amount' => $item['amount']
                    ];
                } else {
                    Logger::log(
                        Logger::NO_VALID,
                        'Штрихкод [' . $item['item']['barcode'] . '] товара с id [' . $item['item']['id'] . '] не валиден.'
                    );
                }
            }

            if (!empty($validProducts)) {
                return $validProducts;
            } else {
                Logger::log(Logger::ERROR, 'В JSON файле с заказом нет валидных продуктов.', true);

                return [];
            }
        } else {
            Logger::log(Logger::ERROR, 'В JSON файле с заказом отсутствуют товары.', true);
            
            return [];
        }
    }

    /**
     * Проверяет штрихкод на валидность EAN13
     *
     * @param  string $barcode
     * @return bool
     */
    private function isValidBarcode(string $barcode): bool
    {
        if (!preg_match("/^[0-9]{13}$/", $barcode)) {
            return false;
        }

        $digits = $barcode;

        $evenSum = $digits[1] + $digits[3] + $digits[5] +
            $digits[7] + $digits[9] + $digits[11];

        $evenSumThree = $evenSum * 3;

        $oddSum = $digits[0] + $digits[2] + $digits[4] +
            $digits[6] + $digits[8] + $digits[10];

        $totalSum = $evenSumThree + $oddSum;

        $nextTen = (ceil($totalSum / 10)) * 10;
        $checkDigit = $nextTen - $totalSum;

        if ($checkDigit == $digits[12]) {
            return true;
        }

        return false;
    }
}
