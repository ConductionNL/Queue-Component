<?php

// src/Service/HuwelijkService.php

namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class QueueService
{
    private $em;
    private $commonGroundService;

    public function __construct(EntityManagerInterface $em, CommonGroundService $commonGroundService)
    {
        $this->em = $em;
        $this->commonGroundService = $commonGroundService;
    }

    /*
     * Run a single task a guzzle command
     *
     * @param Task $task the task to be executed
     * @return Task the executed task
     */

    public function execute(Task $task)
    {
        // Doe guzzle magic
        $client = new Client();

        $request = new Request($task->getType(), $task->getEndpoint());
        $response = $client->send($request, ['timeout' => 2]);
        $date = date('Y-m-d H:i:s.u', time());
        $date2 = $task->getDateToTrigger();
        $body = json_decode($response->getBody(), true);

        // verwerk guzzle magie
        if ($date2->format('Y-m-d H:i:s.u') < $date) {
            echo $response->getBody()->getContents();
            $task->setResponseCode($response->getStatusCode());
            $task->setResponseBody($body);
            $task->setResponseHeaders($response->getHeaders());
            $task->setStatus('completed');
            $task->setDateTriggered(new \DateTime());
            $task->setResponseCode($response->getStatusCode());
        } else {
            $task->setResponseCode(400);
        }

        return $task;
    }
}
