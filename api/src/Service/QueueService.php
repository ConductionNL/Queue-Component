<?php

// src/Service/HuwelijkService.php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Service\CommonGroundService;
use App\Entity\Task;

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
        // Doe guzzle magie
        $client = New CLient();

        $request = new Request($task->getMethod(), $task->getEndpoint());
        $response = $client->send($request, ['timeout' => 2]);

        // verwerk guzzle magie
        $task->setIets($response->getIets());

        $task = $this->em->persist($task);
        $this->em->flush();

        return $task;
    }

}
