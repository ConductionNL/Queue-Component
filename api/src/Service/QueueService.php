<?php

// src/Service/HuwelijkService.php

namespace App\Service;

use GuzzleHttp\Client;
use Doctrine\ORM\EntityManagerInterface;

use App\Service\CommonGroundService;
use App\Entity\CTask;

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

        // verwerk guzzle magie

        $task = $this->em->persist($task);
        $this->em->flush();

        return $task;
    }

}
