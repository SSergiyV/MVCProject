
<?php


require_once dirname(__DIR__) . '/Configs/constants.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Core\Router;

$dotenv = Dotenv::createUnsafeImmutable(BASE_DIR);
$dotenv->load();

if (!session_id()) session_start();

try {
    $router = new Router();
    require_once BASE_DIR . "/routes/web.php";

    if (!preg_match('/assets/i', $_SERVER['REQUEST_URI'])) {
        $router -> dispatch($_SERVER['REQUEST_URI']);
    }
} catch (Exception $exception) {
    dd($exception->getMessage());
}
