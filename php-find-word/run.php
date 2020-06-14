<?php

use Chiquitto\FindWord\AlgoritmoGenetico;

require __DIR__ . '/vendor/autoload.php';

// php run.php "algoritmos geneticos php tdc floripa"
// $entrada = 'algoritmos geneticos';
$entrada = $argv[1];

$entrada = strtoupper($entrada);

AlgoritmoGenetico::$maxTamPopulacao = 1000;
AlgoritmoGenetico::$maxGeracoes = 10000;

$algGenetico = new AlgoritmoGenetico();
$algGenetico->run($entrada);
