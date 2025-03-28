<?php

namespace App\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TaskRepository $taskRepository
    ) {}

    public function save(Task $task)
    {
        //On suppose qu'il est possible de créer plusieurs task avec le même titre/contenu.
        if ($task->getTitle() != "" && $task->getContent() != "") {
            $this->em->persist($task);
            $this->em->flush();
        } else {
            throw new \Exception("Title and Content are mandatory", 400);
        }
    }

    public function getAll()
    {
        $temp = $this->taskRepository->findAll();
        if ($temp) {
            return $temp;
        } else {
            throw new \Exception("No task found", 404);
        }
    }
}
