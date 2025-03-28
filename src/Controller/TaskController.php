<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\AccountService;

class TaskController extends AbstractController
{
    public function __construct(
        private readonly TaskService $taskService
    ) {}

    #[Route('/task/add', name: 'app_task_add')]
    public function addTask(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        $msg = "";
        $status = "";
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->taskService->save($task);
                $status = "success";
                $msg = "Task successfully added";
            } catch (\Exception $e) {
                $msg = $e->getMessage();
                $status = "danger";
            }
            $this->addFlash($status, $msg);
        }
        return $this->render(
            'task/addTask.html.twig',
            [
                'form' => $form
            ]
        );
    }

    #[Route('/tasks', name: 'app_task_tasks')]
    public function showAlltasks(): Response
    {
        try {
            $accounts = $this->taskService->getAll();
        } catch (\Exception $e) {
            $erreur = $e->getMessage();
        }
        return $this->render('user/accounts.html.twig', [
            "accounts" => $accounts ?? null,
            "erreur" => $erreur ?? null
        ]);
    }
}
