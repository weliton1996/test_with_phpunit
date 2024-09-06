<?php

// Defina o caminho dos testes                         Windows                    Linux
$testPath = PHP_OS_FAMILY === 'Windows' ? '.\\src\\OrderBundle\\Test\\Service\\' : './src/OrderBundle/Test/Service';

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


//execute no terminal: php .\src\OrderBundle\Test\Service\run_test.php
