<?php

namespace App\Console\Commands;

use App\Models\GoogleSheet;
use Illuminate\Console\Command;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_Exception;

class FetchGoogleSheetData extends Command
{
    protected $signature = 'google:fetch {count? : Number of rows to display (optional)}';
    protected $description = '📊 Fetches data from Google Sheets and displays it in a structured console format';

    public function handle()
    {
        $count = $this->argument('count') ? (int) $this->argument('count') : null;

        $this->displayHeader();

        try {
            $credentialsPath = storage_path('app/google-credentials.json');
            if (!file_exists($credentialsPath)) {
                $this->errorBox("Credentials file not found at: {$credentialsPath}", [
                    'ℹ Place the google-credentials.json file in storage/app',
                ]);
                return 1;
            }

            $client = new Google_Client();
            $client->setAuthConfig($credentialsPath);
            $client->addScope(Google_Service_Sheets::SPREADSHEETS_READONLY);

            $spreadsheet = GoogleSheet::first();
            if (!$spreadsheet || empty($spreadsheet->spreadsheet_id)) {
                $this->errorBox('Spreadsheet ID not found in the database', [
                    'To add spreadsheet_id:',
                    '1. Run: php artisan tinker',
                    '2. Execute:',
                    '   $sheet = new App\Models\GoogleSheet;',
                    '   $sheet->spreadsheet_id = "your_id";',
                    '   $sheet->save();',
                ]);
                return 1;
            }

            $service = new Google_Service_Sheets($client);
            $spreadsheetId = $spreadsheet->spreadsheet_id;
            $range = 'Sheet1!A2:B';

            $this->infoBox('Connection Details', [
                "Spreadsheet ID: {$spreadsheetId}",
                "Range: {$range}",
            ]);

            $this->line('<fg=blue>🔄 Checking spreadsheet access...</>');
            $response = $service->spreadsheets->get($spreadsheetId);
            $this->line('<fg=green>✅ Spreadsheet found: ' . $response->getSpreadsheetId() . '</>');

            $this->line('<fg=blue>📥 Fetching data from Google Sheets...</>');
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $rows = $response->getValues();

            if (empty($rows)) {
                $this->warn('No data found in the specified range.');
                return 0;
            }

            $totalRows = count($rows);
            $limit = $count ?? $totalRows;

            $this->line("<fg=cyan>📋 Total rows: {$totalRows} | Displaying: {$limit}</>");

            $bar = $this->output->createProgressBar($limit);
            $bar->setBarCharacter('<fg=green>█</>');
            $bar->setEmptyBarCharacter('<fg=gray>─</>');
            $bar->setProgressCharacter('<fg=green>➤</>');
            $bar->setFormat(" %current%/%max% [%bar%] %percent:3s%%\n %message%");
            $bar->setMessage('Processing rows...');
            $bar->start();

            $tableRows = [];
            foreach (array_slice($rows, 0, $limit) as $index => $row) {
                $tableRows[] = [
                    "<fg=cyan>" . ($index + 1) . "</>",
                    "<fg=white>" . ($row[0] ?? 'N/A') . "</>",
                    "<fg=yellow>" . ($row[1] ?? 'N/A') . "</>",
                ];
                $bar->setMessage("Processing row " . ($index + 1));
                $bar->advance();
                usleep(50000);
            }

            $bar->setMessage('<fg=green>Processing complete!</>');
            $bar->finish();
            $this->newLine();

            $this->sectionHeader('Google Sheets Data');
            $this->table(
                ['<fg=cyan>#</>', '<fg=white>ID</>', '<fg=yellow>Comment</>'],
                $tableRows
            );

            $this->displayFooter();

            return 0;

        } catch (Google_Service_Sheets_Exception $e) {
            $this->errorBox('Google Sheets API Error', [
                "Message: {$e->getMessage()}",
                "Code: {$e->getCode()}",
                'Recommendations:',
                '• Ensure the Service Account has Viewer/Editor access to the spreadsheet',
                '• Verify the spreadsheetId and sheet name are correct',
                '• Check Google Sheets API settings in Google Cloud Console',
            ]);
            return 1;
        } catch (\Exception $e) {
            $this->errorBox('Unexpected Error', [
                "Message: {$e->getMessage()}",
                'Check project configuration and access permissions',
            ]);
            return 1;
        }
    }

    private function displayHeader()
    {
        $this->newLine();
        $this->line('<fg=magenta;bg=black> ╔═════════════════════════════════════════════════════╗ </>');
        $this->line('<fg=magenta;bg=black> ║           🚀 GOOGLE SHEETS DATA FETCHER             ║ </>');
        $this->line('<fg=magenta;bg=black> ╚═════════════════════════════════════════════════════╝ </>');
        $this->newLine();
    }

    private function displayFooter()
    {
        $this->newLine();
        $this->line('<fg=magenta;bg=black> ╔═════════════════════════════════════════════════════╗ </>');
        $this->line('<fg=magenta;bg=black> ║        🎉 COMMAND EXECUTED SUCCESSFULLY!            ║ </>');
        $this->line('<fg=magenta;bg=black> ╚═════════════════════════════════════════════════════╝ </>');
        $this->newLine();
    }

    private function sectionHeader(string $title)
    {
        $this->line('<fg=magenta> ┌─────────────────── ' . str_pad($title, 25, ' ') . ' ──────────────────┐ </>');
        $this->line('<fg=magenta> └────────────────────────────────────────────────────┘ </>');
    }

    private function infoBox(string $title, array $lines)
    {
        $this->newLine();
        $this->line("<fg=cyan> ┌────── {$title} ──────┐ </>");
        foreach ($lines as $line) {
            $this->line("<fg=cyan> │ <fg=white>{$line}</> </>");
        }
        $this->line('<fg=cyan> └────────────────────────────┘ </>');
        $this->newLine();
    }

    private function errorBox(string $title, array $lines)
    {
        $this->newLine();
        $this->line("<fg=red> ┌────── {$title} ──────┐ </>");
        foreach ($lines as $line) {
            $this->line("<fg=red> │ <fg=white>{$line}</> </>");
        }
        $this->line('<fg=red> └────────────────────────────┘ </>');
        $this->newLine();
    }

    public function warn($message, $verbosity = null)
    {
        $this->line("<fg=yellow>⚠ {$message}</>", $verbosity);
        $this->newLine();
    }
}
