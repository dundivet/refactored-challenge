<?php

namespace App\Controller;

use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagsController extends AbstractController
{
    #[Route('/api/tags', name: 'app_tags')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $tags = $entityManager->getRepository(Tag::class)->findAll();

        return $this->json($tags, Response::HTTP_OK);
    }
}
