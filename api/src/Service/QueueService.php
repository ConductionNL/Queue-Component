<?php


namespace App\Service;

use GuzzleHttp\Client;
use app\entity\Task;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;


class QueueService implements MessageHandlerInterface
{

}
