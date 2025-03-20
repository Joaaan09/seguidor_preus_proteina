<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Price;

class FetchPrices extends Command
{
    protected $signature = 'prices:fetch';
    protected $description = 'Fetch prices from MyProtein and Prozis';

    public function handle()
{
    // Executa el script Python
    $command = escapeshellcmd('python3 ' . base_path('app/console/commands/run_scrappers.py'));
    $output = shell_exec($command . ' 2>&1');  // Captura tant la sortida com els errors

    // Depuració: Mostra la sortida del script Python
    $this->info("Sortida del script Python:");
    $this->line($output);

    // Decodifica les dades JSON
    $data = json_decode($output, true);

    // Si hi ha un error amb el JSON, mostra'l
    if (json_last_error() !== JSON_ERROR_NONE) {
        $this->error("Error decodificant JSON: " . json_last_error_msg());
        $this->line("Sortida del script Python:");
        $this->line($output);
        return;
    }

    // Guarda les dades a la base de dades
    foreach ($data as $store => $result) {
        if (isset($result['error'])) {
            $this->error("Error amb {$store}: {$result['error']}");
            continue;
        }

        Price::create([
            'store' => $result['store'],
            'price' => $result['price'],
            'discount' => $result['discount'],
        ]);

        $this->info("Dades de {$result['store']} guardades!");
    }
}
}