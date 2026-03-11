<?php

namespace App\Console\Commands;

use App\Models\ExchangeRate;
use App\Models\ExchangeRateSnapshot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportHistoricalRates extends Command
{
    protected $signature = 'import:historical-rates {file=Exchange_Rate_Report_Base_USD.csv}';
    protected $description = 'Import historical exchange rates from CSV (USD-based)';

    public function handle()
    {
        $filePath = $this->argument('file');
        $fullPath = storage_path('app/' . $filePath);

        // Try local storage first, then fall back to the default filesystem disk (S3 on Cloud)
        if (file_exists($fullPath)) {
            $this->info('Reading CSV from local storage...');
            $csvContent = file_get_contents($fullPath);
        } elseif (Storage::exists($filePath)) {
            $this->info('Reading CSV from cloud storage...');
            $csvContent = Storage::get($filePath);
        } else {
            $this->error("File not found locally ({$fullPath}) or in cloud storage ({$filePath})");
            $this->line('Upload the CSV to your Cloud storage bucket and ensure FILESYSTEM_DISK=s3 is set.');
            return 1;
        }
        // Format: single header line (Date + currency names with ISO code in parentheses), data from line 2 onwards
        $lines = explode("\n", $csvContent);

        // Line 1: header — "Date,Algerian dinar (DZD),...,U.S. dollar (USD),..."
        $headers = str_getcsv(rtrim($lines[0] ?? '', ','));

        // Build column index -> currency code map (skip index 0 which is "Date")
        $currencyColumns = [];
        foreach ($headers as $index => $header) {
            if ($index === 0) {
                continue;
            }

            $header = trim($header);
            if ($header === '' || strtoupper($header) === 'DATE') {
                continue;
            }

            $code = strtoupper($header);
            if (preg_match('/\(([A-Z]{3})\)/', $header, $matches)) {
                $code = $matches[1];
            }

            $currencyColumns[$index] = $code;
        }

        $dataLines = array_slice($lines, 1);
        $totalLines = count(array_filter($dataLines, fn($l) => trim($l, ', ') !== ''));

        $this->info('Base currency : USD');
        $this->info('Currencies    : ' . count($currencyColumns));
        $this->info('Date records  : ' . $totalLines);

        $bar = $this->output->createProgressBar($totalLines);
        $bar->start();

        $importedSnapshots = 0;
        $importedRates     = 0;
        $baseCurrency      = 'USD';
        $batchSize         = 200;
        $rateBatch         = [];

        DB::beginTransaction();

        try {
            foreach ($dataLines as $line) {
                $line = trim($line, " ,\r");
                if ($line === '') continue;

                $values = str_getcsv($line);
                $dateStr = trim($values[0] ?? '');
                if ($dateStr === '') continue;

                // Validate date format YYYY-MM-DD
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) continue;

                $snapshot = ExchangeRateSnapshot::firstOrCreate(
                    ['base' => $baseCurrency, 'rate_date' => $dateStr, 'provider' => 'historical_import'],
                    ['fetched_at' => now(), 'is_complete' => false]
                );

                $hasRates = false;

                foreach ($currencyColumns as $colIndex => $currencyCode) {
                    $raw = trim($values[$colIndex] ?? '');
                    if ($raw === '' || $raw === 'N/A' || !is_numeric($raw)) continue;
                    if ($currencyCode === $baseCurrency) continue;

                    $rateBatch[] = [
                        'exchange_rate_snapshot_id' => $snapshot->id,
                        'currency'   => $currencyCode,
                        'rate'       => (float) $raw,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $hasRates = true;
                }

                if ($hasRates) {
                    $snapshot->update(['is_complete' => true]);
                    $importedSnapshots++;
                }

                // Flush in batches to avoid memory issues
                if (count($rateBatch) >= $batchSize) {
                    ExchangeRate::upsert($rateBatch, ['exchange_rate_snapshot_id', 'currency'], ['rate', 'updated_at']);
                    $importedRates += count($rateBatch);
                    $rateBatch = [];
                }

                $bar->advance();
            }

            // Flush remaining
            if (!empty($rateBatch)) {
                ExchangeRate::upsert($rateBatch, ['exchange_rate_snapshot_id', 'currency'], ['rate', 'updated_at']);
                $importedRates += count($rateBatch);
            }

            DB::commit();
            $bar->finish();
            $this->newLine(2);
            $this->info("✓ Imported {$importedSnapshots} snapshots");
            $this->info("✓ Imported {$importedRates} exchange rates");
            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $bar->finish();
            $this->newLine(2);
            $this->error('Import failed: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
