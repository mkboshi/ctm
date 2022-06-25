<?php
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use App\Controller\ChatController;

date_default_timezone_set('Asia/Vladivostok');

require_once dirname(__DIR__) . '/vendor/autoload.php';

$FileLOGS = "/var/www/html/composer/var/logs/MessagesLogs.log";
$FileJSON = "/var/www/html/composer/var/logs/MessagesHistory.json";

$loader = new FilesystemLoader(dirname(__DIR__) . '/templates/');
$mesHandler = new StreamHandler($FileLOGS, Logger::INFO);
$twig = new Environment($loader);
$log = new Logger('chat');
$chat = new ChatController($twig);

$log->pushHandler($mesHandler);

$username = $_GET['username'];
$password = $_GET['password'];
$message = $_GET["OneMessage"];
if (isset($username) && isset($password) && ($message == ''))
{
    if (($username == 'ma' && $password == '123456') ||
        ($username == 'bo' && $password == '123456'))
    {
        $chat->__invokeMes();
        echo "<script> document.getElementById(\"AutForm\").hidden=true; </script>";
        setcookie('user', $_GET['username']);
    }
    else
    {
        echo "<script>alert(\"Invalid username or password\")</script>";
        $log->error('The user tried to log in. Invalid username or password');
    }
}

if (isset($message) && ($message !== ''))
{
    $chat->AddMesToJsonAndLogs($FileJSON, $_COOKIE['user'], $message);
    echo "<script> document.getElementById(\"AutForm\").hidden=true; </script>";
    $chat->__invokeMes();
}

$chat->ReadJson($FileJSON);