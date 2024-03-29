<?php

// src/Service/HuwelijkService.php

namespace App\Service;

use App\Entity\Task;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
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
        // @todo afvangen of de taak wel moet worden uitgevoerd?

        // Lets first see if the endpoint exisits to start with

        $curl = curl_init($task->getEndpoint());
        curl_setopt($curl, CURLOPT_NOBODY, true);
        $result = curl_exec($curl);
        if (!$result) {
            $task->setStatus('failed');
            $this->em->persist($task);
            $this->em->flush();

            return $task;
        }

        // Lets create a request body if we dont have one
        //if(!$task->getRequestBody() || empty($task->getRequestBody())){
        $body = [
            'id'      => $this->commonGroundService->getUuidFromUrl($task->getResource()),
            'resource'=> $task->getResource(),
        ];
        //}

        $task->setRequestBody(json_encode($body));
        $this->em->persist($task);
        $this->em->flush();

        $client = new Client();
        $request = new Request($task->getType(), $task->getEndpoint(), ['json' => $body]);
        $response = $client->send($request, ['timeout' => 20, 'http_errors ' => false, 'json' => $body]);

        $task->setDateTriggered(new \DateTime());
        $task->setResponseCode($response->getStatusCode());
        $task->setResponseHeaders($response->getHeaders());

        if ($response->getStatusCode() && $response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $task->setStatus('completed');
        } else {
            $task->setLog($response->getBody());
            $task->setStatus('failed');
        }

        $this->em->persist($task);
        $this->em->flush();

        return $task;
    }
}
