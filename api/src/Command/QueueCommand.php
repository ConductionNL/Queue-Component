<?php

// src/Command/CreateUserCommand.php

namespace App\Command;

use App\Service\QueueService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class QueueCommand extends Command
{
    private $em;
    private $queueService;

    public function __construct(EntityManagerInterface $em, QueueService $queueService)
    {
        $this->em = $em;
        $this->queueService = $queueService;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
        ->setName('commonground:queue:tasks')
        // the short description shown while running "php bin/console list"
        ->setDescription('Runs the curently queued tasks')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('hier help tekst')
        ->addOption('task', null, InputOption::VALUE_OPTIONAL, 'Only run a single task by uuid');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Executing queed tasks');

        // Lets see if we have a single task
        if ($task = $input->getOption('task')) {
            $task = $this->em->getRepository('App:Task')->get($task);
            $task = $this->queueService->execute($task);

            $io->success('Task '.$task->getId().' status:'.$task->getStatusCode());

            return;
        }

        // Get al teh exactubale tasks
        $tasks = $this->em->getRepository('App:Task')->getExecutable();

        $io->text('Found '.count($tasks).' tasks to execute');

        if (count($tasks) > 0) {
            $io->progressStart(count($tasks));
        }

        foreach ($tasks as $task) {
            $task = $this->queueService->execute($task);

            $io->progressAdvance();
            // iets terugkoppelen aan de gebruiker
        }

        $io->success('All done');
    }
}
