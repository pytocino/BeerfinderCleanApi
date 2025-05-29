<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--path= : Ruta personalizada para el backup} {--data-only : Solo datos, sin estructura} {--schema-only : Solo estructura, sin datos}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear backup de la base de datos MySQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Iniciando backup de la base de datos...');

        // Obtener configuración de la base de datos
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port', 3306);
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        // Verificar que mysqldump esté disponible
        $mysqldumpPath = $this->findMysqldump();
        if (!$mysqldumpPath) {
            $this->error('❌ mysqldump no encontrado. Asegúrate de tener MySQL Client instalado.');
            return 1;
        }

        // Determinar el nombre del archivo
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = $this->option('path') ?: "database/backup-{$timestamp}.sql";
        
        // Asegurar que el directorio existe
        $directory = dirname($filename);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Construir comando mysqldump
        $command = sprintf(
            '%s -h%s -P%s -u%s -p%s',
            $mysqldumpPath,
            escapeshellarg($host),
            $port,
            escapeshellarg($username),
            escapeshellarg($password)
        );

        // Agregar opciones según los flags
        if ($this->option('schema-only')) {
            $command .= ' --no-data';
            $this->info('📝 Modo: Solo estructura (sin datos)');
        } elseif ($this->option('data-only')) {
            $command .= ' --no-create-info --skip-triggers';
            $this->info('📊 Modo: Solo datos (sin estructura)');
        } else {
            $this->info('💾 Modo: Backup completo (estructura + datos)');
        }

        // Agregar base de datos y redirección
        $command .= sprintf(' %s > %s', escapeshellarg($database), escapeshellarg($filename));

        // Ejecutar el comando
        $this->info("🔄 Ejecutando backup...");
        $startTime = microtime(true);
        
        $output = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            $duration = round(microtime(true) - $startTime, 2);
            $fileSize = $this->formatBytes(filesize($filename));
            
            $this->info("✅ Backup completado exitosamente!");
            $this->info("📁 Archivo: {$filename}");
            $this->info("📏 Tamaño: {$fileSize}");
            $this->info("⏱️  Tiempo: {$duration}s");
            
            return 0;
        } else {
            $this->error("❌ Error al crear el backup. Código de retorno: {$returnCode}");
            if (!empty($output)) {
                $this->error("Salida: " . implode("\n", $output));
            }
            return 1;
        }
    }

    /**
     * Buscar la ubicación de mysqldump
     */
    private function findMysqldump(): ?string
    {
        $paths = [
            'mysqldump',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            '/opt/homebrew/bin/mysqldump'
        ];

        foreach ($paths as $path) {
            $output = [];
            $returnCode = 0;
            exec("which {$path} 2>/dev/null", $output, $returnCode);
            
            if ($returnCode === 0 && !empty($output)) {
                return $output[0];
            }
        }

        return null;
    }

    /**
     * Formatear bytes en formato legible
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
