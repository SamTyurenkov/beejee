<?php

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/' :
        require __DIR__ . '/views/home.php';
        break;
    case '' :
        require __DIR__ . '/views/home.php';
        break;
	case '/ajax' :
        require __DIR__ . '/views/ajax.php';
        break;
	case '/ajax/' :
        require __DIR__ . '/views/ajax.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/views/404.php';
        break;
}