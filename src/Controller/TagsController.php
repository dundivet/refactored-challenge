<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Handler\TagHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagsController extends AbstractController
{
    #[Route('/api/tags', name: 'tags', methods: ['GET'])]
    public function index(TagHandler $handler): JsonResponse
    {
        return $handler->handle();
    }

    #[Route('/api/tags', name: 'tags_create', methods: ['POST'])]
    public function create(TagHandler $handler): JsonResponse
    {
        return $handler->handle();
    }
}
