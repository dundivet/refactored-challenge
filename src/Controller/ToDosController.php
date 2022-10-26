<?php

namespace App\Controller;

use App\Entity\ToDo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDosController extends AbstractController
{
    #[Route('/api/todos', name: 'todos')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $todos = $entityManager->getRepository(ToDo::class)->findAll();
        
        return $this->json($todos, Response::HTTP_OK);
    }

    #[Route('/api/todo', name: 'todos_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        return $this->json([], Response::HTTP_CREATED);
    }

    #[Route('/api/todo/{id}', name: 'todos_with_parent', methods: ['POST'])]
    public function withParent(Request $request): JsonResponse
    {
        return $this->json([], Response::HTTP_CREATED);
    }

    #[Route('/api/todo/{id}', name: 'todos_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        return $this->json([], Response::HTTP_ACCEPTED);
    }

    #[Route('/api/todo/{id}', name: 'todo_complete', methods: ['PATCH'])]
    public function complete(int $id, Request $request): JsonResponse
    {
        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/todo/{id}', name: 'todo_delete', methods: ['DELETE'])]
    public function delete(int $id, Request $request): JsonResponse
    {
        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
