<?php

use phpAnvil\Component\Loader\AutoLoader;

//---- Force the displaying of all errors
ini_set('display_errors', '1');

require_once realpath(__DIR__ . '/..') . '/src/phpAnvil/Component/Loader/AutoLoader.php';

$anvilLoader = new AutoLoader();
$anvilLoader->addNamespace('phpAnvil', realpath(__DIR__ . '/..') . '/src/phpAnvil');
$anvilLoader->open();

echo '<br>END OF LINE.';
