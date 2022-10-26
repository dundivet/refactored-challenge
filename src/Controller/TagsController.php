<?php

namespace App\Controller;

use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagsController extends AbstractController
{
    #[Route('/api/tags', name: 'tags')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $tags = $entityManager->getRepository(Tag::class)->findAll();

        return $this->json($tags, Response::HTTP_OK);
    }

    #[Route('/api/tag', name: 'tags_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        return $this->json([], Response::HTTP_CREATED);
    }
}
