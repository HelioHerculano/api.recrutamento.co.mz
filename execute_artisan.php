<?php
// Defina o caminho completo para o arquivo 'artisan' a partir da pasta 'public'
$artisan = dirname(__DIR__) . '/artisan';

// Habilitar exibição de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Caminho para o arquivo artisan: " . $artisan . "<br>";

try {
    // Verifique se o arquivo 'artisan' existe
    if (file_exists($artisan)) {
        echo "Arquivo 'artisan' encontrado.<br>";

        // Execute o comando 'php artisan storage:link'
        $output = shell_exec('php ' . escapeshellarg($artisan) . ' storage:link 2>&1');
        echo "<pre>$output</pre>";
    } else {
        echo "Arquivo 'artisan' não encontrado.<br>";
    }
} catch (Exception $e) {
    echo 'Exceção capturada: ',  $e->getMessage(), "<br>";
}
