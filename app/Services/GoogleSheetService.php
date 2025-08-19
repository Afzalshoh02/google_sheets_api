<?php

namespace App\Services;


use App\Models\GoogleSheet;
use App\Models\Product;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;


class GoogleSheetService
{
    protected $client;
    protected $sheetService;
    protected $spreadsheetId;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(storage_path('app/google-credentials.json'));
        $this->client->addScope(Google_Service_Sheets::SPREADSHEETS);
        $this->sheetService = new Google_Service_Sheets($this->client);
        $spreadsheet = GoogleSheet::first();
        $this->spreadsheetId = $spreadsheet->spreadsheet_id; // ID вашего документа
    }

    public function syncData()
    {
        $allowedProducts = Product::allowed()->get();
        $values = $allowedProducts->map(function ($product) {
            return [$product->name, $product->status, $product->price];
        })->toArray();

// Заголовки
        $headers = [['Название', 'Статус', 'Цена']];
        $rangeHeaders = 'A1:C1'; // Заголовки в первой строке
        $rangeData = 'A2:C'; // Данные

        $service = $this->sheetService;

        if (!empty($values)) {
            // Очистка данных перед вставкой
            $clearRequest = new \Google_Service_Sheets_ClearValuesRequest();
            $service->spreadsheets_values->clear($this->spreadsheetId, $rangeData, $clearRequest);

            // Объединяем заголовки и данные
            $valuesWithHeaders = array_merge($headers, $values);

            $valueRange = new Google_Service_Sheets_ValueRange();
            $valueRange->setValues($valuesWithHeaders);

            $service->spreadsheets_values->update(
                $this->spreadsheetId,
                'A1:C', // Начинаем с A1, чтобы записать заголовки и данные
                $valueRange,
                ['valueInputOption' => 'RAW']
            );
        } else {
            // Если данных нет, очищаем только данные, оставляя заголовки
            $clearRequest = new \Google_Service_Sheets_ClearValuesRequest();
            $service->spreadsheets_values->clear($this->spreadsheetId, $rangeData, $clearRequest);

            // Записываем только заголовки
            $headerRange = new Google_Service_Sheets_ValueRange();
            $headerRange->setValues($headers);

            $service->spreadsheets_values->update(
                $this->spreadsheetId,
                $rangeHeaders,
                $headerRange,
                ['valueInputOption' => 'RAW']
            );
        }



    }
}
