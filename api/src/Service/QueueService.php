<?php

// src/Service/HuwelijkService.php

namespace App\Service;

use GuzzleHttp\Client;
use Symfony\Component\Cache\Adapter\AdapterInterface as CacheInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

use App\Service\CommonGroundService;
use App\Entity\CTask;

class QueueService
{
    private $commonGroundService;

    public function __construct(CommonGroundService $commonGroundService)
    {

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

        return $task;
    }

}
