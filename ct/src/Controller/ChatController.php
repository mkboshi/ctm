<?php
namespace App\Controller;

use Twig\Environment;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class ChatController
{
    private $twig;
    private $log;
    private $mesHandler;

    public function __construct(Environment $twig)
    {
        $FileLOGS = "/var/www/html/composer/var/logs/MessagesLogs.log";

        $this->twig = $twig;
        $this->log = new Logger('chat');
        $this->mesHandler = new StreamHandler($FileLOGS, Logger::INFO);
        echo $this->twig->render('main.html.twig');
    }

    public function __invokeMes()
    {
        echo $this->twig->render('messages.html.twig');
    }


    function AddMesToJsonAndLogs($FileJSON, $username, $message)
    {
        $date = date("d.m G:i");
        $mes = json_decode(file_get_contents($FileJSON));
        $mes_obj = (object)['date' => $date, 'username' => $username, 'message' => $message];

        $mes->AllMessages[] = $mes_obj;
        file_put_contents($FileJSON, json_encode($mes));

        $this->log->pushHandler($this->mesHandler);
        $this->log->info('New message', ['username' => $username, 'message' => $message]);
    }

    function ReadJson($FileJSON)
    {
        $mes = json_decode(file_get_contents($FileJSON));
        foreach ($mes->AllMessages as $message) {
            echo "$message->date       $message->username </p>";
            echo "$message->message </p>";
        }
    }
}