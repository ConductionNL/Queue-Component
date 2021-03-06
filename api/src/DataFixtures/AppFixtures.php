<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AppFixtures extends Fixture
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function load(ObjectManager $manager)
    {
        // Lets make sure we only run these fixtures on larping enviroment
        if ($this->params->get('app_domain') != 'mijncluster.nl' && strpos($this->params->get('app_domain'), 'mijncluster.nl') == false) {
            //return false;
        }

        $id = Uuid::fromString('19f6b927-2a63-470f-a024-7efe98008de7');
        $task = new Task();
        $task->setResource('https://vrc.mijncluster.nl/requests/456918bc-8419-4e54-90eb-bafd3d18c6ff');
        $task->setName('Voorbeeld task');
        $task->setDescription('Een voorbeeld task om aan te tonen dat de task systemetiek werkt');
        $task->setEndpoint('https://timeblockr.pinkprivatecloud.nl/gaas-web/commonground/audit');
        $task->setType('POST');
        $task->setDateToTrigger(new \Datetime());

        $manager->persist($task);
        $task->setId($id);
        $manager->persist($task);
        $manager->flush();
        $task = $manager->getRepository('App:Task')->findOneBy(['id' => $id]);

        $manager->flush();
    }
}
