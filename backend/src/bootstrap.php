<?php
declare(strict_types=1);
require dirname(__DIR__) . '/vendor/autoload.php';

use App\Http\Router;
use App\Http\Request;

$request = new Request($_SERVER);
$router = new Router();
$router->handle($request);
