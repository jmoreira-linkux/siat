<?php

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();
$dotenv->required(['USER', 'PASSWORD', 'NIT', 'CODIGO_SISTEMA']);
