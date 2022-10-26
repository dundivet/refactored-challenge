<?php

namespace App\Controller;

use App\Entity\ToDo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
}
