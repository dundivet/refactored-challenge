<?php

namespace App\Controller;

use App\Entity\ToDo;
use App\Handler\ToDoHandler;
use App\Manager\ToDoManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\Cloner\Data;

class ToDosController extends AbstractController
{
    #[Route('/api/todos', name: 'todos', methods: ['GET'])]
    public function index(ToDoHandler $handler): JsonResponse
    {
        return $handler->handle();
    }

    #[Route('/api/todos', name: 'todos_create', methods: ['POST'])]
    public function create(ToDoHandler $handler): JsonResponse
    {
        return $handler->handle();
    }

    #[Route('/api/todos/{id<\d+>}', name: 'todos_update', methods: ['PUT'])]
    public function update(int $id, ToDoHandler $handler): JsonResponse
    {
        return $handler->handle($id);
    }

    #[Route('/api/todos/{id<\d+>}', name: 'todo_complete', methods: ['PATCH'])]
    public function complete(int $id, ToDoHandler $handler): JsonResponse
    {
        return $handler->handle($id);
    }

    #[Route('/api/todos/{id<\d+>}', name: 'todo_delete', methods: ['DELETE'])]
    public function delete(int $id, ToDoHandler $handler): JsonResponse
    {
        return $handler->handle($id);
    }
}
