<?php

use Chiquitto\FindWord\AlgoritmoGenetico;

require __DIR__ . '/vendor/autoload.php';

// php run.php "algoritmos geneticos php"
// $entrada = 'algoritmos geneticos';
$entrada = $argv[1];

$entrada = strtoupper($entrada);

AlgoritmoGenetico::$maxTamPopulacao = 1000;
AlgoritmoGenetico::$maxGeracoes = 500;

$algGenetico = new AlgoritmoGenetico();
$algGenetico->run($entrada);
