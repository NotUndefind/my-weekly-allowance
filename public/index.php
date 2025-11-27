<?php

require_once '../vendor/autoload.php';

require_once '../src/router.php';
$router = new Router();

use App\Controllers\HomeController;
use App\Controllers\AccountController;
use App\Controllers\ParentController;
use App\Controllers\TeenagerController;

$router->get('/', [HomeController::class, 'index']);

$router->get('/parent/dashboard', [ParentController::class, 'dashboard']);

$router->get('/parent/create', [ParentController::class, 'createView']);

$router->post('/parent/create', [ParentController::class, 'createForm']);

$router->get('/teenager/create', [TeenagerController::class, 'createView']);

$router->post('/teenager/create', [TeenagerController::class, 'createForm']);

$router->post('/teenager/login', [TeenagerController::class, 'login']);

$router->get('/teenager/view', [TeenagerController::class, 'view']);

$router->get('/account/view', [AccountController::class, 'view']);

$router->post('/account/deposit', [AccountController::class, 'deposit']);

$router->post('/account/expense', [AccountController::class, 'expense']);

$router->post('/account/set-allowance', [AccountController::class, 'setAllowance']);

$router->get('/coverage', [HomeController::class, 'coverage']);

$router->run();
