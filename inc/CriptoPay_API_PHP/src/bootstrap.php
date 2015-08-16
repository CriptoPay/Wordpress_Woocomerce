<?php
/**
 * Copyright (c) 2014-2015 CriptoPay
 */

/*
 * Bootstrap CriptoPay V2.2
 */

namespace CriptoPayApiRest\src;
use CriptoPayApiRest\src\Comun;

require_once __DIR__ . '/Comun/AutoLoader.php';

define('CP_DOMINIO','criptopay_api_v2');
define('CP_LANG_DEFECTO','es_ES.utf8');
define('CP_DEBUG',true);
putenv("LANG=".CP_LANG_DEFECTO); 
setlocale(LC_ALL, CP_LANG_DEFECTO);

bindtextdomain(CP_DOMINIO, '/locale'); 
textdomain(CP_DOMINIO);

$autoloader = new Comun\AutoLoader(__NAMESPACE__, dirname(__DIR__));
$autoloader->register();