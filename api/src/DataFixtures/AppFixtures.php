<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Ramsey\Uuid\Uuid;

class AppFixtures extends Fixture
{
    private $params;
    private $encoder;

    public function __construct(ParameterBagInterface $params, UserPasswordEncoderInterface $encoder)
    {
        $this->params = $params;
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Lets make sure we only run these fixtures on larping enviroment
        if (strpos($this->params->get('app_domain'), 'conduction.nl') == false) {
            //return false;
        }

        $id = Uuid::fromString('19f6b927-2a63-470f-a024-7efe98008de7');
        $task = new Task();
        $task->setResource("https://vrc.mijncluster.nl/requests/456918bc-8419-4e54-90eb-bafd3d18c6ff");
        $task->setName("Voorbeeld task");
        $task->setDescription("Een voorbeeld task om aan te tonen dat de task systemetiek werkt");
        $task->setEndpoint("https://www.webhook.mijncluster.nl");
        $task->setType("GET");
        $task->setDateToTrigger(New \Datetime);

        $manager->persist($task);
        $task->setId($id);
        $manager->persist($task);
        $manager->flush();
        $task = $manager->getRepository('App:Task')->findOneBy(['id' => $id]);


        $manager->flush();
    }

}
