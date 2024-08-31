<?php
// Defina o caminho dos testes que você deseja executar
$testPath = './src/PaymentBundle/Test/Service';

// Detecta o sistema operacional                     Windows                    Linux
$phpunitPath = PHP_OS_FAMILY === 'Windows' ? '.\\vendor\\bin\\phpunit' : './vendor/bin/phpunit';

// Verifique se o PHPUnit existe
if (!file_exists($phpunitPath)) die("PHPUnit não encontrado em: $phpunitPath\n");

// Monta o comando de acordo com o sistema operacional
$command = PHP_OS_FAMILY === 'Windows' ? "$phpunitPath $testPath" : "$phpunitPath $testPath 2>&1";

// Execute o comando do PHPUnit
$output = shell_exec($command);

// Exiba a saída dos testes
echo $output;
