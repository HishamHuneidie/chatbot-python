<?php

use Core\RouteResolver;
require_once '../autoload.php';

$router = new RouteResolver($_ENV, $_SERVER);

$router->run();