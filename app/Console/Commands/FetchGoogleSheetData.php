<?php

namespace App\Console\Commands;

use App\Models\GoogleSheet;
use Illuminate\Console\Command;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_Exception;

class FetchGoogleSheetData extends Command
{
    protected $signature = 'google:fetch {count? : Количество строк для вывода (необязательно)}';
    protected $description = '📊 Получает данные из Google Sheets и выводит их в консоль в структурированном формате';

    public function handle()
    {
        $count = $this->argument('count') ? (int) $this->argument('count') : null;

        $this->output->write(PHP_EOL);
        $this->output->writeln('<fg=magenta;bg=black>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</>');
        $this->output->writeln('<fg=magenta;bg=black>          🚀 ЗАПУСК КОМАНДЫ GOOGLE:FETCH          </>');
        $this->output->writeln('<fg=magenta;bg=black>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</>');
        $this->output->write(PHP_EOL);

        try {
            $credentialsPath = storage_path('app/google-credentials.json');
            if (!file_exists($credentialsPath)) {
                $this->output->writeln('<fg=red;options=bold>  ✗ ОШИБКА: Файл google-credentials.json не найден</>');
                $this->output->writeln("<fg=white>  Путь: {$credentialsPath}</>");
                $this->output->writeln('<fg=yellow>  ℹ Убедитесь, что файл находится в storage/app</>');
                return 1;
            }

            $client = new Google_Client();
            $client->setAuthConfig($credentialsPath);
            $client->addScope(Google_Service_Sheets::SPREADSHEETS_READONLY);

            $spreadsheet = GoogleSheet::first();
            if (!$spreadsheet || empty($spreadsheet->spreadsheet_id)) {
                $this->output->writeln('<fg=red;options=bold>  ✗ ОШИБКА: ID таблицы не найден в базе данных</>');
                $this->output->writeln('<fg=cyan>  ╭───────────────────────────────────────────────╮</>');
                $this->output->writeln('<fg=cyan>  │   Как добавить spreadsheet_id:                 │</>');
                $this->output->writeln('<fg=cyan>  │   1. Запустите tinker:                         │</>');
                $this->output->writeln('<fg=cyan>  │      <fg=white>php artisan tinker</>                    │</>');
                $this->output->writeln('<fg=cyan>  │   2. Выполните:                                │</>');
                $this->output->writeln('<fg=cyan>  │      <fg=white>$sheet = new App\Models\GoogleSheet;</>    │</>');
                $this->output->writeln('<fg=cyan>  │      <fg=white>$sheet->spreadsheet_id = "your_id";</>    │</>');
                $this->output->writeln('<fg=cyan>  │      <fg=white>$sheet->save();</>                        │</>');
                $this->output->writeln('<fg=cyan>  ╰───────────────────────────────────────────────╯</>');
                return 1;
            }

            $service = new Google_Service_Sheets($client);
            $spreadsheetId = $spreadsheet->spreadsheet_id;
            $range = 'Sheet1!A2:B';

            $this->output->writeln('<fg=cyan>  ╭───────────────────────────────────────────────╮</>');
            $this->output->writeln('<fg=cyan>  │           🔍 ИНФОРМАЦИЯ О ПОДКЛЮЧЕНИИ         │</>');
            $this->output->writeln('<fg=cyan>  ├───────────────────────────────────────────────┤</>');
            $this->output->writeln("<fg=cyan>  │ <fg=white>Spreadsheet ID:</>  {$spreadsheetId} │");
            $this->output->writeln("<fg=cyan>  │ <fg=white>Диапазон:</>        {$range}         │");
            $this->output->writeln('<fg=cyan>  ╰───────────────────────────────────────────────╯</>');
            $this->output->write(PHP_EOL);

            $this->output->writeln('<fg=blue>  🔄 Проверка доступа к таблице...</>');
            $response = $service->spreadsheets->get($spreadsheetId);
            $this->output->writeln('<fg=green>  ✓ Таблица найдена: ' . $response->getSpreadsheetId() . '</>');
            $this->output->write(PHP_EOL);

            $this->output->writeln('<fg=blue>  📥 Получение данных из Google Sheets...</>');
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $rows = $response->getValues();

            if (empty($rows)) {
                $this->output->writeln('<fg=yellow>  ⚠ Внимание: Данные отсутствуют в указанном диапазоне</>');
                return 0;
            }

            $totalRows = count($rows);
            $limit = $count ?? $totalRows;

            $this->output->writeln("<fg=white>  Всего строк: {$totalRows}, отображаем: {$limit}</>");
            $this->output->write(PHP_EOL);

            $bar = $this->output->createProgressBar($limit);
            $bar->setBarCharacter('<fg=green>■</>');
            $bar->setEmptyBarCharacter('<fg=gray>■</>');
            $bar->setProgressCharacter('<fg=green>➤</>');
            $bar->setFormat("  %current%/%max% [%bar%] %percent:3s%%\n  <fg=white>%message%</>");
            $bar->setMessage('Начало обработки...');
            $bar->start();

            $tableRows = [];
            foreach (array_slice($rows, 0, $limit) as $index => $row) {
                $tableRows[] = [
                    '<fg=cyan>' . ($index + 1) . '</>',
                    '<fg=white>' . ($row[0] ?? 'N/A') . '</>',
                    '<fg=yellow>' . ($row[1] ?? 'N/A') . '</>',
                ];
                $bar->setMessage("Обработка строки " . ($index + 1));
                $bar->advance();
                usleep(50000);
            }

            $bar->setMessage('<fg=green>Обработка завершена!</>');
            $bar->finish();
            $this->output->write(PHP_EOL);

            $this->output->writeln('<fg=magenta>  ╭───────────────────────────────────────────────╮</>');
            $this->output->writeln('<fg=magenta>  │          📋 ДАННЫЕ ИЗ GOOGLE SHEETS           │</>');
            $this->output->writeln('<fg=magenta>  ╰───────────────────────────────────────────────╯</>');

            $this->table(
                ['<fg=cyan>#</>', '<fg=white>ID</>', '<fg=yellow>Комментарий</>'],
                $tableRows
            );

            $this->output->write(PHP_EOL);
            $this->output->writeln('<fg=magenta;bg=black>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</>');
            $this->output->writeln('<fg=magenta;options=bold>          🎉 КОМАНДА УСПЕШНО ВЫПОЛНЕНА!          </>');
            $this->output->writeln('<fg=magenta;bg=black>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</>');
            $this->output->write(PHP_EOL);

            return 0;

        } catch (Google_Service_Sheets_Exception $e) {
            $this->output->writeln(PHP_EOL);
            $this->output->writeln('<fg=red;options=bold>  ✗ ОШИБКА GOOGLE SHEETS API</>');
            $this->output->writeln('<fg=white>  Сообщение: ' . $e->getMessage() . '</>');
            $this->output->writeln('<fg=white>  Код ошибки: ' . $e->getCode() . '</>');

            $this->output->writeln('<fg=yellow>  ╭───────────────────────────────────────────────╮</>');
            $this->output->writeln('<fg=yellow>  │             РЕКОМЕНДАЦИИ ПО ИСПРАВЛЕНИЮ        │</>');
            $this->output->writeln('<fg=yellow>  ├───────────────────────────────────────────────┤</>');
            $this->output->writeln('<fg=yellow>  │ • Проверьте, добавлен ли Service Account       │</>');
            $this->output->writeln('<fg=yellow>  │   в доступ к таблице (Viewer/Editor)           │</>');
            $this->output->writeln('<fg=yellow>  │ • Убедитесь, что spreadsheetId и имя листа    │</>');
            $this->output->writeln('<fg=yellow>  │   корректны                                   │</>');
            $this->output->writeln('<fg=yellow>  │ • Проверьте настройки Google Sheets API в      │</>');
            $this->output->writeln('<fg=yellow>  │   Google Cloud Console                        │</>');
            $this->output->writeln('<fg=yellow>  ╰───────────────────────────────────────────────╯</>');

            return 1;
        } catch (\Exception $e) {
            $this->output->writeln(PHP_EOL);
            $this->output->writeln('<fg=red;options=bold>  ✗ НЕИЗВЕСТНАЯ ОШИБКА</>');
            $this->output->writeln('<fg=white>  Сообщение: ' . $e->getMessage() . '</>');
            $this->output->writeln('<fg=yellow>  Проверьте конфигурацию проекта и права доступа</>');

            return 1;
        }
    }
}
