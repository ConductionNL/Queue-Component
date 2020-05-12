<?php

// src/Command/CreateUserCommand.php

namespace App\Command;

use App\Service\NLXLogService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\QueueService;


class QueueCommand extends Command
{

    private $em;
    private $co;

    public function __construct(EntityManagerInterface $em, QueueService $queueService)
    {
        $this->em = $em;
        $this->queueService = $queueService;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
        ->setName('commonground:queue:tasks')
        // the short description shown while running "php bin/console list"
        ->setDescription('Runs the curently queued task.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to create a new hel chart from the helm template')
        ->addOption('tasj', null, InputOption::VALUE_OPTIONAL, 'Only run a single task by uuid');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Haal tasks op
        $tasks = [];

        foreach($tasks as $task){
            $this->queueService->execute($tasks);
        }

    }
}
