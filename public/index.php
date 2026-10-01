<?php
define('ROOT', dirname(__DIR__));

$smarty = ROOT . '/lib/smarty/libs/Smarty.class.php';
if (!file_exists($smarty)) {
    http_response_code(500);
    exit('Error: Smarty library not found');
}

require_once($smarty);

spl_autoload_register(function (string $class) {
    foreach (['app', 'app/Models', 'app/Controllers'] as $dir) {
        $file = ROOT . "/$dir/$class.php";
        if (file_exists($file)) {
            require_once($file);
        }
   }
});

$router = new Router();
$router->get('/', [IndexController::class, 'index']);
$router->get('/category/{slug}', [CategoryController::class, 'show']);
$router->get('/post/{slug}', [ArticleController::class, 'show']);
$router->dispatch();
