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
        // Ejecuta el script Python capturando solo la salida estándar (no stderr)
        $command = escapeshellcmd('python3 ' . base_path('app/Console/Commands/run_scrappers.py'));
        $output = shell_exec($command);  // Captura solo stdout
        
        // Para depuración, puedes capturar stderr por separado si lo necesitas
        // $debug_output = shell_exec($command . ' 2>&1 1>/dev/null');
        // $this->info("Debug output: " . $debug_output);
        
        // Decodifica las datos JSON
        $data = json_decode($output, true);
        
        // Si hay un error con el JSON, muéstralo
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Error decodificando JSON: " . json_last_error_msg());
            $this->line("Salida del script Python:");
            $this->line($output);
            return;
        }
        
        // Guarda los datos en la base de datos
        foreach ($data as $store => $result) {
            if (isset($result['error'])) {
                $this->error("Error con {$store}: {$result['error']}");
                continue;
            }
            
            Price::create([
                'store' => $result['store'],
                'price' => $result['price'],
                'discount' => $result['discount'],
            ]);
            
            $this->info("Datos de {$result['store']} guardados!");
        }
    }
}