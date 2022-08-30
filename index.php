<?php
declare(strict_types=1);
spl_autoload_register(function($class){
    require __DIR__ . "/src/$class.php";
});
set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");
header("Content-type:application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE');
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  header('HTTP/1.1 200 OK');
  exit();
}
$parts = explode('/', $_SERVER["REQUEST_URI"] );
if($parts[1]!= "bonntech"){
    http_response_code(404);
    exit;
}
$id = $parts[2]??null;
$database = new Database("localhost", "bonntechPHP", "root", "");
$gateway = new ContactGateway($database);

$controller = new ContactController($gateway);

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);

