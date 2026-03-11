<?php

namespace App\Console\Commands;

use App\Models\ExchangeRate;
use App\Models\ExchangeRateSnapshot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportHistoricalRates extends Command
{
    protected $signature = 'import:historical-rates {file=exchange_rates.csv}';
    protected $description = 'Import historical exchange rates from CSV file';

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
        $lines = explode("\n", $csvContent);
        
        // Skip first 2 header lines and get currency headers from line 3
        $headerLine = $lines[2] ?? '';
        $headers = str_getcsv($headerLine);
        
        // First column is "Date", rest are currency pairs like "Australian dollar (AUD)"
        $currencyColumns = [];
        foreach ($headers as $index => $header) {
            if ($index === 0) continue; // Skip "Date" column
            
            // Extract currency code from format like "Australian dollar (AUD)"
            if (preg_match('/\(([A-Z]{3})\)/', $header, $matches)) {
                $currencyColumns[$index] = $matches[1];
            }
        }

        $this->info('Found ' . count($currencyColumns) . ' currencies to import');
        $this->info('Processing ' . (count($lines) - 3) . ' date records...');

        $bar = $this->output->createProgressBar(count($lines) - 3);
        $bar->start();

        $importedSnapshots = 0;
        $importedRates = 0;
        $baseCurrency = 'GBP'; // The CSV appears to be GBP-based rates

        DB::beginTransaction();
        
        try {
            // Process data lines (skip first 3 header/title lines)
            for ($i = 3; $i < count($lines); $i++) {
                $line = trim($lines[$i]);
                if (empty($line)) continue;

                $values = str_getcsv($line);
                $dateStr = $values[0] ?? '';
                
                if (empty($dateStr)) continue;

                // Parse date from format like "1994-01-03"
                try {
                    $date = \Carbon\Carbon::createFromFormat('Y-m-d', $dateStr)->startOfDay();
                } catch (\Exception $e) {
                    $this->warn("Invalid date format: {$dateStr}");
                    continue;
                }

                // Create or get snapshot for this date
                $snapshot = ExchangeRateSnapshot::firstOrCreate([
                    'base' => $baseCurrency,
                    'rate_date' => $date,
                    'provider' => 'historical_import',
                ], [
                    'fetched_at' => now(),
                    'is_complete' => false,
                ]);

                $ratesForSnapshot = [];

                // Process each currency column
                foreach ($currencyColumns as $columnIndex => $currencyCode) {
                    $rateValue = $values[$columnIndex] ?? null;
                    
                    if ($rateValue === null || $rateValue === '' || !is_numeric($rateValue)) {
                        continue;
                    }

                    $ratesForSnapshot[] = [
                        'exchange_rate_snapshot_id' => $snapshot->id,
                        'currency' => $currencyCode,
                        'rate' => (float) $rateValue,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Bulk insert rates for this snapshot
                if (!empty($ratesForSnapshot)) {
                    ExchangeRate::upsert(
                        $ratesForSnapshot,
                        ['exchange_rate_snapshot_id', 'currency'],
                        ['rate', 'updated_at']
                    );
                    
                    $importedRates += count($ratesForSnapshot);
                    
                    // Mark snapshot as complete
                    $snapshot->update(['is_complete' => true]);
                    $importedSnapshots++;
                }

                $bar->advance();
            }

            DB::commit();
            $bar->finish();
            $this->newLine(2);
            
            $this->info("✓ Successfully imported {$importedSnapshots} snapshots");
            $this->info("✓ Successfully imported {$importedRates} exchange rates");
            
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
