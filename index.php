<?php
	/*
	*	Сервис Тарифов
	*
	*/

declare(strict_types=1);
spl_autoload_register();

use App\Controllers\Request\Router;
use App\Controllers\View;

// Подключаем конфиг DB
require_once("Config".DIRECTORY_SEPARATOR."db_cfg.php");

$view = View::getInstance();

$view->setHeader("Content-type: application/json; charset=utf-8");
$view->write(
	Router::Route(
		Router::getCurrentMethod(),
		Router::getCurrentUrl()
	)
);

View::WriteAll();