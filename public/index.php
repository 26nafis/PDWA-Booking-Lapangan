<?php
// ============================================================
//  public/index.php — Front Controller
// ============================================================

define('APP_ROOT',    dirname(__DIR__) . '/app');
define('PUBLIC_ROOT', __DIR__);

require_once APP_ROOT . '/config/app.php';
require_once APP_ROOT . '/config/database.php';

// Load semua helpers
foreach (glob(APP_ROOT . '/helpers/*.php') as $helper) {
    require_once $helper;
}

// ---- Router ------------------------------------------------
$url      = trim($_GET['url'] ?? 'home', '/');
$segments = explode('/', $url);

// Tangani route admin: admin/lapangan/edit → AdminController
$controllerName = ucfirst($segments[0]) . 'Controller';
$method         = $segments[1] ?? 'index';
$param          = $segments[2] ?? null;
$param2         = $segments[3] ?? null;

// Untuk sub-route admin (admin/lapangan, admin/booking, admin/payment)
// tetap pakai AdminController, method = segmen gabungan
if ($segments[0] === 'admin' && isset($segments[1])) {
    $controllerName = 'AdminController';
    // method menjadi: lapanganIndex, lapanganCreate, bookingIndex, dst.
    $method = isset($segments[2])
        ? lcfirst($segments[1]) . ucfirst($segments[2])
        : lcfirst($segments[1]) . 'Index';
    $param  = $segments[3] ?? null;
}

$controllerFile = APP_ROOT . '/controllers/' . $controllerName . '.php';

// Load semua model (mudah di proyek kecil)
foreach (glob(APP_ROOT . '/models/*.php') as $model) {
    require_once $model;
}

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        if (method_exists($controller, $method)) {
            $controller->$method($param, $param2);
        } else {
            http_response_code(404);
            require_once APP_ROOT . '/views/404.php';
        }
    }
} else {
    http_response_code(404);
    require_once APP_ROOT . '/views/404.php';
}
